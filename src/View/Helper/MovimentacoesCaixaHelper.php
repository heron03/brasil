<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

class MovimentacoesCaixaHelper extends Helper
{
    public array $fields = [
        'fields' => [
            ['descricao', 'irmao_id'],
            ['valor', 'tipo', 'data_movimentacao', 'forma_pagamento'],
            ['observacoes'],
        ],
    ];

    public array $titulos = [
        'add' => 'Nova Movimentação de Caixa',
        'view' => 'Detalhes da Movimentação',
        'edit' => 'Edição da Movimentação',
    ];
}
