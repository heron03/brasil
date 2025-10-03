<?php
declare(strict_types=1);

namespace App\Controller\Component;

use MetronicV4\Controller\Component\FormFieldComponent;

/**
 * @method \App\Controller\AppController getController()
 */
class MovimentacoesCaixaFormFieldComponent extends FormFieldComponent
{
    public $params = [
        'data_movimentacao' => [
            'label' => ['text' => 'Data da Movimentação'],
            'type' => 'text',
            'templates' => [
                'inputContainer' => '<div class="col-sm-3 mr-auto {{type}}">{{content}}</div>',
                'inputContainerError' =>
                '<div class="col-sm-3 mr-auto {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
            'data-provide' => 'datepicker',
            'data-date-language' => 'pt-BR',
            'data-date-format' => 'dd/mm/yyyy',
            'data-date-today-highlight' => 1,
            'data-date-orientation' => 'bottom',
            'data-inputmask-alias' => 'date-simple',
        ],
        'descricao' => [
            'label' => ['text' => 'Descrição'],
            'type' => 'text',
            'maxlength' => 255,
            'class' => 'form-control m-input',
            'templates' => [
                'inputContainer' => '<div class="col-sm-6 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-6 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'valor' => [
            'label' => ['text' => 'Valor'],
            'type' => 'text',
            'class' => 'form-control m-input text-right',
            'data-inputmask-alias' => 'cash',
            'templates' => [
                'inputContainer' => '<div class="col-sm-3 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-3 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'tipo' => [
            'label' => ['text' => 'Tipo'],
            'type' => 'select',
            'options' => [
                'entrada' => 'Entrada',
                'Saída' => 'Saída',
            ],
            'templates' => [
                'inputContainer' => '<div class="col-sm-3 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-3 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'irmao_id' => [
            'label' => ['text' => 'Irmão'],
            'type' => 'select',
            'templates' => [
                'inputContainer' => '<div class="col-sm-6 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-6 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
            'options' => [],
        ],
        'forma_pagamento' => [
            'label' => ['text' => 'Forma de Pagamento'],
            'type' => 'select',
            'options' => [
                'Dinheiro' => 'Dinheiro',
                'PIX' => 'PIX',
                'Cartao de Débito' => 'Cartão de Débito',
                'Cartao de Crédito' => 'Cartão de Crédito',
                'Transferência' => 'Transferência',
                'Boleto' => 'Boleto',
                'Cheque' => 'Cheque',
            ],
            'templates' => [
                'inputContainer' => '<div class="col-sm-3 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-3 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'observacoes' => [
            'label' => ['text' => 'Observações'],
            'type' => 'textarea',
            'class' => 'form-control m-input',
            'rows' => 3,
            'templates' => [
                'inputContainer' => '<div class="col-sm-12 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-12 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
    ];

    public function getFields(): array
    {
        $this->setSelectOptions('irmao_id', 'Irmaos', ['Deleted IS NULL']);
        return $this->params;
    }

    public function getFilters(): array
    {
        $this->params['descricao']['data-autocomplete-update-on-select'] = 'MovimentacoesCaixaId';
        $this->params['descricao']['data-autocomplete-template'] = '<div>{{MovimentacoesCaixaDescricao}}</div>';
        $this->params['descricao']['label'] = false;
        $this->params['descricao']['placeholder'] = 'Filtre por Descrição';
        $this->params['descricao']['templates'] = [
            'inputContainer' => '<div class="col-sm-4 {{type}}"><div class="m-typeahead">{{content}}</div></div>',
            'inputContainerError' =>
                '<div class="col-sm-4 {{type}}{{required}} form-error"><div class="m-typeahead">{{content}}{{error}}</div></div>',
        ];
        $this->params['descricao']['value'] = $this->getController()
            ->getRequest()
            ->getSession()
            ->read('MovimentacoesCaixa.descricao');

        return [
            'MovimentacoesCaixa.descricao' => $this->params['descricao'],
        ];
    }
}
