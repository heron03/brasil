<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

class PresencasHelper extends Helper
{
    public array $fields = [
        'fields' => [
            ['sessao_id', 'irmao_id'],
            ['presente'],
        ],
    ];

    public array $titulos = [
        'add' => 'Nova Presença',
        'view' => 'Detalhes da Presença',
        'edit' => 'Edição da Presença',
    ];
}
