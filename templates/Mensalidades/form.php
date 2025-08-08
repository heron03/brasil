<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $mensalidade
 */
$this->formConfiguracao();
$form = $this->Metronic->formCreate($mensalidade, ['default' => true]);

$formBody = $this->camposHidden([
    ['nome' => 'id', 'valor' => []],
]);

$formBody .= $this->formulario();

$this->assign('formBody', $formBody);
$this->assign('form', $form);
