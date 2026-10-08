<?php
use App\Pdf\ReciboLote;

$path = ROOT . DS . 'templates' . DS . 'Pdf' . DS;
$lote = ReciboLote::montar(
    $mensalidades,
    $path . 'report-config-recibos.xml'
);

try {
    $settings = [
        'templateFile' => $lote['records'][0]['templateFile'],
        'records' => $lote['records'],
    ];
    echo $this->Document->create($settings);
} finally {
    foreach ($lote['arquivos'] as $arquivo) {
        if (is_file($arquivo)) {
            unlink($arquivo);
        }
    }
}
