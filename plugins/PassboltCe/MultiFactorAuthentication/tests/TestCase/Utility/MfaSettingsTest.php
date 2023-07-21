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

use App\Test\Factory\UserFactory;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Passbolt\MultiFactorAuthentication\Service\ActionLogs\MfaSortWithLastUsedProviderFirstService;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpUserOnlyScenario;
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
        'plugin.Passbolt/AccountSettings.AccountSettings',
        'app.Base/Users',
        'app.Base/Roles',
    ];

    /**
     * @var \Cake\ORM\Table
     */
    protected $OrganizationSettings;

    /**
     * @var array
     */
    protected $defaultOrgConfig = [
        MfaSettings::PROVIDERS => [
            MfaSettings::PROVIDER_DUO => true,
            MfaSettings::PROVIDER_TOTP => true,
            MfaSettings::PROVIDER_YUBIKEY => true,
        ],
        MfaSettings::PROVIDER_YUBIKEY => [
            'clientId' => '40123',
            'secretKey' => 'i2/j3jIQBO/axOl3ah4mlgXlXUY=',
        ],
        MfaSettings::PROVIDER_DUO => [
            'clientId' => 'UICPIC93F14RWR5F55SJ',
            'clientSecret' => '8tkYNgi8aGAqa3KW1eqhsJLfjc1nJnHDYC1siNYX',
            'apiHostName' => 'api-45e9f2ca.duosecurity.com',
        ],
    ];

    /**
     * @var array
     */
    protected $defaultAccountConfig;

    /**
     * @var \App\Utility\UserAccessControl
     */
    protected $uac;

    /**
     * Setup.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');

        $this->uac = $this->mockUserAccessControl('ada');
        $this->defaultAccountConfig = [
            MfaSettings::PROVIDERS => [
                MfaSettings::PROVIDER_TOTP,
            ],
            MfaSettings::PROVIDER_TOTP => [
                MfaAccountSettings::VERIFIED => FrozenTime::now(),
                MfaAccountSettings::OTP_PROVISIONING_URI => MfaOtpFactory::generateTOTP($this->uac),
            ],
        ];
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

    public function testMfaSettingsGetEnabledProviders_OrgDisabled_UserEnabled()
    {
        $user = UserFactory::make()->persist();
        $uac = $this->makeUac($user);
        $this->loadFixtureScenario(MfaTotpUserOnlyScenario::class, $user);
        $settings = MfaSettings::get($uac);
        $providers = $settings->getEnabledProviders();
        $this->assertEmpty($providers);
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
    public function testMfaSettingsGetDefaultUrl_Unsorted()
    {
        Configure::write(MfaSortWithLastUsedProviderFirstService::SORT_BY_LAST_USAGE_CONFIG_NAME, false);
        $orgSettings = ['providers' => [MfaSettings::PROVIDER_TOTP => true]];
        $this->mockMfaOrgSettings($orgSettings, 'configure');
        $accountSettings = $this->defaultAccountConfig;
        $this->mockMfaAccountSettings('ada', $accountSettings);
        $settings = MfaSettings::get($this->uac);

        $defaultUrl = $settings->getDefaultVerifyUrl(false);
        $this->assertEquals($defaultUrl, Router::url('/mfa/verify/totp', true));
        Configure::write(MfaSortWithLastUsedProviderFirstService::SORT_BY_LAST_USAGE_CONFIG_NAME, true);
    }

    /**
     * @group mfa
     * @group mfaSettings
     */
    public function testMfaSettingsGetDefaultUrl_Sorted()
    {
        Configure::write(MfaSortWithLastUsedProviderFirstService::SORT_BY_LAST_USAGE_CONFIG_NAME, true);
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
                'secretKey' => '8aG/Auv3KW1eqhs/JLfIc1mJnHD=',
            ],
            MfaSettings::PROVIDER_DUO => [
                // For organization settings, ensure that providers don't work with missing configs
                // For account settings, ensure that providers work with missing configs
            ],
        ];
        $this->mockMfaOrgSettings($orgSettings, 'configure');
        $accountSettings = [
            MfaSettings::PROVIDERS => [
                MfaSettings::PROVIDER_TOTP,
                MfaSettings::PROVIDER_YUBIKEY,
                MfaSettings::PROVIDER_DUO,
            ],
            MfaSettings::PROVIDER_DUO => [
                MfaAccountSettings::VERIFIED => FrozenTime::now(),
            ],
            MfaSettings::PROVIDER_YUBIKEY => [
                // missing keyid
                MfaAccountSettings::VERIFIED => FrozenTime::now(),
            ],
            MfaSettings::PROVIDER_TOTP => [
                MfaAccountSettings::VERIFIED => FrozenTime::now(),
                MfaAccountSettings::OTP_PROVISIONING_URI => MfaOtpFactory::generateTOTP($this->uac),
            ],
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
