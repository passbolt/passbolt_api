<?php
namespace Passbolt\AccountSettings\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Passbolt\AccountSettings\Model\Table\AccountSettingsTable;

/**
 * Passbolt\AccountSettings\Model\Table\AccountSettingsTable Test Case
 */
class AccountSettingsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Passbolt\AccountSettings\Model\Table\AccountSettingsTable
     */
    public $AccountSettings;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Base/users',
        'plugin.passbolt/account_settings.account_settings'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AccountSettings') ? [] : ['className' => AccountSettingsTable::class];
        $this->AccountSettings = TableRegistry::get('AccountSettings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AccountSettings);

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
