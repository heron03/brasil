<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $loja
 */
$this->formConfiguracao();
$form = $this->Metronic->formCreate($loja, ['default' => true]);
debug($loja);
$formBody = $this->camposHidden([
    ['nome' => 'id', 'valor' => []],
]);

$formBody .= $this->formulario();

$this->assign('formBody', $formBody);
$this->assign('form', $form);
