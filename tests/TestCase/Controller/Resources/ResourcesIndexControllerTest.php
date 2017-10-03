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
namespace App\Test\TestCase\Controller;

use App\Test\TestCase\ApplicationTest;

class ResourcesIndexControllerTest extends ApplicationTest
{
    public $fixtures = ['app.users', 'app.roles', 'app.profiles', 'app.authentication_tokens', 'app.resources'];

    public function testGetSuccess()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json');
        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));
        $resource = $this->_responseJsonBody[0]->Resource;
        $attributesNames = ['id', 'name', 'username', 'uri', 'description', 'deleted', 'created', 'modified', 'created_by', 'modified_by'];
        $this->assertObjectHasAttributes($attributesNames, $resource);
    }

    public function testNotAuthenticatedError()
    {
        $this->getJson('/resources.json');
        $this->assertAuthenticationError();
    }
}
