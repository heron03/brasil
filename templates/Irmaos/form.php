<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $irmao
 */
$this->formConfiguracao();
$form = $this->Metronic->formCreate($irmao, ['default' => true]);

$formBody = $this->camposHidden([
    ['nome' => 'id', 'valor' => []],
    ['nome' => 'loja_id', 'valor' => ['value' => 1]],
]);

$formBody .= $this->formulario();

$this->assign('formBody', $formBody);
$this->assign('form', $form);
