<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Irmao|null $irmao
 * @var string $passo
 */

$this->extend('MetronicV4.Pages/login3');

if (!$this->request->is('ajax')) {
    $this->assign('title', 'Cadastro de Acesso');
}

$formOptions = [
    'class' => 'm-login__form m-form',
    'default' => false,
    'novalidate' => true,
];

$flash = $this->Flash->render('danger') . $this->Flash->render('success');

$logo = $this->Html->div('m-login__logo', $this->Html->tag('h2', 'Cadastro de Acesso'));
?>

<?php
// ======================================================
// PASSO 1 — BUSCAR CIM
// ======================================================
if ($passo === 'buscar'):

    $form = $this->Metronic->formCreate(null, $formOptions);

    $form .= $flash;

    $form .= $this->Metronic->input('cim', [
        'label' => 'Informe seu CIM',
        'required' => true,
    ]);

    $form .= $this->Form->hidden('passo', ['value' => 'buscar']);

    $form .= $this->Html->div(
        'm-login__form-action',
        $this->Metronic->link('Buscar', [
            'id' => 'm_login_signin_submit',
            'post-url' => $this->Url->build('/cadastro-acesso'),
            'class' => 'btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary',
        ])
    );

    $form .= $this->Form->end();

    $this->assign('form', $this->Html->div('m-login__signin', $logo . $form));

endif;

// ======================================================
// PASSO 2 — FORM COM OS DADOS DO IRMÃO
// ======================================================
if ($passo === 'dados' && $irmao):

    $info = $this->Html->div(
        'alert alert-info mt-3',
        '<strong>Irmão encontrado:</strong><br>' .
        h($irmao->nome) . '<br>CIM: ' . h($irmao->cim)
    );

    $form = $this->Metronic->formCreate(null, $formOptions);

    $form .= $flash;
    $form .= $info;

    $form .= $this->Metronic->input('email', [
        'label' => 'E-mail',
        'required' => true,
        'value' => $irmao->email,
    ]);

    $form .= $this->Metronic->input('telefone', [
        'label' => 'Telefone',
        'value' => $irmao->telefone,
    ]);

    $form .= $this->Metronic->input('senha', [
        'label' => 'Senha',
        'type' => 'password',
        'required' => true,
    ]);

    $form .= $this->Metronic->input('senha_confirm', [
        'label' => 'Confirme a Senha',
        'type' => 'password',
        'required' => true,
    ]);

    $form .= $this->Form->hidden('passo', ['value' => 'dados']);
    $form .= $this->Form->hidden('irmao_id', ['value' => $irmao->id]);

    $form .= $this->Html->div(
        'm-login__form-action',
        $this->Metronic->link('Concluir Cadastro', [
            'id' => 'm_login_signin_submit',
            'post-url' => $this->Url->build('/cadastro-acesso'),
            'class' => 'btn btn-success m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary',
        ])
    );

    $form .= $this->Form->end();

    $this->assign('form', $this->Html->div('m-login__signin', $logo . $form));

endif;

$this->Metronic->buffer('$(".data-toggle").on("click", function() {
    $(this).children().toggleClass("la-eye-slash");
    let input = $(this).parent().prev();
    if (input.attr("type") === "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});');
