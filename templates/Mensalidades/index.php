<?php
$this->extend('MetronicV4.Pages/index');
$this->assign('pageTitle', 'Mensalidades');

$this->assign('singleActions', $this->Metronic->deleteButton());

$session = $this->getRequest()->getSession();
$dataInicial = date('Y/m/d', strtotime($session->read('Mensalidades.data_inicial')));
$dataFinal = date('Y/m/d', strtotime($session->read('Mensalidades.data_final')));
if ($session->read('Mensalidades.data_inicial') == null) {
    $dataInicial = date('Y/m/d', strtotime('-30 days'));
    $dataFinal = date('Y/m/d');
}

$this->assign(
    'filter',
    $this->Form->hidden('Mensalidades.data_inicial', ['date-range-picker' => 'start', 'value' => $dataInicial]) .
    $this->Form->hidden('Mensalidades.data_final', ['date-range-picker' => 'end', 'value' => $dataFinal]) .
    $this->Metronic->input('Mensalidades.filtro') .
    $this->Html->div('col-md-3', $this->Metronic->dateRangePicker()) .
    $this->Html->div('col-sm-1', $this->Metronic->filterButton())
);

$irmaoHeader = $this->Metronic->pageSort('Irmaos.nome', 'Irmão');
$competenciaHeader = $this->Metronic->pageSort('mes_referencia', 'Competência');
$valorHeader = $this->Metronic->pageSort('valor', 'Valor');
$valorPagoHeader = $this->Metronic->pageSort('valor_pago', 'Valor Pago');
$pagoHeader = $this->Metronic->pageSort('pago', 'Pago');
$dataPagamentoHeader = $this->Metronic->pageSort('data_pagamento', 'Pagamento');

$tableHeaders = [
    $irmaoHeader,
    $competenciaHeader,
    $valorHeader,
    $valorPagoHeader,
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
        'R$ ' . number_format((float)($mensalidade->valor_pago ?? 0), 2, ',', '.'),
        $mensalidade->pago ? 'Sim' : 'Não',
        h($pagto),
    ];
    array_unshift($cells[$i], $this->Metronic->rowCheckbox("Mensalidades.$i.id", $mensalidade->id));
    if (!$mensalidade->pago) {
        $cells[$i][] = $this->Metronic->link('Receber Mensalidade', [
            'escape' => false,
            'data-original-title' => 'Receber Mensalidade',
            'data-toggle' => 'm-tooltip',
            'class' => 'm-btn m-btn--icon-only btn btn-success',
            'url' => '/mensalidades/receber/' . $mensalidade->id,
        ]);
    } else {
        $cells[$i][] = '';
    }
}

$this->assign('tableCells', $this->Html->tableCells(
    $cells,
    ['role' => 'row', 'class' => 'odd'],
    ['role' => 'row', 'class' => 'even']
));
