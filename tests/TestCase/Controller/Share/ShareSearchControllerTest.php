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

namespace App\Test\TestCase\Controller\Share;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\Gpg;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class ShareSearchControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.users', 'app.gpgkeys', 'app.profiles', 'app.roles', 'app.groups', 'app.groups_users', 'app.resources', 'app.permissions'];

    public function setUp()
    {
        $this->Permissions = TableRegistry::get('Permissions');
        $this->Resources = TableRegistry::get('Resources');
        $this->gpg = new Gpg();
        parent::setUp();
    }

    public function testShareSearchArosSuccess()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $this->getJson("/share/search-users/resource/$resourceId.json?api-version=2");
        $aros = $this->_responseJsonBody;
        $this->assertNotEmpty($aros);
        $arosIds = Hash::extract($aros, "{n}.id");

        // Should find the user Edith
        $userEId = UuidFactory::uuid('user.id.edith');
        $userE = $aros[array_search($userEId, $arosIds)];
        $this->assertNotEmpty($userE);
        $this->assertUserAttributes($userE);

        // Should not find the user Ada
        $userAId = UuidFactory::uuid('user.id.ada');
        $this->assertFalse(array_search($userAId, $arosIds));

        // Should find the group creative
        $groupCId = UuidFactory::uuid('group.id.creative');
        $groupC = $aros[array_search($groupCId, $arosIds)];
        $this->assertNotEmpty($groupC);
        $this->assertGroupAttributes($groupC);
        // Contain user count field.
        $this->assertNotEmpty($groupC->user_count);

        // Should not find the group board
        $groupBId = UuidFactory::uuid('group.id.board');
        $this->assertFalse(array_search($groupBId, $arosIds));
    }

    public function testShareSearchArosSuccess_SearchUserWang()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $filterParams = 'filter[search]=wang@passbolt';
        $this->getJson("/share/search-users/resource/$resourceId.json?$filterParams&api-version=2");
        $aros = $this->_responseJsonBody;
        $this->assertNotEmpty($aros);
        $this->assertCount(1, $aros);
        $this->assertEquals(UuidFactory::uuid('user.id.wang'), $aros[0]->id);
    }

    public function testShareSearchArosSuccess_SearchGroupCreative()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $filterParams = 'filter[search]=Creative';
        $this->getJson("/share/search-users/resource/$resourceId.json?$filterParams&api-version=2");
        $aros = $this->_responseJsonBody;
        $this->assertNotEmpty($aros);
        $this->assertCount(1, $aros);
        $this->assertEquals(UuidFactory::uuid('group.id.creative'), $aros[0]->id);
    }

    public function testShareSearchArosApiV1Success()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $this->getJson("/share/search-users/resource/$resourceId.json");
        $aros = $this->_responseJsonBody;
        $this->assertNotEmpty($aros);

        // Extract and check a user.
        $users = Hash::extract($aros, "{n}.User");
        $this->assertUserAttributes($users[0]);

        // Extract and check a group.
        $groups = Hash::extract($aros, "{n}.Group");
        $this->assertGroupAttributes($groups[0]);
        // Contain user count field.
        $this->assertNotEmpty($groups[0]->user_count);
    }

    public function testShareSearchAros_ErrorNotValidResourceId()
    {
        $this->authenticateAs('ada');
        $resourceId = 'invalid-id';
        $this->getJson("/share/search-users/resource/$resourceId.json");
        $this->assertError(400, 'The resource id is not valid.');
    }

    public function testShareSearchAros_ErrorDoesNotExistResource()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid();
        $this->getJson("/share/search-users/resource/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testShareSearchArosErrorResourceIsSoftDeleted()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.jquery');
        $this->getJson("/share/search-users/resource/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testShareSearchArosErrorAccessDenied()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.april');
        $this->getJson("/share/search-users/resource/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testShareSearchAros_NotAuthenticated()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->getJson("/share/search-users/resource/$resourceId.json");
        $this->assertAuthenticationError();
    }
}
