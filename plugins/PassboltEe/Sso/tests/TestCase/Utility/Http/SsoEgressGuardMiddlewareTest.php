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
 * @since         5.15.0
 */

namespace Passbolt\Sso\Test\TestCase\Utility\Http;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Passbolt\Sso\Error\Exception\SsoEgressBlockedException;
use Passbolt\Sso\Utility\Http\SsoEgressGuardMiddleware;

/**
 * @covers \Passbolt\Sso\Utility\Http\SsoEgressGuardMiddleware
 */
class SsoEgressGuardMiddlewareTest extends TestCase
{
    /**
     * @var \GuzzleHttp\Handler\MockHandler
     */
    private MockHandler $mockHandler;

    /**
     * @param bool $block Whether the guard should block or just warn.
     * @return \GuzzleHttp\Client
     */
    private function buildClient(bool $block): Client
    {
        Configure::write('passbolt.security.sso.egress', [
            'enabled' => true,
            'block' => $block,
            'blockLinkLocal' => true,
            'blockPrivateRanges' => true,
            'privateRangeAllowedIps' => null,
        ]);

        $this->mockHandler = new MockHandler([new Response(200, [], 'OK')]);
        $stack = HandlerStack::create($this->mockHandler);
        $stack->after('allow_redirects', new SsoEgressGuardMiddleware(), 'sso_egress_guard');

        return new Client(['handler' => $stack]);
    }

    public function testSsoEgressGuardMiddleware_Error_BlockedHostThrowsAndIsNotDispatched(): void
    {
        $client = $this->buildClient(true);

        try {
            $client->get('https://127.0.0.1/.well-known/openid-configuration');
            $this->fail('Expected SsoEgressBlockedException was not thrown.');
        } catch (SsoEgressBlockedException $e) {
            // The request must never reach the underlying handler.
            $this->assertCount(1, $this->mockHandler);
        }
    }

    /**
     * @return array
     */
    public static function unsupportedHostProvider(): array
    {
        return [
            'decimal loopback' => ['2130706433'],
            'decimal metadata' => ['2852039166'],
            'octal loopback' => ['0177.0.0.1'],
            'hexadecimal loopback' => ['0x7f000001'],
            'short form loopback' => ['127.1'],
            'bracketed ipv6 loopback' => ['[::1]'],
            'bracketed aws ipv6 metadata' => ['[fd00:ec2::254]'],
            'unresolvable name' => ['no-such-host.invalid'],
        ];
    }

    /**
     * @dataProvider unsupportedHostProvider
     * @param string $host Host to request.
     * @return void
     */
    public function testSsoEgressGuardMiddleware_Error_BlockedHostIsNotDispatched(string $host): void
    {
        $client = $this->buildClient(true);

        $this->expectException(SsoEgressBlockedException::class);
        try {
            $client->get("https://{$host}/.well-known/openid-configuration");
        } finally {
            // The request must never reach the underlying handler.
            $this->assertCount(1, $this->mockHandler);
        }
    }

    public function testSsoEgressGuardMiddleware_Success_AllowedHostIsDispatched(): void
    {
        $client = $this->buildClient(true);

        $response = $client->get('https://8.8.8.8/.well-known/openid-configuration');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertCount(0, $this->mockHandler);
    }

    public function testSsoEgressGuardMiddleware_Success_WarnOnlyModeLetsBlockedHostThrough(): void
    {
        $client = $this->buildClient(false);

        $response = $client->get('https://127.0.0.1/.well-known/openid-configuration');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertCount(0, $this->mockHandler);
    }
}
