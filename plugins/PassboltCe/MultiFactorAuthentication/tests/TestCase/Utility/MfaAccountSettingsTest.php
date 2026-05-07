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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Utility;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaOtpFactory;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaAccountSettingsTest extends MfaIntegrationTestCase
{
    /**
     * @var \Passbolt\AccountSettings\Model\Table\AccountSettingsTable
     */
    protected $AccountSettings;

    /**
     * Setup.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->AccountSettings = TableRegistry::getTableLocator()->get('Passbolt/AccountSettings.AccountSettings');
    }

    /**
     * @group mfa
     * @group mfaAccountSettings
     */
    public function testMfaAccountSettings_GetEmpty()
    {
        $userA = UserFactory::make()->user()->persist();
        $uac = $this->makeUac($userA);
        $this->expectException(RecordNotFoundException::class);
        MfaAccountSettings::get($uac);
    }

    /**
     * @group mfa
     * @group mfaAccountSettings
     */
    public function testMfaAccountSettings_Guest_Sould_Throw_NotFoundException()
    {
        $uac = new UserAccessControl(Role::GUEST);
        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('Invalid User Account Control ID.');
        MfaAccountSettings::get($uac);
    }

    /**
     * @group mfa
     * @group mfaAccountSettings
     */
    public function testMfaAccountSettings_GetTOTPSuccess()
    {
        $userA = UserFactory::make()->user()->persist();
        $uac = $this->makeUac($userA);
        $uri = MfaOtpFactory::generateTOTP($uac);
        $data = [
            MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_TOTP],
            MfaSettings::PROVIDER_TOTP => [
                MfaAccountSettings::VERIFIED => DateTime::now(),
                MfaAccountSettings::OTP_PROVISIONING_URI => $uri,
            ],
        ];
        $this->mockMfaAccountSettings($uac, $data);
        $settings = MfaAccountSettings::get($uac);
        $this->assertNotEmpty($settings);
        $this->assertEquals($settings->getEnabledProviders(), [MfaSettings::PROVIDER_TOTP]);
        $this->assertEquals($settings->getOtpProvisioningUri(), $uri);
        $this->assertTrue($settings->isProviderReady(MfaSettings::PROVIDER_TOTP));
        $this->assertJson($settings->toJson());
        $this->assertInstanceOf(DateTime::class, $settings->getVerifiedFrozenTime(MfaSettings::PROVIDER_TOTP));
    }

    /**
     * @group mfa
     * @group mfaAccountSettings
     */
    public function testMfaAccountSettings_GetMixedSuccess()
    {
        $userA = UserFactory::make()->user()->persist();
        $uac = $this->makeUac($userA);
        $uri = MfaOtpFactory::generateTOTP($uac);
        $data = [
            MfaSettings::PROVIDERS => [
                MfaSettings::PROVIDER_TOTP,
                MfaSettings::PROVIDER_YUBIKEY,
            ],
            MfaSettings::PROVIDER_TOTP => [
                MfaAccountSettings::VERIFIED => DateTime::now(),
                MfaAccountSettings::OTP_PROVISIONING_URI => $uri,
            ],
            MfaSettings::PROVIDER_YUBIKEY => [
                MfaAccountSettings::VERIFIED => DateTime::now(),
                // missing YUBIKEY_ID
            ],
        ];
        $this->mockMfaAccountSettings($uac, $data);
        $settings = MfaAccountSettings::get($uac);
        $this->assertNotEmpty($settings);

        $providers = $settings->getEnabledProviders();
        $this->assertEquals($providers, [
            MfaSettings::PROVIDER_TOTP,
        ]);
    }

    /**
     * @group mfa
     * @group mfaAccountSettings
     */
    public function testMfaAccountSettings_GetProviderInvalid()
    {
        // No providers
        $userA = UserFactory::make()->user()->persist();
        $uac = $this->makeUac($userA);
        $data = [];
        $this->mockMfaAccountSettings($uac, $data);
        $settings = MfaAccountSettings::get($uac);
        $ready = $settings->isProviderReady(MfaSettings::PROVIDER_YUBIKEY);
        $this->assertFalse($ready);

        // Empty providers
        $uac = $this->makeUac($userA);
        $data = [MfaSettings::PROVIDERS => []];
        $this->mockMfaAccountSettings($uac, $data);
        $settings = MfaAccountSettings::get($uac);
        $ready = $settings->isProviderReady(MfaSettings::PROVIDER_YUBIKEY);
        $this->assertFalse($ready);

        // Not present provider config
        $data = [MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_TOTP]];
        $this->mockMfaAccountSettings($uac, $data);
        $settings = MfaAccountSettings::get($uac);
        $ready = $settings->isProviderReady(MfaSettings::PROVIDER_TOTP);
        $this->assertFalse($ready);
    }

    /**
     * @group mfa
     * @group mfaAccountSettings
     */
    public function testMfaAccountSettings_GetTOTPNotReady()
    {
        // Incomplete TOTP config
        $userA = UserFactory::make()->user()->persist();
        $uac = $this->makeUac($userA);
        $data = [
            MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_TOTP],
            MfaSettings::PROVIDER_TOTP => [
                MfaAccountSettings::VERIFIED => DateTime::now(),
            ],
        ];
        $this->mockMfaAccountSettings($uac, $data);
        $settings = MfaAccountSettings::get($uac);
        $ready = $settings->isProviderReady(MfaSettings::PROVIDER_TOTP);
        $this->assertFalse($ready);
    }

    /**
     * @group mfa
     * @group mfaAccountSettings
     */
    public function testMfaAccountSettings_GetYubikeySuccess()
    {
        $userA = UserFactory::make()->user()->persist();
        $uac = $this->makeUac($userA);
        $data = [
            MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_YUBIKEY],
            MfaSettings::PROVIDER_YUBIKEY => [
                MfaAccountSettings::VERIFIED => DateTime::now(),
                MfaAccountSettings::YUBIKEY_ID => 'something',
            ],
        ];
        $this->mockMfaAccountSettings($uac, $data);
        $settings = MfaAccountSettings::get($uac);
        $ready = $settings->isProviderReady(MfaSettings::PROVIDER_YUBIKEY);
        $this->assertTrue($ready);
    }

    /**
     * @group mfa
     * @group mfaAccountSettings
     */
    public function testMfaAccountSettings_GetWithYubikeyNotReady()
    {
        // Incomplete Yubikey config
        $userA = UserFactory::make()->user()->persist();
        $uac = $this->makeUac($userA);
        $data = [
            MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_YUBIKEY],
            MfaSettings::PROVIDER_YUBIKEY => [
                MfaAccountSettings::VERIFIED => DateTime::now(),
            ],
        ];
        $this->mockMfaAccountSettings($uac, $data);
        $settings = MfaAccountSettings::get($uac);
        $ready = $settings->isProviderReady(MfaSettings::PROVIDER_YUBIKEY);
        $this->assertFalse($ready);
    }

    /**
     * @group mfa
     * @group mfaAccountSettings
     */
    public function testMfaAccountSettings_GetWithProviderDuoNotReady()
    {
        // Incomplete Duo config
        $userA = UserFactory::make()->user()->persist();
        $uac = $this->makeUac($userA);
        $data = [
            MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO],
            MfaSettings::PROVIDER_DUO => [],
        ];
        $this->mockMfaAccountSettings($uac, $data);
        $settings = MfaAccountSettings::get($uac);
        $ready = $settings->isProviderReady(MfaSettings::PROVIDER_DUO);
        $this->assertFalse($ready);
    }

    /**
     * @group mfa
     * @group mfaAccountSettings
     */
    public function testMfaAccountSettings_EnableProvider()
    {
        $userA = UserFactory::make()->user()->persist();
        $uac = $this->makeUac($userA);
        MfaAccountSettings::enableProvider($uac, MfaSettings::PROVIDER_DUO);
        $settings = MfaAccountSettings::get($uac);
        $this->assertTrue($settings->isProviderReady(MfaSettings::PROVIDER_DUO));
        $this->assertFalse($settings->isProviderReady(MfaSettings::PROVIDER_YUBIKEY));

        MfaAccountSettings::enableProvider($uac, MfaSettings::PROVIDER_YUBIKEY, [
            MfaAccountSettings::YUBIKEY_ID => 'yubikey_id',
        ]);
        $settings = MfaAccountSettings::get($uac);
        $this->assertTrue($settings->isProviderReady(MfaSettings::PROVIDER_DUO));
        $this->assertTrue($settings->isProviderReady(MfaSettings::PROVIDER_YUBIKEY));
    }

    /**
     * @group mfa
     * @group mfaAccountSettings
     */
    public function testMfaAccountSettings_EnableProviderFailUserNotExist()
    {
        $uac = $this->mockUserAccessControl('nope');
        $this->expectException(ValidationException::class);
        MfaAccountSettings::enableProvider($uac, MfaSettings::PROVIDER_DUO);
    }

    /**
     * @group mfa
     * @group mfaAccountSettings
     */
    public function testMfaAccountSettings_EnableAndDisableProvider()
    {
        // Enable two providers
        $userA = UserFactory::make()->user()->persist();
        $uac = $this->makeUac($userA);
        MfaAccountSettings::enableProvider($uac, MfaSettings::PROVIDER_DUO);
        MfaAccountSettings::enableProvider($uac, MfaSettings::PROVIDER_YUBIKEY, [
            MfaAccountSettings::YUBIKEY_ID => 'yubikey_id',
        ]);
        $settings = MfaAccountSettings::get($uac);
        $this->assertTrue($settings->isProviderReady(MfaSettings::PROVIDER_DUO));
        $this->assertTrue($settings->isProviderReady(MfaSettings::PROVIDER_YUBIKEY));

        // Disable one, check there is one provider left
        $settings->disableProvider(MfaSettings::PROVIDER_DUO);
        $this->assertFalse($settings->isProviderReady(MfaSettings::PROVIDER_DUO));
        $this->assertTrue($settings->isProviderReady(MfaSettings::PROVIDER_YUBIKEY));

        // Disable another one, there should be no more
        $settings->disableProvider(MfaSettings::PROVIDER_YUBIKEY);
        $this->expectException(RecordNotFoundException::class);
        MfaAccountSettings::get($uac);
    }

    /**
     * @group mfa
     * @group mfaAccountSettings
     */
    public function testMfaAccountSettings_GetProviderStatus()
    {
        // Enable all providers one is badly configured
        $userA = UserFactory::make()->user()->persist();
        $uac = $this->makeUac($userA);
        MfaAccountSettings::enableProvider($uac, MfaSettings::PROVIDER_DUO);
        MfaAccountSettings::enableProvider($uac, MfaSettings::PROVIDER_YUBIKEY, [
            MfaAccountSettings::YUBIKEY_ID => 'yubikey_id',
        ]);
        MfaAccountSettings::enableProvider($uac, MfaSettings::PROVIDER_TOTP);

        // Check statuses
        $settings = MfaAccountSettings::get($uac);
        $this->assertEquals($settings->getProvidersStatus(), [
            MfaSettings::PROVIDER_YUBIKEY => true,
            MfaSettings::PROVIDER_DUO => true,
            MfaSettings::PROVIDER_TOTP => false,
        ]);

        // Disable one, check there is one provider left
        $settings->disableProvider(MfaSettings::PROVIDER_DUO);
        $this->assertEquals($settings->getProvidersStatus(), [
            MfaSettings::PROVIDER_YUBIKEY => true,
            MfaSettings::PROVIDER_DUO => false,
            MfaSettings::PROVIDER_TOTP => false,
        ]);

        // Disable another one
        $settings->disableProvider(MfaSettings::PROVIDER_YUBIKEY);
        $this->assertEquals($settings->getProvidersStatus(), [
            MfaSettings::PROVIDER_YUBIKEY => false,
            MfaSettings::PROVIDER_DUO => false,
            MfaSettings::PROVIDER_TOTP => false,
        ]);
    }
}
