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
        $Irmaos = $this->fetchTable('Irmaos');
        $Mensalidades = $this->fetchTable('Mensalidades');

        // competência = 1º dia do mês corrente
        $competencia = FrozenDate::today()->firstOfMonth();

        $irmaos = $Irmaos->find()
            ->select(['Irmaos.id','Irmaos.loja_id','Irmaos.desconto_valor'])
            ->where(['Irmaos.deleted IS' => null, 'Irmaos.ativo' => 1])
            ->contain(['Lojas' => fn ($q) => $q->select(['Lojas.id','Lojas.valor_mensalidade'])])
            ->all();

        $criados = 0;

        foreach ($irmaos as $i) {
            // evita duplicar a mensalidade do mesmo mês/irmão
            $exists = $Mensalidades->exists([
                'irmao_id'       => $i->id,
                'mes_referencia' => $competencia
            ]);
            if ($exists) continue;

            $base = (float)($i->loja->valor_mensalidade ?? 0.00);
            $desc = (float)max(0.00, min($i->desconto_valor ?? 0.00, $base));
            $valorFinal = $base - $desc;

            $ent = $Mensalidades->newEntity([
                'irmao_id'       => (int)$i->id,
                'mes_referencia' => $competencia,  // coluna do seu banco
                'valor'          => number_format($valorFinal, 2, '.', ''), // coluna do seu banco
                'pago'           => 0,             // começa como não pago (binário do seu banco)
                'data_pagamento' => null,
            ]);
            if ($Mensalidades->save($ent)) $criados++;
        }

        $io->success("Mensalidades geradas: {$criados} (comp. {$competencia->i18nFormat('MM/yyyy')})");
        return static::CODE_SUCCESS;
    }
}
