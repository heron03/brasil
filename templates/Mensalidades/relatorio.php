<?php
/**
 * @var \App\View\AppView $this
 * @var array $credencials
 */

$path = ROOT . DS . 'templates' . DS . 'Pdf' . DS;

$session = $this->getRequest()->getSession();
$dataInicial = date('d/m/Y', strtotime($session->read('Mensalidades.data_inicial')));
$dataFinal = date('d/m/Y', strtotime($session->read('Mensalidades.data_final')));

$valorPagoTotal = 0;
$valorTotal = 0;
foreach ($mensalidades as $key => $value) {
    $mensalidades[$key]['mes_referencia'] = $value['mes_referencia']->i18nFormat('MMMM / yy', 'pt_BR');
    $tipo = 'Não Pago';
    if ($value['pago'] == 1) {
        $tipo = 'Pago';
    }
    $mensalidades[$key]['pago'] = $tipo;
    if ($mensalidades[$key]['data_pagamento'] != null) {
        $mensalidades[$key]['data_pagamento'] = $mensalidades[$key]['data_pagamento']->i18nFormat('dd/MM/yyyy');
    }
}

if ($session->read('Mensalidades.data_inicial') == null) {
    $dataInicial = date('01/01/2021');
    $dataFinal = date('d/m/Y');
}

$settings = [
    'orientation' => 'L',
    'templateFile' => [
        'config' => $path . 'report-config.xml',
        'header' => $path . 'report-header.xml',
        'columnTitles' => $path . 'report-mensalidade-column-titles.xml',
        'body' => $path . 'report-mensalidade-body.xml',
        'sumary' => $path . 'report-sumary.xml',
        'footer' => $path . 'report-mensalidade-footer.xml',
    ],
    'header' => [
        'title' => 'Mensalidades',
        'periodo' => 'Referente ao Período ' . $dataInicial . ' à ' . $dataFinal,
    ],
    'records' => $mensalidades,
];
echo $this->Report->create($settings);