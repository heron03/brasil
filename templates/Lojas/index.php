<?php
$this->extend('MetronicV4.Pages/index');
$this->assign('pageTitle', 'Lojas');
$this->assign(
    'singleActions',
    $this->Metronic->deleteButton()
);

$this->assign('addButton', $this->Metronic->addButton());

$this->assign(
    'filter',
    $this->Metronic->input('Lojas.nome_loja') .
    $this->Html->div('col-sm-5', $this->Metronic->filterButton())
);

$nomeHeader = $this->Metronic->pageSort('nome', 'Nome da Loja');
$cidadeHeader = $this->Metronic->pageSort('cidade', 'Cidade');

$tableHeaders = [
    $nomeHeader,
    $cidadeHeader,
];

array_unshift($tableHeaders, [$this->Metronic->allRowCheckbox() => ['width' => '5%']]);
array_push($tableHeaders, ['' => ['width' => '5%']]);

$this->assign('tableHeaders', $this->Html->tableHeaders($tableHeaders, ['role' => 'row', 'class' => '']));

$cells = [];
foreach ($lojas as $i => $loja) {
    $cells[] = [
        h($loja->nome),
        h($loja->cidade),
    ];
    array_unshift($cells[$i], $this->Metronic->rowCheckbox("Lojas.$i.id", $loja->id));
    array_push($cells[$i], $this->Metronic->editButton($loja->id));
}

$this->assign('tableCells', $this->Html->tableCells(
    $cells,
    ['role' => 'row', 'class' => 'odd'],
    ['role' => 'row', 'class' => 'even']
));
