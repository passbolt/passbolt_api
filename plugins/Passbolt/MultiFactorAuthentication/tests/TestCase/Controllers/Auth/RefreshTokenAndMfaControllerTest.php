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
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenRenewalService;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaAuthenticationTokenFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;

/**
 * Class AuthRefreshTokenControllerTest
 *
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 * @property \App\Model\Table\UsersTable $Users
 */
class RefreshTokenAndMfaControllerTest extends MfaIntegrationTestCase
{
    public function testAuthRefreshTokenControllerWithValidRefreshTokenCookie()
    {
        $user = UserFactory::make()->user()->persist();
        $oldRefreshToken = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->userId($user->id)
            ->persist()
            ->token;

        $this->cookie(RefreshTokenRenewalService::REFRESH_TOKEN_COOKIE, $oldRefreshToken);

        $sessionId = 'Foo'; // Some random session ID
        $mfaCookie = $this->mockMfaCookieValid(
            $this->makeUac($user),
            'Bar', // The provider is not relevant
            false, // Remember me is not relevant
            $sessionId
        );

        // This route, with cookie, should have CSRF protection
        $this->enableCsrfToken();
        $this->postJson('/auth/jwt/refresh.json');
        $this->assertResponseOk();
        $accessToken = $this->_responseJsonBody->access_token;

        $newRefreshToken = AuthenticationTokenFactory::find()->where([
            'active' => true,
            'user_id' => $user->id,
            'type' => AuthenticationToken::TYPE_REFRESH_TOKEN,
        ])->firstOrFail()->get('token');
        $this->assertCookie($newRefreshToken, 'refresh_token');

        // We check that the session ID of the MFA tocken has well been updated
        /** @var AuthenticationToken $updatedMfaCookie */
        $updatedMfaCookie = MfaAuthenticationTokenFactory::find()->where(['token' => $mfaCookie])->firstOrFail();
        $this->assertTrue($updatedMfaCookie->isActive());
        $this->assertFalse($updatedMfaCookie->isExpired());
        $this->assertFalse($updatedMfaCookie->checkSessionId($sessionId));
        $this->assertTrue($updatedMfaCookie->checkSessionId($accessToken));
    }

    public function testAuthRefreshTokenControllerWithValidPayload()
    {
        $user = UserFactory::make()->user()->persist();
        $oldRefreshToken = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->userId($user->id)
            ->persist()
            ->token;

        $sessionId = 'Foo'; // Some random session ID
        $mfaCookie = $this->mockMfaCookieValid(
            $this->makeUac($user),
            'Bar', // The provider is not relevant
            false, // Remember me is not relevant
            $sessionId
        );

        $this->postJson('/auth/jwt/refresh.json', [
            'user_id' => $user->id,
            'refresh_token' => $oldRefreshToken,
        ]);

        $this->assertResponseSuccess();
        $accessToken = $this->_responseJsonBody->access_token;

        // We check that the session ID of the MFA tocken has well been updated
        /** @var AuthenticationToken $updatedMfaCookie */
        $updatedMfaCookie = MfaAuthenticationTokenFactory::find()->where(['token' => $mfaCookie])->firstOrFail();
        $this->assertTrue($updatedMfaCookie->isActive());
        $this->assertFalse($updatedMfaCookie->isExpired());
        $this->assertFalse($updatedMfaCookie->checkSessionId($sessionId));
        $this->assertTrue($updatedMfaCookie->checkSessionId($accessToken));
    }
}