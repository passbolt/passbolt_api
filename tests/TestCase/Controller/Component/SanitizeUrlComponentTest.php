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
 * @since         3.8.0
 */

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\SanitizeUrlComponent;
use Cake\Controller\ComponentRegistry;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;

class SanitizeUrlComponentTest extends TestCase
{
    /**
     * @dataProvider dataForTestSanitize
     * @param string $url
     * @param string $expectedSort
     */
    public function testSanitize(string $url, array $blacklist, bool $ensureStartsWithSlash, bool $escapeSpecialChars, string $expectedResult): void
    {
        $registry = new ComponentRegistry();
        $component = new SanitizeUrlComponent($registry);

        $result = $component->sanitize($url, $blacklist, $ensureStartsWithSlash, $escapeSpecialChars);

        $this->assertSame($result, $expectedResult);
    }

    public function dataForTestSanitize(): array
    {
        return [
            ['', [], false, true, ''],
            ['app/users', [], true, true, '/'],
            ['/app/users', [], true, true, '/app/users'],
            ['/app/users', ['/user'], true, true, '/'],
            ['/mfa/verify/provider', ['/mfa/verify'], true, true, '/'],
            ['/app/users<script>', [], true, false, '/app/users<script>'],
            ['/app/users<script>', [], true, true, '/app/users&lt;script&gt;'],
        ];
    }

    /**
     * @dataProvider dataForTestSanitizeRedirect
     * @param string $url
     * @param string $expectedSort
     */
    public function testSanitizeRedirect(string $url, string $expectedResult): void
    {
        $request = new ServerRequest(compact('url'));
        $controller = $this->getMockBuilder('Cake\Controller\Controller')
            ->disableOriginalConstructor()
            ->getMock();
        $controller->method('getRequest')->willReturn($request);
        $registry = new ComponentRegistry($controller);
        $component = new SanitizeUrlComponent($registry);

        $result = $component->sanitizeRedirect();

        $this->assertSame($result, $expectedResult);
    }

    public function dataForTestSanitizeRedirect(): array
    {
        $baseUrl = '/mfa/verify/provider';

        return [
            [$baseUrl, ''],
            [$baseUrl . '?redirect', ''],
            [$baseUrl . '?redirect=', ''],
            [$baseUrl . '?redirect=/', '/'],
            [$baseUrl . '?redirect=test', '/'],
            [$baseUrl . '?redirect=http://evil.com', '/'],
            [$baseUrl . '?redirect=evil.com', '/'],
            [$baseUrl . '?redirect=8.8.8.8', '/'],
            [$baseUrl . '?redirect=/8.8.8.8', '/8.8.8.8'],
            [$baseUrl . '?redirect=/a', '/a'],
            [$baseUrl . '?redirect=/app/users', '/app/users'],
            [$baseUrl . '?redirect=/../../root', '/'],
            [$baseUrl . '?redirect=/<script>', '/&lt;script&gt;'],
            [$baseUrl . '?redirect=' . $baseUrl, '/'],
        ];
    }
}
