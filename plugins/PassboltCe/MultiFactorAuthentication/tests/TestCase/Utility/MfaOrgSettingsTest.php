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

use App\Error\Exception\CustomValidationException;
use App\Test\Factory\UserFactory;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsDuoService;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Mock\DuoSdkClientMock;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpUserOnlyScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaOrgSettingsTest extends MfaIntegrationTestCase
{
    public $autoFixtures = false;

    /**
     * @var \App\Model\Table\OrganizationSettingsTable
     */
    protected $OrganizationSettings;

    protected $defaultConfig;

    /**
     * Setup.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $this->defaultConfig = $this->getDefaultMfaOrgSettings();
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsGetProvidersSuccess()
    {
        Configure::write('passbolt.plugins.multiFactorAuthentication', $this->defaultConfig);
        $settings = MfaOrgSettings::get();
        $this->assertNotEmpty($settings);
        $this->assertEquals(count($settings->getEnabledProviders()), 3);
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsGetSettingsEmpty()
    {
        Configure::write('passbolt.plugins.multiFactorAuthentication', []);
        $this->expectException(InternalErrorException::class);
        MfaOrgSettings::get();
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsGetProvidersEmpty()
    {
        $config = $this->defaultConfig;
        $config[MfaSettings::PROVIDERS] = [];
        $this->mockMfaOrgSettings($config);
        $settings = MfaOrgSettings::get();
        $this->assertNotEmpty($settings);
        $this->assertEquals($settings->getEnabledProviders(), []);
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsisProviderEnabledSuccess()
    {
        Configure::write('passbolt.plugins.multiFactorAuthentication', $this->defaultConfig);
        $settings = MfaOrgSettings::get();
        $this->assertTrue($settings->isProviderEnabled(MfaSettings::PROVIDER_YUBIKEY));
        $this->assertTrue($settings->isProviderEnabled(MfaSettings::PROVIDER_TOTP));
        $this->assertTrue($settings->isProviderEnabled(MfaSettings::PROVIDER_DUO));
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsisProviderEnabledFail()
    {
        $config = $this->defaultConfig;
        $config[MfaSettings::PROVIDERS] = [];
        $this->mockMfaOrgSettings($config);
        $settings = MfaOrgSettings::get();
        $this->assertFalse($settings->isProviderEnabled(MfaSettings::PROVIDER_YUBIKEY));

        $config = $this->defaultConfig;
        $config[MfaSettings::PROVIDERS] = [MfaSettings::PROVIDER_DUO];
        $this->mockMfaOrgSettings($config);
        $settings = MfaOrgSettings::get();
        $this->assertFalse($settings->isProviderEnabled(MfaSettings::PROVIDER_YUBIKEY));
        $this->assertFalse($settings->isProviderEnabled(MfaSettings::PROVIDER_TOTP));
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsIsProviderEnabledFail_If_Organization_Is_Deactivated()
    {
        $user = UserFactory::make()->persist();
        $uac = $this->makeUac($user);
        $this->loadFixtureScenario(MfaTotpUserOnlyScenario::class, $user);

        $settings = MfaSettings::get($uac);
        foreach (MfaSettings::getProviders() as $provider) {
            $this->assertFalse($settings->isProviderEnabled($provider));
        }
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsGetProviderStatus()
    {
        // All provider set to false
        $providers = [
            MfaSettings::PROVIDER_TOTP => false,
            MfaSettings::PROVIDER_DUO => false,
            MfaSettings::PROVIDER_YUBIKEY => false,
        ];
        $config = $this->defaultConfig;
        $config[MfaSettings::PROVIDERS] = $providers;
        $this->mockMfaOrgSettings($config);
        $settings = MfaOrgSettings::get();
        $status = $settings->getProvidersStatus();
        $this->assertEquals($status, $providers);

        // No provider set
        $config = $this->defaultConfig;
        $config[MfaSettings::PROVIDERS] = [];
        $this->mockMfaOrgSettings($config);
        $settings = MfaOrgSettings::get();
        $status = $settings->getProvidersStatus();
        $this->assertEquals($status, $providers);

        // Mix missing provider and one true
        $providers = [
            MfaSettings::PROVIDER_TOTP => false,
            MfaSettings::PROVIDER_DUO => true,
        ];
        $config = $this->defaultConfig;
        $config[MfaSettings::PROVIDERS] = $providers;
        $this->mockMfaOrgSettings($config);
        $settings = MfaOrgSettings::get();
        $status = $settings->getProvidersStatus();
        $this->assertEquals($status, [
            MfaSettings::PROVIDER_TOTP => false,
            MfaSettings::PROVIDER_DUO => true,
            MfaSettings::PROVIDER_YUBIKEY => false,
        ]);
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsGetProviderEnabled()
    {
        // Mix missing provider and one true
        $providers = [
            MfaSettings::PROVIDER_TOTP => false,
            MfaSettings::PROVIDER_DUO => true,
        ];
        $config = $this->defaultConfig;
        $config[MfaSettings::PROVIDERS] = $providers;
        $this->mockMfaOrgSettings($config);
        $settings = MfaOrgSettings::get();
        $status = $settings->getEnabledProviders();
        $this->assertEquals($status, [MfaSettings::PROVIDER_DUO]);
    }

    /*
     * TEST ORG SETTING DUO TRAIT
     */

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsGetDuoProps()
    {
        Configure::write('passbolt.plugins.multiFactorAuthentication', $this->defaultConfig);
        $settings = MfaOrgSettings::get()->getDuoOrgSettings();
        $this->assertNotEmpty($settings->getDuoApiHostname());
        $this->assertNotEmpty($settings->getDuoClientId());
        $this->assertNotEmpty($settings->getDuoClientSecret());
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsGetDuoIncompletePropsApiHostname()
    {
        $config = [MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO => true, ], MfaSettings::PROVIDER_DUO => []];
        $this->mockMfaOrgSettings($config);
        $settings = MfaOrgSettings::get()->getDuoOrgSettings();
        $this->expectException(RecordNotFoundException::class);
        $this->assertNotEmpty($settings->getDuoApiHostname());
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsGetDuoIncompletePropsClientSecret()
    {
        $config = [MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO => true, ], MfaSettings::PROVIDER_DUO => []];
        $this->mockMfaOrgSettings($config);
        $settings = MfaOrgSettings::get()->getDuoOrgSettings();
        $this->expectException(RecordNotFoundException::class);
        $this->assertNotEmpty($settings->getDuoClientSecret());
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsGetDuoIncompletePropsClientId()
    {
        $config = [MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO => true, ], MfaSettings::PROVIDER_DUO => []];
        $this->mockMfaOrgSettings($config);
        $settings = MfaOrgSettings::get()->getDuoOrgSettings();
        $this->expectException(RecordNotFoundException::class);
        $this->assertNotEmpty($settings->getDuoClientId());
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsValidateDuoSettings_Empty()
    {
        $duoSdkClientMock = (new DuoSdkClientMock($this))->mockSuccessHealthCheck()->getClient();
        try {
            $duoSettings = new MfaOrgSettingsDuoService([[
                MfaSettings::PROVIDER_DUO => [
                    MfaOrgSettings::DUO_CLIENT_ID => '',
                    MfaOrgSettings::DUO_API_HOSTNAME => '',
                    MfaOrgSettings::DUO_CLIENT_SECRET => '',
                ],
            ]]);
            $duoSettings->validateDuoSettings($duoSdkClientMock);
        } catch (CustomValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertTrue(isset($errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_CLIENT_SECRET]['notEmpty']));
            $this->assertTrue(isset($errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_CLIENT_ID]['notEmpty']));
            $this->assertTrue(isset($errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_API_HOSTNAME]['notEmpty']));
        }
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsValidateDuoSettings_Invalid()
    {
        $duoSdkClientMock = (new DuoSdkClientMock($this))->mockSuccessHealthCheck()->getClient();
        try {
            $duoSettings = new MfaOrgSettingsDuoService([
                MfaSettings::PROVIDER_DUO => [
                    MfaOrgSettings::DUO_CLIENT_ID => '🔥',
                    MfaOrgSettings::DUO_API_HOSTNAME => '🔥',
                    MfaOrgSettings::DUO_CLIENT_SECRET => '🔥',
                ],
            ]);
            $duoSettings->validateDuoSettings($duoSdkClientMock);
        } catch (CustomValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertTrue(isset($errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_CLIENT_SECRET]['isValidClientSecret']));
            $this->assertTrue(isset($errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_CLIENT_ID]['isValidClientId']));
            $this->assertTrue(isset($errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_API_HOSTNAME]['isValidApiHostname']));
        }
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsValidateDuoSettings_Success()
    {
        $duoSettings = new MfaOrgSettingsDuoService(
            [
                // SEC-5652 Note to security researchers: these are not leaked credentials
                // They look valid as they should pass validation, but are fake
                MfaSettings::PROVIDER_DUO => [
                    MfaOrgSettings::DUO_CLIENT_ID => 'DICPIC33F13IWF1FR52J',
                    MfaOrgSettings::DUO_API_HOSTNAME => 'api-42e9f2fe.duosecurity.com',
                    MfaOrgSettings::DUO_CLIENT_SECRET => '7TkYNgK8AGAuv3KW12qhsJLeIc1mJjHDHC1siNYX',
                ],
            ],
        );
        $duoSdkClientMock = (new DuoSdkClientMock($this))->mockSuccessHealthCheck()->getClient();
        $duoSettings->validateDuoSettings($duoSdkClientMock);
        $this->assertTrue(true);
    }

    /*
     * TEST ORG SETTING YUBIKEY TRAIT
     */

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsGetYubikeyProp()
    {
        Configure::write('passbolt.plugins.multiFactorAuthentication', $this->defaultConfig);
        $settings = MfaOrgSettings::get();
        $this->assertNotEmpty($settings->getYubikeyOTPSecretKey());
        $this->assertNotEmpty($settings->getYubikeyOTPClientId());
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsGetYubikeyIncompletePropsSeckey()
    {
        $config = [MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_YUBIKEY => true], MfaSettings::PROVIDER_YUBIKEY => []];
        $this->mockMfaOrgSettings($config);
        $settings = MfaOrgSettings::get();
        $this->expectException(RecordNotFoundException::class);
        $this->assertNotEmpty($settings->getYubikeyOTPSecretKey());
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsGetYubikeyIncompletePropsClientId()
    {
        $config = [MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_YUBIKEY => true], MfaSettings::PROVIDER_YUBIKEY => []];
        $this->mockMfaOrgSettings($config);
        $settings = MfaOrgSettings::get();
        $this->expectException(RecordNotFoundException::class);
        $this->assertNotEmpty($settings->getYubikeyOTPClientId());
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsValidateYubikeySettings_Empty()
    {
        $settings = new MfaOrgSettings([MfaSettings::PROVIDERS => []]);
        try {
            $settings->validateYubikeySettings([[
                MfaSettings::PROVIDER_YUBIKEY => [
                    MfaOrgSettings::YUBIKEY_CLIENT_ID => '',
                    MfaOrgSettings::YUBIKEY_SECRET_KEY => '',
                ],
            ]]);
        } catch (CustomValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertTrue(isset($errors[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_CLIENT_ID]['notEmpty']));
            $this->assertTrue(isset($errors[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_SECRET_KEY]['notEmpty']));
        }
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsValidateYubikeySettings_Invalid()
    {
        $settings = new MfaOrgSettings([MfaSettings::PROVIDERS => []]);
        try {
            $settings->validateYubikeySettings([
                MfaSettings::PROVIDER_YUBIKEY => [
                    MfaOrgSettings::YUBIKEY_CLIENT_ID => '🔥',
                    MfaOrgSettings::YUBIKEY_SECRET_KEY => '🔥',
                ],
            ]);
        } catch (CustomValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertTrue(isset($errors[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_CLIENT_ID]['isValidClientId']));
            $this->assertTrue(isset($errors[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_SECRET_KEY]['isValidSecretKey']));
        }
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsValidateYubikeySettings_Success()
    {
        $settings = new MfaOrgSettings([MfaSettings::PROVIDERS => []]);
        $settings->validateYubikeySettings([
            MfaSettings::PROVIDER_YUBIKEY => [
                MfaOrgSettings::YUBIKEY_CLIENT_ID => '12345',
                MfaOrgSettings::YUBIKEY_SECRET_KEY => 'i2/fAjeQBO/Axef16h2xlgRlXxY=',
            ],
        ]);
        $this->assertTrue(true);
    }
}
