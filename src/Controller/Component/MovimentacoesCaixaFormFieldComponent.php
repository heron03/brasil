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
            'class' => 'form-control m-input',
            'data-inputmask' => "'alias': 'datetime', 'inputFormat': 'dd/mm/yyyy'",
            'templates' => [
                'inputContainer' => '<div class="col-sm-4 {{type}}">{{content}}</div>',
                'inputContainerError' =>
                    '<div class="col-sm-4 {{type}}{{required}} form-error">{{content}}{{error}}</div>',
            ],
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
