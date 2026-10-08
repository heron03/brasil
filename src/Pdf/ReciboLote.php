<?php
declare(strict_types=1);

namespace App\Pdf;

use App\Model\Entity\Mensalidade;

class ReciboLote
{
    private const POR_PAGINA = 3;

    /**
     * @param array<\App\Model\Entity\Mensalidade> $mensalidades
     * @return array{records: array<int, array<string, mixed>>, arquivos: array<int, string>}
     */
    public static function montar(array $mensalidades, string $configFile, ?string $diretorio = null): array
    {
        $registros = [];
        foreach ($mensalidades as $mensalidade) {
            $registros[] = self::dados($mensalidade);
        }

        return self::deRegistros($registros, $configFile, $diretorio);
    }

    /**
     * @param array<int, array<string, string>> $registros
     * @return array{records: array<int, array<string, mixed>>, arquivos: array<int, string>}
     */
    public static function deRegistros(array $registros, string $configFile, ?string $diretorio = null): array
    {
        $diretorio = $diretorio ?? (TMP . 'recibos');
        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0775, true);
        }

        $token = str_replace('.', '', uniqid('recibo', true));
        $paginas = array_chunk(array_values($registros), self::POR_PAGINA);
        $corpos = [];
        $arquivos = [];
        $records = [];

        foreach ($paginas as $pagina) {
            $quantidade = count($pagina);
            if (!isset($corpos[$quantidade])) {
                $arquivo = $diretorio . DS . $token . '-' . $quantidade . '.xml';
                file_put_contents($arquivo, self::corpo($quantidade));
                $corpos[$quantidade] = $arquivo;
                $arquivos[] = $arquivo;
            }

            $record = [];
            foreach ($pagina as $indice => $dados) {
                $sufixo = '_' . ($indice + 1);
                foreach ($dados as $campo => $valor) {
                    $record[$campo . $sufixo] = $valor;
                }
            }
            $record['templateFile'] = [
                'config' => $configFile,
                'body' => $corpos[$quantidade],
            ];
            $records[] = $record;
        }

        return [
            'records' => $records,
            'arquivos' => $arquivos,
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function dados(Mensalidade $mensalidade): array
    {
        $meses = [
            1 => 'JANEIRO',
            2 => 'FEVEREIRO',
            3 => 'MARÇO',
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
        $mesRefRecibo = '';
        if (!empty($mensalidade->mes_referencia)) {
            $mesNum = (int)$mensalidade->mes_referencia->format('n');
            $ano2 = (int)$mensalidade->mes_referencia->format('y');
            $mesRefRecibo = ($meses[$mesNum] ?? '') . '/' . str_pad((string)$ano2, 2, '0', STR_PAD_LEFT);
        }

        $dataPagamentoRecibo = '';
        if (!empty($mensalidade->data_pagamento)) {
            $dataPagamentoRecibo = 'Pago em ' . $mensalidade->data_pagamento->format('d/m/Y');
        }

        $valorBase = (float)($mensalidade->valor ?? 0);
        $valorPago = (float)($mensalidade->valor_pago ?? 0);
        $totalGeral = $valorPago > 0 ? $valorPago : $valorBase;

        return [
            'loja_titulo_recibo' => 'Loja Maçônica "Brasil II" - MARÍLIA - SP',
            'loja_endereco_recibo' => 'Av. Mauá, 39 - Tel. (14) 3433-5001',
            'irmao_nome_recibo' => strtoupper(trim((string)($irmao->nome ?? ''))),
            'irmao_cim' => trim((string)($irmao->cim ?? '')),
            'irmao_cpf' => trim((string)($irmao->cpf ?? '')),
            'mes_referencia_recibo' => $mesRefRecibo,
            'data_pagamento_recibo' => $dataPagamentoRecibo,
            'mensalidade_maconica_recibo_text' => self::moeda($valorBase),
            'mutua_maconica_recibo_text' => self::moeda($mensalidade->mutua ?? 0),
            'capitacao_recibo_text' => self::moeda($mensalidade->capitacao ?? 0),
            'diversos_recibo_text' => self::moeda($mensalidade->diversos ?? 0),
            'reserva_recibo_text' => self::moeda($mensalidade->reserva ?? 0),
            'total_geral_recibo_text' => self::moeda($totalGeral),
            'forma_pagamento_texto' => (string)($mensalidade->forma_pagamento ?? ''),
            'assinatura_arquivo' => str_replace('\\', '/', WWW_ROOT . 'assinatura.jpeg'),
        ];
    }

    public static function corpo(int $quantidade): string
    {
        $quantidade = max(1, min(self::POR_PAGINA, $quantidade));
        $linhas = [];
        for ($numero = 1; $numero <= $quantidade; $numero++) {
            if ($numero > 1) {
                foreach (self::separador() as $linha) {
                    $linhas[] = $linha;
                }
            }
            foreach (self::bloco($numero) as $linha) {
                $linhas[] = $linha;
            }
        }

        $xml = "<body>\n";
        foreach ($linhas as $indice => $linha) {
            $tag = 'line' . ($indice + 1);
            $xml .= "<{$tag}>\n<line>\n{$linha}</line>\n</{$tag}>\n";
        }
        $xml .= "</body>\n";

        return $xml;
    }

    private static function moeda($valor): string
    {
        return 'R$ ' . number_format((float)$valor, 2, ',', '.');
    }

    /**
     * @return array<int, string>
     */
    private static function separador(): array
    {
        return [
            self::linha([
                ['lineWidth' => '205'],
            ]),
            self::linha([
                ['lineWidth' => '205', 'border' => 'T'],
            ]),
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function bloco(int $numero): array
    {
        $linhas = [
            [
                self::celula('TLR', '100', [
                    'fontSizePt' => '12',
                    'fontStyle' => 'B',
                    'fontFamily' => 'Times',
                    'align' => 'C',
                    'fieldName' => 'loja_titulo_recibo',
                ]),
            ],
            [
                self::celula('BLR', '100', [
                    'fontSizePt' => '10',
                    'fontFamily' => 'Times',
                    'fontStyle' => 'B',
                    'align' => 'C',
                    'fieldName' => 'loja_endereco_recibo',
                ]),
            ],
            [
                self::celula('BL', '8', [
                    'fontSizePt' => '10',
                    'fontStyle' => 'B',
                    'fontFamily' => 'Times',
                    'align' => 'L',
                ], 'I.R.'),
                self::celula('BR', '92', [
                    'fontSizePt' => '12',
                    'fontFamily' => 'Times',
                    'align' => 'L',
                    'fieldName' => 'irmao_nome_recibo',
                ]),
            ],
            [
                self::celula('BL', '12', [
                    'fontSizePt' => '10',
                    'fontStyle' => 'B',
                    'fontFamily' => 'Times',
                    'align' => 'L',
                ], 'CIM'),
                self::celula('BR', '38', [
                    'fontSizePt' => '12',
                    'fontFamily' => 'Times',
                    'align' => 'L',
                    'fieldName' => 'irmao_cim',
                ]),
                self::celula('BL', '12', [
                    'fontSizePt' => '10',
                    'fontStyle' => 'B',
                    'fontFamily' => 'Times',
                    'align' => 'L',
                ], 'CPF'),
                self::celula('BR', '38', [
                    'fontSizePt' => '12',
                    'fontFamily' => 'Times',
                    'align' => 'L',
                    'fieldName' => 'irmao_cpf',
                ]),
            ],
            [
                self::rotuloVia('Recibo de Mensalidade 1º Via', 'Recibo de Mensalidade 2º Via'),
                self::celula('R', '52', [
                    'fontSizePt' => '10',
                    'fontFamily' => 'Times',
                    'fontStyle' => 'B',
                    'align' => 'L',
                    'fieldName' => 'mes_referencia_recibo',
                ]),
            ],
        ];

        foreach ([
            'Mensalidade Maçônica' => 'mensalidade_maconica_recibo_text',
            'Mútua Maçônica' => 'mutua_maconica_recibo_text',
            'Capitação' => 'capitacao_recibo_text',
            'Diversos' => 'diversos_recibo_text',
            'Reserva' => 'reserva_recibo_text',
        ] as $rotulo => $campo) {
            $linhas[] = [
                self::celula('L', '10', [
                    'fontSizePt' => '10',
                    'fontFamily' => 'Times',
                    'align' => 'L',
                ]),
                self::celula('', '60', [
                    'fontSizePt' => '10',
                    'fontFamily' => 'Times',
                    'align' => 'L',
                ], $rotulo),
                self::celula('R', '30', [
                    'fontSizePt' => '10',
                    'fontFamily' => 'Times',
                    'align' => 'L',
                    'fieldName' => $campo,
                ]),
            ];
        }

        $linhas[] = [
            self::celula('BL', '10', [
                'fontSizePt' => '10',
                'fontFamily' => 'Times',
                'align' => 'L',
            ]),
            self::celula('B', '60', [
                'fontSizePt' => '11',
                'fontStyle' => 'B',
                'fontFamily' => 'Times',
            ], 'Total Geral'),
            self::celula('BT', '19', [
                'fontSizePt' => '11',
                'fontStyle' => 'B',
                'fontFamily' => 'Times',
                'align' => 'L',
                'fieldName' => 'total_geral_recibo_text',
            ]),
            self::celula('BR', '11'),
        ];
        $linhas[] = [
            self::celula('LR', '100', [
                'fontSizePt' => '10',
                'fontFamily' => 'Times',
                'align' => 'L',
                'fieldName' => 'data_pagamento_recibo',
            ]),
        ];
        $linhas[] = [
            self::celula('L', '74', ['lineHeight' => '17']),
            self::imagem('12', '17', 'assinatura_arquivo'),
            self::celula('R', '14', ['lineHeight' => '17']),
        ];
        $linhas[] = [
            self::celula('LB', '35', [
                'fontSizePt' => '9',
                'fontFamily' => 'Times',
            ], 'Forma de Pagamento: '),
            self::celula('B', '35', [
                'fontSizePt' => '9',
                'fontFamily' => 'Times',
                'fieldName' => 'forma_pagamento_texto',
            ]),
            self::celula('BT', '20', [
                'fontSizePt' => '10',
                'fontStyle' => 'B',
                'fontFamily' => 'Times',
                'align' => 'C',
            ], 'Tesoureiro'),
            self::celula('RB', '10'),
        ];

        $xml = [];
        foreach ($linhas as $celulas) {
            $xml[] = self::linha(self::duasVias(self::comSufixo($celulas, $numero)));
        }

        return $xml;
    }

    /**
     * @param array<int, array<string, string>> $celulas
     * @return array<int, array<string, string>>
     */
    private static function comSufixo(array $celulas, int $numero): array
    {
        foreach ($celulas as &$celula) {
            if (isset($celula['fieldName'])) {
                $celula['fieldName'] .= '_' . $numero;
            }
        }

        return $celulas;
    }

    /**
     * @param array<int, array<string, string>> $esquerda
     * @return array<int, array<string, string>>
     */
    private static function duasVias(array $esquerda): array
    {
        $celulas = $esquerda;
        $celulas[] = ['lineWidth' => '5'];
        foreach ($esquerda as $celula) {
            if (isset($celula['textoDireita'])) {
                $celula['text'] = $celula['textoDireita'];
                unset($celula['textoDireita']);
            }
            $celulas[] = $celula;
        }

        return $celulas;
    }

    /**
     * @return array<string, string>
     */
    private static function imagem(string $lineWidth, string $lineHeight, string $fieldName): array
    {
        return [
            'tipo' => 'image',
            'lineWidth' => $lineWidth,
            'lineHeight' => $lineHeight,
            'fieldName' => $fieldName,
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function rotuloVia(string $esquerda, string $direita): array
    {
        $celula = self::celula('L', '48', [
            'fontSizePt' => '10',
            'fontFamily' => 'Times',
        ], $esquerda);
        $celula['textoDireita'] = $direita;

        return $celula;
    }

    /**
     * @param array<string, string> $attrs
     * @return array<string, string>
     */
    private static function celula(string $border, string $lineWidth, array $attrs = [], string $text = ''): array
    {
        $celula = $attrs;
        $celula['lineWidth'] = $lineWidth;
        if ($border !== '') {
            $celula['border'] = $border;
        }
        if ($text !== '') {
            $celula['text'] = $text;
        }

        return $celula;
    }

    /**
     * @param array<int, array<string, string>> $celulas
     */
    private static function linha(array $celulas): string
    {
        $xml = '';
        foreach ($celulas as $indice => $celula) {
            $texto = $celula['text'] ?? '';
            $tipo = $celula['tipo'] ?? 'cell';
            unset($celula['text'], $celula['tipo'], $celula['textoDireita']);
            $attrs = '';
            foreach ($celula as $nome => $valor) {
                $attrs .= ' ' . $nome . '="' . htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8') . '"';
            }
            $tag = $tipo . ($indice + 1);
            $xml .= "<{$tag}><{$tipo}{$attrs}>" . htmlspecialchars($texto, ENT_QUOTES, 'UTF-8') . "</{$tipo}></{$tag}>\n";
        }

        return $xml;
    }
}
