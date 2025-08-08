<?php
declare(strict_types=1);

namespace App\Controller;

class MensalidadesController extends AppController
{
    public $paginate = [
        'fields' => ['id', 'referencia', 'valor', 'irmao_id'],
        'contain' => [
            'Irmaos' => ['fields' => ['id', 'nome']],
        ],
        'order' => ['Mensalidades.referencia' => 'desc'],
        'limit' => 30,
    ];

    public function paginateConditions(): array
    {
        $conditions = parent::paginateConditions();
        $referencia = $this->request->is('post') ?
            $this->dataCondition('Mensalidades.referencia') :
            $this->sessionCondition('Mensalidades.referencia');

        if (!empty($referencia)) {
            $conditions['Mensalidades.referencia'] = $referencia;
        }

        return $conditions;
    }
}
