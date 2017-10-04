<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GpgkeysTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GpgkeysTable Test Case
 */
class GpgkeysTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GpgkeysTable
     */
    public $Gpgkeys;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.gpgkeys',
        'app.users',
        'app.roles',
        'app.authentication_tokens',
        'app.profiles',
        'app.secrets',
        'app.groups'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Gpgkeys') ? [] : ['className' => GpgkeysTable::class];
        $this->Gpgkeys = TableRegistry::get('Gpgkeys', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Gpgkeys);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
