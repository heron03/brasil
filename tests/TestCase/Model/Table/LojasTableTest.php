<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LojasTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LojasTable Test Case
 */
class LojasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LojasTable
     */
    protected $Lojas;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Lojas',
        'app.Irmaos',
        'app.MovimentacoesCaixa',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Lojas') ? [] : ['className' => LojasTable::class];
        $this->Lojas = $this->getTableLocator()->get('Lojas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Lojas);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\LojasTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
