<?php
$this->extend('MetronicV4.Pages/index');
$this->assign('pageTitle', 'Movimentações de Caixa');
$this->assign(
    'singleActions',
    $this->Metronic->deleteButton()
);

$this->assign('addButton', $this->Metronic->addButton());

$this->assign(
    'filter',
    $this->Metronic->input('MovimentacoesCaixa.descricao') .
    $this->Html->div('col-sm-5', $this->Metronic->filterButton())
);

$dataHeader = $this->Metronic->pageSort('data_movimentacao', 'Data');
$descricaoHeader = $this->Metronic->pageSort('descricao', 'Descrição');
$valorHeader = $this->Metronic->pageSort('valor', 'Valor');
$tipoHeader = $this->Metronic->pageSort('tipo', 'Tipo');

$tableHeaders = [
    $dataHeader,
    $descricaoHeader,
    $valorHeader,
    $tipoHeader,
];

array_unshift($tableHeaders, [$this->Metronic->allRowCheckbox() => ['width' => '5%']]);
array_push($tableHeaders, ['' => ['width' => '5%']]);

$this->assign('tableHeaders', $this->Html->tableHeaders($tableHeaders, ['role' => 'row', 'class' => '']));

$cells = [];
foreach ($movimentacoesCaixa as $i => $mov) {
    $cells[] = [
        $mov->data_movimentacao->format('d/m/Y'),
        h($mov->descricao),
        'R$ ' . number_format($mov->valor, 2, ',', '.'),
        h(ucfirst($mov->tipo)),
    ];
    array_unshift($cells[$i], $this->Metronic->rowCheckbox("MovimentacoesCaixa.$i.id", $mov->id));
    array_push($cells[$i], $this->Metronic->editButton($mov->id));
}

$this->assign('tableCells', $this->Html->tableCells(
    $cells,
    ['role' => 'row', 'class' => 'odd'],
    ['role' => 'row', 'class' => 'even']
));
