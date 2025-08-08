<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

class MovimentacoesCaixaHelper extends Helper
{
    public array $fields = [
        'fields' => [
            ['data', 'descricao'],
            ['valor', 'tipo'],
            ['loja_id'],
        ],
    ];

    public array $titulos = [
        'add' => 'Nova Movimentação de Caixa',
        'view' => 'Detalhes da Movimentação',
        'edit' => 'Edição da Movimentação',
    ];
}
