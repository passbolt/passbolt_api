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
 * @since         5.7.0
 */

namespace App\Test\TestCase\Middleware;

use App\Middleware\ContentSecurityPolicyMiddleware;
use App\Test\Lib\Http\TestRequestHandler;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;

/**
 * @covers \App\Middleware\ContentSecurityPolicyMiddleware
 */
class ContentSecurityPolicyMiddlewareTest extends TestCase
{
    public function testContentSecurityPolicyMiddleware_Default_CSP()
    {
        $middleware = new ContentSecurityPolicyMiddleware();

        /** @var \Cake\Http\ServerRequest $response */
        $response = $middleware->process(new ServerRequest(), new TestRequestHandler());

        $cspHeaders = $response->getHeader('Content-Security-Policy');
        $expectedHeaders = [
            "default-src 'self'",
            "script-src 'self'",
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self'",
            "frame-src 'self' https://*.duosecurity.com",
            "frame-ancestors 'none'",
            "form-action 'self' https://*.duosecurity.com",
        ];
        $this->assertSame($expectedHeaders, $cspHeaders);
    }

    public function testContentSecurityPolicyMiddleware_Config_Overwrite()
    {
        $csp = 'foo';
        Configure::write('passbolt.security.csp', $csp);
        $middleware = new ContentSecurityPolicyMiddleware();

        /** @var \Cake\Http\ServerRequest $response */
        $response = $middleware->process(new ServerRequest(), new TestRequestHandler());

        $cspHeaders = $response->getHeader('Content-Security-Policy');
        $this->assertSame([$csp], $cspHeaders);
    }
}
