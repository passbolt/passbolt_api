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

use App\Model\Table\OrganizationSettingsTable;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
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
        'app.Base/organization_settings',
        'app.Base/authentication_tokens', 'app.Base/users',
        'app.Base/roles'
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
        $this->OrganizationSettings = TableRegistry::get('OrganizationSettings');

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
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsGetProvidersSuccess()
    {
        $settings = MfaOrgSettings::get();
        $this->assertInstanceOf(MfaOrgSettings::class, $settings);
    }
}