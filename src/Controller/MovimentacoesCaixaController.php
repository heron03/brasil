<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\EntityInterface;

class MovimentacoesCaixaController extends AppController
{
    public $paginate = [
        'fields' => ['id', 'descricao', 'valor', 'tipo', 'data_movimentacao', 'irmao_id', 'forma_pagamento', 'deleted'],
        'contain' => [
            'Irmaos' => ['fields' => ['id', 'nome']],
        ],
        'order' => ['MovimentacoesCaixa.data_movimentacao' => 'desc'],
        'limit' => 10,
    ];

    public function paginateConditions(): array
    {
        $conditions = parent::paginateConditions();

        if ($this->request->is('post')) {
            $descricao = $this->dataCondition('MovimentacoesCaixa.descricao');
            $tipo = $this->dataCondition('MovimentacoesCaixa.tipo');
            $dataInicial = $this->dataCondition('MovimentacoesCaixa.data_inicial');
            $dataFinal = $this->dataCondition('MovimentacoesCaixa.data_final');
        } else {
            $descricao = $this->sessionCondition('MovimentacoesCaixa.descricao');
            $tipo = $this->sessionCondition('MovimentacoesCaixa.tipo');
            $dataInicial = $this->dataCondition('MovimentacoesCaixa.data_inicial');
            $dataFinal = $this->dataCondition('MovimentacoesCaixa.data_final');
        }

        $conditions[] = ["MovimentacoesCaixa.deleted IS NULL"];

        if (!empty($descricao)) {
            $conditions['MovimentacoesCaixa.descricao LIKE'] = "%{$descricao}%";
        }

        if (!empty($tipo)) {
            $conditions['MovimentacoesCaixa.tipo'] = $tipo;
        }

        if (empty($dataInicial)) {
            $dataInicial = date('Y-m-d', strtotime('-1 year'));
            $dataFinal = date('Y-m-d');
        }
        $conditions['and'] = ["MovimentacoesCaixa.data_movimentacao BETWEEN '$dataInicial' AND '$dataFinal'"];

        return $conditions;
    }

    public function beforeInsert(): void
    {
        $movimentacao = $this->request->getData();


        $this->request = $this->request->withParsedBody($movimentacao);
    }

    public function beforeUpdate(): void
    {

        $movimentacao = $this->request->getData();

        $this->request = $this->request->withParsedBody($movimentacao);
    }

    public function getEditEntity(int $id): EntityInterface
    {
        $entity = $this->{$this->getModelName()}->newEmptyEntity();

        if ($id != null) {
            $entity = $this->{$this->getModelName()}->get($id);
        }

        return $entity;
    }

    public function relatorio(): void
    {
        $session = $this->getRequest()->getSession();
        $dataInicial = $session->read('MovimentacoesCaixa.data_inicial');
        $dataFinal = $session->read('MovimentacoesCaixa.data_final');
        $conditions = $this->reportConditions();

        if (empty($dataFinal)) {
            $dataFinal = date('Y-m-d');
        }

        if (empty($dataInicial)) {
            $dataInicial = mktime(0, 0, 0, 1, 1, 2010);

            if ($dataInicial) {
                $dataInicial = date('Y-m-d', $dataInicial);
            }
        }

        $this->report();
        $movimentacoesCaixa = $this->MovimentacoesCaixa->find('all', [
            'fields' => [
                'id',
                'descricao',
                'tipo',
                'valor',
                'data_movimentacao',
                'irmao_id',
                'forma_pagamento',
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
                "MovimentacoesCaixa.data_movimentacao BETWEEN '{$dataInicial}' AND '{$dataFinal}'",
                $conditions,
                'MovimentacoesCaixa.deleted IS NULL',
            ],
        ])->toArray();
        $this->set('movimentacoesCaixa', $movimentacoesCaixa);

        $session->write(['MovimentacoesCaixa.naoEncontrada' => false]);
        if (empty($movimentacoesCaixa)) {
            $session->write(['MovimentacoesCaixa.naoEncontrada' => true]);
            $this->redirect('/movimentacoesCaixa/movimentacoesCaixaCadastradas');
        }
    }
}
