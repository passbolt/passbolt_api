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
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaOrgSettingsDuoTraitTest extends MfaIntegrationTestCase
{
    /**
     * @var array
     */
    public $fixtures = [
        'app.Base/OrganizationSettings',
        'app.Base/AuthenticationTokens', 'app.Base/Users',
        'app.Base/Roles',
    ];

    /**
     * @var OrganizationSettingsTable
     */
    protected $OrganizationSettings;

    protected $defaultConfig = [
        'providers' => [
            MfaSettings::PROVIDER_DUO => true,
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
        $config = ['providers' => [MfaSettings::PROVIDER_DUO => true, ], MfaSettings::PROVIDER_DUO => []];
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
        $config = ['providers' => [MfaSettings::PROVIDER_DUO => true, ], MfaSettings::PROVIDER_DUO => []];
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
        $config = ['providers' => [MfaSettings::PROVIDER_DUO => true, ], MfaSettings::PROVIDER_DUO => []];
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
        $config = ['providers' => [MfaSettings::PROVIDER_DUO => true, ], MfaSettings::PROVIDER_DUO => []];
        $this->mockMfaOrgSettings($config, 'configure');
        $settings = MfaOrgSettings::get();
        $this->expectException(RecordNotFoundException::class);
        $this->assertNotEmpty($settings->getDuoIntegrationKey());
    }
}
