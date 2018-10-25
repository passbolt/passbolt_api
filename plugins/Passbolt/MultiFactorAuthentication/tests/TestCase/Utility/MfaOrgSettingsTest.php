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
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;

class MfaOrgSettingsTest extends MfaIntegrationTestCase
{

    public $fixtures = [
        'app.Base/organization_settings',
        'app.Base/authentication_tokens', 'app.Base/users', 'app.Base/profiles',
        'app.Base/gpgkeys', 'app.Base/roles'
    ];

    /**
     * @var OrganizationSettingsTable
     */
    protected $OrganizationSettings;

    protected $defaultConfig = [
        'providers' => [
            'totp' => true,
            'duo' => true,
            'yubikey' => true
        ],
        'yubikey' => [
            'clientId' => '40123',
            'secretKey' => 'i2/j3jIQBO/axOl3ah4mlgXlXUY='
        ],
        'duo' => [
            'salt' => '__CHANGE_ME__THIS_MUST_BE_AT_LEAST_FOURTY_CHARACTERS_____',
            'integrationKey' => 'UICPIC93F14RWR5F55SJ',
            'secretKey' => '8tkYNgi8aGAqa3KW1eqhsJLfjc1nJnHDYC1siNYX',
            'hostName' => 'api-45e9f2ca.duosecurity.com'
        ]
    ];

    /**
     * Setup.
     */
    public function setUp()
    {
        parent::setUp();
        $this->OrganizationSettings = TableRegistry::get('OrganizationSettings');
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     */
    public function testMfaOrgSettingsGetSuccess()
    {
        Configure::write('passbolt.plugins.multiFactorAuthentication', $this->defaultConfig);
        $settings = MfaOrgSettings::get();
        $this->assertNotEmpty($settings);
        $this->assertEquals(count($settings->getProviders()), 3);
    }
}