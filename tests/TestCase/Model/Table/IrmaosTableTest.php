<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\IrmaosTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\IrmaosTable Test Case
 */
class IrmaosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\IrmaosTable
     */
    protected $Irmaos;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Irmaos',
        'app.Lojas',
        'app.Mensalidades',
        'app.Presencas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Irmaos') ? [] : ['className' => IrmaosTable::class];
        $this->Irmaos = $this->getTableLocator()->get('Irmaos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Irmaos);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\IrmaosTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\IrmaosTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
