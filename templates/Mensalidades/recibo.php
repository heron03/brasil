<?php
$path = ROOT . DS . 'templates' . DS . 'Pdf' . DS;


$fmtBRL = static function ($value): string {
    $value = (float)$value;
    return 'R$ ' . number_format($value, 2, ',', '.');
};

$meses = [
    1 => 'JANEIRO',
    2 => 'FEVEREIRO',
    3 => 'MARCO',
    4 => 'ABRIL',
    5 => 'MAIO',
    6 => 'JUNHO',
    7 => 'JULHO',
    8 => 'AGOSTO',
    9 => 'SETEMBRO',
    10 => 'OUTUBRO',
    11 => 'NOVEMBRO',
    12 => 'DEZEMBRO',
];

$irmao = $mensalidade->irmao ?? null;
$loja = $irmao ? ($irmao->loja ?? null) : null;
$irmaoNome = strtoupper(trim((string)($irmao->nome ?? '')));

// Ex.: JANEIRO/26
$mesRefRecibo = '';
if (!empty($mensalidade->mes_referencia)) {
    $mesNum = (int)$mensalidade->mes_referencia->format('n');
    $ano2 = (int)$mensalidade->mes_referencia->format('y');
    $mesRefRecibo = ($meses[$mesNum] ?? '') . '/' . str_pad((string)$ano2, 2, '0', STR_PAD_LEFT);
}

$dataPagamentoRecibo = '';
if (!empty($mensalidade->data_pagamento)) {
    $dataPagamentoRecibo = $mensalidade->data_pagamento->format('d/m/Y');
}

$titleParts = [];

$lojaTituloRecibo = 'Loja Maçonica "Brasil II" - MARÍLIA - SP';

$lojaEnderecoRecibo = 'Av. Mauá, 39 - Tel. (14) 3433-5001';


$valorBase = (float)($mensalidade->valor ?? 0);
$valorPago = (float)($mensalidade->valor_pago ?? 0);

$mutua = (float)($mensalidade->mutua ?? 0);
$capitacao = (float)($mensalidade->capitacao ?? 0);
$diversos = (float)($mensalidade->diversos ?? 0);
$reserva = (float)($mensalidade->reserva ?? 0);
$mensalidadeRecibo = $valorBase;

$totalGeral = $valorPago > 0 ? $valorPago : $valorBase;
$formaPagamento = (string)($mensalidade->forma_pagamento ?? '');

$record = $mensalidade->toArray();
$record['loja_titulo_recibo'] = $lojaTituloRecibo;
$record['loja_endereco_recibo'] = $lojaEnderecoRecibo;
$record['irmao_nome_recibo'] = $irmaoNome;
$record['mes_referencia_recibo'] = $mesRefRecibo;
$record['data_pagamento_recibo'] = $dataPagamentoRecibo;

$record['mensalidade_maconica_recibo_text'] = $fmtBRL($mensalidadeRecibo);
$record['mutua_maconica_recibo_text'] = $fmtBRL($mutua);
$record['capitacao_recibo_text'] = $fmtBRL($capitacao);
$record['diversos_recibo_text'] = $fmtBRL($diversos);
$record['reserva_recibo_text'] = $fmtBRL($reserva);
$record['total_geral_recibo_text'] = $fmtBRL($totalGeral);
$record['forma_pagamento_texto'] = $formaPagamento;

$settings = [
    'templateFile' => [
        'config' => $path . 'report-config-recibo.xml',
        'body' => $path . 'report-mensalidade-recibo-body.xml',
    ],
    'records' => [$record],
];

echo $this->Document->create($settings);
