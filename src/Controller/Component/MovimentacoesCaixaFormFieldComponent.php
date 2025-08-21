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
            'data-inputmask' => "'alias': 'currency', 'prefix': 'R$ ', 'groupSeparator': '.', 'radixPoint': ','",
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
                'saida' => 'Saída',
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
        return $this->params;
    }

    public function getFilters(): array
    {
        $this->params['descricao']['label'] = false;
        $this->params['descricao']['placeholder'] = 'Filtrar por Descrição';
        $this->params['descricao']['value'] = $this->getController()
            ->getRequest()
            ->getSession()
            ->read('MovimentacoesCaixa.descricao');

        return [
            'MovimentacoesCaixa.descricao' => $this->params['descricao'],
        ];
    }
}
