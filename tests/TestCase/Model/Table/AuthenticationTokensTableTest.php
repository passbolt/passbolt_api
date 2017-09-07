<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AuthenticationTokensTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AuthenticationTokensTable Test Case
 */
class AuthenticationTokensTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AuthenticationTokensTable
     */
    public $AuthenticationTokens;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.authentication_tokens',
        'app.users',
        'app.roles',
        'app.controller_logs',
        'app.favorites',
        'app.file_storage',
        'app.gpgkeys',
        'app.profiles',
        'app.secrets',
        'app.users_resources_permissions',
        'app.groups_users',
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
        $config = TableRegistry::exists('AuthenticationTokens') ? [] : ['className' => AuthenticationTokensTable::class];
        $this->AuthenticationTokens = TableRegistry::get('AuthenticationTokens', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AuthenticationTokens);

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
