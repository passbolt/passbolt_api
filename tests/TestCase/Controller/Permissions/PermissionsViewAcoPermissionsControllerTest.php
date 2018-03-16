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

namespace App\Test\TestCase\Controller\Permissions;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class PermissionsViewAcoPermissionsControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/groups', 'app.Base/groups_users', 'app.Base/permissions', 'app.Base/profiles', 'app.Base/resources', 'app.Base/users', 'app.Base/avatars'];

    public function testSuccess()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->getJson("/permissions/resource/$resourceId.json?api-version=2");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);

        // Expected fields.
        $this->assertPermissionAttributes($this->_responseJsonBody[0]);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('user', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('group', $this->_responseJsonBody[0]);
    }

    public function testApiV1Success()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->getJson("/permissions/resource/$resourceId.json?api-version=v1");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);

        // Expected fields.
        $this->assertObjectHasAttribute('Permission', $this->_responseJsonBody[0]);
        $this->assertPermissionAttributes($this->_responseJsonBody[0]->Permission);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('User', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('Group', $this->_responseJsonBody[0]);
    }

    public function testContainSuccess()
    {
        $this->authenticateAs('ada');
        $urlParameter = 'contain[group]=1&contain[user]=1&contain[user.profile]=1';
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $this->getJson("/permissions/resource/$resourceId.json?$urlParameter&api-version=2");
        $this->assertSuccess();

        // Search a user permission.
        $key = array_search('User', array_column($this->_responseJsonBody, 'aro'));
        $permission = $this->_responseJsonBody[$key];
        $this->assertPermissionAttributes($permission);
        // Contain user.
        $this->assertObjectHasAttribute('user', $permission);
        $this->assertUserAttributes($permission->user);
        // Contain user profile.
        $this->assertObjectHasAttribute('profile', $permission->user);
        $this->assertProfileAttributes($permission->user->profile);
        // Contain profile avatar.
        $this->assertObjectHasAttribute('avatar', $permission->user->profile);
        $this->assertAvatarAttributes($permission->user->profile->avatar);

        // Search a group permission.
        $key = array_search('Group', array_column($this->_responseJsonBody, 'aro'));
        $permission = $this->_responseJsonBody[$key];
        $this->assertPermissionAttributes($permission);
        // Contain group.
        $this->assertObjectHasAttribute('group', $permission);
        $this->assertGroupAttributes($permission->group);
    }

    public function testContainApiV1Success()
    {
        $this->authenticateAs('ada');
        $urlParameter = 'api-version=v1&contain[group]=1&contain[user]=1&contain[user.profile]=1';
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $this->getJson("/permissions/resource/$resourceId.json?$urlParameter");
        $this->assertSuccess();

        // Search a user permission.
        $permission = array_reduce($this->_responseJsonBody, function ($carry, $item) {
            if (isset($item->User)) {
                $carry = $item;
            }

            return $carry;
        }, null);
        $this->assertObjectHasAttribute('Permission', $permission);
        $this->assertPermissionAttributes($permission->Permission);

        // Contain user.
        $this->assertObjectHasAttribute('User', $permission);
        $this->assertUserAttributes($permission->User);
        // Contain user profile.
        $this->assertObjectHasAttribute('Profile', $permission->User);
        $this->assertProfileAttributes($permission->User->Profile);
        // Contain profile avatar.
        $this->assertObjectHasAttribute('Avatar', $permission->User->Profile);
        $this->assertAvatarAttributes($permission->User->Profile->Avatar);

        // Search a group permission.
        $permission = array_reduce($this->_responseJsonBody, function ($carry, $item) {
            if (isset($item->Group)) {
                $carry = $item;
            }

            return $carry;
        }, null);
        $this->assertObjectHasAttribute('Permission', $permission);
        $this->assertPermissionAttributes($permission->Permission);
        // Contain group.
        $this->assertObjectHasAttribute('Group', $permission);
        $this->assertGroupAttributes($permission->Group);
    }

    public function testErrorNotAuthenticated()
    {
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->getJson("/permissions/resource/$resourceId.json?api-version=v1");
        $this->assertAuthenticationError();
    }

    public function testErrorNotValidId()
    {
        $this->authenticateAs('dame');
        $resourceId = 'invalid-id';
        $this->getJson("/permissions/resource/$resourceId.json?api-version=v1");
        $this->assertError(400, 'The id is not valid for model Resource');
    }

    public function testErrorSoftDeletedResource()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.jquery');
        $this->getJson("/permissions/resource/$resourceId.json?api-version=v1");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testErrorResourceAccessDenied()
    {
        $resourceId = UuidFactory::uuid('resource.id.canjs');

        // Check that the resource exists.
        $Resources = TableRegistry::get('Resources');
        $resource = $Resources->get($resourceId);
        $this->assertNotNull($resource);

        // Check that the user cannot access the resource
        $this->authenticateAs('dame');
        $this->getJson("/permissions/resource/$resourceId.json?api-version=2");
        $this->assertError(404, 'The resource does not exist.');
    }
}
