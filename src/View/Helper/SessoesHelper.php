<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

class SessoesHelper extends Helper
{
    public array $fields = [
        'fields' => [
            ['data', 'tipo'],
            ['descricao'],
        ],
    ];

    public array $titulos = [
        'add' => 'Nova Sessão',
        'view' => 'Detalhes da Sessão',
        'edit' => 'Edição da Sessão',
    ];
}
