<?php
$session = $this->getRequest()->getSession();

$title = $this->Html->tag('strong', 'Brasil II');
$menu = [
    ['title' => 'Configurações', 'submenu' => [
        ['title' => '1.1 Loja', 'url' => '/lojas'],
        ['title' => '1.2 Usuários', 'url' => '/usuarios'],
    ]],
    ['title' => 'Caixa', 'url' => '/movimentacoesCaixa'],
    ['title' => 'Irmãos', 'submenu' => [
        ['title' => 'Irmãos', 'url' => '/irmaos'],
        ['title' => 'Mensalidade', 'url' => '/mensalidades']],
    ],
    ['title' => 'Sessões', 'submenu' => [
        ['title' => 'Sessões', 'url' => '/sessoes'],
        ['title' => 'Presenças', 'url' => '/presencas']],
    ],
];



$userMenu = [
    ['icon' => 'flaticon-edit-1', 'title' => 'Alterar Senha', 'url' => '/usuarios/editSenha'],
    'separator',
    ['title' => 'Sair', 'url' => '/usuarios/logout']
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
            'name' => $session->read('Auth.nome'),
            'email' => $session->read('Auth.email'),
            'login' => $session->read('Auth.login'),
        ],
        'menu' => $userMenu,
    ],
];

echo $this->Metronic->header($elements);
