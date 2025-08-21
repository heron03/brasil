<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $loja
 */
$this->formConfiguracao();
$form = $this->Metronic->formCreate($movimentacoesCaixa, ['default' => true]);

$formBody = $this->camposHidden([
    ['nome' => 'id', 'valor' => []],
    ['nome' => 'loja_id', 'valor' => ['value' => 1]],
]);


$formBody .= $this->formulario();

$this->assign('formBody', $formBody);
$this->assign('form', $form);
