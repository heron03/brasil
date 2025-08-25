<?php
/**
 * @var \App\View\AppView $this
 */
$this->extend('MetronicV4.Pages/login3');

if (!$this->request->is('ajax')) {
    $this->assign('title', 'BRASIL');
}

$formOptions = [
    'class' => 'm-login__form m-form',
    'default' => false,
    'novalidate' => true,
];

$form = $this->Metronic->formCreate(null, $formOptions);
$form .= $this->Flash->render('danger') . $this->Flash->render('success');
$form .= $this->Metronic->input('email');
$form .= $this->Metronic->input('senha');
// $form .= $this->Html->div('row m-login__form-sub', $this->Html->div(
//     'col m--align-right m-login__form-right',
//     $this->Metronic->link('Esqueceu a senha?', [
//         'class' => 'm-link',
//         'get-url' => $this->Url->build('/usuarios/lembrar-senha'),
//     ])
// ));
$form .= $this->Html->div(
    'm-login__form-action',
    $this->Metronic->link('Entrar', [
        'id' => 'm_login_signin_submit',
        'post-url' => $this->Url->build('/login'),
        'class' => 'btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary',
    ])
);
$form .= $this->Form->end();
$logo = $this->Html->div('m-login__logo', $this->Html->tag('h2', 'BRASIL'));
// $head = $this->Html->div('m-login__head', $this->Html->div('m-login__title', $this->Html->tag('h6', 'Sistema de Planejamento e Desenvolvimento Urbano')));
$form = $this->Html->div('m-login__signin', $logo . $head . $form);

$this->assign('form', $form);

$this->Metronic->buffer('$(".data-toggle").on("click", function() {
    $(this).children().toggleClass("la-eye-slash");
    let input = $(this).parent().prev();
    if (input.attr("type") === "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});');
