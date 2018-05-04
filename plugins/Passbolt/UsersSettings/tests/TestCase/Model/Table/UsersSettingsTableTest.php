<?php
namespace Passbolt\UsersSettings\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Passbolt\UsersSettings\Model\Table\UsersSettingsTable;

/**
 * Passbolt\UsersSettings\Model\Table\UsersSettingsTable Test Case
 */
class UsersSettingsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Passbolt\UsersSettings\Model\Table\UsersSettingsTable
     */
    public $UsersSettings;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.passbolt/users_settings.users_settings',
        'plugin.passbolt/users_settings.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UsersSettings') ? [] : ['className' => UsersSettingsTable::class];
        $this->UsersSettings = TableRegistry::get('UsersSettings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsersSettings);

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
