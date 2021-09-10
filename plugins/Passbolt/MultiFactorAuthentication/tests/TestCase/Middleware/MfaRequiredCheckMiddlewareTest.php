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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Middleware;

use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use Passbolt\MultiFactorAuthentication\Middleware\MfaRequiredCheckMiddleware;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaRequiredCheckMiddlewareTest extends TestCase
{
    public $middleware;

    public function setUp(): void
    {
        $this->middleware = new MfaRequiredCheckMiddleware();
    }

    public function tearDown(): void
    {
        unset($this->middleware);
    }

    public function dataForWhiteListUrl()
    {
        return [
            ['/mfa/verify',],
            ['/auth/logout',],
            ['/logout',],
        ];
    }

    /**
     * @dataProvider dataForWhiteListUrl
     */
    public function testMfaRequiredCheckMiddlewareIsMfaCheckRequired_WhiteListed_Route($url)
    {
        $request = new ServerRequest(['url' => $url]);
        $result = $this->middleware->isMfaCheckRequired($request, $this->createMock(MfaSettings::class));
        $this->assertFalse($result);
    }
}
