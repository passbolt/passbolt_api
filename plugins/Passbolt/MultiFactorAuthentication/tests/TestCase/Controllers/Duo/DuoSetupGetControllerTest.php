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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Duo;

use Passbolt\MultiFactorAuthentication\Test\Lib\MfaDuoSettingsTestTrait;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class DuoSetupGetControllerTest extends MfaIntegrationTestCase
{
    use MfaDuoSettingsTestTrait;

    /**
     * @var array
     */
    public $fixtures = [
        'app.Base/OrganizationSettings',
        'plugin.Passbolt/AccountSettings.AccountSettings',
        'app.Base/AuthenticationTokens', 'app.Base/Users',
        'app.Base/Roles',
    ];

    public function setUp()
    {
        parent::setUp();
        $this->useHttpServer(true);
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupGet
     * @group mfaSetupGetDuo
     */
    public function testMfaSetupGetDuoNotAuthenticated()
    {
        $this->get('/mfa/setup/duo.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupGet
     * @group mfaSetupGetDuo
     */
    public function testMfaSetupGetDuoJsonNotAllowed()
    {
        $this->authenticateAs('ada');
        $this->get('/mfa/setup/duo.json?api-version=v2');
        $this->assertResponseError('not available');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupGet
     * @group mfaSetupGetDuo
     */
    public function testMfaSetupGetDuoAlreadyConfigured()
    {
        $this->mockMfaDuoSettings('ada', 'valid');
        $this->mockMfaVerified('ada', MfaSettings::PROVIDER_DUO);
        $this->authenticateAs('ada');
        $this->get('/mfa/setup/duo');
        $this->assertResponseOk();
        $this->assertResponseContains('Duo multi-factor authentication is enabled');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupGet
     * @group mfaSetupGetDuo
     */
    public function testMfaSetupGetDuoOrgSettingsNotEnabled()
    {
        $this->mockMfaTotpSettings('ada', 'valid');
        $this->mockMfaVerified('ada', MfaSettings::PROVIDER_TOTP);
        $this->authenticateAs('ada');
        $this->get('/mfa/setup/duo');
        $this->assertResponseError();
        $this->assertResponseContains('This authentication provider is not enabled for your organization.');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupGet
     * @group mfaSetupGetDuo
     */
    public function testMfaSetupGetDuoAccountSettingsEmpty()
    {
        $this->authenticateAs('ada');
        $this->mockMfaDuoSettings('ada', 'orgOnly');
        $this->get('/mfa/setup/duo');
        $this->assertResponseOk();
        $this->assertResponseContains('<iframe');
    }
}
