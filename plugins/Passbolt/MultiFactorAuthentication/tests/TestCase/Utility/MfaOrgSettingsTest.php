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
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaOrgSettingsTest extends MfaIntegrationTestCase
{
    /**
     * @var array
     */
    public $fixtures = [
        'app.Base/Users',
        'app.Base/Roles',
    ];

    /**
     * @var OrganizationSettingsTable
     */
    protected $OrganizationSettings;

    protected $defaultConfig = [
        'providers' => [
            MfaSettings::PROVIDER_DUO => true,
            MfaSettings::PROVIDER_TOTP => true,
            MfaSettings::PROVIDER_YUBIKEY => true,
        ],
        MfaSettings::PROVIDER_YUBIKEY => [
            'clientId' => '40123',
            'secretKey' => 'i2/j3jIQBO/axOl3ah4mlgXlXUY=',
        ],
        MfaSettings::PROVIDER_DUO => [
            'salt' => '__CHANGE_ME__THIS_MUST_BE_AT_LEAST_FOURTY_CHARACTERS_____',
            'integrationKey' => 'UICPIC93F14RWR5F55SJ',
            'secretKey' => '8tkYNgi8aGAqa3KW1eqhsJLfjc1nJnHDYC1siNYX',
            'hostName' => 'api-45e9f2ca.duosecurity.com',
        ],
    ];

    /**
     * Setup.
     */
    public function setUp()
    {
        parent::setUp();
        $this->OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
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
        $this->mockMfaOrgSettings($config, 'configure');
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
        $this->mockMfaOrgSettings($config, 'configure');
        $settings = MfaOrgSettings::get();
        $this->assertFalse($settings->isProviderEnabled(MfaSettings::PROVIDER_YUBIKEY));

        $config = $this->defaultConfig;
        $config[MfaSettings::PROVIDERS] = [MfaSettings::PROVIDER_DUO];
        $this->mockMfaOrgSettings($config, 'configure');
        $settings = MfaOrgSettings::get();
        $this->assertFalse($settings->isProviderEnabled(MfaSettings::PROVIDER_YUBIKEY));
        $this->assertFalse($settings->isProviderEnabled(MfaSettings::PROVIDER_TOTP));
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
        $this->mockMfaOrgSettings($config, 'configure');
        $settings = MfaOrgSettings::get();
        $status = $settings->getProvidersStatus();
        $this->assertEquals($status, $providers);

        // No provider set
        $config = $this->defaultConfig;
        $config[MfaSettings::PROVIDERS] = [];
        $this->mockMfaOrgSettings($config, 'configure');
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
        $this->mockMfaOrgSettings($config, 'configure');
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
        $this->mockMfaOrgSettings($config, 'configure');
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
        $settings = MfaOrgSettings::get();
        $this->assertNotEmpty($settings->getDuoSalt());
        $this->assertNotEmpty($settings->getDuoHostname());
        $this->assertNotEmpty($settings->getDuoSecretKey());
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsGetDuoIncompletePropsSalt()
    {
        $config = [MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO => true, ], MfaSettings::PROVIDER_DUO => []];
        $this->mockMfaOrgSettings($config, 'configure');
        $settings = MfaOrgSettings::get();
        $this->expectException(RecordNotFoundException::class);
        $this->assertNotEmpty($settings->getDuoSalt());
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsGetDuoIncompletePropsHostname()
    {
        $config = [MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO => true, ], MfaSettings::PROVIDER_DUO => []];
        $this->mockMfaOrgSettings($config, 'configure');
        $settings = MfaOrgSettings::get();
        $this->expectException(RecordNotFoundException::class);
        $this->assertNotEmpty($settings->getDuoHostname());
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsGetDuoIncompletePropsSeckey()
    {
        $config = [MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO => true, ], MfaSettings::PROVIDER_DUO => []];
        $this->mockMfaOrgSettings($config, 'configure');
        $settings = MfaOrgSettings::get();
        $this->expectException(RecordNotFoundException::class);
        $this->assertNotEmpty($settings->getDuoSecretKey());
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsGetDuoIncompletePropsIKey()
    {
        $config = [MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO => true, ], MfaSettings::PROVIDER_DUO => []];
        $this->mockMfaOrgSettings($config, 'configure');
        $settings = MfaOrgSettings::get();
        $this->expectException(RecordNotFoundException::class);
        $this->assertNotEmpty($settings->getDuoIntegrationKey());
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsValidateDuoSettings_Empty()
    {
        $settings = new MfaOrgSettings([MfaSettings::PROVIDERS => []]);
        try {
            $settings->validateDuoSettings([[
                MfaSettings::PROVIDER_DUO => [
                    MfaOrgSettings::DUO_SALT => '',
                    MfaOrgSettings::DUO_INTEGRATION_KEY => '',
                    MfaOrgSettings::DUO_HOSTNAME => '',
                    MfaOrgSettings::DUO_SECRET_KEY => '',
                ],
            ]]);
        } catch (CustomValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertTrue(isset($errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SALT]['notEmpty']));
            $this->assertTrue(isset($errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SECRET_KEY]['notEmpty']));
            $this->assertTrue(isset($errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_INTEGRATION_KEY]['notEmpty']));
            $this->assertTrue(isset($errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_HOSTNAME]['notEmpty']));
        }
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsValidateDuoSettings_Invalid()
    {
        $settings = new MfaOrgSettings([MfaSettings::PROVIDERS => []]);
        try {
            $settings->validateDuoSettings([
                MfaSettings::PROVIDER_DUO => [
                    MfaOrgSettings::DUO_SALT => '🔥',
                    MfaOrgSettings::DUO_INTEGRATION_KEY => '🔥',
                    MfaOrgSettings::DUO_HOSTNAME => '🔥',
                    MfaOrgSettings::DUO_SECRET_KEY => '🔥',
                ],
            ]);
        } catch (CustomValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertTrue(isset($errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SALT]['lengthBetween']));
            $this->assertTrue(isset($errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SECRET_KEY]['isValidSecretKey']));
            $this->assertTrue(isset($errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_INTEGRATION_KEY]['isValidIntegrationKey']));
            $this->assertTrue(isset($errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_HOSTNAME]['isValidHostname']));
        }
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsValidateDuoSettings_Success()
    {
        $settings = new MfaOrgSettings([MfaSettings::PROVIDERS => []]);
        $settings->validateDuoSettings([
            MfaSettings::PROVIDER_DUO => [
                MfaOrgSettings::DUO_SALT => 'qwertyuiopasdfghjklzxcvbnm12345678901234567890',
                MfaOrgSettings::DUO_INTEGRATION_KEY => 'DICPIC33F13IWF1FR52J',
                MfaOrgSettings::DUO_HOSTNAME => 'api-42e9f2fe.duosecurity.com',
                MfaOrgSettings::DUO_SECRET_KEY => '7TkYNgK8AGAuv3KW12qhsJLeIc1mJjHDHC1siNYX',
            ],
        ]);
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
        $this->mockMfaOrgSettings($config, 'configure');
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
        $this->mockMfaOrgSettings($config, 'configure');
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
