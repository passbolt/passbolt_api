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
 * @since         4.11.0
 */

namespace App\Test\TestCase\Middleware;

use App\Middleware\PreventHostHeaderFallbackMiddleware;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Http\TestRequestHandler;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Response;
use Cake\Http\ServerRequest;

/**
 * @covers \App\Middleware\PreventHostHeaderFallbackMiddleware
 */
class PreventHostHeaderFallbackMiddlewareTest extends AppIntegrationTestCase
{
    /**
     * @dataProvider preventHostHeaderFallbackFullBaseUrlValuesProvider
     * @return void
     */
    public function testPreventHostHeaderFallbackMiddleware_PreventHostHeaderFallback($invalidFullBaseUrl): void
    {
        Configure::write('App.fullBaseUrl', $invalidFullBaseUrl);
        Configure::write('passbolt.security.preventHostHeaderFallback', true);
        $request = (new ServerRequest())->withHeader('Host', 'evil.com');

        $this->expectException(BadRequestException::class);

        $middleware = new PreventHostHeaderFallbackMiddleware();
        $middleware->process($request, new TestRequestHandler());
    }

    public function preventHostHeaderFallbackFullBaseUrlValuesProvider(): array
    {
        return [
            [false],
            [true],
            [null],
            [''],
            [[]],
        ];
    }

    public function testPreventHostHeaderFallbackMiddleware_PreventHostHeaderFallback_NoExceptionIfFullBaseUrlSet(): void
    {
        Configure::write('App.fullBaseUrl', 'https://passbolt.test');
        Configure::write('passbolt.security.preventHostHeaderFallback', true);
        $request = (new ServerRequest())->withHeader('Host', 'evil.com');

        $middleware = new PreventHostHeaderFallbackMiddleware();
        /** @var \Cake\Http\ServerRequest $response */
        $response = $middleware->process($request, new TestRequestHandler());

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testPreventHostHeaderFallbackMiddleware_PreventHostHeaderFallback_NoExceptionIfConfigIsFalse(): void
    {
        Configure::write('App.fullBaseUrl', false);
        Configure::write('passbolt.security.preventHostHeaderFallback', false);
        $request = (new ServerRequest())->withHeader('Host', 'evil.com');

        $middleware = new PreventHostHeaderFallbackMiddleware();
        /** @var \Cake\Http\ServerRequest $response */
        $response = $middleware->process($request, new TestRequestHandler());

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testPreventHostHeaderFallbackMiddleware_PreventHostHeaderFallback_NoExceptionIfHostHeaderNotSet(): void
    {
        Configure::write('App.fullBaseUrl', false);
        Configure::write('passbolt.security.preventHostHeaderFallback', true);
        $request = new ServerRequest();

        $middleware = new PreventHostHeaderFallbackMiddleware();
        /** @var \Cake\Http\ServerRequest $response */
        $response = $middleware->process($request, new TestRequestHandler());

        $this->assertInstanceOf(Response::class, $response);
    }
}
