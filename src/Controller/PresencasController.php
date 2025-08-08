<?php
declare(strict_types=1);

namespace App\Controller;

class PresencasController extends AppController
{
    public $paginate = [
        'fields' => ['id', 'data', 'irmao_id', 'loja_id'],
        'contain' => [
            'Irmaos' => ['fields' => ['id', 'nome']],
            'Lojas' => ['fields' => ['id', 'nome']],
        ],
        'order' => ['Presencas.data' => 'desc'],
        'limit' => 30,
    ];

    public function paginateConditions(): array
    {
        $conditions = parent::paginateConditions();
        $data = $this->request->is('post') ?
            $this->dataCondition('Presencas.data') :
            $this->sessionCondition('Presencas.data');

        if (!empty($data)) {
            $conditions['Presencas.data'] = $data;
        }

        return $conditions;
    }
}
