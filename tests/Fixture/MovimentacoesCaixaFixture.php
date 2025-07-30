<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MovimentacoesCaixaFixture
 */
class MovimentacoesCaixaFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'movimentacoes_caixa';
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
                'loja_id' => 1,
                'tipo' => 'Lorem ipsum dolor sit amet',
                'descricao' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'valor' => 1.5,
                'data_movimentacao' => '2025-07-30',
                'origem' => 'Lorem ipsum dolor sit amet',
                'created' => '2025-07-30 13:26:52',
                'modified' => '2025-07-30 13:26:52',
                'deleted' => '2025-07-30 13:26:52',
            ],
        ];
        parent::init();
    }
}
