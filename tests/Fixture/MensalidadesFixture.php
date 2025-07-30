<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MensalidadesFixture
 */
class MensalidadesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'irmao_id' => 1,
                'mes_referencia' => '2025-07-30',
                'valor' => 1.5,
                'pago' => 1,
                'data_pagamento' => '2025-07-30',
                'created' => '2025-07-30 13:26:43',
                'modified' => '2025-07-30 13:26:43',
                'deleted' => '2025-07-30 13:26:43',
            ],
        ];
        parent::init();
    }
}
