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
$cpfHeader = $this->Metronic->pageSort('cpf', 'CPF');
$telefoneHeader = $this->Metronic->pageSort('telefone', 'Telefone');
$emailHeader = $this->Metronic->pageSort('email', 'Email');
$lojaHeader = $this->Metronic->pageSort('Loja.nome', 'Loja');

$tableHeaders = [
    $nomeHeader,
    $cpfHeader,
    $telefoneHeader,
    $emailHeader,
    $lojaHeader,
];

array_unshift($tableHeaders, [$this->Metronic->allRowCheckbox() => ['width' => '5%']]);
array_push($tableHeaders, ['' => ['width' => '5%']]);

$this->assign('tableHeaders', $this->Html->tableHeaders($tableHeaders, ['role' => 'row', 'class' => '']));

$cells = [];
foreach ($irmaos as $i => $irmao) {
    $cells[] = [
        h($irmao->nome),
        h($irmao->cpf),
        h($irmao->telefone),
        h($irmao->email),
        h($irmao->loja->nome ?? '-'),
    ];
    array_unshift($cells[$i], $this->Metronic->rowCheckbox("Irmaos.$i.id", $irmao->id));
    array_push($cells[$i], $this->Metronic->editButton($irmao->id));
}

$this->assign('tableCells', $this->Html->tableCells(
    $cells,
    ['role' => 'row', 'class' => 'odd'],
    ['role' => 'row', 'class' => 'even']
));
