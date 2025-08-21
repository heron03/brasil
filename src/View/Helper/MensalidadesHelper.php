<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

class MensalidadesHelper extends Helper
{
    public array $fields = [
        'fields' => [
            ['irmao_id', 'data_vencimento'],
            ['valor', 'pago'],
            ['data_pagamento', 'loja_id'],
        ],
    ];

    public array $titulos = [
        'add' => 'Nova Mensalidade',
        'view' => 'Detalhes da Mensalidade',
        'edit' => 'Edição da Mensalidade',
    ];
}
