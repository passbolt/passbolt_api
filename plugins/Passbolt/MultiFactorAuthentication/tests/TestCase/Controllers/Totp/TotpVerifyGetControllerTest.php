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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Totp;

use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class TotpVerifyGetControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyGet
     * @group mfaVerifyGetTotp
     */
    public function testMfaVerifyGetTotpNotAuthenticated()
    {
        $this->get('/mfa/verify/totp.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyGet
     * @group mfaVerifyGetTotp
     */
    public function testMfaVerifyGetTotpAlreadyVerified()
    {
        $this->mockMfaTotpSettings('ada', 'valid');
        $this->mockMfaVerified('ada', MfaSettings::PROVIDER_TOTP);
        $this->authenticateAs('ada');
        $this->get('/mfa/verify/totp');
        $this->assertResponseError();
        $this->assertResponseContains('MFA is not required');
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyGet
     * @group mfaVerifyGetTotp
     */
    public function testMfaVerifyGetTotpOrgSettingsNotEnabled()
    {
        $this->mockMfaDuoSettings('ada', 'valid');
        $this->authenticateAs('ada');
        $this->get('/mfa/verify/totp');
        $this->assertResponseError();
        $this->assertResponseContains('No valid MFA settings');
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyGet
     * @group mfaVerifyGetTotp
     */
    public function testMfaVerifyGetTotpSuccess()
    {
        $this->mockMfaTotpSettings('ada', 'valid');
        $this->authenticateAs('ada');
        $this->get('/mfa/verify/totp');
        $this->assertResponseOk();
        $this->assertResponseContains('<form');
        $this->assertResponseContains('<input type="text" name="totp"');
        $this->assertResponseContains('<input type="checkbox" name="remember"');
    }


    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyGet
     * @group mfaVerifyGetTotp
     */
    public function testMfaVerifyGetTotpSuccessJson()
    {
        $this->mockMfaTotpSettings('ada', 'valid');
        $this->authenticateAs('ada');
        $this->getJson('/mfa/verify/totp.json?api-version=v2');
        $this->assertResponseOk();
        $this->assertResponseContains('Please provide the one time password.');
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyGet
     * @group mfaVerifyGetTotp
     */
    public function testMfaVerifyGetTotpErrorJson()
    {
        $this->mockMfaDuoSettings('ada', 'valid');
        $this->authenticateAs('ada');
        $this->getJson('/mfa/verify/totp.json?api-version=v2');
        $this->assertError();
        $this->assertResponseContains('No valid MFA settings');
    }
}
