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
 * @since         2.6.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\OrgSettings;

use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use Cake\Core\Configure;
use Duo\DuoUniversal\Client;
use Passbolt\AccountSettings\Test\Factory\AccountSettingFactory;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaAuthenticationTokenFactory;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaOrganizationSettingFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Mock\DuoSdkClientMock;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Multi\MfaTotpDuoScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Multi\MfaTotpDuoUserOnlyScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpUserOnlyScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaOrgSettingsPostControllerTest extends MfaIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Configure::write('App.fullBaseUrl', 'https://passbolt.example.com');
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsPost
     */
    public function testMfaOrgSettingsPostControllerNotLoggedIn()
    {
        $this->postJson('/mfa/settings.json?api-version=v2', ['providers' => []]);
        $this->assertResponseError();
        $this->assertResponseContains('Authentication is required to continue');
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsPost
     */
    public function testMfaOrgSettingsPostControllerNotJson()
    {
        $this->authenticateAs('admin');
        $this->post('/mfa/settings', ['providers' => []]);
        $this->assertResponseError();
        $this->assertResponseContains('This is not a valid Ajax/Json request.');
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsPost
     */
    public function testMfaOrgSettingsPostControllerNoPayload()
    {
        $this->authenticateAs('admin');
        $this->postJson('/mfa/settings.json');
        $this->assertResponseError();
        $this->assertResponseContains('The multi-factor authentication settings data should not be empty.');
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsPost
     */
    public function testMfaOrgSettingsPostControllerNotAllowed()
    {
        $this->authenticateAs('ada');
        $this->postJson('/mfa/settings.json?api-version=v2', ['providers' => []]);
        $this->assertResponseError();
        $this->assertResponseContains('Access restricted to administrators.');
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsPost
     */
    public function testMfaOrgSettingsPostControllerSuccess_NoConfigBeforeNoConfigAfter()
    {
        $this->authenticateAs('admin');
        $this->postJson('/mfa/settings.json?api-version=v2', ['providers' => []]);
        $this->assertResponseSuccess();
        $this->assertEquals($this->_responseJsonBody->providers, []);
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsPost
     */
    public function testMfaOrgSettingsPostControllerSuccess_TotpConfigureBeforeNoConfigAfter()
    {
        $config['providers'] = [MfaSettings::PROVIDER_TOTP => true];
        $this->mockMfaOrgSettings($config);
        $this->authenticateAs('admin');
        $this->postJson('/mfa/settings.json?api-version=v2', ['providers' => []]);
        $this->assertResponseSuccess();
        $this->assertEquals($this->_responseJsonBody->providers, []);
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsPost
     */
    public function testMfaOrgSettingsPutControllerSuccess_TotpDbConfigureBeforeNoConfigAfter()
    {
        $config['providers'] = [MfaSettings::PROVIDER_TOTP => true];
        $this->mockMfaOrgSettings($config, 'database', $this->mockUserAccessControl('admin', Role::ADMIN));
        $this->authenticateAs('admin');
        $this->putJson('/mfa/settings.json?api-version=v2', ['providers' => []]);
        $this->assertResponseSuccess();
        $this->assertEquals($this->_responseJsonBody->providers, []);
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsPost
     */
    public function testMfaOrgSettingsPutControllerSuccess_TotpDbConfigureAllAfter()
    {
        $config['providers'] = [MfaSettings::PROVIDER_TOTP => true];
        $user = UserFactory::make()->admin()->persist();
        $this->mockMfaOrgSettings($config, 'database', $this->makeUac($user));
        $this->authenticateAs('admin');
        $this->mockService(Client::class, function () use ($user) {
            return DuoSdkClientMock::createDefault($this->getMockBuilder(Client::class), $user)->getClient();
        });

        $this->putJson('/mfa/settings.json?api-version=v2', $this->getDefaultMfaOrgSettings());
        $this->assertResponseSuccess();
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsPost
     */
    public function testMfaOrgSettingsPostController_EnablesWebauthn_WithArrayShape(): void
    {
        $this->authenticateAs('admin');
        $this->postJson('/mfa/settings.json?api-version=v2', [
            'providers' => [MfaSettings::PROVIDER_WEBAUTHN],
        ]);
        $this->assertResponseSuccess();
        $this->assertContains(MfaSettings::PROVIDER_WEBAUTHN, $this->_responseJsonBody->providers);
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsPost
     */
    public function testMfaOrgSettingsPostController_EnablesWebauthn_WithObjectShape(): void
    {
        $this->authenticateAs('admin');
        $this->postJson('/mfa/settings.json?api-version=v2', [
            'providers' => [
                MfaSettings::PROVIDER_TOTP => true,
                MfaSettings::PROVIDER_WEBAUTHN => true,
            ],
        ]);
        $this->assertResponseSuccess();
        $this->assertContains(MfaSettings::PROVIDER_WEBAUTHN, $this->_responseJsonBody->providers);
        $this->assertContains(MfaSettings::PROVIDER_TOTP, $this->_responseJsonBody->providers);
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsPost
     */
    public function testMfaOrgSettingsPostController_Error_WebauthnFullBaseUrlIsIpAddress(): void
    {
        Configure::write('App.fullBaseUrl', 'https://192.168.1.10');
        $this->logInAsAdmin();

        $this->postJson('/mfa/settings.json?api-version=v2', [
            'providers' => [MfaSettings::PROVIDER_WEBAUTHN],
        ]);

        $this->assertResponseError();
        $result = json_decode($this->_getBodyAsString(), true);
        $this->assertSame(
            'Passkeys require Passbolt to be reached through a domain name, not an IP address.',
            $result['body'][MfaSettings::PROVIDER_WEBAUTHN]['relyingPartyIdInvalid'],
        );
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsPost
     */
    public function testMfaOrgSettingsPostController_Error_WebauthnFullBaseUrlIsNotConfigured(): void
    {
        Configure::write('App.fullBaseUrl', '');
        $this->logInAsAdmin();

        $this->postJson('/mfa/settings.json?api-version=v2', [
            'providers' => [MfaSettings::PROVIDER_WEBAUTHN],
        ]);

        $this->assertResponseError();
        $result = json_decode($this->_getBodyAsString(), true);
        $this->assertSame(
            'Passkeys require a valid full base URL to be configured.',
            $result['body'][MfaSettings::PROVIDER_WEBAUTHN]['relyingPartyIdInvalid'],
        );
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsPost
     */
    public function testMfaOrgSettingsPostController_DisablesWebauthn_DropsFromProviderList(): void
    {
        $config['providers'] = [
            MfaSettings::PROVIDER_TOTP => true,
            MfaSettings::PROVIDER_WEBAUTHN => true,
        ];
        $this->mockMfaOrgSettings($config);
        $this->authenticateAs('admin');

        $this->postJson('/mfa/settings.json?api-version=v2', [
            'providers' => [MfaSettings::PROVIDER_TOTP],
        ]);
        $this->assertResponseSuccess();
        $this->assertNotContains(MfaSettings::PROVIDER_WEBAUTHN, $this->_responseJsonBody->providers);
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsPost
     */
    public function testMfaOrgSettingsPostControllerError_WrongConfig()
    {
        $config['providers'] = [MfaSettings::PROVIDER_TOTP => true];
        $this->mockMfaOrgSettings($config, 'database', $this->mockUserAccessControl('admin', Role::ADMIN));
        $this->authenticateAs('admin');

        $this->putJson('/mfa/settings.json?api-version=v2', [
            'providers' => ['duo', 'nope', 'yubikey'],
            'duo' => ['wrong' => 'config'],
            'yubikey' => ['clientId' => 'aaa', 'secretKey' => '123'],
        ]);

        $result = json_decode($this->_getBodyAsString(), true);
        $this->assertTrue(isset($result['body']['duo']['clientSecret']['notEmpty']));
        $this->assertTrue(isset($result['body']['duo']['apiHostName']['notEmpty']));
        $this->assertTrue(isset($result['body']['duo']['clientId']['notEmpty']));
        $this->assertTrue(isset($result['body']['yubikey']['secretKey']['isValidSecretKey']));
        $this->assertTrue(isset($result['body']['yubikey']['clientId']['isValidClientId']));
        $this->assertTrue(isset($result['body']['nope']));
        $this->assertResponseError();
    }

    public function testMfaOrgSettingsPutControllerSuccess_DisablingAllProvidersCleansAllAssociatedData()
    {
        $userA = UserFactory::make()->user()->persist();
        $userB = UserFactory::make()->user()->persist();
        $userC = UserFactory::make()->admin()->persist();

        // Account settings and tokens for userA
        $this->loadFixtureScenario(MfaTotpDuoScenario::class, $userA);
        $this->mockMfaCookieValid($this->makeUac($userA), MfaSettings::PROVIDER_TOTP);
        $this->mockMfaCookieValid($this->makeUac($userA), MfaSettings::PROVIDER_DUO);
        // Account settings and tokens for userB
        $this->loadFixtureScenario(MfaTotpDuoUserOnlyScenario::class, $userB);
        $this->mockMfaCookieValid($this->makeUac($userB), MfaSettings::PROVIDER_TOTP);
        $this->mockMfaCookieValid($this->makeUac($userB), MfaSettings::PROVIDER_DUO);
        // Account settings and tokens for userC
        $this->loadFixtureScenario(MfaTotpUserOnlyScenario::class, $userC);
        $this->mockMfaCookieValid($this->makeUac($userC), MfaSettings::PROVIDER_TOTP);

        // Not related MFA account settings for userA
        AccountSettingFactory::make()->withUser($userA)->locale('en-UK')->persist();

        $this->logInAsAdmin();
        $this->putJson('/mfa/settings.json?api-version=v2', ['providers' => []]);

        $this->assertResponseSuccess();
        $this->assertResponseContains('The multi factor authentication settings for the organization were updated.');
        $this->assertEquals($this->_responseJsonBody->providers, []);
        $this->assertSame(1, MfaOrganizationSettingFactory::count());
        // Here it should be 1 (keeping the locale setting and flushing all MFA settings as there is no provider left)
        $this->assertSame(1, AccountSettingFactory::count());
        $this->assertSame(5, MfaAuthenticationTokenFactory::count());
        //Here it should be 0 since we are removing all providers and the tokens should be set inactive
        $this->assertSame(0, MfaAuthenticationTokenFactory::find()->where(['active' => true])->all()->count());
    }

    public function testMfaOrgSettingsPostControllerSuccess_DisablingOneProviderCleansAllAssociatedData()
    {
        $user = UserFactory::make()->user()->persist();
        $this->loadFixtureScenario(MfaTotpDuoScenario::class, $user);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_TOTP);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_DUO);

        $this->logInAsAdmin();
        $this->postJson('/mfa/settings.json?api-version=v2', [
            MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_TOTP => true],
        ]);

        $this->assertResponseSuccess();
        $this->assertResponseContains('The multi factor authentication settings for the organization were updated.');
        $this->assertEquals($this->_responseJsonBody->providers, ['totp']);
        // Org Level
        $this->assertSame(1, MfaOrganizationSettingFactory::count());
        $orgSettingsInDB = $this->getMfaOrganizationSettingValue();
        // Duo provider should be removed from the provider's array
        $this->assertArrayNotHasKey('duo', $orgSettingsInDB);
        // User Level
        $this->assertSame(1, AccountSettingFactory::count());
        $accountSettingsInDB = $this->getMfaAccountSettingValue();
        // The provider's array should have just TOTP, as DUO was disabled
        $this->assertSame(['totp'], $accountSettingsInDB['providers']);
        $this->assertArrayNotHasKey('duo', $accountSettingsInDB);
        $this->assertSame(2, MfaAuthenticationTokenFactory::count());
        // Here it should be 2 because the mfa token for duo should be not set inactive
        // Inactivating this token while user is logged in with it can cause problems
        $this->assertSame(2, MfaAuthenticationTokenFactory::find()->where(['active' => true])->all()->count());
    }
}
