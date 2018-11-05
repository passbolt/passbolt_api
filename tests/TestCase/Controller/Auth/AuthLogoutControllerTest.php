<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Test\TestCase\Controller\Auth;

use App\Utility\UuidFactory;
use Cake\TestSuite\IntegrationTestCase;

class AuthLogoutControllerTest extends IntegrationTestCase
{
    public $fixtures = ['app.Base/users', 'app.Base/roles', 'app.Base/profiles'];

    public function testCheckLogoutLoggedIn()
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

    public function testCheckLogoutNotLoggedIn()
    {
        $this->get('/auth/logout');
        $this->assertRedirect('/auth/login');
    }

    public function testCheckLogoutRedirect()
    {
        $this->get('/logout');
        $this->assertRedirect('/auth/logout');
    }
}
