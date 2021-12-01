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

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use OTPHP\Factory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class TotpVerifyPostControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyPost
     */
    public function testMfaVerifyPostTotpNotAuthenticated()
    {
        $this->post('/mfa/verify/totp.json?api-version=v2', []);
        $this->assertResponseError('You need to login to access this location.');
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyPost
     * @group mfaVerifyPostTotp
     */
    public function testMfaVerifyPostTotpUriSuccess()
    {
        $user = $this->logInAsUser();
        [$uri] = $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        $otp = Factory::loadFromProvisioningUri($uri);
        $this->post('/mfa/verify/totp?redirect=/app/users', [
            'totp' => $otp->now(),
        ]);
        $this->assertRedirect('/app/users');
    }

    /**
     * @group mfa
     * @group mfaVerifys
     * @group mfaVerifyPost
     * @group mfaVerifyPostTotp
     */
    public function testMfaVerifyPostTotpUriSuccessJson()
    {
        $user = $this->logInAsUser();
        [$uri] = $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        $otp = Factory::loadFromProvisioningUri($uri);
        $sessionId = 'Foo';
        $this->mockSessionId($sessionId);
        $this->post('/mfa/verify/totp.json?api-version=v2', [
            'totp' => $otp->now(),
        ]);
        $this->assertResponseOk();

        /** @var AuthenticationToken $mfaToken */
        $mfaToken = AuthenticationTokenFactory::find()
            ->where([
                'type' => AuthenticationToken::TYPE_MFA,
                'user_id' => $user->id,
            ])->firstOrFail();

        $this->assertTrue($mfaToken->checkSessionId($sessionId));
        $this->assertCookieIsSecure($mfaToken->get('token'), MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }

    /**
     * @group mfa
     * @group mfaVerifys
     * @group mfaVerifyPost
     * @group mfaVerifyPostTotp
     */
    public function testMfaVerifyPostTotpUriSuccessJson_JwtLogin()
    {
        $user = UserFactory::make()->user()->persist();
        $accessToken = $this->createJwtTokenAndSetInHeader($user->id);
        [$uri] = $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        $otp = Factory::loadFromProvisioningUri($uri);

        $this->post('/mfa/verify/totp.json?api-version=v2', [
            'totp' => $otp->now(),
        ]);
        $this->assertResponseOk();

        /** @var AuthenticationToken $mfaToken */
        $mfaToken = AuthenticationTokenFactory::find()
            ->where([
                'type' => AuthenticationToken::TYPE_MFA,
                'user_id' => $user->id,
            ])->orderDesc('created')->firstOrFail();

        $this->assertTrue($mfaToken->checkSessionId($accessToken));
        $this->assertCookieIsSecure($mfaToken->get('token'), MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }
}
