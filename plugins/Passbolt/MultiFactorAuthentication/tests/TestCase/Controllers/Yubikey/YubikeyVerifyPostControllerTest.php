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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Yubikey;

use App\Test\Factory\AuthenticationTokenFactory;
use Passbolt\MultiFactorAuthentication\Form\Yubikey\YubikeyVerifyForm;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Yubikey\MfaYubikeyScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class YubikeyVerifyPostControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyPost
     */
    public function testMfaVerifyPostYubikeyNotAuthenticated()
    {
        $this->post('/mfa/verify/yubikey.json?api-version=v2', []);
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testMfaVerifyPostYubikey_OTP_Valid()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaYubikeyScenario::class, $user);
        $this->mockValidMfaFormInterface(YubikeyVerifyForm::class, $this->makeUac($user));
        $this->post('/mfa/verify/yubikey.json?api-version=v2', [
            'hotp' => 'i-am-mocked',
        ]);
        $this->assertResponseSuccess();
        $mfaToken = AuthenticationTokenFactory::find()->firstOrFail();
        $this->assertCookieIsSecure($mfaToken->get('token'), MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }

    public function testMfaVerifyPostYubikey_OTP_Not_Valid()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaYubikeyScenario::class, $user, false);
        $this->post('/mfa/verify/yubikey.json?api-version=v2', [
            'hotp' => 'unvalid-otp',
        ]);
        $this->assertResponseError('This OTP is not valid.');
        $this->assertResponseCode(400);
    }
}
