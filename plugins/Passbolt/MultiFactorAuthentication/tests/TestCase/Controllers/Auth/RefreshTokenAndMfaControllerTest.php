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
 * @since         3.3.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Auth;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use Cake\Http\ServerRequest;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenAbstractService;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenCreateService;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenRenewalService;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaAuthenticationTokenFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Yubikey\MfaYubikeyScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

/**
 * Class AuthRefreshTokenControllerTest
 *
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 * @property \App\Model\Table\UsersTable $Users
 */
class RefreshTokenAndMfaControllerTest extends MfaIntegrationTestCase
{
    public function dataProviderWithAndWithoutAccessToken(): array
    {
        return [
            [true], [false],
        ];
    }

    /**
     * MFA should apply to the refresh token endpoint.
     *
     * @dataProvider dataProviderWithAndWithoutAccessToken
     * @param bool $withAccessToken With valid access token in header or no access token
     */
    public function testRefreshTokenController_With_Mfa_Required_And_No_Mfa_Token_Provided_Should_Redirect(bool $withAccessToken)
    {
        $user = UserFactory::make()->user()->persist();
        if ($withAccessToken) {
            $this->createJwtTokenAndSetInHeader($user->id);
        }

        $this->loadFixtureScenario(MfaYubikeyScenario::class, $user);
        $oldRefreshToken = (new RefreshTokenCreateService())
            ->createToken(new ServerRequest(), $user->id, 'Foo');

        $this->post('/auth/jwt/refresh.json', [
            'user_id' => $user->id,
            'refresh_token' => $oldRefreshToken->token,
        ]);
        $this->assertRedirect('/mfa/verify/error.json');
        $this->assertResponseEquals('');
    }

    /**
     * MFA session ID should be read from the refresh token's access token,
     * and not from the access token in the header (is any in the header).
     *
     * @dataProvider dataProviderWithAndWithoutAccessToken
     * @param bool $withAccessToken With valid access token in header or no access token
     */
    public function testAuthRefreshTokenControllerWithValidRefreshTokenCookieAndValidNoRememberMeMfaToken(bool $withAccessToken)
    {
        $user = UserFactory::make()->user()->persist();
        if ($withAccessToken) {
            $this->createJwtTokenAndSetInHeader($user->id);
        }

        $sessionId = 'Foo';
        $this->loadFixtureScenario(MfaYubikeyScenario::class, $user);
        $oldRefreshToken = (new RefreshTokenCreateService())->createToken(new ServerRequest(), $user->id, $sessionId);

        $this->cookie(RefreshTokenRenewalService::REFRESH_TOKEN_COOKIE, $oldRefreshToken->token);

        $mfaCookie = $this->mockMfaCookieValid(
            $this->makeUac($user),
            MfaSettings::PROVIDER_YUBIKEY,
            false, // Remember me is false, so sessionId is checked in the access token
            $sessionId
        );

        // This route, with cookie, should have CSRF protection
        $this->enableCsrfToken();
        $this->postJson('/auth/jwt/refresh.json');
        $this->assertResponseOk();
        $accessToken = $this->_responseJsonBody->access_token;

        /** @var AuthenticationToken $newRefreshToken */
        $newRefreshToken = AuthenticationTokenFactory::find()->where([
            'active' => true,
            'user_id' => $user->id,
            'type' => AuthenticationToken::TYPE_REFRESH_TOKEN,
        ])->firstOrFail();
        $this->assertCookieIsSecure($newRefreshToken->get('token'), RefreshTokenAbstractService::REFRESH_TOKEN_COOKIE);
        $this->assertCookieIsSecure($mfaCookie, MfaVerifiedCookie::MFA_COOKIE_ALIAS);

        // We check that the session ID of the MFA token has been updated
        /** @var AuthenticationToken $updatedMfaCookie */
        $updatedMfaCookie = MfaAuthenticationTokenFactory::find()->where(['token' => $mfaCookie])->firstOrFail();
        $this->assertTrue($updatedMfaCookie->isActive());
        $this->assertTrue($newRefreshToken->isActive());
        $this->assertFalse($updatedMfaCookie->isExpired());
        $this->assertFalse($newRefreshToken->isExpired());
        $this->assertFalse($updatedMfaCookie->checkSessionId($sessionId));
        $this->assertTrue($updatedMfaCookie->checkSessionId($accessToken));
        $this->assertTrue($newRefreshToken->checkSessionId($accessToken));
    }

    /**
     * @dataProvider dataProviderWithAndWithoutAccessToken
     * @param bool $withAccessToken With valid access token in header or no access token
     */
    public function testAuthRefreshTokenControllerWithValidPayload_MFA_No_Remember_Wrong_Session_Should_Redirect(bool $withAccessToken)
    {
        $user = UserFactory::make()->user()->persist();
        if ($withAccessToken) {
            $this->createJwtTokenAndSetInHeader($user->id);
        }
        $this->loadFixtureScenario(MfaYubikeyScenario::class, $user);

        $refreshToken = (new RefreshTokenCreateService())->createToken(new ServerRequest(), $user->id, 'Bar');

        $sessionId = 'Foo'; // Some random session ID that dp not match the $refreshToken
        $this->mockMfaCookieValid(
            $this->makeUac($user),
            MfaSettings::PROVIDER_YUBIKEY,
            false,
            $sessionId
        );

        $this->post('/auth/jwt/refresh.json', [
            'user_id' => $user->id,
            'refresh_token' => $refreshToken->token,
        ]);

        $this->assertRedirect('/mfa/verify/error.json');
        $this->assertResponseEquals('');
    }

