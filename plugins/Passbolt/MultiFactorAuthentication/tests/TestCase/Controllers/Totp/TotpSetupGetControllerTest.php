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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Totp;

use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaTotpSettingsTestTrait;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class TotpSetupGetControllerTest extends MfaIntegrationTestCase
{
    use MfaTotpSettingsTestTrait;

    public function setUp()
    {
        parent::setUp();
        $this->useHttpServer(true);
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupGet
     * @group mfaSetupGetTotp
     */
    public function testMfaSetupGetTotpNotAuthenticated()
    {
        $this->get('/mfa/setup/totp.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupGet
     * @group mfaSetupGetTotp
     */
    public function testMfaSetupGetTotpAlreadyConfigured()
    {
        $this->mockMfaTotpSettings('ada', 'valid');
        $this->mockMfaVerified('ada', MfaSettings::PROVIDER_TOTP);
        $this->authenticateAs('ada');
        $this->get('/mfa/setup/totp');
        $this->assertResponseOk();
        $this->assertResponseContains('is enabled');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupGet
     * @group mfaSetupGetTotp
     */
    public function testMfaSetupGetTotpOrgSettingsNotEnabled()
    {
        $this->mockMfaDuoSettings('ada', 'valid');
        $this->mockMfaVerified('ada', MfaSettings::PROVIDER_DUO);
        $this->authenticateAs('ada');
        $this->get('/mfa/setup/totp');
        $this->assertResponseError();
        $this->assertResponseContains('This authentication provider is not enabled for your organization.');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupGet
     * @group mfaSetupGetTotp
     */
    public function testMfaSetupGetTotpAccountSettingsEmpty()
    {
        $this->authenticateAs('ada');
        $this->mockMfaTotpSettings('ada', 'orgOnly');
        $this->get('/mfa/setup/totp');
        $this->assertResponseOk();
        $this->assertResponseContains('<form');
        $this->assertResponseContains('<img class="qrcode"');
    }
}
