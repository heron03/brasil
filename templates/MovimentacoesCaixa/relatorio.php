<?php
/**
 * @var \App\View\AppView $this
 * @var array $credencials
 */

$path = ROOT . DS . 'templates' . DS . 'Pdf' . DS;

$session = $this->getRequest()->getSession();
$dataInicial = date('d/m/Y', strtotime($session->read('MovimentacoesCaixa.data_inicial')));
$dataFinal = date('d/m/Y', strtotime($session->read('MovimentacoesCaixa.data_final')));

if ($session->read('MovimentacoesCaixa.data_inicial') == null) {
    $dataInicial = date('01/01/2021');
    $dataFinal = date('d/m/Y');
}

foreach ($movimentacoesCaixa as $key => $value) {
    if ($value['tipo'] == 'Saída') {
        $movimentacoesCaixa[$key]['valor'] = -1 * $value['valor'];
    }
}


$settings = [
    'orientation' => 'L',
    'templateFile' => [
        'config' => $path . 'report-config.xml',
        'header' => $path . 'report-header.xml',
        'columnTitles' => $path . 'report-caixa-column-titles.xml',
        'body' => $path . 'report-caixa-body.xml',
        'sumary' => $path . 'report-caixa-sumary.xml',
        'footer' => $path . 'report-footer.xml',
    ],
    'header' => [
        'title' => 'Movimentações do Caixa',
        'periodo' => 'Referente ao Período ' . $dataInicial . ' à ' . $dataFinal,
    ],
    'records' => $movimentacoesCaixa,
];
echo $this->Report->create($settings);