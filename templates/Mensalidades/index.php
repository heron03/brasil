<?php
$this->extend('MetronicV4.Pages/index');
$this->assign('pageTitle', 'Mensalidades');

$this->assign('singleActions', $this->Metronic->deleteButton());

$this->assign('printButton', $this->Metronic->printButton([
    'url' => [
        'action' => 'relatorio',
        '?' => $this->request->getQueryParams(),
    ],
    'class' => 'btn btn-metal m-btn m-btn--air m-btn--icon-only',
]));

$session = $this->getRequest()->getSession();
$dataInicial = date('Y/m/d', strtotime($session->read('Mensalidades.data_inicial')));
$dataFinal = date('Y/m/d', strtotime($session->read('Mensalidades.data_final')));
if ($session->read('Mensalidades.data_inicial') == null) {
    $dataInicial = date('Y/m/d', strtotime('-1 year'));
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

$this->assign('tabs', $this->Metronic->filterTabs('Mensalidades.pago', ['Todos' => 'Todos', 'Pago' => 1, 'Não Pago' => 0]));

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
array_push($tableHeaders, ['' => ['width' => '1%']]);

$this->assign('tableHeaders', $this->Html->tableHeaders($tableHeaders, ['role' => 'row', 'class' => '']));

$cells = [];
foreach ($mensalidades as $i => $mensalidade) {
    $comp  = $mensalidade->mes_referencia ? $mensalidade->mes_referencia->format('m/Y') : '-';
    $pagto = $mensalidade->data_pagamento ? $mensalidade->data_pagamento->format('d/m/Y') : '-';
    $valorTotal = (float)($mensalidade->valor ?? 0)
        + (float)($mensalidade->mutua ?? 0)
        + (float)($mensalidade->capitacao ?? 0)
        + (float)($mensalidade->diversos ?? 0)
        + (float)($mensalidade->reserva ?? 0);

    $cells[] = [
        h($mensalidade->irmao->nome ?? '-'),
        h($comp),
        'R$ ' . number_format($valorTotal, 2, ',', '.'),
        'R$ ' . number_format((float)($mensalidade->valor_pago ?? 0), 2, ',', '.'),
        $mensalidade->pago ? 'Sim' : 'Não',
        h($pagto),
    ];
    array_unshift($cells[$i], $this->Metronic->rowCheckbox("Mensalidades.$i.id", $mensalidade->id));

    $session = $this->getRequest()->getSession();
    if ($session->read('Auth.nivel') === 'Gestor') {

        if (!$mensalidade->pago) {
            $cells[$i][] = $this->Metronic->link('Receber Mensalidade', [
                'escape' => false,
                'data-original-title' => 'Receber Mensalidade',
                'data-toggle' => 'm-tooltip',
                'class' => 'm-btn m-btn--icon-only btn btn-success',
                'url' => '/mensalidades/receber/' . $mensalidade->id,
            ]);
            $cells[$i][] = '';
        } else {
            $cells[$i][] = $this->Metronic->link('Recibo de Mensalidade', [
                'escape' => false,
                'target' => '_blank',
                'data-original-title' => 'Recibo de Mensalidade',
                'data-toggle' => 'm-tooltip',
                'class' => 'm-btn m-btn--icon-only btn btn-primary',
                'url' => '/mensalidades/recibo/' . $mensalidade->id,
            ]);
            array_push($cells[$i], $this->Metronic->editButton($mensalidade->id));
        }
    } else {
        $cells[$i][] = '';
        $cells[$i][] = '';
    }
}

$this->assign('tableCells', $this->Html->tableCells(
    $cells,
    ['role' => 'row', 'class' => 'odd'],
    ['role' => 'row', 'class' => 'even']
));
