<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * IrmaosFixture
 */
class IrmaosFixture extends TestFixture
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
                'loja_id' => 1,
                'nome' => 'Lorem ipsum dolor sit amet',
                'data_nascimento' => '2025-07-30',
                'grau' => 'Lorem ipsum dolor sit amet',
                'logradouro' => 'Lorem ipsum dolor sit amet',
                'numero' => 'Lorem ip',
                'complemento' => 'Lorem ipsum dolor sit amet',
                'bairro' => 'Lorem ipsum dolor sit amet',
                'cidade' => 'Lorem ipsum dolor sit amet',
                'estado' => 'Lo',
                'cep' => 'Lorem ip',
                'telefone' => 'Lorem ipsum dolor ',
                'email' => 'Lorem ipsum dolor sit amet',
                'ativo' => 1,
                'created' => '2025-07-30 13:25:31',
                'modified' => '2025-07-30 13:25:31',
                'deleted' => '2025-07-30 13:25:31',
            ],
        ];
        parent::init();
    }
}
