<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace Passbolt\AccountSettings\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Passbolt\AccountSettings\Model\Table\AccountSettingsTable;
use Passbolt\AccountSettings\Test\Lib\AccountSettingsPluginTestCase;

/**
 * Passbolt\AccountSettings\Model\Table\AccountSettingsTable Test Case
 */
class AccountSettingsTableTest extends AccountSettingsPluginTestCase
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
