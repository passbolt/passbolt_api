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
use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;

/**
 * Test for HttpProxyMiddleware
 */
class HttpProxyMiddlewareIntegrationTest extends AppIntegrationTestCase
{
    public function testHttpProxyMiddlewareIntegrationTest_Proxy_Headers_Found()
    {
        Configure::write(HttpProxyMiddleware::PASSBOLT_SECURITY_PROXIES_ACTIVE_CONFIG_NAME, true);

        $this->logInAsUser();
        $this->getJson('/auth/is-authenticated.json');
        $this->assertSuccess();

        $this->assertTrue($this->_response->hasHeader('Access-Control-Expose-Headers'));
        $headersWhitelist = $this->_response->getHeader('Access-Control-Expose-Headers');
        $this->assertTrue(in_array('X-Forwarded-For', $headersWhitelist));
        $this->assertTrue(in_array('X-Real-IP', $headersWhitelist));
        $this->assertTrue(in_array('Client-IP', $headersWhitelist));
        $this->assertTrue(in_array('X-GPGAuth-Version', $headersWhitelist));

        Configure::write(HttpProxyMiddleware::PASSBOLT_SECURITY_PROXIES_ACTIVE_CONFIG_NAME, false);
    }
}
