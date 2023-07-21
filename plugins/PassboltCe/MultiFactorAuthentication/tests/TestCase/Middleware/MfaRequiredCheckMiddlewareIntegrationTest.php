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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Middleware;

use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenAbstractService;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenRenewalService;
use Passbolt\JwtAuthentication\Test\Factory\RefreshTokenAuthenticationTokenFactory;
use Passbolt\JwtAuthentication\Test\Utility\JwtAuthTestTrait;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaAuthenticationTokenFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Duo\MfaDuoScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Yubikey\MfaYubikeyScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class MfaRequiredCheckMiddlewareIntegrationTest extends MfaIntegrationTestCase
{
    use JwtAuthTestTrait;

    /**
     * @group mfa
     * @group mfaMiddleware
     */
    public function testMfaRequiredCheckMiddlewareVerifyNotNeededAnonymousUser()
    {
        $this->get('/app/users');
        $this->assertRedirectContains('/auth/login?redirect=%2Fapp%2Fusers');
    }

    /**
     * @group mfa
     * @group mfaMiddleware
     */
    public function testMfaRequiredCheckMiddlewareVerifyNotNeededOnLogout()
    {
        $this->post('/auth/logout');
        $this->assertRedirect('/auth/login');
    }

    /**
     * @group mfa
     * @group mfaMiddleware
     */
    public function testMfaRequiredCheckMiddlewareErrorNoVerifyCookie_Session_Auth()
    {
        RoleFactory::make()->guest()->persist();
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        $this->get('/users.json');
        $this->assertRedirect('/mfa/verify/error.json');

        $this->get('/app/users');
        $this->assertRedirect('mfa/verify/totp?redirect=/app/users');
    }

    /**
     * @group mfa
     * @group mfaMiddleware
     */
    public function testMfaRequiredCheckMiddlewareErrorNoVerifyCookie_JWT_Auth()
    {
        $user = UserFactory::make()->user()->persist();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);

        // Authenticate with JWT access token
        $this->createJwtTokenAndSetInHeader($user->id);
        $this->get('/app/users');
        $this->assertRedirect('/mfa/verify/totp?redirect=/app/users');
    }

    /**
     * @group mfa
     * @group mfaMiddleware
     */
    public function testMfaRequiredCheckMiddlewareSuccessWithVerifyCookie_JWT_Auth()
    {
        $user = UserFactory::make()->user()->persist();
        RoleFactory::make()->guest()->persist();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        // Authenticate with JWT access token
        $sessionId = $this->createJwtTokenAndSetInHeader($user->id);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_TOTP, false, $sessionId);
        $this->getJson('users.json');
        $this->assertResponseOk();
        $this->assertResponseContains($user->username);
    }

    public function testUsersAdd_Without_MFA_Token_In_Cookie_Is_Not_Allowed()
    {
        $admin = $this->logInAsAdmin();
        $this->loadFixtureScenario(MfaTotpScenario::class, $admin);
        RoleFactory::make()->guest()->persist();
        $data = [
            'username' => 'aurore@passbolt.com',
            'role_id' => RoleFactory::make()->user()->persist()->id,
            'profile' => [
                'first_name' => 'Aurore',
                'last_name' => 'Avarguès-Weber',
            ],
        ];

        $this->post('/users.json', $data);
        $this->assertRedirect('/mfa/verify/error.json');

        // Check user was not saved, since authorization was refused
        $query = UserFactory::find()->where(['username' => $data['username']]);
        $this->assertEquals(0, $query->count());
    }

    /**
     * @group mfa
     * @group mfaMiddleware
     */
    public function testMfaRequiredCheckMiddlewareError_ExpiredVerifyCookie()
    {
        $user = UserFactory::make()
            ->user()
            ->withAuthenticationTokens(
                MfaAuthenticationTokenFactory::make()->expired()
            )
            ->persist();
        $mfaCookie = $user->authentication_tokens[0]->token;

        $this->cookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS, $mfaCookie);

        $this->logInAs($user);
        $this->loadFixtureScenario(MfaDuoScenario::class, $user);
        $this->delete('/mfa/setup/duo.json?api-version=v2');
        $this->assertRedirect('/mfa/verify/error.json');
    }

    public function testMfaRefreshTokenCreatedListenerMiddleware_RefreshTokenSuccessful_No_MFA_Token()
    {
        $user = UserFactory::make()
            ->user()
            ->with(
                'AuthenticationTokens',
                RefreshTokenAuthenticationTokenFactory::make()->active()->getEntity()
            )
            ->persist();

        $oldRefreshToken = $user->authentication_tokens[0];
        $this->cookie(RefreshTokenRenewalService::REFRESH_TOKEN_COOKIE, $oldRefreshToken->token);
        $this->postJson('/auth/jwt/refresh.json');
        $this->assertResponseSuccess();
    }

    public function testMfaRefreshTokenCreatedListenerMiddleware_RefreshTokenSuccessful_Invalid_MFA_Token_Should_Redirect()
    {
        $user = UserFactory::make()
            ->user()
            ->with('AuthenticationTokens', [
                RefreshTokenAuthenticationTokenFactory::make()->active()->getEntity(),
                MfaAuthenticationTokenFactory::make()->inactive()->getEntity(),
            ])
            ->persist();
        $this->loadFixtureScenario(MfaYubikeyScenario::class, $user);

        $oldRefreshToken = $user->authentication_tokens[0];
        $mfaToken = $user->authentication_tokens[1];

        $this->cookie(RefreshTokenRenewalService::REFRESH_TOKEN_COOKIE, $oldRefreshToken->token);
        $this->cookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS, $mfaToken->token);

        $this->post('/auth/jwt/refresh.json');
        $this->assertRedirect('/mfa/verify/error.json');
    }

    public function testMfaRefreshTokenCreatedListenerMiddleware_RefreshTokenSuccessful_MFA_Required()
    {
        // This route, with cookie set, should have CSRF protection
        $this->enableCsrfToken();

        $user = UserFactory::make()
            ->with('Gpgkeys')
            ->user()
            ->with('AuthenticationTokens', RefreshTokenAuthenticationTokenFactory::make()->active())
            ->persist();

        $this->loadFixtureScenario(MfaYubikeyScenario::class, $user);
        $mfaToken = $this->mockMfaCookieValid(
            $this->makeUac($user),
            MfaSettings::PROVIDER_YUBIKEY,
            true
        );

        $oldRefreshToken = $user->authentication_tokens[0];
        $this->cookie(RefreshTokenAbstractService::REFRESH_TOKEN_COOKIE, $oldRefreshToken->token);

        $this->postJson('/auth/jwt/refresh.json');
        $this->assertResponseOk();

        $accessToken = $this->_responseJsonBody->access_token;
        /** @var \App\Model\Entity\AuthenticationToken $mfaToken */
        $mfaToken = MfaAuthenticationTokenFactory::find()->where(['token' => $mfaToken])->firstOrFail();

        // Checks that the session ID of the MFA token (here access token) is updated
        $this->assertTrue($mfaToken->checkSessionId($accessToken));
    }
}
