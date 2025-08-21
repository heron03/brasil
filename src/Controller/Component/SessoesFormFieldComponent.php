<?php
declare(strict_types=1);

namespace App\Controller\Component;

use MetronicV4\Controller\Component\FormFieldComponent;

/**
 * @method \App\Controller\AppController getController()
 */
class SessoesFormFieldComponent extends FormFieldComponent
{
    public $params = [
        'data' => [
            'label' => ['text' => 'Data da Sessão'],
            'type' => 'text',
            'class' => 'form-control m-input',
            'data-inputmask' => "'alias': 'datetime', 'inputFormat': 'dd/mm/yyyy'",
            'templates' => [
                'inputContainer' => '<div class="col-sm-4 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-4 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'tipo_sessao' => [
            'label' => ['text' => 'Tipo de Sessão'],
            'type' => 'select',
            'options' => [
                'Ordinária' => 'Ordinária',
                'Extraordinária' => 'Extraordinária',
                'Magna' => 'Magna',
            ],
            'templates' => [
                'inputContainer' => '<div class="col-sm-4 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-4 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'descricao' => [
            'label' => ['text' => 'Descrição'],
            'type' => 'textarea',
            'class' => 'form-control m-input',
            'templates' => [
                'inputContainer' => '<div class="col-sm-8 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-8 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
    ];

    public function getFields(): array
    {
        return $this->params;
    }

    public function getFilters(): array
    {
        $this->params['data']['label'] = false;
        $this->params['data']['placeholder'] = 'Filtrar por Data';
        $this->params['data']['value'] = $this->getController()
            ->getRequest()
            ->getSession()
            ->read('Sessoes.data');

        return [
            'Sessoes.data' => $this->params['data'],
        ];
    }
}
