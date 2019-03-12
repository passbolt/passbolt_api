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

class CheckSessionControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/Users', 'app.Base/Roles', 'app.Base/Profiles', 'app.Base/AuthenticationTokens'];

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
        $this->authenticateAs('ada');
        $this->get('/auth/checksession.json');
        $this->assertResponseOk();
        $response = json_decode($this->_getBodyAsString());
        $this->assertTextContains('success', $response->header->status);
    }
}
