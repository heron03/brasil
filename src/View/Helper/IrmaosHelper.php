<?php

declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

class IrmaosHelper extends Helper
{
    public array $fields = [
        'fields' => [
            ['nome', 'cim', 'cpf', 'data_nascimento'],
            ['logradouro', 'numero', 'cep'],
            ['bairro', 'complemento', 'cidade'],
            ['telefone', 'email'],
            ['grau', 'desconto_valor', 'ativo'],
            ['senha', 'confirma_senha'],
        ],
    ];

    public array $titulos = [
        'add' => 'Novo Irmão',
        'view' => 'Detalhes do Irmão',
        'edit' => 'Edição do Irmão',
    ];
}
