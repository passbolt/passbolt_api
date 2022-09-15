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
 * @since         3.3.0
 */
namespace Passbolt\JwtAuthentication\Test\TestCase\Middleware;

use Passbolt\JwtAuthentication\Test\Utility\JwtAuthenticationIntegrationTestCase;

class JwtRouteFilterMiddlewareTest extends JwtAuthenticationIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->createJwtTokenAndSetInHeader();
    }

    public function testJwtRouteFilterMiddleware_Not_Allowed_Logout_Route_GET()
    {
        $this->getJson('auth/logout.json');
        $this->assertResponseError('The route /auth/logout is not permitted with JWT authentication.');
    }

    public function testJwtRouteFilterMiddleware_Not_Allowed_Logout_Route_POST()
    {
        $this->postJson('auth/logout.json');
        $this->assertResponseError('The route /auth/logout is not permitted with JWT authentication.');
    }

    public function testJwtRouteFilterMiddleware_Not_Allowed_Login_Route_GET()
    {
        $this->getJson('auth/login.json');
        $this->assertResponseError('The route /auth/login is not permitted with JWT authentication.');
    }

    public function testJwtRouteFilterMiddleware_Not_Allowed_Login_Route_POST()
    {
        $this->postJson('auth/login.json');
        $this->assertResponseError('The route /auth/login is not permitted with JWT authentication.');
    }
}
