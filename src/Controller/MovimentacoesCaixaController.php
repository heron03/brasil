<?php
declare(strict_types=1);

namespace App\Controller;

class MovimentacoesCaixaController extends AppController
{
    public $paginate = [
        'fields' => ['id', 'descricao', 'valor', 'tipo', 'data'],
        'order' => ['MovimentacoesCaixa.data' => 'desc'],
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
}
