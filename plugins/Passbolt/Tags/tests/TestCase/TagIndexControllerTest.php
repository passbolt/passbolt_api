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
namespace Passbolt\Tags\Test\TestCase\Controller\Auth;

use App\Test\Lib\AppIntegrationTestCase;

class TagIndexControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/users', 'app.Base/roles'];

    public function testTagIndexNotLoggedIn()
    {
        $this->get('/tags.json');
        $this->assertResponseError();
        $response = json_decode($this->_getBodyAsString());
        $this->assertTextContains('error', $response->header->status);
        $this->assertTextContains('You need to login to access this location.', $response->header->message);
    }

    public function testTagIndexSuccess()
    {
        $this->get('/tags.json');
        $this->assertSuccess();
    }
}