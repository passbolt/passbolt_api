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
 * @since         3.4.0
 */
namespace Passbolt\JwtAuthentication\Test\TestCase\Authenticator;

use App\Authenticator\SessionIdentificationService;
use App\Authenticator\SessionIdentificationServiceInterface;
use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Authentication\AuthenticationService;
use Authentication\Authenticator\Result;
use Authentication\Identifier\IdentifierInterface;
use Cake\Core\Container;
use Cake\Http\Cookie\Cookie;
use Cake\Http\Cookie\CookieCollection;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\JwtAuthentication\Authenticator\JwtRefreshTokenAuthenticator;
use Passbolt\JwtAuthentication\Error\Exception\RefreshToken\ConsumedRefreshTokenAccessException;
use Passbolt\JwtAuthentication\Error\Exception\RefreshToken\ExpiredRefreshTokenAccessException;
use Passbolt\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenAbstractService;
use Passbolt\JwtAuthentication\Test\Factory\RefreshTokenAuthenticationTokenFactory;

class JwtRefreshTokenAuthenticatorTest extends TestCase
{
    use TruncateDirtyTables;

    /**
     * @var JwtRefreshTokenAuthenticator
     */
    public $authenticator;

    public function setUp(): void
    {
        parent::setUp();
        $this->authenticator = new JwtRefreshTokenAuthenticator(
            $this->createMock(IdentifierInterface::class)
        );
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->authenticator);
    }

    public function testJwtRefreshTokenAuthenticator_Success_With_Cookie()
    {
        $refreshToken = RefreshTokenAuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user())
            ->active()
            ->persist();

        $cookies = (new CookieCollection())->add(
            new Cookie(RefreshTokenAbstractService::REFRESH_TOKEN_COOKIE, $refreshToken->token)
        );
        $container = new Container();
        $container->add(SessionIdentificationServiceInterface::class, SessionIdentificationService::class);

        $request = (new ServerRequest())
            ->withCookieCollection($cookies)
            ->withAttribute('container', $container);

        $result = $this->authenticator->authenticate($request);

        $this->assertSuccess($request, $container, $refreshToken, $result);
    }

    public function testJwtRefreshTokenAuthenticator_Success_With_Payload()
    {
        $refreshToken = RefreshTokenAuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user())
            ->active()
            ->persist();

        $container = new Container();
        $container->add(SessionIdentificationServiceInterface::class, SessionIdentificationService::class);

        $request = (new ServerRequest())
            ->withData(RefreshTokenAbstractService::REFRESH_TOKEN_DATA_KEY, $refreshToken->token)
            ->withData('user_id', $refreshToken->user_id)
            ->withAttribute('container', $container);

        $result = $this->authenticator->authenticate($request);

        $this->assertSuccess($request, $container, $refreshToken, $result);
    }

    public function testJwtRefreshTokenAuthenticator_Empty_Request_Should_Return_Bad_Request()
    {
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The refresh token should be a valid UUID');
        $this->authenticator->authenticate(new ServerRequest());
    }

    public function testJwtRefreshTokenAuthenticator_Empty_Token_Should_Return_Bad_Request()
    {
        $request = (new ServerRequest())->withData(RefreshTokenAbstractService::REFRESH_TOKEN_DATA_KEY, 'Blah');
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The refresh token should be a valid UUID');
        $this->authenticator->authenticate($request);
    }

    public function testJwtRefreshTokenAuthenticator_Payload_With_Token_Only_Should_Return_Bad_Request()
    {
        $request = (new ServerRequest())->withData(RefreshTokenAbstractService::REFRESH_TOKEN_DATA_KEY, UuidFactory::uuid());
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The user ID should be a valid UUID.');
        $this->authenticator->authenticate($request);
    }

    public function testJwtRefreshTokenAuthenticator_Payload_With_UserID_Only_Should_Return_Bad_Request()
    {
        $request = (new ServerRequest())->withData('user_id', UuidFactory::uuid());
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The refresh token should be a valid UUID.');
        $this->authenticator->authenticate($request);
    }

    public function testJwtRefreshTokenAuthenticator_Non_Existing_Token_Should_Return_Security_Exception()
    {
        $request = (new ServerRequest())
            ->withData(RefreshTokenAbstractService::REFRESH_TOKEN_DATA_KEY, UuidFactory::uuid())
            ->withData('user_id', UuidFactory::uuid());
        $this->expectException(RefreshTokenNotFoundException::class);
        $this->expectExceptionMessage('No active refresh token matching the request could be found.');
        $this->authenticator->authenticate($request);
    }

    public function testJwtRefreshTokenAuthenticator_Non_Existing_Token_Should_Return_Security_Exception_Cookie_Variant()
    {
        $cookies = (new CookieCollection())->add(
            new Cookie(RefreshTokenAbstractService::REFRESH_TOKEN_COOKIE, UuidFactory::uuid())
        );
        $request = (new ServerRequest())->withCookieCollection($cookies);
        $this->expectException(RefreshTokenNotFoundException::class);
        $this->expectExceptionMessage('No active refresh token matching the request could be found.');
        $this->authenticator->authenticate($request);
    }

    public function testJwtRefreshTokenAuthenticator_Non_Active_Token_Should_Return_Security_Exception()
    {
        $token = RefreshTokenAuthenticationTokenFactory::make()->with('Users')->inactive()->persist();
        $request = (new ServerRequest())
            ->withData(RefreshTokenAbstractService::REFRESH_TOKEN_DATA_KEY, $token->token)
            ->withData('user_id', $token->user_id);
        $this->expectException(ConsumedRefreshTokenAccessException::class);
        $this->expectExceptionMessage('The refresh token provided was already used.');
        $this->authenticator->authenticate($request);
    }

    public function testJwtRefreshTokenAuthenticator_Non_Active_Token_Should_Return_Security_Exception_Cookie_Variant()
    {
        $token = RefreshTokenAuthenticationTokenFactory::make()->inactive()->persist();
        $cookies = (new CookieCollection())->add(
            new Cookie(RefreshTokenAbstractService::REFRESH_TOKEN_COOKIE, $token->token)
        );
        $request = (new ServerRequest())->withCookieCollection($cookies);
        $this->expectException(ConsumedRefreshTokenAccessException::class);
        $this->expectExceptionMessage('The refresh token provided was already used.');
        $this->authenticator->authenticate($request);
    }

    public function testJwtRefreshTokenAuthenticator_Expired_Token_Should_Return_Security_Exception()
    {
        $token = RefreshTokenAuthenticationTokenFactory::make()->with('Users')->active()->expired()->persist();
        $request = (new ServerRequest())
            ->withData(RefreshTokenAbstractService::REFRESH_TOKEN_DATA_KEY, $token->token)
            ->withData('user_id', $token->user_id);
        $this->expectException(ExpiredRefreshTokenAccessException::class);
        $this->expectExceptionMessage('Expired refresh token provided.');
        $this->authenticator->authenticate($request);
    }

    public function testJwtRefreshTokenAuthenticator_Expired_Token_Should_Return_Security_Exception_Cookie_Variant()
    {
        $token = RefreshTokenAuthenticationTokenFactory::make()->active()->expired()->persist();
        $cookies = (new CookieCollection())->add(
            new Cookie(RefreshTokenAbstractService::REFRESH_TOKEN_COOKIE, $token->token)
        );
        $request = (new ServerRequest())->withCookieCollection($cookies);
        $this->expectException(ExpiredRefreshTokenAccessException::class);
        $this->expectExceptionMessage('Expired refresh token provided.');
        $this->authenticator->authenticate($request);
    }

    public function testJwtRefreshTokenAuthenticator_User_Id_Refresh_Token_Mismatch_Should_Return_Security_Exception()
    {
        $token = RefreshTokenAuthenticationTokenFactory::make()->active()->expired()->persist();
        $user = UserFactory::make()->persist();
        $request = (new ServerRequest())
            ->withData(RefreshTokenAbstractService::REFRESH_TOKEN_DATA_KEY, $token->token)
            ->withData('user_id', $user->id);
        $this->expectException(RefreshTokenNotFoundException::class);
        $this->expectExceptionMessage('No active refresh token matching the request could be found.');
        $this->authenticator->authenticate($request);
    }

    public function testJwtRefreshTokenAuthenticator_User_Deleted_Should_Return_No_Security_Exception()
    {
        $token = RefreshTokenAuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->deleted())
            ->persist();
        $request = (new ServerRequest())
            ->withData(RefreshTokenAbstractService::REFRESH_TOKEN_DATA_KEY, $token->token)
            ->withData('user_id', $token->user_id);
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The user is deleted.');
        $this->authenticator->authenticate($request);
    }

    public function testJwtRefreshTokenAuthenticator_User_Deactivated_Should_Return_No_Security_Exception()
    {
        $token = RefreshTokenAuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->inactive())
            ->persist();
        $request = (new ServerRequest())
            ->withData(RefreshTokenAbstractService::REFRESH_TOKEN_DATA_KEY, $token->token)
            ->withData('user_id', $token->user_id);
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The user is not activated or disabled.');
        $this->authenticator->authenticate($request);
    }

    private function assertSuccess(
        ServerRequest $request,
        Container $container,
        AuthenticationToken $refreshToken,
        Result $result
    ): void {
        $user = $refreshToken->user;
        $this->assertTrue($result->isValid());

        /** @var \Passbolt\JwtAuthentication\Authenticator\RefreshTokenSessionIdentificationService $sessionIdentification */
        $sessionIdentification = $container->get(SessionIdentificationServiceInterface::class);
        $successfulAuthentication = $this->createMock(AuthenticationService::class);
        $successfulAuthentication->method('getResult')->willReturn($result);
        $request = $request->withAttribute('authentication', $successfulAuthentication);
        $this->assertSame($refreshToken->id, $sessionIdentification->getSessionIdentifier($request)->id);
        $this->assertSame($refreshToken->token, $sessionIdentification->getSessionIdentifier($request)->token);
        $this->assertSame($refreshToken->user_id, $sessionIdentification->getSessionIdentifier($request)->user_id);

        $identifiedUser = $result->getData()['user'];
        $this->assertSame($user->id, $identifiedUser['id']);
        $this->assertSame($user->role->name, $identifiedUser['role']['name']);
    }
}
