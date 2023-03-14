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
 * @since         3.10.0
 */
namespace App\Test\TestCase;

use App\Application;
use App\Middleware\ApiVersionMiddleware;
use App\Middleware\ContainerInjectorMiddleware;
use App\Middleware\ContentSecurityPolicyMiddleware;
use App\Middleware\CsrfProtectionMiddleware;
use App\Middleware\GpgAuthHeadersMiddleware;
use App\Middleware\HttpProxyMiddleware;
use App\Middleware\SessionAuthPreventDeletedUsersMiddleware;
use App\Middleware\SessionPreventExtensionMiddleware;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\TestSuite\TestCase;

/**
 * ApplicationTest class
 */
class ApplicationTest extends TestCase
{
    /**
     * @return void
     */
    public function testApplication_Middleware()
    {
        $app = new Application('');
        $middleware = new MiddlewareQueue();

        $middleware = $app->middleware($middleware);

        $middlewareClassesInOrder = [
            ContainerInjectorMiddleware::class,
            ContentSecurityPolicyMiddleware::class,
            ErrorHandlerMiddleware::class,
            AssetMiddleware::class,
            RoutingMiddleware::class,
            ApiVersionMiddleware::class,
            SessionPreventExtensionMiddleware::class,
            BodyParserMiddleware::class,
            SessionAuthPreventDeletedUsersMiddleware::class,
            AuthenticationMiddleware::class,
            GpgAuthHeadersMiddleware::class,
            CsrfProtectionMiddleware::class,
            HttpProxyMiddleware::class,
        ];

        foreach ($middlewareClassesInOrder as $midClass) {
            $this->assertInstanceOf($midClass, $middleware->current());
            $middleware->next();
        }
    }
}
