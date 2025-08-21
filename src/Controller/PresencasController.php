<?php
declare(strict_types=1);

namespace App\Controller;

class PresencasController extends AppController
{
    public $paginate = [
        'fields' => ['id', 'sessao_id', 'irmao_id', 'presente'],
        'contain' => [
            'Sessoes' => ['fields' => ['id', 'data_sessao']],
            'Irmaos' => ['fields' => ['id', 'nome']],
        ],
        'order' => ['Sessoes.data_sessao' => 'desc'],
        'limit' => 30,
    ];

    public function paginateConditions(): array
    {
        $conditions = parent::paginateConditions();
        $sessaoId = $this->request->is('post') ?
            $this->dataCondition('Presencas.sessao_id') :
            $this->sessionCondition('Presencas.sessao_id');

        if (!empty($sessaoId)) {
            $conditions['Presencas.sessao_id'] = $sessaoId;
        }

        return $conditions;
    }
}
