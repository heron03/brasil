<?php
$this->extend('MetronicV4.Pages/index');
$this->assign('pageTitle', 'Movimentações de Caixa');
$this->assign(
    'singleActions',
    $this->Metronic->deleteButton()
);

$this->assign('addButton', $this->Metronic->addButton());

$session = $this->getRequest()->getSession();
$dataInicial = date('Y/m/d', strtotime($session->read('MovimentacoesCaixa.data_inicial')));
$dataFinal = date('Y/m/d', strtotime($session->read('MovimentacoesCaixa.data_final')));
if ($session->read('MovimentacoesCaixa.data_inicial') == null) {
    $dataInicial = date('Y/m/d', strtotime('-30 days'));
    $dataFinal = date('Y/m/d');
}

$this->assign(
    'filter',
    $this->Form->hidden('MovimentacoesCaixa.data_inicial', ['date-range-picker' => 'start', 'value' => $dataInicial]) .
    $this->Form->hidden('MovimentacoesCaixa.data_final', ['date-range-picker' => 'end', 'value' => $dataFinal]) .
    $this->Metronic->input('MovimentacoesCaixa.descricao') .
    $this->Html->div('col-md-3', $this->Metronic->dateRangePicker()) .
    $this->Html->div('col-sm-1', $this->Metronic->filterButton())
);

$this->assign('tabs', $this->Metronic->filterTabs('MovimentacoesCaixa.tipo', ['Todos' => 0, 'Entrada' => 'Entrada', 'Saída' => 'Saída']));

$dataHeader = $this->Metronic->pageSort('data_movimentacao', 'Data', ['width' => '6%']);
$descricaoHeader = $this->Metronic->pageSort('descricao', 'Descrição');
$valorHeader = $this->Metronic->pageSort('valor', 'Valor', ['width' => '7%']);
$tipoHeader = $this->Metronic->pageSort('tipo', 'Tipo', ['width' => '6%']);
$formaPagamentoHeader = $this->Metronic->pageSort('forma_pagamento', 'Forma Pagamento', ['width' => '7%']);
$irmaoHeader = $this->Metronic->pageSort('Irmaos.nome', 'Irmão', ['width' => '23%']);

$tableHeaders = [
    $irmaoHeader,
    $descricaoHeader,
    $tipoHeader,
    $valorHeader,
    $dataHeader,
    $formaPagamentoHeader,
];

array_unshift($tableHeaders, [$this->Metronic->allRowCheckbox() => ['width' => '1%']]);
array_push($tableHeaders, ['' => ['width' => '1%']]);

$this->assign('tableHeaders', $this->Html->tableHeaders($tableHeaders, ['role' => 'row', 'class' => '']));

$cells = [];
foreach ($movimentacoesCaixa as $i => $mov) {
    $cells[] = [
        h($mov->irmao->nome ?? '-'),
        h($mov->descricao),
        h(ucfirst($mov->tipo)),
        'R$ ' . number_format($mov->valor, 2, ',', '.'),
        $mov->data_movimentacao->format('d/m/Y'),
        h($mov->forma_pagamento ?? '-'),
    ];
    array_unshift($cells[$i], $this->Metronic->rowCheckbox("MovimentacoesCaixa.$i.id", $mov->id));
    array_push($cells[$i], $this->Metronic->editButton($mov->id));
}

$this->assign('tableCells', $this->Html->tableCells(
    $cells,
    ['role' => 'row', 'class' => 'odd'],
    ['role' => 'row', 'class' => 'even']
));
