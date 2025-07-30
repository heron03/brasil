<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MensalidadesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MensalidadesTable Test Case
 */
class MensalidadesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MensalidadesTable
     */
    protected $Mensalidades;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Mensalidades',
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
        $config = $this->getTableLocator()->exists('Mensalidades') ? [] : ['className' => MensalidadesTable::class];
        $this->Mensalidades = $this->getTableLocator()->get('Mensalidades', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Mensalidades);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\MensalidadesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\MensalidadesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
