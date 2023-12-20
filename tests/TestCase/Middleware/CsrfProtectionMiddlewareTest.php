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
 * @since         4.2.0
 */

namespace App\Test\TestCase\Middleware;

use App\Middleware\CsrfProtectionMiddleware;
use App\Service\Cookie\AbstractSecureCookieService;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Http\TestRequestHandler;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;

/**
 * @covers \App\Middleware\CsrfProtectionMiddleware
 */
class CsrfProtectionMiddlewareTest extends AppIntegrationTestCase
{
    public function testCsrfProtectionMiddleware_SSL_And_Cookie_Secure_Activated()
    {
        $request = (new ServerRequest())->withEnv('HTTPS', 'on');
        $middleware = new CsrfProtectionMiddleware();

        /** @var \Cake\Http\ServerRequest $response */
        $response = $middleware->process($request, new TestRequestHandler());

        $csrfToken = $response->getCookieCollection()->get('csrfToken');
        $this->assertTrue($csrfToken->isSecure());
    }

    /**
     * @TODO v5 cookie should be secure if cookie secure is true
     * @see CsrfProtectionMiddleware::makeCsrfCookieSecureIfRequestIsSsl()
     */
    public function testCsrfProtectionMiddleware_Non_SSL_And_Cookie_Secure_Activated()
    {
        $request = new ServerRequest();
        $middleware = new CsrfProtectionMiddleware();

        /** @var \Cake\Http\ServerRequest $response */
        $response = $middleware->process($request, new TestRequestHandler());

        $csrfToken = $response->getCookieCollection()->get('csrfToken');
        $this->assertFalse($csrfToken->isSecure());
    }

    public function testCsrfProtectionMiddleware_SSL_And_Cookie_Secure_Deactivated()
    {
        Configure::write(AbstractSecureCookieService::PASSBOLT_SECURITY_COOKIES_SECURE_CONFIG, false);
        $request = (new ServerRequest())->withEnv('HTTPS', 'on');
        $middleware = new CsrfProtectionMiddleware();

        /** @var \Cake\Http\ServerRequest $response */
        $response = $middleware->process($request, new TestRequestHandler());

        $csrfToken = $response->getCookieCollection()->get('csrfToken');
        $this->assertTrue($csrfToken->isSecure());
    }

    public function testCsrfProtectionMiddleware_Non_SSL_And_Cookie_Secure_Deactivated()
    {
        Configure::write(AbstractSecureCookieService::PASSBOLT_SECURITY_COOKIES_SECURE_CONFIG, false);
        $request = new ServerRequest();
        $middleware = new CsrfProtectionMiddleware();

        /** @var \Cake\Http\ServerRequest $response */
        $response = $middleware->process($request, new TestRequestHandler());

        $csrfToken = $response->getCookieCollection()->get('csrfToken');
        $this->assertFalse($csrfToken->isSecure());
    }
}
