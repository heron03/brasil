<?php
declare(strict_types=1);

namespace App\Controller;

class SessoesController extends AppController
{
    public $paginate = [
        'fields' => ['id', 'data', 'tipo', 'loja_id'],
        'contain' => [
            'Lojas' => [
                'fields' => ['id', 'nome']
            ]
        ],
        'order' => ['Sessoes.data' => 'desc'],
        'limit' => 20,
    ];

    public function paginateConditions(): array
    {
        $conditions = parent::paginateConditions();

        $data = $this->request->is('post')
            ? $this->dataCondition('Sessoes.data')
            : $this->sessionCondition('Sessoes.data');

        $tipo = $this->request->is('post')
            ? $this->dataCondition('Sessoes.tipo')
            : $this->sessionCondition('Sessoes.tipo');

        if (!empty($data)) {
            $conditions['Sessoes.data'] = $data;
        }

        if (!empty($tipo)) {
            $conditions['Sessoes.tipo LIKE'] = "%{$tipo}%";
        }

        return $conditions;
    }
}
