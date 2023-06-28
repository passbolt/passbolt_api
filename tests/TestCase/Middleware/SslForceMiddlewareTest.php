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
 * @since         4.1.0
 */

namespace App\Test\TestCase\Middleware;

use App\Middleware\SslForceMiddleware;
use App\Test\Lib\Utility\MiddlewareTestTrait;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use Laminas\Diactoros\Uri;

/**
 * @covers \App\Middleware\SslForceMiddleware
 */
class SslForceMiddlewareTest extends TestCase
{
    use MiddlewareTestTrait;

    public function testSslForceMiddleware_HTTP_With_SSL_Force_should_redirect_to_https()
    {
        Configure::write(SslForceMiddleware::PASSBOLT_SSL_FORCE_CONFIG_NAME, true);
        $request = new ServerRequest();
        $uri = new Uri('http://passbolt.test');

        $request = $request->withUri($uri);
        $middleware = new SslForceMiddleware();
        $response = $middleware->process($request, $this->mockHandler());

        $this->assertSame(['https://passbolt.test'], $response->getHeader('Location'));
        $this->assertSame(302, $response->getStatusCode());
    }

    public function testSslForceMiddleware_HTTP_Without_SSL_Force_should_not_redirect_to_https()
    {
        Configure::write(SslForceMiddleware::PASSBOLT_SSL_FORCE_CONFIG_NAME, false);
        $request = new ServerRequest();
        $uri = new Uri('http://passbolt.test');

        $request = $request->withUri($uri);
        $middleware = new SslForceMiddleware();
        $response = $middleware->process($request, $this->mockHandler());

        $this->assertFalse($response->hasHeader('Location'));
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testSslForceMiddleware_HTTPS_With_SSL_Force_should_add_strict_transport_security()
    {
        Configure::write(SslForceMiddleware::PASSBOLT_SSL_FORCE_CONFIG_NAME, true);
        $request = new ServerRequest();
        $uri = new Uri('https://passbolt.test');

        $request = $request->withUri($uri);
        $middleware = new SslForceMiddleware();
        $response = $middleware->process($request, $this->mockHandler());

        $this->assertSame(['max-age=31536000; includeSubDomains'], $response->getHeader('strict-transport-security'));
    }
}
