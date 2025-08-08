<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

class IrmaosHelper extends Helper
{
    public array $fields = [
        'fields' => [
            ['nome', 'cpf', 'cim'],
            ['logradouro', 'numero', 'bairro', 'complemento'],
            ['telefone', 'email'],
        ],
    ];

    public array $titulos = [
        'add' => 'Novo Irmão',
        'view' => 'Detalhes do Irmão',
        'edit' => 'Edição do Irmão',
    ];
}
