<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $presenca
 */
$this->formConfiguracao();
$form = $this->Metronic->formCreate($presenca, ['default' => true]);

$formBody = $this->camposHidden([
    ['nome' => 'id', 'valor' => []],
]);

$formBody .= $this->formulario();

$this->assign('formBody', $formBody);
$this->assign('form', $form);
