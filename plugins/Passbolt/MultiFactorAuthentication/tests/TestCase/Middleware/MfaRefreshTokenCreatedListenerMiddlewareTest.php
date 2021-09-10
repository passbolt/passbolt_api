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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Middleware;

use App\Test\Factory\UserFactory;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenRenewalService;
use Passbolt\JwtAuthentication\Test\Factory\RefreshTokenAuthenticationTokenFactory;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaAccountSettingFactory;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaAuthenticationTokenFactory;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaOrganizationSettingFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class MfaRefreshTokenCreatedListenerMiddlewareTest extends MfaIntegrationTestCase
{
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

    public function testMfaRefreshTokenCreatedListenerMiddleware_RefreshTokenSuccessful_Invalid_MFA_Token()
    {
        $user = UserFactory::make()
            ->user()
            ->with('AuthenticationTokens', [
                RefreshTokenAuthenticationTokenFactory::make()->active()->getEntity(),
                MfaAuthenticationTokenFactory::make()->inactive()->getEntity(),
            ])
            ->persist();

        $oldRefreshToken = $user->authentication_tokens[0];
        $mfaToken = $user->authentication_tokens[1];

        $this->cookie(RefreshTokenRenewalService::REFRESH_TOKEN_COOKIE, $oldRefreshToken->token);
        $this->cookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS, $mfaToken->token);

        $this->postJson('/auth/jwt/refresh.json');
        $this->assertBadRequestError('The MFA token provided does not exist or is inactive.');
    }

    public function testMfaRefreshTokenCreatedListenerMiddleware_RefreshTokenSuccessful_MFA_Required()
    {
        // This route, with cookie set, should have CSRF protection
        $this->enableCsrfToken();

        MfaOrganizationSettingFactory::make()->totp()->persist();
        $user = UserFactory::make()
            ->user()
            ->with('AuthenticationTokens', [
                RefreshTokenAuthenticationTokenFactory::make()->active()->getEntity(),
                MfaAuthenticationTokenFactory::make()->active()->getEntity(),
            ])
            ->with('AccountSettings', MfaAccountSettingFactory::make()->totp())
            ->persist();

        $oldRefreshToken = $user->authentication_tokens[0];
        $mfaToken = $user->authentication_tokens[1];

        $this->cookie(RefreshTokenRenewalService::REFRESH_TOKEN_COOKIE, $oldRefreshToken->token);
        $this->cookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS, $mfaToken->token);

        $this->postJson('/auth/jwt/refresh.json');
        $this->assertResponseOk();

        $accessToken = $this->_responseJsonBody->access_token;
        /** @var \App\Model\Entity\AuthenticationToken $mfaToken */
        $mfaToken = MfaAuthenticationTokenFactory::find()->where(['id' => $mfaToken->id])->firstOrFail();

        // Checks that the session ID of the MFA token (here access token) is updated
        $this->assertTrue($mfaToken->checkSessionId($accessToken));
    }
}
