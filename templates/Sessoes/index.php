<?php
$this->extend('MetronicV4.Pages/index');
$this->assign('pageTitle', 'Sessões');
$this->assign(
    'singleActions',
    $this->Metronic->deleteButton()
);

$this->assign('addButton', $this->Metronic->addButton());

$this->assign(
    'filter',
    $this->Metronic->input('Sessoes.data_sessao') .
    $this->Html->div('col-sm-5', $this->Metronic->filterButton())
);

$dataHeader = $this->Metronic->pageSort('data_sessao', 'Data da Sessão');
$tipoHeader = $this->Metronic->pageSort('tipo', 'Tipo');
$lojaHeader = $this->Metronic->pageSort('Loja.nome', 'Loja');

$tableHeaders = [
    $dataHeader,
    $tipoHeader,
    $lojaHeader,
];

array_unshift($tableHeaders, [$this->Metronic->allRowCheckbox() => ['width' => '5%']]);
array_push($tableHeaders, ['' => ['width' => '5%']]);

$this->assign('tableHeaders', $this->Html->tableHeaders($tableHeaders, ['role' => 'row', 'class' => '']));

$cells = [];
foreach ($sessoes as $i => $sessao) {
    $cells[] = [
        h($sessao->data_sessao->format('d/m/Y')),
        h($sessao->tipo),
        h($sessao->loja->nome ?? '-'),
    ];
    array_unshift($cells[$i], $this->Metronic->rowCheckbox("Sessoes.$i.id", $sessao->id));
    array_push($cells[$i], $this->Metronic->editButton($sessao->id));
}

$this->assign('tableCells', $this->Html->tableCells(
    $cells,
    ['role' => 'row', 'class' => 'odd'],
    ['role' => 'row', 'class' => 'even']
));
