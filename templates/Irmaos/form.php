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

if ($this->request->getParam('action') === 'add') {
    $formBody .= $this->Html->div(
        'm-form__group',
        $this->Html->div(
            'form-group row',
            $this->Metronic->input('senha') .
            $this->Metronic->input('confirma_senha')
        )
    );
}

$session = $this->getRequest()->getSession();
if ($session->read('Auth.nivel') === \App\Model\Entity\Irmao::NIVEL_DESENVOLVEDOR) {
    $formBody .= $this->Html->div(
        'm-form__group',
        $this->Html->div(
            'form-group row',
            $this->Metronic->input('nivel', [
                'value' => $irmao->nivel ?: \App\Model\Entity\Irmao::NIVEL_IRMAO,
            ])
        )
    );
}
if (\App\Model\Entity\Irmao::temAcessoGestao($session->read('Auth.nivel'))) {
    $formBody .= $this->Html->div(
        'm-form__group',
        $this->Html->div(
            'form-group row',
            $this->Metronic->input('desconto_valor') .
            $this->Metronic->input('desconto_mutua')
        )
    );
    $formBody .= $this->Html->div(
        'm-form__group',
        $this->Html->div(
            'form-group row',
            $this->Metronic->input('desconto_capitacao') .
            $this->Metronic->input('desconto_diversos')
        )
    );
    $formBody .= $this->Html->div(
        'm-form__group',
        $this->Html->div(
            'form-group row',
            $this->Metronic->input('desconto_reserva')
        )
    );
}

$this->assign('formBody', $formBody);
$this->assign('form', $form);
