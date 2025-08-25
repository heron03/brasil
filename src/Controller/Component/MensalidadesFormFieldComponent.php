<?php
declare(strict_types=1);

namespace App\Controller\Component;

use MetronicV4\Controller\Component\FormFieldComponent;

/**
 * @method \App\Controller\AppController getController()
 */
class MensalidadesFormFieldComponent extends FormFieldComponent
{
    public $params = [
        'irmao_id' => [
            'label' => ['text' => 'Irmão'],
            'type' => 'select',
            'options' => [],
            'templates' => [
                'inputContainer' => '<div class="col-sm-5 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-5 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'referencia' => [
            'label' => ['text' => 'Referência (MM/AAAA)'],
            'type' => 'text',
            'class' => 'form-control m-input',
            'data-inputmask' => "'mask': ['99/9999']",
            'templates' => [
                'inputContainer' => '<div class="col-sm-3 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-3 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'valor' => [
            'label' => ['text' => 'Valor'],
            'type' => 'text',
            'class' => 'form-control m-input text-right',
            'data-inputmask' => "'alias': 'currency', 'prefix': 'R$ ', 'groupSeparator': '.', 'radixPoint': ','",
            'templates' => [
                'inputContainer' => '<div class="col-sm-3 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-3 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'data_pagamento' => [
            'label' => ['text' => 'Data de Pagamento'],
            'type' => 'text',
            'templates' => [
                'inputContainer' => '<div class="col-sm-6 mr-auto {{type}}">{{content}}</div>',
                'inputContainerError' =>
                '<div class="col-sm-6 mr-auto {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
            'data-provide' => 'datepicker',
            'data-date-language' => 'pt-BR',
            'data-date-format' => 'dd/mm/yyyy',
            'data-date-today-highlight' => 1,
            'data-date-orientation' => 'bottom',
            'data-inputmask-alias' => 'date-simple',
        ],
        'valor_recebido' => [
            'label' => ['text' => 'Valor Recebido'],
            'type' => 'text',
            'class' => 'form-control m-input text-right',
            'data-inputmask' => "'alias': 'currency', 'prefix': 'R$ ', 'groupSeparator': '.', 'radixPoint': ','",
            'templates' => [
                'inputContainer' => '<div class="col-sm-6 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-6 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'forma_pagamento' => [
            'label' => ['text' => 'Forma de Pagamento'],
            'type' => 'select',
            'options' => [
                'dinheiro' => 'Dinheiro',
                'pix' => 'PIX',
                'cartao_debito' => 'Cartão de Débito',
                'cartao_credito' => 'Cartão de Crédito',
                'transferencia' => 'Transferência',
                'boleto' => 'Boleto',
                'cheque' => 'Cheque',
            ],
            'templates' => [
                'inputContainer' => '<div class="col-sm-6 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-6 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
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
        'status' => [
            'label' => ['text' => 'Status'],
            'type' => 'select',
            'options' => [
                'pendente' => 'Pendente',
                'pago' => 'Pago',
                'atrasado' => 'Atrasado',
            ],
            'templates' => [
                'inputContainer' => '<div class="col-sm-3 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-3 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
    ];

    public function getFields(): array
    {
        // $this->setSelectOptions('irmao_id', 'Irmaos', ['Deleted IS NULL']);
        return $this->params;
    }

    public function getFilters(): array
    {
        $this->params['filtro']['data-autocomplete-update-on-select'] = 'MensalidadesId';
        $this->params['filtro']['data-autocomplete-template'] = '<div>{{MensalidadesNome}}</div>';
        $this->params['filtro']['label'] = false;
        $this->params['filtro']['class'] = "form-control m-input";
        $this->params['filtro']['placeholder'] = 'Filtre por Nome do Irmão';
        $this->params['filtro']['templates'] = [
            'inputContainer' => '<div class="col-sm-4 {{type}}"><div class="m-typeahead">{{content}}</div></div>',
            'inputContainerError' =>
                '<div class="col-sm-4 {{type}}{{required}} form-error"><div class="m-typeahead">{{content}}{{error}}</div></div>',
        ];
        $this->params['filtro']['value'] = $this->getController()
            ->getRequest()
            ->getSession()
            ->read('Mensalidades.filtro');

        return [
            'Mensalidades.filtro' => $this->params['filtro'],
        ];
    }
}
