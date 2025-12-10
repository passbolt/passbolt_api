<?php
declare(strict_types=1);

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
 * @since         5.7.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Command;

use App\Model\Table\UsersTable;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use App\Utility\UserAccessControl;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Core\Configure;
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\MultiFactorAuthenticationPlugin;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaAccountSettingsTestTrait;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaOrgSettingsTestTrait;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaOtpFactory;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaUserSettingsDisableCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use PassboltCommandTestTrait;
    use MfaOrgSettingsTestTrait;
    use MfaAccountSettingsTestTrait;
    use EmailQueueTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->enableFeaturePlugin(MultiFactorAuthenticationPlugin::class);
        $this->mockProcessUserService('www-data');
    }

    /**
     * Basic help test
     */
    public function testMfaUserSettingsDisableCommandHelp()
    {
        $this->exec('passbolt mfa_user_settings_disable -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Disable MFA for a user.');
        $this->assertOutputContains('cake passbolt mfa_user_settings_disable');
    }

    /**
     * @Given the MFA plugin is disabled
     * @When I run "passbolt MfaUserSettingsDisableCommand"
     * @Then the command is not found
     */
    public function testMfaUserSettingsDisableCommandErrorPluginDisabled()
    {
        $this->disableFeaturePlugin(MultiFactorAuthenticationPlugin::class);
        $this->exec('passbolt mfa_user_settings_disable --user-username john.doe@passbolt.com');
        $this->assertExitError();
        $this->assertErrorContains('Error: Unknown option `user-username`.');
    }

    /**
     * @Given I am root
     * @When I run "passbolt MfaUserSettingsDisableCommand"
     * @Then the command cannot be run.
     */
    public function testMfaUserSettingsDisableCommandAsRoot()
    {
        $this->assertCommandCannotBeRunAsRootUser('mfa_user_settings_disable --user-username john.doe@passbolt.com');
    }

    /**
     * @Given I am not root
     * @When I run "passbolt MfaUserSettingsDisableCommand"
     * @And the user has an MFA set
     * @Then the command runs, returning a success code and message.
     * @Then the user receives and email informing him his MFA is disabled
     */
    public function testMfaUserSettingsDisableCommandAsNonRoot()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->active()->withProfileName('John', 'Doe')->persist();

        // MFA org settings
        $orgSettings = ['providers' => [MfaSettings::PROVIDER_TOTP => true]];
        $this->mockMfaOrgSettings($orgSettings, 'configure');
        $accountSettings = [
            MfaSettings::PROVIDERS => [
                MfaSettings::PROVIDER_TOTP,
            ],
            MfaSettings::PROVIDER_TOTP => [
                MfaAccountSettings::VERIFIED => DateTime::now(),
                MfaAccountSettings::OTP_PROVISIONING_URI => MfaOtpFactory::generateTOTP(
                    new UserAccessControl(
                        $user->get('role')->get('name'),
                        $user->get('id'),
                        $user->get('username'),
                    )
                ),
            ],
        ];
        $data = json_encode($accountSettings);
        /** @var \Passbolt\AccountSettings\Model\Table\AccountSettingsTable $AccountSettings */
        $AccountSettings = TableRegistry::getTableLocator()->get('Passbolt/AccountSettings.AccountSettings');
        $AccountSettings->createOrUpdateSetting($user->get('id'), MfaSettings::MFA, $data);

        $options = " --user-username $user->username";
        $this->exec('passbolt mfa_user_settings_disable' . $options);
        $this->assertExitSuccess();

        $this->assertOutputContains('has been disabled');

        // an email should be in the queue
        $this->assertEmailQueueCount(1);
        $this->assertEmailInBatchContains([ 'John Doe', 'Your multi-factor authentication settings were reset by you', ], $user->get('username'));
    }

    /**
     * @Given username case sensitivity is enabled
     * @When two users exist with the same username but different casing
     * @And I run the disable command for one of them
     * @Then the correct user's MFA is disabled
     */
    public function testMfaUserSettingsDisableCommandCaseSensitiveUserSelection()
    {
        Configure::write(UsersTable::PASSBOLT_SECURITY_USERNAME_CASE_SENSITIVE, true);

        // 2 users with same username but different case
        /** @var \App\Model\Entity\User $userUpper */
        $userUpper = UserFactory::make()->setField('username', 'John.Doe@passbolt.com')->user()->active()->persist();
        /** @var \App\Model\Entity\User $userLower */
        $userLower = UserFactory::make()->setField('username', 'john.doe@passbolt.com')->user()->active()->persist();

        // MFA org settings
        $orgSettings = ['providers' => [MfaSettings::PROVIDER_TOTP => true]];
        $this->mockMfaOrgSettings($orgSettings, 'configure');
        $accountSettingsUpper = [
            MfaSettings::PROVIDERS => [
                MfaSettings::PROVIDER_TOTP,
            ],
            MfaSettings::PROVIDER_TOTP => [
                MfaAccountSettings::VERIFIED => DateTime::now(),
                MfaAccountSettings::OTP_PROVISIONING_URI => MfaOtpFactory::generateTOTP(
                    new UserAccessControl(
                        $userUpper->get('role')->get('name'),
                        $userUpper->get('id'),
                        $userUpper->get('username'),
                    )
                ),
            ],
        ];
        $accountSettingsLower = [
            MfaSettings::PROVIDERS => [
                MfaSettings::PROVIDER_TOTP,
            ],
            MfaSettings::PROVIDER_TOTP => [
                MfaAccountSettings::VERIFIED => DateTime::now(),
                MfaAccountSettings::OTP_PROVISIONING_URI => MfaOtpFactory::generateTOTP(
                    new UserAccessControl(
                        $userLower->get('role')->get('name'),
                        $userLower->get('id'),
                        $userLower->get('username'),
                    )
                ),
            ],
        ];
        $dataUpper = json_encode($accountSettingsUpper);
        $dataLower = json_encode($accountSettingsLower);
        /** @var \Passbolt\AccountSettings\Model\Table\AccountSettingsTable $AccountSettings */
        $AccountSettings = TableRegistry::getTableLocator()->get('Passbolt/AccountSettings.AccountSettings');
        $AccountSettings->createOrUpdateSetting($userUpper->get('id'), MfaSettings::MFA, $dataUpper);
        $AccountSettings->createOrUpdateSetting($userLower->get('id'), MfaSettings::MFA, $dataLower);

        $options = " --user-username $userUpper->username";
        $this->exec('passbolt mfa_user_settings_disable' . $options);
        $this->assertExitSuccess();

        $this->assertOutputContains('has been disabled');

        // Assert that upper user is disabled and lower is not in the DB
        $upperMfa = $AccountSettings->getByProperty($userUpper->get('id'), MfaSettings::MFA);
        $this->assertNull($upperMfa, 'Expected the MFA settings to be deleted for the upper-case user.');

        $lowerMfa = $AccountSettings->getByProperty($userLower->get('id'), MfaSettings::MFA);
        $this->assertNotNull($lowerMfa, 'Expected the MFA settings to not be deleted for the lower-case user.');
    }

    /**
     * @Given I am not root
     * @When I run "passbolt MfaUserSettingsDisableCommand"
     * @And the user has no MFA set
     * @Then the command runs, returning an error code and message.
     */
    public function testMfaUserSettingsDisableCommandAsNonRootAlreadyDisabledMFA()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->active()->persist();

        $options = " --user-username $user->username";
        $this->exec('passbolt mfa_user_settings_disable' . $options);
        $this->assertExitError();

        $this->assertOutputContains('has MFA disabled already');
    }

    /**
     * @Given I am not root
     * @When I run "passbolt MfaUserSettingsDisableCommand"
     * @And the given user does not exists
     * @Then the command runs, returning an error code and message.
     */
    public function testMfaUserSettingsDisableCommandAsNonRootInvalidUsernameFormat()
    {
        $this->exec('passbolt mfa_user_settings_disable  --user-username InvalidUserUserName');
        $this->assertExitError();
        $this->assertOutputContains('The username must be a valid email address');
    }

    /**
     * @Given I am not root
     * @When I run "passbolt MfaUserSettingsDisableCommand"
     * @And the given user does not exists
     * @Then the command runs, returning an error code and message.
     */
    public function testMfaUserSettingsDisableCommandAsNonRootUserNotFound()
    {
        $options = ' --user-username john.doe@passbolt.com';
        $this->exec('passbolt mfa_user_settings_disable' . $options);
        $this->assertExitError();

        $this->assertOutputContains('No user matching the username');
    }
}
