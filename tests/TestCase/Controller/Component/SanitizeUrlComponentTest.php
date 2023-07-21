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
     * @dataProvider dataForTestSanitizeUrlComponent_Sanitize
     */
    public function testSanitizeUrlComponent_Sanitize(string $url, array $blacklist, bool $allowEmpty, bool $ensureStartsWithSlash, bool $escapeSpecialChars, string $expectedResult): void
    {
        $registry = new ComponentRegistry();
        $component = new SanitizeUrlComponent($registry);

        $result = $component->sanitize($url, $blacklist, $allowEmpty, $ensureStartsWithSlash, $escapeSpecialChars);

        $this->assertSame($expectedResult, $result);
    }

    public function dataForTestSanitizeUrlComponent_Sanitize(): array
    {
        return [
            ['', [], false, false, true, '/'],
            ['', [], true, false, true, ''],
            ['app/users', [], false, true, true, '/'],
            ['/app/users', [], false, true, true, '/app/users'],
            ['/subdir/app/users', [], false, true, true, '/subdir/app/users'],
            ['/app/users', ['/user'], false, true, true, '/'],
            ['/mfa/verify/provider', ['/mfa/verify'], false, true, true, '/'],
            ['/app/users<script>', [], false, true, false, '/app/users<script>'],
            ['/app/users<script>', [], false, true, true, '/app/users&lt;script&gt;'],
        ];
    }

    /**
     * @dataProvider dataForTestSanitizeUrlComponent_SanitizeRedirect
     * @param string $url
     * @param string $expectedResult
     */
    public function testSanitizeUrlComponent_SanitizeRedirect(string $url, string $expectedResult): void
    {
        $result = $this->getComponent($url)->sanitizeRedirect();
        $this->assertSame($expectedResult, $result);
    }

    public function dataForTestSanitizeUrlComponent_SanitizeRedirect(): array
    {
        $baseUrl = '/foo';

        return [
            [$baseUrl, '/'],
            [$baseUrl . '?redirect', '/'],
            [$baseUrl . '?redirect=', '/'],
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
            [$baseUrl . '?redirect=/mfa', '/mfa'],
        ];
    }

    /**
     * @dataProvider dataForTestSanitizeUrlComponent_SanitizeRedirect_With_LoopStop
     */
    public function testSanitizeUrlComponent_SanitizeRedirect_With_LoopStop(
        ?string $stopper,
        string $redirect,
        string $expectedRedirect
    ) {
        $url = "/foo?redirect=$redirect";
        $result = $this->getComponent($url)->sanitizeRedirect($stopper);

        $this->assertSame($expectedRedirect, $result);
    }

    public function dataForTestSanitizeUrlComponent_SanitizeRedirect_With_LoopStop(): array
    {
        return [
            ['', '', '/'],
            ['', '/bar', '/bar'],
            ['/', '/bar', '/'],
            ['stop', '/bar', '/bar'],
            ['stop', '/stop', '/'],
            ['stop', '/stop/deep', '/'],
            ['stop/deep', '/stop/deep', '/'],
            ['stop/deeper', '/stop/deep', '/stop/deep'],
            ['stop/deeper', '/stop/deeper/even-deeper', '/'],
        ];
    }

    /**
     * @param string $url
     * @return SanitizeUrlComponent
     */
    protected function getComponent(string $url): SanitizeUrlComponent
    {
        $request = new ServerRequest(compact('url'));
        $controller = $this->getMockBuilder('Cake\Controller\Controller')
            ->disableOriginalConstructor()
            ->getMock();
        $controller->method('getRequest')->willReturn($request);
        $registry = new ComponentRegistry($controller);

        return new SanitizeUrlComponent($registry);
    }
}
