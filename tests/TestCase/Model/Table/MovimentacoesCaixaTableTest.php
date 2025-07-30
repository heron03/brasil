<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MovimentacoesCaixaTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MovimentacoesCaixaTable Test Case
 */
class MovimentacoesCaixaTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MovimentacoesCaixaTable
     */
    protected $MovimentacoesCaixa;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.MovimentacoesCaixa',
        'app.Lojas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('MovimentacoesCaixa') ? [] : ['className' => MovimentacoesCaixaTable::class];
        $this->MovimentacoesCaixa = $this->getTableLocator()->get('MovimentacoesCaixa', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->MovimentacoesCaixa);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\MovimentacoesCaixaTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\MovimentacoesCaixaTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
