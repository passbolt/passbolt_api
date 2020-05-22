<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Users;

use App\Model\Entity\Role;
use App\Test\Fixture\Alt0\GroupsUsersFixture;
use App\Test\Fixture\Base\AvatarsFixture;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\OrganizationSettingsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsUsersModelTrait;
use App\Test\Lib\Utility\UserAccessControlTrait;
use App\Utility\UuidFactory;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Passbolt\AccountSettings\Model\Table\AccountSettingsTable;
use Passbolt\AccountSettings\Test\Fixture\AccountSettingsFixture;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaOrgSettingsTestTrait;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class UsersIndexControllerTest extends AppIntegrationTestCase
{
    use GroupsUsersModelTrait;
    use MfaOrgSettingsTestTrait;
    use UserAccessControlTrait;

    public $fixtures = [
        OrganizationSettingsFixture::class,
        AccountSettingsFixture::class,
        UsersFixture::class,
        ProfilesFixture::class,
        GpgkeysFixture::class,
        RolesFixture::class,
        GroupsUsersFixture::class,
        AvatarsFixture::class,
    ];

    /**
     * @return void
     */
    public function testThatColumnIsMfaEnabledIsDisabledIfMfaIsDisabledForOrg()
    {
        $config = [
            MfaSettings::PROVIDERS => [
                MfaSettings::PROVIDER_DUO => false,
                MfaSettings::PROVIDER_TOTP => false,
                MfaSettings::PROVIDER_YUBIKEY => false,
            ],
        ];

        $this->mockMfaOrgSettings($config, 'configure');
        $this->authenticateAs('ada');
        $this->getJson('/users.json?contain[is_mfa_enabled]=1');
        $this->assertSuccess();
        $this->assertObjectHasAttribute('is_mfa_enabled', $this->_responseJsonBody[0]->User);
        $this->assertAttributeEquals(false, 'is_mfa_enabled', $this->_responseJsonBody[0]->User);
    }

    /**
     * @return void
     */
    public function testThatUsersIndexResultsContainIsMfaEnabledPropertyWhenContainParameterHaveIsMfaEnabled()
    {
        $this->mockMfaOrgSettings($this->getMfaProvidersConfig(), 'configure');
        $this->authenticateAs('ada');
        $this->getJson('/users.json?contain[is_mfa_enabled]=1');
        $this->assertSuccess();
        $this->assertObjectHasAttribute('is_mfa_enabled', $this->_responseJsonBody[0]->User);
    }

    /**
     * @return void
     */
    public function testThatUsersIndexResultsAreFilteredWhenFilterParameterHaveIsMfaEnabled()
    {
        $this->mockMfaOrgSettings($this->getMfaProvidersConfig(), 'configure');
        $this->mockMfaOrgSettings($this->getMfaProvidersConfig(), 'database', $this->mockUserAccessControl('admin', Role::ADMIN));

        $userId = UuidFactory::uuid('user.id.ada');
        /** @var AccountSettingsTable $accountSettings */
        $accountSettings = TableRegistry::getTableLocator()->get('Passbolt/AccountSettings.AccountSettings');
        $accountSettings->createOrUpdateSetting($userId, MfaSettings::MFA, json_encode([
            MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_TOTP],
            MfaSettings::PROVIDER_TOTP => [
                MfaAccountSettings::VERIFIED => FrozenTime::now(),
                MfaAccountSettings::OTP_PROVISIONING_URI => 'http://provisioning.uri',
            ],
        ]));

        $this->authenticateAs('admin');
        $this->clearRegistry();
        $this->getJson('/users.json?filter[is-mfa-enabled]=1&contain[is_mfa_enabled]=1');
        $this->assertSuccess();
        foreach ($this->_responseJsonBody as $user) {
            $this->assertAttributeEquals(true, 'is_mfa_enabled', $user->User, 'All users in the results should have MFA enabled.');
        }

        $this->getJson('/users.json?filter[is-mfa-enabled]=0&contain[is_mfa_enabled]=1');
        $this->assertSuccess();
        foreach ($this->_responseJsonBody as $user) {
            $this->assertAttributeEquals(false, 'is_mfa_enabled', $user->User, 'All users in the results should have MFA disabled.');
        }
    }

    /**
     * @return void
     */
    public function testThatUsersIndexResultsAreNotFilteredWhenFilterParameterDoesNotHaveIsMfaEnabled()
    {
        $this->mockMfaOrgSettings($this->getMfaProvidersConfig(), 'configure');
        $this->mockMfaOrgSettings($this->getMfaProvidersConfig(), 'database', $this->mockUserAccessControl('admin', Role::ADMIN));

        $userId = UuidFactory::uuid('user.id.ada');
        /** @var AccountSettingsTable $accountSettings */
        $accountSettings = TableRegistry::getTableLocator()->get('Passbolt/AccountSettings.AccountSettings');
        $accountSettings->createOrUpdateSetting($userId, MfaSettings::MFA, json_encode([
            MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_TOTP],
            MfaSettings::PROVIDER_TOTP => [
                MfaAccountSettings::VERIFIED => FrozenTime::now(),
                MfaAccountSettings::OTP_PROVISIONING_URI => 'http://provisioning.uri',
            ],
        ]));

        $this->authenticateAs('admin');
        $this->clearRegistry();
        $this->getJson('/users.json?contain[is_mfa_enabled]=1');
        $this->assertSuccess();
        foreach ($this->_responseJsonBody as $user) {
            if ($user->User->id === $userId) {
                $this->assertAttributeEquals(true, 'is_mfa_enabled', $user->User);
            } else {
                $this->assertAttributeEquals(false, 'is_mfa_enabled', $user->User);
            }
        }
    }

    /**
     * Clear the registry because the registry load models and their behaviors available at the moment
     * and they will be reused when running the test, and Behaviors registered in the application won't be run.
     *
     * @return void
     */
    private function clearRegistry()
    {
        TableRegistry::getTableLocator()->clear();
    }

    /**
     * @return array
     */
    private function getMfaProvidersConfig()
    {
        return [
            MfaSettings::PROVIDERS => [
                MfaSettings::PROVIDER_TOTP => true,
            ],
        ];
    }
}
