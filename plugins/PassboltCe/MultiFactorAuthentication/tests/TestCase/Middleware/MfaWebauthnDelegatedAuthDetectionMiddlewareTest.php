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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Middleware;

use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Cake\Core\Container;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use Passbolt\MultiFactorAuthentication\Authenticator\MfaWebauthnDelegatedAuthenticationService;
use Passbolt\MultiFactorAuthentication\Middleware\MfaWebauthnDelegatedAuthDetectionMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Middleware\MfaWebauthnDelegatedAuthDetectionMiddleware
 */
class MfaWebauthnDelegatedAuthDetectionMiddlewareTest extends TestCase
{
    private MfaWebauthnDelegatedAuthDetectionMiddleware $middleware;
    private Container $container;

    public function setUp(): void
    {
        parent::setUp();
        $this->middleware = new MfaWebauthnDelegatedAuthDetectionMiddleware();
        $this->container = new Container();
        $this->container->add(AuthenticationServiceInterface::class, AuthenticationService::class);
    }

    public function tearDown(): void
    {
        unset($this->middleware, $this->container);
        parent::tearDown();
    }

    private function requestForPath(string $path): ServerRequestInterface
    {
        return (new ServerRequest(['url' => $path]))
            ->withAttribute('container', $this->container);
    }

    private function passThroughHandler(): RequestHandlerInterface
    {
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn(new Response());

        return $handler;
    }

    public function testMfaWebauthnDelegatedAuthDetectionMiddleware_SwapsAuthServiceOnDelegatedGetRoute(): void
    {
        $request = $this->requestForPath('/mfa/verify/webauthn/delegated');

        $this->middleware->process($request, $this->passThroughHandler());

        $this->assertInstanceOf(
            MfaWebauthnDelegatedAuthenticationService::class,
            $this->container->get(AuthenticationServiceInterface::class),
        );
    }

    public function testMfaWebauthnDelegatedAuthDetectionMiddleware_SwapsAuthServiceOnDelegatedBeginRoute(): void
    {
        $request = $this->requestForPath('/mfa/verify/webauthn/delegated/begin');

        $this->middleware->process($request, $this->passThroughHandler());

        $this->assertInstanceOf(
            MfaWebauthnDelegatedAuthenticationService::class,
            $this->container->get(AuthenticationServiceInterface::class),
        );
    }

    public function testMfaWebauthnDelegatedAuthDetectionMiddleware_SwapsAuthServiceOnDelegatedFinishRoute(): void
    {
        $request = $this->requestForPath('/mfa/verify/webauthn/delegated/finish');

        $this->middleware->process($request, $this->passThroughHandler());

        $this->assertInstanceOf(
            MfaWebauthnDelegatedAuthenticationService::class,
            $this->container->get(AuthenticationServiceInterface::class),
        );
    }

    public function testMfaWebauthnDelegatedAuthDetectionMiddleware_StripsJsonExtensionBeforeMatching(): void
    {
        $request = $this->requestForPath('/mfa/verify/webauthn/delegated/begin.json');

        $this->middleware->process($request, $this->passThroughHandler());

        $this->assertInstanceOf(
            MfaWebauthnDelegatedAuthenticationService::class,
            $this->container->get(AuthenticationServiceInterface::class),
        );
    }

    public function testMfaWebauthnDelegatedAuthDetectionMiddleware_DoesNotSwapOnPromptRoute(): void
    {
        $request = $this->requestForPath('/mfa/verify/webauthn/delegated/prompt');

        $this->middleware->process($request, $this->passThroughHandler());

        $this->assertNotInstanceOf(
            MfaWebauthnDelegatedAuthenticationService::class,
            $this->container->get(AuthenticationServiceInterface::class),
        );
    }

    public function testMfaWebauthnDelegatedAuthDetectionMiddleware_DoesNotSwapOnCallbackRoute(): void
    {
        $request = $this->requestForPath('/mfa/verify/webauthn/delegated/callback');

        $this->middleware->process($request, $this->passThroughHandler());

        $this->assertNotInstanceOf(
            MfaWebauthnDelegatedAuthenticationService::class,
            $this->container->get(AuthenticationServiceInterface::class),
        );
    }

    public function testMfaWebauthnDelegatedAuthDetectionMiddleware_DoesNotSwapOnUnrelatedRoute(): void
    {
        $request = $this->requestForPath('/auth/login');

        $this->middleware->process($request, $this->passThroughHandler());

        $this->assertNotInstanceOf(
            MfaWebauthnDelegatedAuthenticationService::class,
            $this->container->get(AuthenticationServiceInterface::class),
        );
    }

    public function testMfaWebauthnDelegatedAuthDetectionMiddleware_ReturnsHandlerResponse(): void
    {
        $expected = new Response();
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($expected);
        $request = $this->requestForPath('/mfa/verify/webauthn/delegated/begin');

        $actual = $this->middleware->process($request, $handler);

        $this->assertSame($expected, $actual);
    }

    public function testMfaWebauthnDelegatedAuthDetectionMiddleware_PassesRequestToHandler(): void
    {
        $request = $this->requestForPath('/mfa/verify/webauthn/delegated/begin');
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())
            ->method('handle')
            ->with($this->identicalTo($request))
            ->willReturn(new Response());

        $this->middleware->process($request, $handler);
    }

    public function testMfaWebauthnDelegatedAuthDetectionMiddleware_DoesNotConsumeContainerOnNonDelegatedRoute(): void
    {
        $request = new ServerRequest(['url' => '/some/other/route']);

        $response = $this->middleware->process($request, $this->passThroughHandler());

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
