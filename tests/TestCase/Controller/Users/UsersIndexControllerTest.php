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

class UsersIndexControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.users', 'app.profiles', 'app.gpgkeys', 'app.roles'];

    public function testUsersIndexGetSuccess()
    {
        $this->authenticateAs('ada');
        $this->getJson('/users.json?api-version=2');
        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));
        $this->assertUserAttributes($this->_responseJsonBody[0]);

        // gpgkey
        $this->assertObjectHasAttribute('gpgkey', $this->_responseJsonBody[0]);
        $this->assertGpgkeyAttributes($this->_responseJsonBody[0]->gpgkey);
        // profile
        $this->assertObjectHasAttribute('profile', $this->_responseJsonBody[0]);
        $this->assertProfileAttributes($this->_responseJsonBody[0]->profile);
        // role
        $this->assertObjectHasAttribute('role', $this->_responseJsonBody[0]);
        $this->assertRoleAttributes($this->_responseJsonBody[0]->role);
    }

    public function testUsersIndexGetApiV1Success()
    {
        $this->authenticateAs('ada');
        $this->getJson('/users.json');
        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));
        $this->assertObjectHasAttribute('User', $this->_responseJsonBody[0]);
        $this->assertUserAttributes($this->_responseJsonBody[0]->User);

        // gpgkey
        $this->assertObjectHasAttribute('Gpgkey', $this->_responseJsonBody[0]);
        $this->assertGpgkeyAttributes($this->_responseJsonBody[0]->Gpgkey);
        // profile
        $this->assertObjectHasAttribute('Profile', $this->_responseJsonBody[0]);
        $this->assertProfileAttributes($this->_responseJsonBody[0]->Profile);
        // role
        $this->assertObjectHasAttribute('Role', $this->_responseJsonBody[0]);
        $this->assertRoleAttributes($this->_responseJsonBody[0]->Role);
    }

    public function testUsersIndexErrorNotAuthenticated()
    {
        $this->getJson('/users.json');
        $this->assertAuthenticationError();
    }
}
