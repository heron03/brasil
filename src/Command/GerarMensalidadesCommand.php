<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\I18n\FrozenDate;

class GerarMensalidadesCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {
        // ===== 1) Parâmetros =====
        $mes  = (int)$args->getArgumentAt(0);
        $ano  = (int)$args->getArgumentAt(1);

        if ($mes < 1 || $mes > 12) {
            $mes = (int)date('m');
        }

        if ($ano < 2000 || $ano > 2100) {
            $ano = (int)date('Y');
        }

        // competência = 1º dia do mês informado
        $competencia = FrozenDate::create($ano, $mes, 1);

        $io->info("Gerando mensalidades para {$competencia->i18nFormat('MM/yyyy')}");

        // ===== 2) Tables =====
        $Irmaos = $this->fetchTable('Irmaos');
        $Mensalidades = $this->fetchTable('Mensalidades');

        $irmaos = $Irmaos->find()
            ->select([
                'Irmaos.id',
                'Irmaos.loja_id',
                'Irmaos.desconto_valor',
                'Irmaos.desconto_mutua',
                'Irmaos.desconto_capitacao',
                'Irmaos.desconto_diversos',
                'Irmaos.desconto_reserva',
            ])
            ->where(['Irmaos.deleted IS' => null, 'Irmaos.ativo' => 1])
            ->contain([
                'Lojas' => fn ($q) => $q->select([
                    'Lojas.id',
                    'Lojas.valor_mensalidade',
                    'Lojas.mutua',
                    'Lojas.capitacao',
                    'Lojas.diversos',
                    'Lojas.reserva',
                ])
            ])
            ->all();

        // ===== 3) Geração =====
        $criados = 0;

        foreach ($irmaos as $i) {
            $exists = $Mensalidades->exists([
                'irmao_id'       => $i->id,
                'mes_referencia' => $competencia
            ]);

            if ($exists) {
                continue;
            }

            $mensalidadeBase = (float)($i->loja->valor_mensalidade ?? 0.00);
            $mutuaBase = (float)($i->loja->mutua ?? 0.00);
            $capitacaoBase = (float)($i->loja->capitacao ?? 0.00);
            $diversosBase = (float)($i->loja->diversos ?? 0.00);
            $reservaBase = (float)($i->loja->reserva ?? 0.00);

            $descMensalidade = (float)max(0.00, min($i->desconto_valor ?? 0.00, $mensalidadeBase));
            $descMutua = (float)max(0.00, min($i->desconto_mutua ?? 0.00, $mutuaBase));
            $descCapitacao = (float)max(0.00, min($i->desconto_capitacao ?? 0.00, $capitacaoBase));
            $descDiversos = (float)max(0.00, min($i->desconto_diversos ?? 0.00, $diversosBase));
            $descReserva = (float)max(0.00, min($i->desconto_reserva ?? 0.00, $reservaBase));

            $mensalidadeLiquida = round($mensalidadeBase - $descMensalidade, 2);
            $mutuaLiquida = round($mutuaBase - $descMutua, 2);
            $capitacaoLiquida = round($capitacaoBase - $descCapitacao, 2);
            $diversosLiquida = round($diversosBase - $descDiversos, 2);
            $reservaLiquida = round($reservaBase - $descReserva, 2);
            $valorFinal = $mensalidadeLiquida;

            $ent = $Mensalidades->newEntity([
                'irmao_id'       => (int)$i->id,
                'mes_referencia' => $competencia,
                'valor'          => $valorFinal,
                'mutua'          => $mutuaLiquida,
                'capitacao'      => $capitacaoLiquida,
                'diversos'       => $diversosLiquida,
                'reserva'        => $reservaLiquida,
                'pago'           => 0,
            ]);
            if ($Mensalidades->save($ent)) {
                $criados++;
            }
        }

        // ===== 4) Resultado =====
        $io->success("Mensalidades geradas: {$criados} (comp. {$competencia->i18nFormat('MM/yyyy')})");
        return static::CODE_SUCCESS;
    }
}
