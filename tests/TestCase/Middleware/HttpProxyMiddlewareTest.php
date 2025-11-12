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

namespace App\Test\TestCase\Middleware;

use App\Middleware\HttpProxyMiddleware;
use App\Test\Lib\Http\TestRequestHandler;
use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\Http\ServerRequestFactory;
use Cake\TestSuite\TestCase;

/**
 * Test for HttpProxyMiddleware
 */
class HttpProxyMiddlewareTest extends TestCase
{
    /**
     * @var string
     */
    private $remoteAddr;

    /**
     * @var string
     */
    private $xRealIp;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->remoteAddr = env('REMOTE_ADDR');
        $this->xRealIp = env('HTTP_X_REAL_IP');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        putenv('REMOTE_ADDR=' . $this->remoteAddr);
        putenv('HTTP_X_REAL_IP=' . $this->xRealIp);
        parent::tearDown();
    }

    public function testHttpProxyMiddlewareTest_No_Proxy()
    {
        $realClientIP = '1.2.3.4';
        $request = ServerRequestFactory::fromGlobals(['REMOTE_ADDR' => $realClientIP]);
        // Mock response
        $response = new Response();
        $requestHandler = new TestRequestHandler(function ($request) use ($response) {
            return $response;
        });

        $middleware = new HttpProxyMiddleware();
        $middleware->process($request, $requestHandler);

        $this->assertEmpty($response->getHeader('Access-Control-Expose-Headers'));
        $this->assertEquals($request->clientIp(), $realClientIP);
    }

    public function testHttpProxyMiddlewareTest_With_Proxy_With_Security_Activated_Should_Set_Headers_White_List_In_Response()
    {
        $realClientIP = '2.3.4.5';
        $proxyIP = '1.2.3.4';
        Configure::write(HttpProxyMiddleware::PASSBOLT_SECURITY_PROXIES_ACTIVE_CONFIG_NAME, true);
        $request = ServerRequestFactory::fromGlobals([
            'REMOTE_ADDR' => $proxyIP,
            'HTTP_X_REAL_IP' => $realClientIP,
        ]);
        $response = new Response();
        $requestHandler = new TestRequestHandler(function ($request) use ($response) {
            return $response;
        });

        $middleware = new HttpProxyMiddleware();
        $response = $middleware->process($request, $requestHandler);

        $this->assertEquals(
            HttpProxyMiddleware::HTTP_HEADERS_WHITELIST,
            $response->getHeader(HttpProxyMiddleware::ACCESS_CONTROL_EXPOSE_HEADERS)
        );
        $this->assertEquals($request->clientIp(), $realClientIP);
        Configure::write(HttpProxyMiddleware::PASSBOLT_SECURITY_PROXIES_ACTIVE_CONFIG_NAME, false);
    }

    public function testHttpProxyMiddlewareTest_With_Proxy_With_Security_Deactivated_Should_Not_Set_Headers_White_List_In_Response()
    {
        $realClientIP = '2.3.4.5';
        $proxyIP = '1.2.3.4';
        Configure::write(HttpProxyMiddleware::PASSBOLT_SECURITY_PROXIES_ACTIVE_CONFIG_NAME, false);
        $request = ServerRequestFactory::fromGlobals([
            'REMOTE_ADDR' => $proxyIP,
            'HTTP_X_REAL_IP' => $realClientIP,
        ]);

        $response = new Response();
        $requestHandler = new TestRequestHandler(function ($request) use ($response) {
            return $response;
        });

        $middleware = new HttpProxyMiddleware();
        $response = $middleware->process($request, $requestHandler);

        $this->assertEmpty(
            $response->getHeader(HttpProxyMiddleware::ACCESS_CONTROL_EXPOSE_HEADERS)
        );
        $this->assertEquals($request->clientIp(), $proxyIP);
    }

    public static function trustedProxiesConfigurationProvider(): array
    {
        return [
            [
                [
                    'HTTP_X_FORWARDED_FOR' => '192.168.1.0, 192.168.1.2, 192.168.1.3',
                    'HTTP_X_REAL_IP' => '192.168.1.1',
                    'HTTP_CLIENT_IP' => '192.168.1.2',
                    'REMOTE_ADDR' => '192.168.1.4',
                ], //request params
                '192.168.1.2, 192.168.1.3', //trusted proxies configuration
                '192.168.1.0', //real client IP (expected result)
            ],
            [
                [
                    'HTTP_X_FORWARDED_FOR' => '192.168.1.0, 192.168.1.2, 192.168.1.3',
                    'HTTP_X_REAL_IP' => '192.168.1.1',
                    'HTTP_CLIENT_IP' => '192.168.1.2',
                    'REMOTE_ADDR' => '192.168.1.4',
                ],
                [
                    '192.168.1.2',
                    '192.168.1.3',
                ],
                '192.168.1.0',
            ],
            [
                [
                    'HTTP_X_FORWARDED_FOR' => '192.168.1.0, 192.168.1.2, 192.168.1.3',
                    'HTTP_X_REAL_IP' => '192.168.1.1',
                    'HTTP_CLIENT_IP' => '192.168.1.2',
                    'REMOTE_ADDR' => '192.168.1.4',
                ],
                [
                    '192.168.1.0',
                    '192.168.1.2',
                    '192.168.1.3',
                ],
                '192.168.1.3',
            ],
            [
                [
                    'HTTP_X_FORWARDED_FOR' => '',
                    'HTTP_X_REAL_IP' => '192.168.1.1',
                    'HTTP_CLIENT_IP' => '192.168.1.2',
                    'REMOTE_ADDR' => '192.168.1.4',
                ],
                [
                    '192.168.1.0',
                    '192.168.1.1',
                    '192.168.1.2',
                    '192.168.1.3',
                ],
                '192.168.1.1',
            ],
        ];
    }

    /**
     * @dataProvider trustedProxiesConfigurationProvider
     * @param array $serverRequestParams Parameters to pass to server request object.
     * @param mixed $trustedProxiesConfiguration Trusted proxies configuration to set.
     * @param string $realClientIP Expected result IP.
     * @return void
     */
    public function testHttpProxyMiddleware_MultipleTrustedProxiesConfigurationSet(array $serverRequestParams, mixed $trustedProxiesConfiguration, string $realClientIP): void
    {
        Configure::write(HttpProxyMiddleware::PASSBOLT_SECURITY_PROXIES_ACTIVE_CONFIG_NAME, true);
        Configure::write(HttpProxyMiddleware::PASSBOLT_SECURITY_PROXIES_TRUSTED_PROXIES_CONFIG_NAME, $trustedProxiesConfiguration);

        $request = ServerRequestFactory::fromGlobals($serverRequestParams);
        $response = (new HttpProxyMiddleware())->process($request, new TestRequestHandler());

        $this->assertEquals(
            HttpProxyMiddleware::HTTP_HEADERS_WHITELIST,
            $response->getHeader(HttpProxyMiddleware::ACCESS_CONTROL_EXPOSE_HEADERS)
        );
        $this->assertSame($realClientIP, $request->clientIp());
    }

    public static function invalidTrustedProxiesConfigurationProvider(): array
    {
        return [
            ['new \stdClass()'],
            ['🔥🔥🔥'],
            ['foo-bar,bar-baz'],
            ['\';'],
            [['🔥', '😎']],
        ];
    }

    /**
     * @dataProvider invalidTrustedProxiesConfigurationProvider
     * @param mixed $value Configuration value to test.
     * @return void
     */
    public function testHttpProxyMiddleware_InvalidMultipleTrustedProxiesConfiguration(mixed $value): void
    {
        Configure::write(HttpProxyMiddleware::PASSBOLT_SECURITY_PROXIES_ACTIVE_CONFIG_NAME, true);
        Configure::write(HttpProxyMiddleware::PASSBOLT_SECURITY_PROXIES_TRUSTED_PROXIES_CONFIG_NAME, $value);

        $request = ServerRequestFactory::fromGlobals([
            'HTTP_X_FORWARDED_FOR' => '192.168.1.0, 192.168.1.2, 192.168.1.3',
            'HTTP_X_REAL_IP' => '192.168.1.1',
            'HTTP_CLIENT_IP' => '192.168.1.2',
            'REMOTE_ADDR' => '192.168.1.4',
        ]);
        $response = (new HttpProxyMiddleware())->process($request, new TestRequestHandler());

        $this->assertEquals(
            HttpProxyMiddleware::HTTP_HEADERS_WHITELIST,
            $response->getHeader(HttpProxyMiddleware::ACCESS_CONTROL_EXPOSE_HEADERS)
        );
        $this->assertSame('192.168.1.3', $request->clientIp());
    }

    public function testHttpProxyMiddleware_TrustedProxiesConfigurationDisabled(): void
    {
        $request = ServerRequestFactory::fromGlobals([
            'HTTP_X_FORWARDED_FOR' => '192.168.1.0, 192.168.1.2, 192.168.1.3',
            'HTTP_X_REAL_IP' => '192.168.1.1',
            'HTTP_CLIENT_IP' => '192.168.1.2',
            'REMOTE_ADDR' => '192.168.1.4',
        ]);

        (new HttpProxyMiddleware())->process($request, new TestRequestHandler());

        $this->assertSame('192.168.1.4', $request->clientIp());
    }
}
