<?php

/**
 * @var \App\View\AppView $this
 * @var mixed $formBody
 * @var mixed $usuario
 * @var \App\View\AppView $this
 * @var mixed $formBody
 * @var mixed $usuario
 */
$this->extend('MetronicV4.Pages/add');
$this->assign('pageTitle', 'Receber Mensalidade');

$this->formConfiguracao();
$form = $this->Metronic->formCreate($mensalidade, ['default' => true, 'type' => 'file']);

$formBody = $this->camposHidden([
    ['nome' => 'id', 'valor' => []],
]);

$formBody .= $this->Html->div(
    'form-group m-form__group',
    $this->Html->div(
        'row',
        $this->Html->div('col-sm-4 mt-3', $this->Metronic->input('valor_recebido')) .
        $this->Html->div('col-sm-4 mt-3', $this->Metronic->input('data_pagamento')) .
        $this->Html->div('col-sm-4 mt-3', $this->Metronic->input('forma_pagamento')),
    ),
);

$this->assign('form', $form);
$this->assign('formBody', $formBody);