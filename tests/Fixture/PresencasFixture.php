<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PresencasFixture
 */
class PresencasFixture extends TestFixture
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
                'data_sessao' => '2025-07-30',
                'tipo_sessao' => 'Lorem ipsum dolor ',
                'presente' => 1,
                'created' => '2025-07-30 13:26:32',
                'modified' => '2025-07-30 13:26:32',
                'deleted' => '2025-07-30 13:26:32',
            ],
        ];
        parent::init();
    }
}
