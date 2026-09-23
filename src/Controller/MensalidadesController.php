<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Irmao;
use Cake\Datasource\EntityInterface;

class MensalidadesController extends AppController
{
    public $paginate = [
        'fields' => [
            'Mensalidades.id',
            'Mensalidades.irmao_id',
            'Mensalidades.mes_referencia',
            'Mensalidades.valor',
            'Mensalidades.mutua',
            'Mensalidades.capitacao',
            'Mensalidades.diversos',
            'Mensalidades.reserva',
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

        $session = $this->getRequest()->getSession();
        if (!Irmao::temAcessoGestao($session->read('Auth.nivel'))) {
            $conditions[] = ["Mensalidades.irmao_id" => $session->read('Auth.id')];
        }
        $conditions[] = [
            'Mensalidades.irmao_id NOT IN' => $this->fetchTable('Irmaos')->idsDesenvolvedor(),
        ];
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

        return $entity;
    }

    protected function parseMoneyBR($valor): float
    {
        if ($valor === null) {
            return 0.0;
        }
        $valor = trim($valor);
        $valor = preg_replace('/[^0-9.,-]/', '', $valor) ?? '';
        if (strpos($valor, ',') !== false) {
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
        }
        return (float)$valor;
    }

    public function beforeUpdate(): void
    {
        $data = $this->request->getData();

        $valorReq = $this->parseMoneyBR($data['valor_recebido'] ?? null);

        $id = (int)($data['id'] ?? 0);
        $current = $this->getEditEntity($id);
        $valorMensalidade = (float)($current->valor ?? 0.0);
        $valorMutua = (float)($current->mutua ?? 0.0);
        $valorCapitacao = (float)($current->capitacao ?? 0.0);
        $valorDiversos = (float)($current->diversos ?? 0.0);
        $valorReserva = (float)($current->reserva ?? 0.0);
        $total = round(
            $valorMensalidade + $valorMutua + $valorCapitacao + $valorDiversos + $valorReserva,
            2
        );
        $pagoAtu = (float)($current->valor_pago ?? 0.0);
        if ($total <= 0) {
            $total = round($pagoAtu + $valorReq, 2);
            $data['valor'] = $total;
        }

        $saldo = max(0.0, $total - $pagoAtu);
        $valorConsiderado = empty($current->id)
            ? round(max(0.0, $valorReq), 2)
            : round(max(0.0, min($valorReq, $saldo)), 2);

        $novoPago = round($pagoAtu + $valorConsiderado, 2);
        $data['valor_pago'] = $novoPago;
        $data['pago'] = ($novoPago >= $total && $total > 0) ? 1 : 0;
        $data['_valor_recebido_normalizado'] = $valorConsiderado;

        $this->request = $this->request->withParsedBody($data);
    }

    public function afterEdit(?EntityInterface $saved = null): void
    {
        $data  = $this->request->getData();
        $valor = (float)($data['_valor_recebido_normalizado'] ?? 0.0);
        if ($valor <= 0) {
            $this->redirect($this->indexUrl());
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

    public function limparPagamento(?int $id = null): void
    {
        $this->request->allowMethod(['post']);
        $mensalidade = $this->Mensalidades->get((int)$id, [
            'contain' => ['Irmaos'],
        ]);
        $this->Authorization->authorize($mensalidade);

        $mensalidade->set('valor_pago', 0);
        $mensalidade->set('pago', false);
        $mensalidade->set('data_pagamento', null);
        $mensalidade->set('forma_pagamento', null);

        if ($this->Mensalidades->save($mensalidade, ['validate' => false])) {
            $this->estornarMovimentacoesDaMensalidade($mensalidade);
            $this->Flash->bootstrapNotifyMessage('Pagamento apagado. O valor pago voltou a zero.', [
                'plugin' => 'MetronicV4',
                'key' => 'success',
            ]);
        } else {
            $this->Flash->bootstrapNotifyMessage('Não foi possível apagar o pagamento.', [
                'plugin' => 'MetronicV4',
                'key' => 'danger',
            ]);
        }

        $this->redirect(['action' => 'index']);
    }

    protected function estornarMovimentacoesDaMensalidade(EntityInterface $mensalidade): void
    {
        $mesRef = $mensalidade->mes_referencia
            ? (is_object($mensalidade->mes_referencia)
                ? $mensalidade->mes_referencia->format('m/Y')
                : date('m/Y', strtotime((string)$mensalidade->mes_referencia)))
            : '';
        $nome = $mensalidade->irmao->nome ?? ('Irmão #' . $mensalidade->irmao_id);
        $descricao = sprintf('Mensalidade %s - %s', $mesRef, $nome);

        $movimentacoes = $this->fetchTable('MovimentacoesCaixa');
        $lancamentos = $movimentacoes->find()
            ->where([
                'MovimentacoesCaixa.irmao_id' => $mensalidade->irmao_id,
                'MovimentacoesCaixa.origem' => 'mensalidade',
                'MovimentacoesCaixa.descricao' => $descricao,
                'MovimentacoesCaixa.deleted IS' => null,
            ])
            ->all();

        foreach ($lancamentos as $lancamento) {
            $movimentacoes->excluir($lancamento);
        }
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
                'valor_aberto' => 'GREATEST(0, (Mensalidades.valor - Mensalidades.valor_pago))',
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


    public function anuais(?int $irmaoId = null): void
    {
        $session = $this->getRequest()->getSession();
        $ano = (int)$this->request->getQuery('ano');
        $dataInicial = $session->read('Mensalidades.data_inicial');
        $dataFinal = $session->read('Mensalidades.data_final');
        $this->viewBuilder()->setLayout('ajax');
        $this->response = $this->response->withType('pdf');
        $mensalidadesTable = $this->getTableLocator()->get('Mensalidades');
        $entity = $mensalidadesTable->newEmptyEntity();
        $this->Authorization->authorize($entity);

        if (empty($dataFinal)) {
            $dataFinal = date('Y-m-d');
        }

        if (empty($dataInicial)) {
            $dataInicial = mktime(0, 0, 0, 1, 1, 2010);
            $dataInicial = date('Y-m-d', $dataInicial);
        }

        if ($ano >= 2000 && $ano <= 2100) {
            $dataInicial = "{$ano}-01-01";
            $dataFinal = "{$ano}-12-31";
        }

        $session = $this->getRequest()->getSession();
        $irmaoRelatorioId = Irmao::temAcessoGestao($session->read('Auth.nivel'))
            ? (int)$irmaoId
            : (int)$session->read('Auth.id');
        $irmaoRelatorio = $this->fetchTable('Irmaos')->find()
            ->select(['id', 'nivel'])
            ->where(['Irmaos.id' => $irmaoRelatorioId])
            ->first();
        if (!$irmaoRelatorio || $irmaoRelatorio->nivel === Irmao::NIVEL_DESENVOLVEDOR) {
            $session->write(['Mensalidades.naoEncontrada' => true]);
            $this->redirect('/irmaos');

            return;
        }
        $conditions = [
            'Mensalidades.irmao_id' => $irmaoRelatorioId,
            "Mensalidades.mes_referencia >=" => $dataInicial,
            "Mensalidades.mes_referencia <=" => $dataFinal,
        ];
        $mensalidadesPeriodo = $mensalidadesTable->findMensalidadesPorAnual($conditions);
        $this->set('mensalidadesPeriodo', $mensalidadesPeriodo);

        $session->write(['Mensalidades.naoEncontrada' => false]);
        if (empty($mensalidadesPeriodo)) {
            $session->write(['Mensalidades.naoEncontrada' => true]);
            $this->redirect('/irmaos');
        }
    }

    public function recibo(?int $id = null): void
    {
        $session = $this->getRequest()->getSession();
        $entity = $this->{$this->getModelName()}->get((int)$id, [
            'contain' => [
                'Irmaos' => [
                    'Lojas',
                ],
            ],
        ]);
        $this->Authorization->authorize($entity);
        if (($entity->irmao->nivel ?? null) === Irmao::NIVEL_DESENVOLVEDOR) {
            $this->redirect('/mensalidades');

            return;
        }

        $movimentacoesCaixa = $this->fetchTable('MovimentacoesCaixa')
            ->find()
            ->where([
                'MovimentacoesCaixa.irmao_id' => $entity->irmao_id,
                'MovimentacoesCaixa.origem' => 'mensalidade',
                'MovimentacoesCaixa.deleted IS' => null,
            ])
            ->orderDesc('MovimentacoesCaixa.id')
            ->all()
            ->toList();

        $entity->set('movimentacoes_caixa', $movimentacoesCaixa);

        $this->set($this->getEntityName(), $entity);
        $this->set('movimentacoesCaixa', $movimentacoesCaixa);
        $this->setFields();
        $this->viewBuilder()->setLayout('ajax');
        $this->response = $this->response->withType('pdf');
    }
}
