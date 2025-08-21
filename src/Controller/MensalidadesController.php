<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\EntityInterface;

class MensalidadesController extends AppController
{
    public $paginate = [
        'fields' => [
            'Mensalidades.id',
            'Mensalidades.irmao_id',
            'Mensalidades.mes_referencia',
            'Mensalidades.valor',
            'Mensalidades.valor_pago',
            'Mensalidades.pago',
            'Mensalidades.data_pagamento',
        ],
        'contain' => [
            'Irmaos' => ['fields' => ['id', 'nome']],
        ],
        'order' => ['Mensalidades.mes_referencia' => 'desc'],
        'limit' => 30,
    ];

    public function paginateConditions(): array
    {
        $conditions = parent::paginateConditions();
        $referencia = $this->request->is('post') ?
            $this->dataCondition('Mensalidades.mes_referencia') :
            $this->sessionCondition('Mensalidades.mes_referencia');

        if (!empty($referencia)) {
            $conditions['Mensalidades.mes_referencia'] = $referencia;
        }

        return $conditions;
    }

    public function receber(?int $id = null): void
    {
        if (empty($id) && !empty($this->request->getData('id'))) {
            $id = (int)$this->request->getData('id');
        }
        $entity = $this->getEditEntity((int)$id);
        // $this->Authorization->authorize($entity);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->beforeUpdate(); // vamos ajustar pago/data_pagamento aqui
            $entity = $this->{$this->getModelName()}->patchEntity(
                $entity,
                $this->request->getData(),
                $this->patchOptions
            );

            $saved = $this->{$this->getModelName()}->save($entity, $this->saveOptions);
            if ($saved) {
                $this->Flash->bootstrapNotifyMessage($this->getFlashMessage('edit'), [
                    'plugin' => 'MetronicV4',
                    'key' => 'success',
                ]);
                $this->afterEdit($saved); // cria a movimentação de caixa aqui
            }
        } else {
            $this->beforeEdit();
        }

        $this->set($this->getEntityName(), $entity);
        $this->setFields(); // campos vêm do *FormFieldComponent*
    }

    public function getEditEntity(int $id): EntityInterface
    {
        $entity = $this->{$this->getModelName()}->newEmptyEntity();

        if ($id != null) {
            $entity = $this->{$this->getModelName()}->get($id);
        }

        if ($entity['data_pagamento'] != null) {
            $entity['data_pagamento'] = $entity['data_pagamento']->format('d/m/Y');
        }

        return $entity;
    }

    protected function parseMoneyBR(?string $s): float
    {
        if ($s === null) {
            return 0.0;
        }
        $s = trim($s);
        // remove tudo que não for dígito, ponto ou vírgula (tira "R$ ", espaços etc.)
        $s = preg_replace('/[^0-9.,-]/', '', $s) ?? '';
        // Se tiver vírgula, assumimos formato BR: primeiro removemos separador de milhar ".", depois trocamos vírgula por ponto
        if (strpos($s, ',') !== false) {
            $s = str_replace('.', '', $s);
            $s = str_replace(',', '.', $s);
        }
        return (float)$s;
    }

    public function beforeUpdate(): void
    {
        $data = $this->request->getData();
        $valorReq = $this->parseMoneyBR((string)($data['valor_recebido'] ?? '0'));
        $current = $this->getEditEntity((int)($data['id'] ?? 0));
        $total   = (float)($current->valor ?? 0.0);
        $pagoAtu = (float)($current->valor_pago ?? 0.0);
        $saldo   = max(0.0, $total - $pagoAtu);

        $valorConsiderado = round(max(0.0, min($valorReq, $saldo)), 2);
        $novoPago = round($pagoAtu + $valorConsiderado, 2);
        $quita = $novoPago >= $total;
        $data['valor_pago'] = (float)($novoPago ?? 0.0);
        $data['pago'] = $quita ? 1 : 0;

        $data['_valor_recebido_normalizado'] = $valorConsiderado;
        $this->request = $this->request->withParsedBody($data);
    }

    public function afterEdit(?EntityInterface $saved = null): void
    {
        $data  = $this->request->getData();
        $valor = (float)($data['_valor_recebido_normalizado'] ?? 0.0);
        if ($valor <= 0) {
            return; // nada a lançar
        }

        // Data da movimentação: usa a que veio no form (data_pagamento) ou hoje
        $dataMov = !empty($data['data_pagamento']) ? $data['data_pagamento'] : date('Y-m-d');

        // Descobre a loja do irmão
        $Irmaos = $this->fetchTable('Irmaos');
        $irmao  = $Irmaos->get((int)$saved->irmao_id);
        $lojaId = (int)$irmao->loja_id;

        // Descrição padrão: "Mensalidade MM/AAAA - Nome"
        $mesRef = $saved->mes_referencia
            ? (is_object($saved->mes_referencia)
                ? $saved->mes_referencia->format('m/Y')
                : date('m/Y', strtotime((string)$saved->mes_referencia)))
            : '';
        $descricao = sprintf('Mensalidade %s - %s', $mesRef, $irmao->nome ?? ('Irmão #' . $saved->irmao_id));

        $Movs = $this->fetchTable('MovimentacoesCaixa');
        $mov  = $Movs->newEntity([
            'loja_id'           => $lojaId,
            'tipo'              => 'entrada',
            'descricao'         => $descricao,
            'valor'             => $valor,
            'data_movimentacao' => $dataMov,
            'origem'            => 'mensalidade',
        ]);
        $Movs->saveOrFail($mov);
        $this->redirect($this->indexUrl());
    }
}
