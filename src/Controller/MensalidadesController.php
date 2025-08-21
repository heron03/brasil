<?php
declare(strict_types=1);

namespace App\Controller;

class MensalidadesController extends AppController
{
    public $paginate = ['fields' => [
        'Mensalidades.id',
        'Mensalidades.irmao_id',
        'Mensalidades.mes_referencia',
        'Mensalidades.valor',
        'Mensalidades.pago',
        'Mensalidades.data_pagamento',
    ],
        'contain' => [
            'Irmaos' => ['fields' => ['id', 'nome']],
        ],
        'order' => ['Mensalidades.mes_referencia' => 'desc'],
        'limit' => 30,
    ];

    public function paginateConditions(): array
    {
        $conditions = parent::paginateConditions();
        $referencia = $this->request->is('post') ?
            $this->dataCondition('Mensalidades.mes_referencia') :
            $this->sessionCondition('Mensalidades.mes_referencia');

        if (!empty($referencia)) {
            $conditions['Mensalidades.mes_referencia'] = $referencia;
        }

        return $conditions;
    }
}
