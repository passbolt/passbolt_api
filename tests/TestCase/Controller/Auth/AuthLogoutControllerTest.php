<?php
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
use App\Utility\UuidFactory;
use Cake\Routing\Router;
use Zend\Diactoros\Response\RedirectResponse;

class AuthLogoutControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/Users', 'app.Base/Roles', 'app.Base/Profiles'];

    /**
     * Check if a redirection is of type ZendRedirect
     * Usefull for high level routes redirections / route alias testing
     */
    public function assertZendRedirect(string $url)
    {
        $this->assertTrue($this->_response instanceof RedirectResponse);
        $url = Router::url($url, true);
        $location = $this->_response->getHeader('location');
        $this->assertNotEmpty($location);
        $this->assertEquals($url, $location[0]);
    }

    public function testAuthLogoutLoggedIn()
    {
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => UuidFactory::uuid('users.id.ada'),
                    'username' => 'ada@passbolt.com',
                ]
            ]
        ]);

        $this->get('/auth/logout');
        $this->assertRedirect('/auth/login');
    }

    public function testAuthLogoutNotLoggedIn()
    {
        $this->get('/auth/logout');
        $this->assertRedirect('/auth/login');
    }

    public function testAuthLogoutRedirect()
    {
        $this->get('/logout');
        $this->assertZendRedirect('/auth/logout');
    }
}
