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
 * @since         2.6.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\OrgSettings;

use App\Model\Entity\Role;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaOrgSettingsPostControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsPost
     */
    public function testMfaOrgSettingsPostControllerNotLoggedIn()
    {
        $this->postJson('/mfa/settings.json?api-version=v2', ['providers' => []]);
        $this->assertResponseError('You need to login to access this location.');
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
        $this->assertResponseError('This is not a valid Ajax/Json request.');
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
        $this->assertResponseError('You are not allowed to access this location.');
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
        $this->mockMfaOrgSettings($config, 'configure');
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
        $this->mockMfaOrgSettings($config, 'database', $this->mockUserAccessControl('admin', Role::ADMIN));
        $this->authenticateAs('admin');
        $this->putJson('/mfa/settings.json?api-version=v2', [
            MfaSettings::PROVIDERS => [
                MfaSettings::PROVIDER_DUO => true,
                MfaSettings::PROVIDER_TOTP => true,
                MfaSettings::PROVIDER_YUBIKEY => true,
            ],
            MfaSettings::PROVIDER_YUBIKEY => [
                'clientId' => '12345',
                'secretKey' => 'i2/j3jIQBO/axOl3ah4mlgXlXUY=',
            ],
            MfaSettings::PROVIDER_DUO => [
                'salt' => '__CHANGE_ME__THIS_MUST_BE_AT_LEAST_FOURTY_CHARACTERS_____',
                'integrationKey' => 'UICPIC93F14RWR5F55SJ',
                'secretKey' => '8tkYNgi8aGAqa3KW1eqhsJLfjc1nJnHDYC1siNYX',
                'hostName' => 'api-45e9f2ca.duosecurity.com',
            ],
        ]);
        $this->assertResponseSuccess();
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
        $this->assertTrue(isset($result['body']['duo']['salt']['notEmpty']));
        $this->assertTrue(isset($result['body']['duo']['secretKey']['notEmpty']));
        $this->assertTrue(isset($result['body']['duo']['hostName']['notEmpty']));
        $this->assertTrue(isset($result['body']['duo']['integrationKey']['notEmpty']));
        $this->assertTrue(isset($result['body']['yubikey']['secretKey']['isValidSecretKey']));
        $this->assertTrue(isset($result['body']['yubikey']['clientId']['isValidClientId']));
        $this->assertTrue(isset($result['body']['nope']));
        $this->assertResponseError();
    }
}
