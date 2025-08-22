<?php
declare(strict_types=1);

namespace App\Controller\Component;

use MetronicV4\Controller\Component\FormFieldComponent;

/**
 * @method \App\Controller\AppController getController()
 */
class IrmaosFormFieldComponent extends FormFieldComponent
{
    public $params = [
        'nome' => [
            'label' => ['text' => 'Nome do Irmão'],
            'type' => 'text',
            'maxlength' => 255,
            'class' => 'form-control m-input',
            'templates' => [
                'inputContainer' => '<div class="col-sm-6 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-6 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'cim' => [
            'label' => ['text' => 'CIM'],
            'type' => 'text',
            'maxlength' => 255,
            'class' => 'form-control m-input',
            'templates' => [
                'inputContainer' => '<div class="col-sm-2 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-2 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'cpf' => [
            'label' => ['text' => 'CPF'],
            'type' => 'text',
            'templates' => [
                'inputContainer' => '<div class="col-sm-2 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                '<div class="col-sm-2 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
            'data-inputmask' => "'mask': ['999.999.999-99']",
        ],
        'desconto_valor' => [
            'label' => ['text' => 'Desconto Mensalidade'],
            'type' => 'text',
            'class' => 'form-control m-input text-right',
            'data-inputmask' => "'alias': 'currency', 'prefix': 'R$ ', 'groupSeparator': '.', 'radixPoint': ','",
            'templates' => [
                'inputContainer' => '<div class="col-sm-3 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-3 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'data_nascimento' => [
            'label' => ['text' => 'Data de Nascimento'],
            'type' => 'text',
            'templates' => [
                'inputContainer' => '<div class="col-sm-2 mr-auto {{type}}">{{content}}</div>',
                'inputContainerError' =>
                '<div class="col-sm-2 mr-auto {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
            'data-provide' => 'datepicker',
            'data-date-language' => 'pt-BR',
            'data-date-format' => 'dd/mm/yyyy',
            'data-date-today-highlight' => 1,
            'data-date-orientation' => 'bottom',
            'data-inputmask-alias' => 'date-simple',
        ],
        'cargo' => [
            'label' => ['text' => 'Cargo'],
            'type' => 'text',
            'maxlength' => 100,
            'class' => 'form-control m-input',
            'templates' => [
                'inputContainer' => '<div class="col-sm-3 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-3 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'ativo' => [
            'label' => ['text' => 'Ativo'],
            'type' => 'select',
            'templates' => [
                'inputContainer' => '<div class="col-sm-5 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-5 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
            'options' => [
                1 => 'Ativo',
                0 => 'Inativo',
            ],
        ],
        'telefone' => [
            'label' => ['text' => 'Telefone'],
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
        'email' => [
            'label' => ['text' => 'E-mail'],
            'type' => 'email',
            'maxlength' => 100,
            'class' => 'form-control m-input',
            'templates' => [
                'inputContainer' => '<div class="col-sm-3 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-3 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
         'cep' => [
            'label' => ['text' => 'CEP'],
            'type' => 'text',
            'templates' => [
                'inputContainer' => '<div class="col-sm-3 mr-auto {{type}}">{{content}}</div>',
                'inputContainerError' =>
                '<div class="col-sm-3 mr-auto {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
            'data-inputmask' => "'mask': '99999-999'",
        ],
        'bairro' => [
            'type' => 'text',
            'maxlength' => 100,
            'templates' => [
                'inputContainer' => '<div class="col-sm-3 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                '<div class="col-sm-3 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'grau' => [
            'type' => 'text',
            'maxlength' => 100,
            'templates' => [
                'inputContainer' => '<div class="col-sm-3 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                '<div class="col-sm-3 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'complemento' => [
            'type' => 'text',
            'maxlength' => 100,
            'templates' => [
                'inputContainer' => '<div class="col-sm-3 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                '<div class="col-sm-3 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'logradouro' => [
            'label' => ['text' => 'Endereço'],
            'type' => 'text',
            'maxlength' => 100,
            'templates' => [
                'inputContainer' => '<div class="col-sm-6 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                '<div class="col-sm-6 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'numero' => [
            'label' => ['text' => 'Número'],
            'type' => 'text',
            'maxlength' => 20,
            'templates' => [
                'inputContainer' => '<div class="col-sm-3 mr-auto {{type}}">{{content}}</div>',
                'inputContainerError' =>
                '<div class="col-sm-3 mr-auto {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
        'cidade' => [
            'label' => ['text' => 'Cidade'],
            'type' => 'text',
            'maxlength' => 100,
            'templates' => [
                'inputContainer' => '<div class="col-sm-3 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                '<div class="col-sm-3 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
        ],
    ];

    public function getFields(): array
    {
        // $this->setSelectOptions('loja_id', 'Lojas', ['Deleted IS NULL']);
        return $this->params;
    }

    public function getFilters(): array
    {
        $this->params['nome']['data-autocomplete-update-on-select'] = 'IrmaosId';
        $this->params['nome']['data-autocomplete-template'] = '<div>{{IrmaosNome}}</div>';
        $this->params['nome']['label'] = false;
        $this->params['nome']['placeholder'] = 'Filtre por Nome do Irmão';
        $this->params['nome']['templates'] = [
            'inputContainer' => '<div class="col-sm-4 {{type}}"><div class="m-typeahead">{{content}}</div></div>',
            'inputContainerError' =>
                '<div class="col-sm-4 {{type}}{{required}} form-error"><div class="m-typeahead">{{content}}{{error}}</div></div>',
        ];
        $this->params['nome']['value'] = $this->getController()
            ->getRequest()
            ->getSession()
            ->read('Irmaos.nome');

        return [
            'Irmaos.nome' => $this->params['nome'],
        ];
    }
}
