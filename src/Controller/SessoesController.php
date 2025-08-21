<?php
declare(strict_types=1);

namespace App\Controller;

class SessoesController extends AppController
{
    public $paginate = [
        'fields' => ['id', 'data_sessao', 'tipo', 'loja_id'],
        'contain' => [
            'Lojas' => [
                'fields' => ['id', 'nome']
            ]
        ],
        'order' => ['Sessoes.data_sessao' => 'desc'],
        'limit' => 20,
    ];

    public function paginateConditions(): array
    {
        $conditions = parent::paginateConditions();

        $data = $this->request->is('post')
            ? $this->dataCondition('Sessoes.data_sessao')
            : $this->sessionCondition('Sessoes.data_sessao');

        $tipo = $this->request->is('post')
            ? $this->dataCondition('Sessoes.tipo')
            : $this->sessionCondition('Sessoes.tipo');

        if (!empty($data)) {
            $conditions['Sessoes.data_sessao'] = $data;
        }

        if (!empty($tipo)) {
            $conditions['Sessoes.tipo LIKE'] = "%{$tipo}%";
        }

        return $conditions;
    }
}
