<?php
$this->extend('MetronicV4.Pages/index');
$this->assign('pageTitle', 'Irmãos');
$this->assign(
    'singleActions',
    $this->Metronic->deleteButton()
);

$this->assign('addButton', $this->Metronic->addButton());

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
array_push($tableHeaders, ['' => ['width' => '2%']]);

$this->assign('tableHeaders', $this->Html->tableHeaders($tableHeaders, ['role' => 'row', 'class' => '']));

$cells = [];
foreach ($irmaos as $i => $irmao) {
    $cells[] = [
        h($irmao->nome),
        h($irmao->cim),
        h($irmao->cpf),
        h($irmao->ativo ? 'Ativo' : 'Inativo'),
    ];
    array_unshift($cells[$i], $this->Metronic->rowCheckbox("Irmaos.$i.id", $irmao->id));
    array_push($cells[$i], $this->Metronic->editButton($irmao->id));
}

$this->assign('tableCells', $this->Html->tableCells(
    $cells,
    ['role' => 'row', 'class' => 'odd'],
    ['role' => 'row', 'class' => 'even']
));
