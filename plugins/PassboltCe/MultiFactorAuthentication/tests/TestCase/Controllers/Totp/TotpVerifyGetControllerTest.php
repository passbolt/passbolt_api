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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Totp;

use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Duo\MfaDuoScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
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
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_TOTP);
        $this->get('/mfa/verify/totp');
        $this->assertResponseError();
        $this->assertResponseContains('The multi-factor authentication is not required');
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyGet
     * @group mfaVerifyGetTotp
     */
    public function testMfaVerifyGetTotpOrgSettingsNotEnabled()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoScenario::class, $user);
        $this->get('/mfa/verify/totp');
        $this->assertRedirect('/');
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyGet
     * @group mfaVerifyGetTotp
     */
    public function testMfaVerifyGetTotpSuccess()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        $this->get('/mfa/verify/totp?redirect=/app/users');
        $this->assertResponseOk();
        $this->assertResponseContains('<form');
        $this->assertResponseContains('<input type="text" name="totp"');
        $this->assertResponseContains('<input type="checkbox" name="remember"');
        $this->assertResponseContains('/app/users');
        $this->assertResponseContains('Remember this device for a month');
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyGet
     * @group mfaVerifyGetTotp
     */
    public function testMfaVerifyGetTotpSuccessJson()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        $this->getJson('/mfa/verify/totp.json?api-version=v2');
        $this->assertResponseOk();
        $this->assertResponseContains('Please provide the one-time password.');
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyGet
     * @group mfaVerifyGetTotp
     */
    public function testMfaVerifyGetTotpErrorJson()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoScenario::class, $user);
        $this->getJson('/mfa/verify/totp.json?api-version=v2');
        $this->assertError();
        $this->assertResponseContains('No valid multi-factor authentication settings found for this provider.');
    }
}
