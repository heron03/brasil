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

        if ($this->request->is('post')) {
            $nome = $this->dataCondition('Mensalidades.filtro');
            $pago = $this->dataCondition('Mensalidades.pago');
            $dataInicial = $this->dataCondition('Mensalidades.data_inicial');
            $dataFinal = $this->dataCondition('Mensalidades.data_final');
        } else {
            $nome = $this->sessionCondition('Mensalidades.filtro');
            $pago = $this->sessionCondition('Mensalidades.pago');
            $dataInicial = $this->sessionCondition('Mensalidades.data_inicial');
            $dataFinal = $this->sessionCondition('Mensalidades.data_final');
        }
        if (empty($dataInicial) && empty($dataFinal)) {
            $dataInicial = date('Y-m-d', strtotime('-1 year', (int)strtotime(date('Y-m-d'))));
            $dataFinal = date('Y-m-d');
            $this->request = $this->request->withData('Mensalidades.data_inicial', $dataInicial);
            $this->request = $this->request->withData('Mensalidades.data_final', $dataFinal);
        }

        $conditions[] = ["Mensalidades.deleted IS NULL"];

        if (!empty($nome)) {
            $conditions['Irmaos.nome LIKE'] = "%{$nome}%";
        }

        if ($pago != null) {
            if ($pago != 'Todos') {
                $conditions['Mensalidades.pago'] = $pago;
            }
        }

        if (empty($dataInicial)) {
            $dataInicial = date('Y-m-d', strtotime('-1 year'));
            $dataFinal = date('Y-m-d');
        }
        $conditions[] = ["Mensalidades.mes_referencia BETWEEN '$dataInicial' AND '$dataFinal'"];

        return $conditions;
    }

    public function receber(?int $id = null): void
    {
        if (empty($id) && !empty($this->request->getData('id'))) {
            $id = (int)$this->request->getData('id');
        }
        $entity = $this->getEditEntity((int)$id);
        $this->Authorization->authorize($entity);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->beforeUpdate();
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
                $this->afterEdit($saved);
            }
        } else {
            $this->beforeEdit();
        }

        $this->set($this->getEntityName(), $entity);
        $this->setFields();
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
        $s = preg_replace('/[^0-9.,-]/', '', $s) ?? '';
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
            return;
        }

        $dataMov = !empty($data['data_pagamento']) ? $data['data_pagamento'] : date('Y-m-d');
        $Irmaos = $this->fetchTable('Irmaos');
        $irmao  = $Irmaos->get((int)$saved->irmao_id);
        $lojaId = (int)$irmao->loja_id;

        $mesRef = $saved->mes_referencia
            ? (is_object($saved->mes_referencia)
                ? $saved->mes_referencia->format('m/Y')
                : date('m/Y', strtotime((string)$saved->mes_referencia)))
            : '';
        $descricao = sprintf('Mensalidade %s - %s', $mesRef, $irmao->nome ?? ('Irmão #' . $saved->irmao_id));

                 $Movs = $this->fetchTable('MovimentacoesCaixa');
         $mov  = $Movs->newEntity([
             'loja_id'           => $lojaId,
             'irmao_id'          => $saved->irmao_id,
             'tipo'              => 'entrada',
             'descricao'         => $descricao,
             'valor'             => $valor,
             'data_movimentacao' => $dataMov,
             'origem'            => 'mensalidade',
             'forma_pagamento'   => $data['forma_pagamento'] ?? 'dinheiro',
             'observacoes'       => $data['observacoes'] ?? null,
         ]);
        $Movs->saveOrFail($mov);
        $this->redirect($this->indexUrl());
    }


    public function relatorio(): void
    {
        $page = $this->reportPage();
        $limit = $this->reportLimit();
        $conditions = $this->reportConditions();
        $order = $this->reportOrder();
        $params = compact('page', 'conditions', 'limit', 'order');
        $session = $this->getRequest()->getSession();

        $this->report();
        $mensalidades = $this->Mensalidades->find('all', [
            'fields' => [
                'id',
                'valor',
                'pago',
                'data_pagamento',
                'mes_referencia',
                'valor_pago',
                'irmao_id',
                'deleted',
            ],
            'contain' => [
                'Irmaos' => [
                    'fields' => [
                        'id',
                        'nome',
                    ],
                    'conditions' => [
                        "Irmaos.deleted IS NULL",
                    ],
                ],


            ],
            'conditions' => [
                $conditions,
                'Mensalidades.deleted IS NULL',

            ],
        ])->toArray();
        $this->set('mensalidades', $mensalidades);
        if (empty($mensalidades)) {
            $this->redirect('/mensalidades/mensalidadesCadastradas');
        }
    }

    public function mensalidadesRelatorio(): void
    {
        $this->request->getSession()->write(['indexUrl' => 'Mensalidades']);
        $this->setEntityAuthorization();

        if ($this->request->is('post')) {
            $this->dataCondition('Mensalidades.data_inicial');
            $this->dataCondition('Mensalidades.data_final');
            $this->dataCondition('Mensalidades.irmao_id');
            $this->dataCondition('Mensalidades.pago');
        } else {
            $this->sessionCondition('Mensalidades.data_inicial');
            $this->sessionCondition('Mensalidades.data_final');
            $this->sessionCondition('Mensalidades.irmao_id');
            $this->sessionCondition('Mensalidades.pago');
        }

        $session = $this->getRequest()->getSession();
        if ($session->read('Mensalidades.naoEncontrada')) {
            $this->Flash->bootstrapNotifyMessage(
                'Nenhuma Mensalidade foi encontrada no período informado',
                ['plugin' => 'MetronicV4', 'key' => 'info']
            );
        }

        $session->write(['Mensalidades.naoEncontrada' => false]);
    }
}
