<?php
declare(strict_types=1);

namespace App\Controller\Component;

use MetronicV4\Controller\Component\FormFieldComponent;

/**
 * @method \App\Controller\AppController getController()
 */
class LojasFormFieldComponent extends FormFieldComponent
{
    public $params = [
        'nome_loja' => [
            'label' => ['text' => 'Nome da Loja'],
            'type' => 'text',
            'maxlength' => 255,
            'class' => 'form-control m-input',
            'templates' => [
                'inputContainer' => '<div class="col-sm-6 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-6 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'potencia' => [
            'label' => ['text' => 'Potência'],
            'type' => 'text',
            'maxlength' => 100,
            'class' => 'form-control m-input',
            'templates' => [
                'inputContainer' => '<div class="col-sm-4 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-4 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'endereco_loja' => [
            'label' => ['text' => 'Endereço Completo'],
            'type' => 'textarea',
            'class' => 'form-control m-input',
            'templates' => [
                'inputContainer' => '<div class="col-sm-8 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-8 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'telefone_loja' => [
            'label' => ['text' => 'Telefone da Loja'],
            'type' => 'text',
            'maxlength' => 20,
            'class' => 'form-control m-input',
            'data-inputmask' => "'mask': ['(99) 9999-9999', '(99) 99999-9999']",
            'templates' => [
                'inputContainer' => '<div class="col-sm-3 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-3 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'email_loja' => [
            'label' => ['text' => 'E-mail da Loja'],
            'type' => 'email',
            'maxlength' => 100,
            'class' => 'form-control m-input',
            'templates' => [
                'inputContainer' => '<div class="col-sm-5 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-5 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'valor_mensalidade' => [
            'label' => ['text' => 'Valor Mensalidade'],
            'type' => 'text',
            'class' => 'form-control m-input text-right',
            'data-inputmask' => "'alias': 'currency', 'prefix': 'R$ ', 'groupSeparator': '.', 'radixPoint': ','",
            'templates' => [
                'inputContainer' => '<div class="col-sm-3 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-3 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
    ];

    public function getFields(): array
    {
        return $this->params;
    }

    public function getFilters(): array
    {
        $this->params['nome_loja']['data-autocomplete-update-on-select'] = 'LojasId';
        $this->params['nome_loja']['data-autocomplete-template'] = '<div>{{LojasNomeLoja}}</div>';
        $this->params['nome_loja']['label'] = false;
        $this->params['nome_loja']['placeholder'] = 'Filtre por Nome da Loja';
        $this->params['nome_loja']['templates'] = [
            'inputContainer' => '<div class="col-sm-4 {{type}}"><div class="m-typeahead">{{content}}</div></div>',
            'inputContainerError' =>
                '<div class="col-sm-4 {{type}}{{required}} form-error"><div class="m-typeahead">{{content}}{{error}}</div></div>',
        ];
        $this->params['nome_loja']['value'] = $this->getController()
            ->getRequest()
            ->getSession()
            ->read('Lojas.nome_loja');

        return [
            'Lojas.nome_loja' => $this->params['nome_loja'],
        ];
    }
}
