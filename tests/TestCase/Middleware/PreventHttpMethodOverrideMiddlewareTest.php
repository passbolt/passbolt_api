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
 * @since         5.18.0
 */

namespace App\Test\TestCase\Middleware;

use App\Middleware\PreventHttpMethodOverrideMiddleware;
use Cake\Core\Configure;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\Http\ServerRequestFactory;
use Cake\TestSuite\TestCase;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @covers \App\Middleware\PreventHttpMethodOverrideMiddleware
 */
class PreventHttpMethodOverrideMiddlewareTest extends TestCase
{
    public function testPreventHttpMethodOverrideMiddleware_Success_NoOverride(): void
    {
        $request = ServerRequestFactory::fromGlobals([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/groups/foo.json',
        ]);

        $this->assertRequestPassesThrough($request);
    }

    public function testPreventHttpMethodOverrideMiddleware_Success_BodyMethodEqualsOriginalMethod(): void
    {
        // Same shape as a form rendered by FormHelper (MFA verify, WebInstaller), which always adds _method=POST
        $request = ServerRequestFactory::fromGlobals(
            ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/mfa/verify/totp'],
            [],
            ['_method' => 'POST', 'totp' => '123456'],
        );

        $this->assertRequestPassesThrough($request);
    }

    public function testPreventHttpMethodOverrideMiddleware_Success_BodyMethodEqualsOriginalMethodCaseInsensitive(): void
    {
        $request = ServerRequestFactory::fromGlobals(
            ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/mfa/verify/totp'],
            [],
            ['_method' => 'post'],
        );

        $this->assertRequestPassesThrough($request);
    }

    public function testPreventHttpMethodOverrideMiddleware_Success_RequestNotBuiltByFactory(): void
    {
        $request = new ServerRequest(['environment' => ['REQUEST_METHOD' => 'GET']]);

        $this->assertRequestPassesThrough($request);
    }

    public function testPreventHttpMethodOverrideMiddleware_Error_BodyOverride(): void
    {
        $request = ServerRequestFactory::fromGlobals(
            ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/groups/foo.json'],
            [],
            ['_method' => 'DELETE'],
        );

        $this->assertRequestIsRejected($request);
    }

    public function testPreventHttpMethodOverrideMiddleware_Error_HeaderOverride(): void
    {
        $request = ServerRequestFactory::fromGlobals([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/settings.json',
            'HTTP_X_HTTP_METHOD_OVERRIDE' => 'GET',
        ]);

        $this->assertRequestIsRejected($request);
    }

    public function testPreventHttpMethodOverrideMiddleware_Error_HeaderOverrideOnGetRequest(): void
    {
        $request = ServerRequestFactory::fromGlobals([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/groups/foo.json',
            'HTTP_X_HTTP_METHOD_OVERRIDE' => 'DELETE',
        ]);

        $this->assertRequestIsRejected($request);
    }

    public function testPreventHttpMethodOverrideMiddleware_Success_OverrideAllowedWhenPreventionDisabledInConfig(): void
    {
        Configure::write(PreventHttpMethodOverrideMiddleware::PASSBOLT_SECURITY_PREVENT_HTTP_METHOD_OVERRIDE, false);
        $request = ServerRequestFactory::fromGlobals(
            ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/groups/foo.json'],
            [],
            ['_method' => 'DELETE'],
        );

        $this->assertRequestPassesThrough($request);
    }

    public function testPreventHttpMethodOverrideMiddleware_Error_OverrideRejectedWhenConfigIsMissing(): void
    {
        Configure::delete(PreventHttpMethodOverrideMiddleware::PASSBOLT_SECURITY_PREVENT_HTTP_METHOD_OVERRIDE);
        $request = ServerRequestFactory::fromGlobals(
            ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/groups/foo.json'],
            [],
            ['_method' => 'DELETE'],
        );

        $this->assertRequestIsRejected($request);
    }

    public function testPreventHttpMethodOverrideMiddleware_Error_OverrideRejectedWhenConfigIsNotABoolean(): void
    {
        Configure::write(PreventHttpMethodOverrideMiddleware::PASSBOLT_SECURITY_PREVENT_HTTP_METHOD_OVERRIDE, 'foo-bar');
        $request = ServerRequestFactory::fromGlobals(
            ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/groups/foo.json'],
            [],
            ['_method' => 'DELETE'],
        );

        $this->assertRequestIsRejected($request);
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    private function assertRequestPassesThrough(ServerRequest $request): void
    {
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())->method('handle')->willReturn(new Response());

        (new PreventHttpMethodOverrideMiddleware())->process($request, $handler);
    }

    private function assertRequestIsRejected(ServerRequest $request): void
    {
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->never())->method('handle');

        $this->expectException(MethodNotAllowedException::class);
        $this->expectExceptionMessage('HTTP method override is not allowed.');

        (new PreventHttpMethodOverrideMiddleware())->process($request, $handler);
    }
}
