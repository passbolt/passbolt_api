<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SecretsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SecretsTable Test Case
 */
class SecretsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SecretsTable
     */
    public $Secrets;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.secrets',
        'app.users',
        'app.roles',
        'app.authentication_tokens',
        'app.favorites',
        'app.file_storage',
        'app.gpgkeys',
        'app.profiles',
        'app.users_resources_permissions',
        'app.groups',
        'app.groups_users',
        'app.resources'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Secrets') ? [] : ['className' => SecretsTable::class];
        $this->Secrets = TableRegistry::get('Secrets', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Secrets);

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
