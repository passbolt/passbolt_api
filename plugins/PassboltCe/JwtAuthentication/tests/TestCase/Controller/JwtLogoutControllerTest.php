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
namespace Passbolt\JwtAuthentication\Test\TestCase\Controller;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\JwtAuthentication\Service\Middleware\JwtAuthenticationService;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenAbstractService;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenRenewalService;
use Passbolt\JwtAuthentication\Test\Utility\JwtAuthenticationIntegrationTestCase;

/**
 * Class AuthJwtLogoutControllerTest
 */
class JwtLogoutControllerTest extends JwtAuthenticationIntegrationTestCase
{
    use LocatorAwareTrait;

    /**
     * @var \App\Model\Table\AuthenticationTokensTable
     */
    protected $AuthenticationTokens;

    public function setUp(): void
    {
        parent::setUp();
        $this->AuthenticationTokens = $this->fetchTable('AuthenticationTokens');
    }

    public function testAuthJwtLogoutControllerUnauthenticated()
    {
        $this->postJson('/auth/jwt/logout.json');
        $this->assertResponseError();
    }

    public function testAuthJwtLogoutControllerNoPayload()
    {
        $user = UserFactory::make()->user()->persist();
        $this->createJwtTokenAndSetInHeader($user->id);
        $nToken = 3;
        AuthenticationTokenFactory::make($nToken)
            ->active()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->userId($user->id)
            ->persist();

        $this->postJson('/auth/jwt/logout.json');
        $this->assertResponseSuccess();

        $this->assertSame(0, $this->countActiveAuthenticationTokens($user->id));
        $this->assertSame($nToken, $this->countInactiveAuthenticationTokens($user->id));
    }

    public function testAuthJwtLogoutControllerWithPayload()
    {
        $userId = UserFactory::make()->user()->persist()->id;
        $this->createJwtTokenAndSetInHeader($userId);
        $nToken = 3;
        /** @var array $tokens */
        $tokens = AuthenticationTokenFactory::make($nToken)
            ->active()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->userId($userId)
            ->persist();

        $tokenToDeactivate = $tokens[0]->token;

        $payload = [RefreshTokenAbstractService::REFRESH_TOKEN_DATA_KEY => $tokenToDeactivate,];

        $this->postJson('/auth/jwt/logout.json', $payload);
        $this->assertResponseSuccess();
        $this->assertCookieNotSet(RefreshTokenRenewalService::REFRESH_TOKEN_COOKIE);

        $this->assertSame($nToken - 1, $this->countActiveAuthenticationTokens($userId));
        $this->assertSame(1, $this->countInactiveAuthenticationTokens($userId));
        $this->assertFalse($this->AuthenticationTokens->isValid($tokenToDeactivate, $userId));

        $this->getJson('/auth/is-authenticated.json');
        $this->assertResponseError();
    }

    public function testAuthJwtLogoutControllerWithCookie()
    {
        $userId = UserFactory::make()->user()->persist()->id;
        $this->createJwtTokenAndSetInHeader($userId);
        $nToken = 3;
        /** @var array $tokens */
        $tokens = AuthenticationTokenFactory::make($nToken)
            ->active()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->userId($userId)
            ->persist();

        $tokenToDeactivate = $tokens[0]->token;

        $this->cookie(
            RefreshTokenRenewalService::REFRESH_TOKEN_COOKIE,
            $tokenToDeactivate
        );

        $this->postJson('/auth/jwt/logout.json');
        $this->assertResponseSuccess();
        $this->assertCookieNotSet(RefreshTokenRenewalService::REFRESH_TOKEN_COOKIE);

        $this->assertSame($nToken - 1, $this->countActiveAuthenticationTokens($userId));
        $this->assertSame(1, $this->countInactiveAuthenticationTokens($userId));
        $this->assertFalse($this->AuthenticationTokens->isValid($tokenToDeactivate, $userId));

        $this->getJson('/auth/is-authenticated.json');
        $this->assertResponseError();
    }

    /**
     * @Given a user is logged in with JWT access token
     * @When the regular auth/logout endpoint is called with a valid access token in header
     * @Then the user is logged out
     * @And her access token is deactivated
     */
    public function testAuthJwtLogoutController_Logout_From_Session_Endpoint()
    {
        $userId = UserFactory::make()->user()->persist()->id;
        $this->createJwtTokenAndSetInHeader($userId);
        AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->userId($userId)
            ->persist();

        $bearerToken = $this->_request['headers'][JwtAuthenticationService::JWT_HEADER];

        $this->post('/auth/logout');
        $this->assertResponseError('The route /auth/logout is not permitted with JWT authentication.');

        $this->setJwtTokenInHeader($bearerToken);
        $this->getJson('/auth/is-authenticated.json');
        $this->assertResponseSuccess();

        $this->assertSame(1, $this->countActiveAuthenticationTokens($userId));
    }

    /**
     * @Given a user is login in session
     * @When the auth/jwt/logout.json is called
     * @Then the end point is not accessible and the user is not logged out from session
     */
    public function testAuthJwtLogoutController_LoggedIn_In_Session_And_Logout_From_JWT_Endpoint()
    {
        $this->logInAsUser();
        $this->postJson('/auth/jwt/logout.json');
        $this->assertResponseError('Authentication is required to continue');
    }

    private function countActiveAuthenticationTokens(string $userId): int
    {
        return AuthenticationTokenFactory::find()->where([
            'user_id' => $userId,
            'active' => true,
            'type' => AuthenticationToken::TYPE_REFRESH_TOKEN,
        ])->count();
    }

    private function countInactiveAuthenticationTokens(string $userId): int
    {
        return AuthenticationTokenFactory::find()->where([
            'user_id' => $userId,
            'active' => false,
            'type' => AuthenticationToken::TYPE_REFRESH_TOKEN,
        ])->count();
    }
}
