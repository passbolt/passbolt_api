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
namespace App\Test\TestCase\Controller\Users;

use App\Test\Lib\AppIntegrationTestCase;

class UsersRecoverControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.users', 'app.roles', 'app.profiles', 'app.authentication_tokens'];

    public function testRecoverGetRedirect()
    {
        $this->get('/recover');
        $this->assertResponseCode(301);
    }

    public function testRecoverGetSuccess()
    {
        $this->get('/users/recover');
        $this->assertResponseOk();
    }

    public function testRecoverGetJsonSuccess()
    {
        $this->getJson('/users/recover.json');
        $this->assertSuccess();
    }

    public function testRecoverPostNoUsername()
    {

    }

    public function testReocverPostInvalidUsername()
    {

    }

    public function testRecoverPostDeletedUser ()
    {

    }

    public function testRecoverPostInactiveUser()
    {

    }

    public function testRecoverPostSuccess()
    {

    }

    public function testRecoverPostJsonError()
    {

    }

    public function testRecoverPostJsonSuccess()
    {

    }
}
