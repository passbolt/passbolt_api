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
use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestCase;

class CheckSessionControllerTest extends IntegrationTestCase
{
    public $fixtures = ['app.users', 'app.roles', 'app.profiles', 'app.authentication_tokens'];

    public function testCheckSessionNotLoggedIn()
    {
        $this->get('/auth/checksession.json');
        $this->assertResponseError();
        $response = json_decode($this->_getBodyAsString());
        $this->assertTextContains('error', $response->header->status);
        $this->assertTextContains('You need to login to access this location.', $response->header->message);
    }

    public function testCheckSessionNotLoggedInLegacy()
    {
        $this->get('/auth/checkSession.json');
        $this->assertResponseError();
        $response = json_decode($this->_getBodyAsString());
        $this->assertTextContains('error', $response->header->status);
        $this->assertTextContains('You need to login to access this location.', $response->header->message);
    }

    public function testCheckSessionLoggedIn()
    {
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => Common::uuid('users.id.ada'),
                    'username' => 'ada@passbolt.com',
                ]
            ]
        ]);

        $this->get('/auth/checksession.json');
        $this->assertResponseOk();
        $response = json_decode($this->_getBodyAsString());
        $this->assertTextContains('success', $response->header->status);
    }
}
