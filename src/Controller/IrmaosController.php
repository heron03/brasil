<?php
declare(strict_types=1);

namespace App\Controller;

class IrmaosController extends AppController
{
    public $paginate = [
        'fields' => ['id', 'nome', 'cim', 'cpf', 'loja_id', 'ativo'],
        'contain' => [
            'Lojas' => ['fields' => ['id', 'nome']],
        ],
        'order' => ['Irmaos.nome' => 'asc'],
        'limit' => 20,
    ];

    public function paginateConditions(): array
    {
        $conditions = parent::paginateConditions();
        $nome = $this->request->is('post') ?
            $this->dataCondition('Irmaos.nome') :
            $this->sessionCondition('Irmaos.nome');

        if (!empty($nome)) {
            $conditions['Irmaos.nome LIKE'] = "%{$nome}%";
        }

        return $conditions;
    }
}
