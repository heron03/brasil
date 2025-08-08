<?php
$this->extend('MetronicV4.Pages/index');
$this->assign('pageTitle', 'Mensalidades');
$this->assign(
    'singleActions',
    $this->Metronic->deleteButton()
);

$this->assign('addButton', $this->Metronic->addButton());

$this->assign(
    'filter',
    $this->Metronic->input('Mensalidades.irmao_id') .
    $this->Html->div('col-sm-5', $this->Metronic->filterButton())
);

$irmaoHeader = $this->Metronic->pageSort('Irmao.nome', 'Irmão');
$dataVencimentoHeader = $this->Metronic->pageSort('data_vencimento', 'Vencimento');
$valorHeader = $this->Metronic->pageSort('valor', 'Valor');
$pagoHeader = $this->Metronic->pageSort('pago', 'Pago');
$dataPagamentoHeader = $this->Metronic->pageSort('data_pagamento', 'Pagamento');
$lojaHeader = $this->Metronic->pageSort('Loja.nome', 'Loja');

$tableHeaders = [
    $irmaoHeader,
    $dataVencimentoHeader,
    $valorHeader,
    $pagoHeader,
    $dataPagamentoHeader,
    $lojaHeader,
];

array_unshift($tableHeaders, [$this->Metronic->allRowCheckbox() => ['width' => '5%']]);
array_push($tableHeaders, ['' => ['width' => '5%']]);

$this->assign('tableHeaders', $this->Html->tableHeaders($tableHeaders, ['role' => 'row', 'class' => '']));

$cells = [];
foreach ($mensalidades as $i => $mensalidade) {
    $cells[] = [
        h($mensalidade->irmao->nome ?? '-'),
        h($mensalidade->data_vencimento->format('d/m/Y')),
        'R$ ' . number_format($mensalidade->valor, 2, ',', '.'),
        $mensalidade->pago ? 'Sim' : 'Não',
        $mensalidade->data_pagamento ? h($mensalidade->data_pagamento->format('d/m/Y')) : '-',
        h($mensalidade->loja->nome ?? '-'),
    ];
    array_unshift($cells[$i], $this->Metronic->rowCheckbox("Mensalidades.$i.id", $mensalidade->id));
    array_push($cells[$i], $this->Metronic->editButton($mensalidade->id));
}

$this->assign('tableCells', $this->Html->tableCells(
    $cells,
    ['role' => 'row', 'class' => 'odd'],
    ['role' => 'row', 'class' => 'even']
));
