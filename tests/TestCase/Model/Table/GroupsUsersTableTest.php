<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GroupsUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GroupsUsersTable Test Case
 */
class GroupsUsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GroupsUsersTable
     */
    public $GroupsUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.groups_users',
        'app.groups',
        'app.users',
        'app.roles',
        'app.controller_logs',
        'app.authentication_tokens',
        'app.favorites',
        'app.file_storage',
        'app.gpgkeys',
        'app.profiles',
        'app.secrets',
        'app.users_resources_permissions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('GroupsUsers') ? [] : ['className' => GroupsUsersTable::class];
        $this->GroupsUsers = TableRegistry::get('GroupsUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GroupsUsers);

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
