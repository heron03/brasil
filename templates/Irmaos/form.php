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

$session = $this->getRequest()->getSession();
if ($session->read('Auth.nivel') === 'Gestor') {
    $formBody .= $this->Html->div(
        'form-group m-form__group',
        $this->Html->div(
            'row',
            $this->Html->div('col', $this->Metronic->input('desconto_valor')) .
            $this->Html->div('col', $this->Metronic->input('desconto_mutua')),
        ),
    );
    $formBody .= $this->Html->div(
        'form-group m-form__group',
        $this->Html->div(
            'row',
            $this->Html->div('col', $this->Metronic->input('desconto_capitacao')) .
            $this->Html->div('col', $this->Metronic->input('desconto_diversos')),
        ),
    );

    $formBody .= $this->Html->div(
        'form-group m-form__group',
        $this->Html->div(
            'row',
            $this->Html->div('col', $this->Metronic->input('desconto_reserva')),
        ),
    );
}

$this->assign('formBody', $formBody);
$this->assign('form', $form);
