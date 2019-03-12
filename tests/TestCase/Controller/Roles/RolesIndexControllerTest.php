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

namespace App\Test\TestCase\Controller\Roles;

use App\Test\Lib\AppIntegrationTestCase;

class RolesIndexControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/Users', 'app.Base/Roles'];

    public function testRolesIndexGetSuccess()
    {
        $this->authenticateAs('ada');
        $this->getJson('/roles.json?api-version=2');
        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));
        $this->assertRoleAttributes($this->_responseJsonBody[0]);
    }

    public function testRolesIndexGetApiV1Success()
    {
        $this->authenticateAs('ada');
        $this->getJson('/roles.json');
        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));
        $this->assertObjectHasAttribute('Role', $this->_responseJsonBody[0]);
        $this->assertRoleAttributes($this->_responseJsonBody[0]->Role);
    }

    public function testRolesIndexErrorNotAuthenticated()
    {
        $this->getJson('/roles.json');
        $this->assertAuthenticationError();
    }
}
