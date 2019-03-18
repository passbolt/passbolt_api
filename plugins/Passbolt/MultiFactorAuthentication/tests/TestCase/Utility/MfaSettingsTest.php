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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Utility;

use App\Model\Table\OrganizationSettingsTable;
use App\Utility\UserAccessControl;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaOtpFactory;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaSettingsTest extends MfaIntegrationTestCase
{
    /**
     * @var array
     */
    public $fixtures = [
        'app.Base/OrganizationSettings',
        'plugin.Passbolt/AccountSettings.AccountSettings',
        'app.Base/AuthenticationTokens', 'app.Base/Users',
        'app.Base/Roles'
    ];

    /**
     * @var OrganizationSettingsTable
     */
    protected $OrganizationSettings;

    /**
     * @var array
     */
    protected $defaultOrgConfig = [
        MfaSettings::PROVIDERS => [
            MfaSettings::PROVIDER_DUO => true,
            MfaSettings::PROVIDER_TOTP => true,
            MfaSettings::PROVIDER_YUBIKEY => true
        ],
        MfaSettings::PROVIDER_YUBIKEY => [
            'clientId' => '40123',
            'secretKey' => 'i2/j3jIQBO/axOl3ah4mlgXlXUY='
        ],
        MfaSettings::PROVIDER_DUO => [
            'salt' => '__CHANGE_ME__THIS_MUST_BE_AT_LEAST_FOURTY_CHARACTERS_____',
            'integrationKey' => 'UICPIC93F14RWR5F55SJ',
            'secretKey' => '8tkYNgi8aGAqa3KW1eqhsJLfjc1nJnHDYC1siNYX',
            'hostName' => 'api-45e9f2ca.duosecurity.com'
        ]
    ];

    /**
     * @var array
     */
    protected $defaultAccountConfig;

    /**
     * @var UserAccessControl
     */
    protected $uac;

    /**
     * Setup.
     */
    public function setUp()
    {
        parent::setUp();
        $this->OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');

        $this->uac = $this->mockUserAccessControl('ada');
        $this->defaultAccountConfig = [
            MfaSettings::PROVIDERS => [
                MfaSettings::PROVIDER_TOTP
            ],
            MfaSettings::PROVIDER_TOTP => [
                MfaAccountSettings::VERIFIED => FrozenTime::now(),
                MfaAccountSettings::OTP_PROVISIONING_URI => MfaOtpFactory::generateTOTP($this->uac)
            ]
        ];
    }

    /**
     * @group mfa
     * @group mfaSettings
     */
    public function testMfaSettingsGetProvidersSuccess()
    {
        $this->assertTrue(is_array(MfaSettings::getProviders()));
    }

    /**
     * @group mfa
     * @group mfaSettings
     */
    public function testMfaSettingsGetSuccess()
    {
        $config = ['providers' => [MfaSettings::PROVIDER_DUO => true, ], MfaSettings::PROVIDER_DUO => []];
        $this->mockMfaOrgSettings($config, 'configure');
        $settings = MfaSettings::get($this->uac);
        $this->assertInstanceOf(MfaOrgSettings::class, $settings->getOrganizationSettings());
        $this->assertNull($settings->getAccountSettings());

        $this->mockMfaAccountSettings('ada', $this->defaultAccountConfig);
        $settings = MfaSettings::get($this->uac);
        $this->assertInstanceOf(MfaAccountSettings::class, $settings->getAccountSettings());
    }

    /**
     * @group mfa
     * @group mfaSettings
     */
    public function testMfaSettingsGetStatus()
    {
        $orgSettings = ['providers' => [MfaSettings::PROVIDER_TOTP => true]];
        $this->mockMfaOrgSettings($orgSettings, 'configure');
        $accountSettings = $this->defaultAccountConfig;
        $this->mockMfaAccountSettings('ada', $accountSettings);
        $settings = MfaSettings::get($this->uac);

        $status = $settings->getProvidersStatuses();
        $this->assertTrue($status[MfaSettings::ACCOUNT_SETTINGS][MfaSettings::PROVIDER_TOTP]);
        $this->assertFalse($status[MfaSettings::ACCOUNT_SETTINGS][MfaSettings::PROVIDER_DUO]);
        $this->assertFalse($status[MfaSettings::ACCOUNT_SETTINGS][MfaSettings::PROVIDER_YUBIKEY]);
        $this->assertTrue($status[MfaSettings::ORG_SETTINGS][MfaSettings::PROVIDER_TOTP]);
        $this->assertFalse($status[MfaSettings::ORG_SETTINGS][MfaSettings::PROVIDER_DUO]);
        $this->assertFalse($status[MfaSettings::ORG_SETTINGS][MfaSettings::PROVIDER_YUBIKEY]);
    }

    /**
     * @group mfa
     * @group mfaSettings
     */
    public function testMfaSettingsGetEnabledProviders()
    {
        $orgSettings = ['providers' => [MfaSettings::PROVIDER_TOTP => true]];
        $this->mockMfaOrgSettings($orgSettings, 'configure');
        $accountSettings = $this->defaultAccountConfig;
        $this->mockMfaAccountSettings('ada', $accountSettings);
        $settings = MfaSettings::get($this->uac);

        $providers = $settings->getEnabledProviders();
        $this->assertTrue(count($providers) === 1);
        $this->assertEquals($providers[0], MfaSettings::PROVIDER_TOTP);
    }

    /**
     * @group mfa
     * @group mfaSettings
     */
    public function testMfaSettingsGetProvidersVerifyUrls()
    {
        $orgSettings = ['providers' => [MfaSettings::PROVIDER_TOTP => true]];
        $this->mockMfaOrgSettings($orgSettings, 'configure');
        $accountSettings = $this->defaultAccountConfig;
        $this->mockMfaAccountSettings('ada', $accountSettings);
        $settings = MfaSettings::get($this->uac);

        $providersUrls = $settings->getProvidersVerifyUrls(true);
        $this->assertEquals($providersUrls[MfaSettings::PROVIDER_TOTP], Router::url('/mfa/verify/totp.json', true));
    }

    /**
     * @group mfa
     * @group mfaSettings
     */
    public function testMfaSettingsGetDefaultUrl()
    {
        $orgSettings = ['providers' => [MfaSettings::PROVIDER_TOTP => true]];
        $this->mockMfaOrgSettings($orgSettings, 'configure');
        $accountSettings = $this->defaultAccountConfig;
        $this->mockMfaAccountSettings('ada', $accountSettings);
        $settings = MfaSettings::get($this->uac);

        $defaultUrl = $settings->getDefaultVerifyUrl(false);
        $this->assertEquals($defaultUrl, Router::url('/mfa/verify/totp', true));
    }

    /**
     * @group mfa
     * @group mfaSettings
     */
    public function testMfaSettingsGetStatusInvalid()
    {
        $orgSettings = [
            'providers' => [
                MfaSettings::PROVIDER_TOTP => true,
                MfaSettings::PROVIDER_DUO => true,
                MfaSettings::PROVIDER_YUBIKEY => true,
            ],
            MfaSettings::PROVIDER_YUBIKEY => [
                'clientId' => '123456',
                'secretKey' => '8aG/Auv3KW1eqhs/JLfIc1mJnHD='
            ],
            MfaSettings::PROVIDER_DUO => [
                'salt' => 'Passbolt\MultiFactorAuthentication\Test\TestCase\Utility\MfaSettingsTest',
                // others are missing
            ]
        ];
        $this->mockMfaOrgSettings($orgSettings, 'configure');
        $accountSettings = [
            MfaSettings::PROVIDERS => [
                MfaSettings::PROVIDER_TOTP,
                MfaSettings::PROVIDER_YUBIKEY,
                MfaSettings::PROVIDER_DUO,
            ],
            MfaSettings::PROVIDER_DUO => [
                MfaAccountSettings::VERIFIED => FrozenTime::now()
            ],
            MfaSettings::PROVIDER_YUBIKEY => [
                // missing keyid
                MfaAccountSettings::VERIFIED => FrozenTime::now()
            ],
            MfaSettings::PROVIDER_TOTP => [
                MfaAccountSettings::VERIFIED => FrozenTime::now(),
                MfaAccountSettings::OTP_PROVISIONING_URI => MfaOtpFactory::generateTOTP($this->uac)
            ]
        ];
        $this->mockMfaAccountSettings('ada', $accountSettings);
        $settings = MfaSettings::get($this->uac);

        $status = $settings->getProvidersStatuses();
        $this->assertTrue($status[MfaSettings::ACCOUNT_SETTINGS][MfaSettings::PROVIDER_TOTP]);
        $this->assertTrue($status[MfaSettings::ACCOUNT_SETTINGS][MfaSettings::PROVIDER_DUO]);
        $this->assertFalse($status[MfaSettings::ACCOUNT_SETTINGS][MfaSettings::PROVIDER_YUBIKEY]);
        $this->assertTrue($status[MfaSettings::ORG_SETTINGS][MfaSettings::PROVIDER_TOTP]);
        $this->assertFalse($status[MfaSettings::ORG_SETTINGS][MfaSettings::PROVIDER_DUO]);
        $this->assertTrue($status[MfaSettings::ORG_SETTINGS][MfaSettings::PROVIDER_YUBIKEY]);

        $providers = $settings->getEnabledProviders();
        $this->assertTrue(count($providers) === 1);
        $this->assertEquals($providers[0], MfaSettings::PROVIDER_TOTP);
    }
}
