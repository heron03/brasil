<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Irmao $irmao
 * @var bool $exigirSenhaAtual
 */
$this->formConfiguracao(' — ' . $irmao->nome);
$form = $this->Metronic->formCreate($irmao, [
    'default' => true,
    'url' => ['action' => 'editSenha', $irmao->id],
]);

$campos = '';
if ($exigirSenhaAtual) {
    $campos .= $this->Metronic->input('senha_atual');
}
$campos .= $this->Metronic->input('senha', [
    'label' => ['text' => 'Nova senha'],
]);
$campos .= $this->Metronic->input('confirma_senha', [
    'label' => ['text' => 'Confirme a nova senha'],
]);

$formBody = $this->Html->div(
    'm-form__group',
    $this->Html->div('form-group row', $campos)
);

$this->assign('formBody', $formBody);
$this->assign('form', $form);
