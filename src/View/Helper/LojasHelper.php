<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

class LojasHelper extends Helper
{
    public array $fields = [
        'fields' => [
            ['nome'],
            ['telefone', 'email'],
            ['logradouro', 'numero'],
            ['bairro', 'complemento'],
            ['cidade', 'uf'],
        ],
    ];

    public array $titulos = [
        'add' => 'Nova Loja',
        'view' => 'Detalhes da Loja',
        'edit' => 'Edição da Loja',
    ];
}
