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
 * @since         4.11.1
 */

namespace App\Test\TestCase\Middleware;

use App\Middleware\AssertFullBaseUrlMiddleware;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Http\TestRequestHandler;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use stdClass;

/**
 * @covers \App\Middleware\AssertFullBaseUrlMiddleware
 */
class AssertFullBaseUrlMiddlewareTest extends AppIntegrationTestCase
{
    /**
     * @dataProvider invalidFullBaseUrlValuesProvider
     * @param mixed $invalidFullBaseUrl Invalid values.
     * @return void
     */
    public function testAssertFullBaseUrlMiddleware_DisallowInvalidFullBaseUrl_FlagIsTrue($invalidFullBaseUrl): void
    {
        Configure::write('passbolt.originalFullBaseUrl', $invalidFullBaseUrl);
        Configure::write('passbolt.security.fullBaseUrlEnforce', true);

        $this->expectException(InternalErrorException::class);

        $middleware = new AssertFullBaseUrlMiddleware();
        $middleware->process((new ServerRequest()), new TestRequestHandler());
    }

    public static function invalidFullBaseUrlValuesProvider(): array
    {
        return [
            [false],
            [true],
            [null],
            [''],
            [[]],
            [new stdClass()],
        ];
    }

    public function testAssertFullBaseUrlMiddleware_ValidFullBaseUrl_FlagIsTrue(): void
    {
        Configure::write('passbolt.originalFullBaseUrl', 'https://passbolt.test');
        Configure::write('passbolt.security.fullBaseUrlEnforce', true);

        $middleware = new AssertFullBaseUrlMiddleware();
        $response = $middleware->process((new ServerRequest()), new TestRequestHandler());

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testAssertFullBaseUrlMiddleware_AllowInvalidFullBaseUrl_FlagIsFalse(): void
    {
        Configure::write('passbolt.originalFullBaseUrl', false);
        Configure::write('passbolt.security.fullBaseUrlEnforce', false);

        $middleware = new AssertFullBaseUrlMiddleware();
        $response = $middleware->process(new ServerRequest(), new TestRequestHandler());

        $this->assertInstanceOf(Response::class, $response);
    }
}
