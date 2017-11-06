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

use App\Utility\Common;
use Cake\TestSuite\IntegrationTestCase;

class AuthLogoutControllerTest extends IntegrationTestCase
{
    public $fixtures = ['app.users', 'app.roles', 'app.profiles'];

    public function testCheckLogoutLoggedIn()
    {
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => Common::uuid('users.id.ada'),
                    'username' => 'ada@passbolt.com',
                ]
            ]
        ]);

        $this->get('/auth/logout.json');
        $this->assertResponseOk();
        $response = ($this->_getBodyAsString();
        $this->assertTextContains('logint', $response->header->status);
    }
}
