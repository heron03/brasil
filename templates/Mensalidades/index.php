<?php
$this->extend('MetronicV4.Pages/index');
$this->assign('pageTitle', 'Mensalidades');

$this->assign('singleActions', $this->Metronic->deleteButton());
$this->assign('addButton', $this->Metronic->addButton());

$this->assign(
    'filter',
    $this->Metronic->input('Mensalidades.irmao_id') .
    $this->Html->div('col-sm-5', $this->Metronic->filterButton())
);

$irmaoHeader          = $this->Metronic->pageSort('Irmaos.nome', 'Irmão');
$competenciaHeader    = $this->Metronic->pageSort('mes_referencia', 'Competência');
$valorHeader          = $this->Metronic->pageSort('valor', 'Valor');
$pagoHeader           = $this->Metronic->pageSort('pago', 'Pago');
$dataPagamentoHeader  = $this->Metronic->pageSort('data_pagamento', 'Pagamento');

$tableHeaders = [
    $irmaoHeader,
    $competenciaHeader,
    $valorHeader,
    $pagoHeader,
    $dataPagamentoHeader,
];

array_unshift($tableHeaders, [$this->Metronic->allRowCheckbox() => ['width' => '5%']]);
array_push($tableHeaders, ['' => ['width' => '5%']]);

$this->assign('tableHeaders', $this->Html->tableHeaders($tableHeaders, ['role' => 'row', 'class' => '']));

$cells = [];
foreach ($mensalidades as $i => $mensalidade) {
    $comp  = $mensalidade->mes_referencia ? $mensalidade->mes_referencia->format('m/Y') : '-';
    $pagto = $mensalidade->data_pagamento ? $mensalidade->data_pagamento->format('d/m/Y') : '-';

    $cells[] = [
        h($mensalidade->irmao->nome ?? '-'),
        h($comp),
        'R$ ' . number_format((float)$mensalidade->valor, 2, ',', '.'),
        $mensalidade->pago ? 'Sim' : 'Não',
        h($pagto),
    ];
    array_unshift($cells[$i], $this->Metronic->rowCheckbox("Mensalidades.$i.id", $mensalidade->id));
    array_push($cells[$i], $this->Metronic->editButton($mensalidade->id));
}

$this->assign('tableCells', $this->Html->tableCells(
    $cells,
    ['role' => 'row', 'class' => 'odd'],
    ['role' => 'row', 'class' => 'even']
));
