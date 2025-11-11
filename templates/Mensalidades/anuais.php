<?php
use Cake\I18n\FrozenDate;

// ===== 1) Descobrir ano e indivíduo a partir do próprio resultado =====
$primeiro = isset($mensalidadesPeriodo) && method_exists($mensalidadesPeriodo, 'first')
    ? $mensalidadesPeriodo->first()
    : (is_array($mensalidadesPeriodo) ? reset($mensalidadesPeriodo) : null);

$ano = (int)(
    $primeiro && !empty($primeiro->mes_referencia)
        ? $primeiro->mes_referencia->format('Y')
        : date('Y')
);

$irmaoId   = (int)($primeiro && isset($primeiro->irmao_id) ? $primeiro->irmao_id : 0);
$irmaoNome = (string)(
    $primeiro && isset($primeiro->irmao) && isset($primeiro->irmao->nome)
        ? $primeiro->irmao->nome
        : ''
);

// ===== 2) Acumular valor pago por mês (1..12) =====
$porMes = array_fill(1, 12, 0.0);
$dataPagamento = [];

if (!empty($mensalidadesPeriodo)) {
    foreach ($mensalidadesPeriodo as $m) {
        // garante mesmo indivíduo (se vier misto por algum motivo)
        if ($irmaoId && (int)$m->irmao_id !== $irmaoId) {
            continue;
        }

        // considerar apenas pagos; se preferir qualquer valor_pago > 0, troque a condição
        if (empty($m->pago)) {
            continue;
        }

        // proteger contra null
        if (empty($m->mes_referencia)) {
            continue;
        }

        $mes = (int)$m->mes_referencia->format('n');
        $porMes[$mes] += (float)($m->valor_pago ?? 0);

        if (!empty($m->data_pagamento)) {
            $dataPagamento[$mes] = $m->data_pagamento->format('d/m/Y');
        }
    }
}

// ===== 3) Montar $records (um item por mês) para o Report =====
$records = [];
for ($m = 1; $m <= 12; $m++) {
    // i18nFormat($format, $timezone = null, $locale = null)
    $labelMes = FrozenDate::create($ano, $m, 1)->i18nFormat('MMMM / yy', null, 'pt_BR');
    $records[] = [
        'mes'            => $labelMes,                                 // ex.: "setembro / 25"
        'valor_pago'     => number_format((float)$porMes[$m], 2, ',', '.'),
        'valor_pago_num' => (float)$porMes[$m],                         // numérico (útil p/ somatórios)
        'data_pagamento' => $dataPagamento[$m] ?? '',
    ];
}

// ===== 4) Cabeçalho do período (mantendo sua lógica de sessão) =====
$session = $this->getRequest()->getSession();
$dataInicial = '01/01/' . $ano;
$dataFinal   = '31/12/' . $ano;

// ===== 5) Continuar com o que você já tinha =====
$path = ROOT . DS . 'templates' . DS . 'Pdf' . DS;

$settings = [
    'orientation' => 'P',
    'templateFile' => [
        'config'        => $path . 'report-config.xml',
        'header'        => $path . 'report-header-irmaos.xml',
        'columnTitles'  => $path . 'report-mensalidade-irmao-column-titles.xml',
        'body'          => $path . 'report-mensalidade-irmao-body.xml',
        'sumary'        => $path . 'report-sumary-irmaos.xml',
        'footer'        => $path . 'report-mensalidade-irmao-footer.xml',
    ],
    'header' => [
        'title'   => 'Mensalidades do Irmão ' . $irmaoNome,
        'periodo' => 'Referente ao Período ' . $dataInicial . ' à ' . $dataFinal,
    ],
    'records' => $records, // <<< só mês + valor pago
];

echo $this->Report->create($settings);
