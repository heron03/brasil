<?php
$this->extend('MetronicV4.Pages/index');
$this->assign('pageTitle', 'Irmãos');
$this->assign(
    'singleActions',
    $this->Metronic->deleteButton()
);

$session = $this->getRequest()->getSession();
if ($session->read('Auth.nivel') === 'Gestor') {
    $this->assign('addButton', $this->Metronic->addButton());
}

$this->assign(
    'filter',
    $this->Metronic->input('Irmaos.nome') .
        $this->Html->div('col-sm-5', $this->Metronic->filterButton())
);

$nomeHeader = $this->Metronic->pageSort('nome', 'Nome');
$cimHeader = $this->Metronic->pageSort('cim', 'CIM', ['width' => '5%']);
$cpfHeader = $this->Metronic->pageSort('cpf', 'CPF', ['width' => '11%']);
$ativoHeader = $this->Metronic->pageSort('ativo', 'Ativo', ['width' => '5%']);

$tableHeaders = [
    $nomeHeader,
    $cimHeader,
    $cpfHeader,
    $ativoHeader,
];

array_unshift($tableHeaders, [$this->Metronic->allRowCheckbox() => ['width' => '2%']]);
array_push($tableHeaders, ['' => ['width' => '5%']]);
array_push($tableHeaders, ['' => ['width' => '2%']]);

$this->assign('tableHeaders', $this->Html->tableHeaders($tableHeaders, ['role' => 'row', 'class' => '']));

$cells = [];
$anoAtual = (int)date('Y');
$anoInicialRelatorio = 2025;
foreach ($irmaos as $i => $irmao) {
    $cells[] = [
        h($irmao->nome),
        h($irmao->cim),
        h($irmao->cpf),
        h($irmao->ativo ? 'Ativo' : 'Inativo'),
    ];
    $opcoesRelatorio = [];
    for ($ano = $anoAtual; $ano >= $anoInicialRelatorio; $ano--) {
        $opcoesRelatorio["Mensalidades {$ano}"] = [
            'url' => '/mensalidades/anuais/' . $irmao->id . '?ano=' . $ano,
            'target' => '_blank',
            'escape' => true, 
            'title' => "Mensalidades {$ano}",
        ];
    }

    $cells[$i][] = $this->Metronic->buttonDropdown(
        [
            'button' => [
                // 'class' => 'btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill',
                'title' => 'Mensalidades',
            ],
            'menu' => $opcoesRelatorio
        ]
    );
    array_unshift($cells[$i], $this->Metronic->rowCheckbox("Irmaos.$i.id", $irmao->id));
    array_push($cells[$i], $this->Metronic->editButton($irmao->id));
}

$this->assign('tableCells', $this->Html->tableCells(
    $cells,
    ['role' => 'row', 'class' => 'odd'],
    ['role' => 'row', 'class' => 'even']
));
