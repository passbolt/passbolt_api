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
use Duo\DuoUniversal\Client;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Mock\DuoSdkClientMock;
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
            return DuoSdkClientMock::createDefault($this, $user)->getClient();
        });

        $this->putJson('/mfa/settings.json?api-version=v2', $this->getDefaultMfaOrgSettings());
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
        $this->assertTrue(isset($result['body']['duo']['clientSecret']['notEmpty']));
        $this->assertTrue(isset($result['body']['duo']['apiHostName']['notEmpty']));
        $this->assertTrue(isset($result['body']['duo']['clientId']['notEmpty']));
        $this->assertTrue(isset($result['body']['yubikey']['secretKey']['isValidSecretKey']));
        $this->assertTrue(isset($result['body']['yubikey']['clientId']['isValidClientId']));
        $this->assertTrue(isset($result['body']['nope']));
        $this->assertResponseError();
    }
}
