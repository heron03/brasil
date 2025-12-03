<?php
$session = $this->getRequest()->getSession();

$title = $this->Html->tag('strong', 'Brasil II');
if ($session->read('Auth.nivel') === 'Gestor') {
    $menu = [
        ['title' => 'Irmãos', 'url' => '/irmaos'],
        ['title' => 'Mensalidade', 'url' => '/mensalidades'],
        ['title' => 'Caixa', 'url' => '/movimentacoesCaixa'],
    ];
} else {
    $menu = [
        ['title' => 'Irmãos', 'url' => '/irmaos'],
        ['title' => 'Mensalidade', 'url' => '/mensalidades'],
    ];
}

$userMenu = [
    ['icon' => 'flaticon-edit-1', 'title' => 'Alterar Senha', 'url' => '/irmaos/editSenha'],
    'separator',
    ['title' => 'Sair', 'url' => '/irmaos/logout']
];

$elements = [
    'brand' => [
        'text' => $this->Html->div(
            'm-stack m-stack--ver m-stack--general m-stack--inline',
            $this->Html->div(
                'm-stack__item m-stack__item--middle m-brand__tools col-sm-',
                $this->Html->tag('h4', $title, [
                    'class' => 'm--font-metal header-mobile-title',
                    'style' => 'width: 600px; text-align:left;',
                ])
            )
        ),
    ],
    'menu' => $menu,
    'userProfile' => [
        'user' => [
            'login' => $session->read('Auth.nome'),
            'name' => $session->read('Auth.nome'),
            'email' => $session->read('Auth.email'),
        ],
        'menu' => $userMenu,
    ],
];

echo $this->Metronic->header($elements);
