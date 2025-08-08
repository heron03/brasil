<?php
declare(strict_types=1);

namespace App\Controller\Component;

use MetronicV4\Controller\Component\FormFieldComponent;

/**
 * @method \App\Controller\AppController getController()
 */
class PresencasFormFieldComponent extends FormFieldComponent
{
    public $params = [
        'sessao_id' => [
            'label' => ['text' => 'Sessão'],
            'type' => 'select',
            'options' => [],
            'templates' => [
                'inputContainer' => '<div class="col-sm-6 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-6 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'irmao_id' => [
            'label' => ['text' => 'Irmão'],
            'type' => 'select',
            'options' => [],
            'templates' => [
                'inputContainer' => '<div class="col-sm-6 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-6 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'presente' => [
            'label' => ['text' => 'Presença Confirmada'],
            'type' => 'select',
            'options' => [
                true => 'Presente',
                false => 'Faltou',
            ],
            'templates' => [
                'inputContainer' => '<div class="col-sm-4 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-4 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
    ];

    public function getFields(): array
    {
        $this->setSelectOptions('sessao_id', 'Sessoes', ['Deleted IS NULL']);
        $this->setSelectOptions('irmao_id', 'Irmaos', ['Deleted IS NULL']);
        return $this->params;
    }

    public function getFilters(): array
    {
        $this->params['sessao_id']['label'] = false;
        $this->params['sessao_id']['placeholder'] = 'Filtrar por Sessão';
        $this->params['sessao_id']['value'] = $this->getController()
            ->getRequest()
            ->getSession()
            ->read('Presencas.sessao_id');

        return [
            'Presencas.sessao_id' => $this->params['sessao_id'],
        ];
    }
}