    /**
     * @dataProvider dataProviderWithAndWithoutAccessToken
     * @param bool $withAccessToken With valid access token in header or no access token
     */
    public function testAuthRefreshTokenControllerWithValidPayload(bool $withAccessToken)
    {
        $user = UserFactory::make()->user()->persist();
        if ($withAccessToken) {
            $this->createJwtTokenAndSetInHeader($user->id);
        }
        $this->loadFixtureScenario(MfaYubikeyScenario::class, $user);

        $sessionId = 'Foo'; // Some random session ID
        $oldRefreshToken = (new RefreshTokenCreateService())->createToken(new ServerRequest(), $user->id, $sessionId);

        $mfaCookie = $this->mockMfaCookieValid(
            $this->makeUac($user),
            MfaSettings::PROVIDER_YUBIKEY,
            false,
            $sessionId
        );

        $this->postJson('/auth/jwt/refresh.json', [
            'user_id' => $user->id,
            'refresh_token' => $oldRefreshToken->token,
        ]);

        $this->assertResponseOk();
        $accessToken = $this->_responseJsonBody->access_token;

        // We check that the session ID of the MFA token has well been updated
        /** @var AuthenticationToken $updatedMfaCookie */
        $updatedMfaCookie = MfaAuthenticationTokenFactory::find()->where(['token' => $mfaCookie])->firstOrFail();
        $this->assertTrue($updatedMfaCookie->isActive());
        $this->assertFalse($updatedMfaCookie->isExpired());
        $this->assertFalse($updatedMfaCookie->checkSessionId($sessionId));
        $this->assertTrue($updatedMfaCookie->checkSessionId($accessToken));

        $newRefreshToken = AuthenticationTokenFactory::find()->where([
            'active' => true,
            'user_id' => $user->id,
            'type' => AuthenticationToken::TYPE_REFRESH_TOKEN,
        ])->firstOrFail()->get('token');
        $this->assertCookieIsSecure($newRefreshToken, RefreshTokenAbstractService::REFRESH_TOKEN_COOKIE);
        $this->assertCookieIsSecure($mfaCookie, MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }

    /**
     * @dataProvider dataProviderWithAndWithoutAccessToken
     * @param bool $withAccessToken With valid access token in header or no access token
     */
    public function testAuthRefreshTokenControllerWithValidPayload_And_Refresh_Token_In_Cookie(bool $withAccessToken)
    {
        $user = UserFactory::make()->user()->persist();
        if ($withAccessToken) {
            $this->createJwtTokenAndSetInHeader($user->id);
        }
        $this->loadFixtureScenario(MfaYubikeyScenario::class, $user);

        $sessionId = 'Foo'; // Some random session ID
        $oldRefreshTokenInPayload = (new RefreshTokenCreateService())->createToken(new ServerRequest(), $user->id, $sessionId);
        $oldRefreshTokenInCookie = (new RefreshTokenCreateService())->createToken(new ServerRequest(), $user->id, 'Bar');

        $this->cookie(RefreshTokenRenewalService::REFRESH_TOKEN_COOKIE, $oldRefreshTokenInCookie->token);

        $mfaCookie = $this->mockMfaCookieValid(
            $this->makeUac($user),
            MfaSettings::PROVIDER_YUBIKEY,
            false,
            $sessionId
        );

        $this->postJson('/auth/jwt/refresh.json', [
            'user_id' => $user->id,
            'refresh_token' => $oldRefreshTokenInPayload->token,
        ]);

        $this->assertResponseOk();
        $accessToken = $this->_responseJsonBody->access_token;

        // We check that the session ID of the MFA token has well been updated
        /** @var AuthenticationToken $updatedMfaCookie */
        $updatedMfaCookie = MfaAuthenticationTokenFactory::find()->where(['token' => $mfaCookie])->firstOrFail();
        $this->assertTrue($updatedMfaCookie->isActive());
        $this->assertFalse($updatedMfaCookie->isExpired());
        $this->assertFalse($updatedMfaCookie->checkSessionId($sessionId));
        $this->assertTrue($updatedMfaCookie->checkSessionId($accessToken));

        $newRefreshToken = AuthenticationTokenFactory::find()->where([
            'id !=' => $oldRefreshTokenInCookie->id,
            'active' => true,
            'user_id' => $user->id,
            'type' => AuthenticationToken::TYPE_REFRESH_TOKEN,
        ])->firstOrFail()->get('token');
        $this->assertCookieIsSecure($newRefreshToken, RefreshTokenAbstractService::REFRESH_TOKEN_COOKIE);
        $this->assertCookieIsSecure($mfaCookie, MfaVerifiedCookie::MFA_COOKIE_ALIAS);

        $oldRefreshTokenInCookie = AuthenticationTokenFactory::get($oldRefreshTokenInCookie->id);
        $oldRefreshTokenInPayload = AuthenticationTokenFactory::get($oldRefreshTokenInPayload->id);
        $this->assertTrue($oldRefreshTokenInCookie->isActive());
        $this->assertFalse($oldRefreshTokenInPayload->isActive());
    }

    /**
     * @dataProvider dataProviderWithAndWithoutAccessToken
     * @param bool $withAccessToken With valid access token in header or no access token
     */
    public function testAuthRefreshTokenControllerWithInvalidMfaAndMfaDeactivatedShouldSkipMfaCheck(bool $withAccessToken)
    {
        $user = UserFactory::make()->user()->persist();
        if ($withAccessToken) {
            $this->createJwtTokenAndSetInHeader($user->id);
        }

        $refreshToken = (new RefreshTokenCreateService())->createToken(new ServerRequest(), $user->id, 'Bar');

        $mfaToken = 'Foo';
        $this->cookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS, $mfaToken);

        $this->postJson('/auth/jwt/refresh.json', [
            'user_id' => $user->id,
            'refresh_token' => $refreshToken->token,
        ]);

        $this->assertResponseOk();
    }
}
