<?php
declare(strict_types=1);

namespace App\Controller;

class LojasController extends AppController
{
    public $paginate = [
        'fields' => ['id', 'nome'],
        'order' => ['Lojas.nome' => 'asc'],
        'limit' => 10,
    ];

    public function paginateConditions(): array
    {
        $conditions = parent::paginateConditions();

        if ($this->request->is('post')) {
            $nome = $this->dataCondition('Lojas.nome');
        } else {
            $nome = $this->sessionCondition('Lojas.nome');
        }

        if (!empty($nome)) {
            $conditions['Lojas.nome LIKE'] = '%' . $nome . '%';
        }

        return $conditions;
    }
}
