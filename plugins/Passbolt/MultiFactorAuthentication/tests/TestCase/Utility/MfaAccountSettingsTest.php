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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Utility;

use Cake\ORM\TableRegistry;
use Passbolt\AccountSettings\Model\Table\AccountSettingsTable;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;

class MfaAccountSettingsTest extends MfaIntegrationTestCase
{
    public $fixtures = [
        'plugin.passbolt/account_settings.account_settings',
        'app.Base/authentication_tokens', 'app.Base/users',
        'app.Base/roles'
    ];

    /**
     * @var AccountSettingsTable
     */
    protected $AccountSettings;

    /**
     * Setup.
     */
    public function setUp()
    {
        parent::setUp();
        $this->AccountSettings = TableRegistry::get('Passbolt/AccountSettings.AccountSettings');
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaAccountSettingsIsProviderReadySuccess()
    {
        $this->markTestIncomplete();
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaAccountSettingsIsProviderReadyFail()
    {
        $this->markTestIncomplete();
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaAccountSettingToJson()
    {
        $this->markTestIncomplete();
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaAccountSettingsToJsonFails()
    {
        $this->markTestIncomplete();
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaAccountSettingsGetEnabledProvidersSuccess()
    {
        $this->markTestIncomplete();
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaAccountSettingsGetEnabledProvidersFails()
    {
        $this->markTestIncomplete();
    }
}