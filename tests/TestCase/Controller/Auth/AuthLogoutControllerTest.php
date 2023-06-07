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
 * @since         2.0.0
 */
namespace App\Test\TestCase\Controller\Auth;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Laminas\Diactoros\Response\RedirectResponse;

class AuthLogoutControllerTest extends AppIntegrationTestCase
{
    /**
     * Check if a redirection is of type ZendRedirect
     * Usefull for high level routes redirections / route alias testing
     */
    public function assertZendRedirect(string $url): void
    {
        $this->assertTrue($this->_response instanceof RedirectResponse);
        $url = Router::url($url, true);
        $location = $this->_response->getHeader('location');
        $this->assertNotEmpty($location);
        $this->assertEquals($url, $location[0]);
    }

    public function testAuthLogoutController_GetLoggedIn(): void
    {
        $this->logInAsUser();

        $this->get('/auth/logout');
        $this->assertRedirect('/auth/login');
    }

    public function testAuthLogoutController_PostLoggedIn(): void
    {
        $this->logInAsUser();

        $this->post('/auth/logout');
        $this->assertRedirect('/auth/login');
    }

    public function testAuthLogoutController_PostLoggedInWithouthCSRF(): void
    {
        $this->logInAsUser();
        $this->disableCsrfToken();

        $this->post('/auth/logout');
        $this->assertResponseError('Missing or incorrect CSRF cookie type.');
    }

    public function testAuthLogoutController_NotLoggedIn(): void
    {
        $this->get('/auth/logout');
        $this->assertRedirect('/auth/login');
    }

    public function testAuthLogoutController_Redirect(): void
    {
        $this->get('/logout');
        $this->assertZendRedirect('/auth/logout');
    }

    public function testAuthLogoutJson_Disabled()
    {
        $this->getJson('/auth/logout.json');
        $this->assertNotFoundError('The logout route should only be accessed with POST method.');
    }

    public function testAuthLogoutJson_Enabled()
    {
        Configure::write('passbolt.security.getLogoutEndpointEnabled', true);
        $this->get('/auth/logout.json');
        $this->assertRedirect('auth/login');
    }
}
