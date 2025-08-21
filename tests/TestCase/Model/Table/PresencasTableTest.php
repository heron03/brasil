<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PresencasTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PresencasTable Test Case
 */
class PresencasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PresencasTable
     */
    protected $Presencas;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Presencas',
        'app.Sessoes',
        'app.Irmaos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Presencas') ? [] : ['className' => PresencasTable::class];
        $this->Presencas = $this->getTableLocator()->get('Presencas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Presencas);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\PresencasTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\PresencasTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
