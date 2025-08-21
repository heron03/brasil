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
                'sessao_id' => 1,
                'irmao_id' => 1,
                'presente' => 1,
                'created' => '2025-07-30 13:53:57',
                'modified' => '2025-07-30 13:53:57',
                'deleted' => '2025-07-30 13:53:57',
            ],
        ];
        parent::init();
    }
}
