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
            ->select(['Irmaos.id','Irmaos.loja_id','Irmaos.desconto_valor'])
            ->where(['Irmaos.deleted IS' => null, 'Irmaos.ativo' => 1])
            ->contain([
                'Lojas' => fn ($q) => $q->select(['Lojas.id','Lojas.valor_mensalidade'])
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

            $base = (float)($i->loja->valor_mensalidade ?? 0.00);
            $desc = (float)max(0.00, min($i->desconto_valor ?? 0.00, $base));
            $valorFinal = $base - $desc;

            $ent = $Mensalidades->newEntity([
                'irmao_id'       => (int)$i->id,
                'mes_referencia' => $competencia,
                'valor'          => number_format($valorFinal, 2, '.', ''),
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
