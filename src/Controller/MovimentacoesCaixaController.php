<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\EntityInterface;

class MovimentacoesCaixaController extends AppController
{
    public $paginate = [
        'fields' => ['id', 'descricao', 'valor', 'tipo', 'data_movimentacao'],
        'order' => ['MovimentacoesCaixa.data_movimentacao' => 'desc'],
        'limit' => 50,
    ];

    public function paginateConditions(): array
    {
        $conditions = parent::paginateConditions();
        $descricao = $this->request->is('post') ?
            $this->dataCondition('MovimentacoesCaixa.descricao') :
            $this->sessionCondition('MovimentacoesCaixa.descricao');

        if (!empty($descricao)) {
            $conditions['MovimentacoesCaixa.descricao LIKE'] = "%{$descricao}%";
        }

        return $conditions;
    }

    public function beforeInsert(): void
    {
        $movimentacao = $this->request->getData();

        if (!empty($movimentacao['valor'])) {
            $valor = preg_replace('/[^\d,\.]/', '', $movimentacao['valor']);
            $valor = str_replace(',', '.', $valor);
            $movimentacao['valor'] = (float)$valor;
        }

        $this->request = $this->request->withParsedBody($movimentacao);
    }

    public function beforeUpdate(): void
    {

        $movimentacao = $this->request->getData();

        if (!empty($movimentacao['valor'])) {
            $valor = preg_replace('/[^\d,\.]/', '', $movimentacao['valor']);
            $valor = str_replace(',', '.', $valor);
            $movimentacao['valor'] = (float)$valor;
        }

        $this->request = $this->request->withParsedBody($movimentacao);
    }

    public function getEditEntity(int $id): EntityInterface
    {
        $entity = $this->{$this->getModelName()}->newEmptyEntity();

        if ($id != null) {
            $entity = $this->{$this->getModelName()}->get($id);
        }

        $entity['data_movimentacao'] = $entity['data_movimentacao']->format('d/m/Y');

        return $entity;
    }
}
