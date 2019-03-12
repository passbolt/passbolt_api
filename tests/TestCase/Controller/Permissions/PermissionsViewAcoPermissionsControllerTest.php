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

namespace App\Test\TestCase\Controller\Permissions;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class PermissionsViewAcoPermissionsControllerTest extends AppIntegrationTestCase
{
    use GroupsModelTrait;

    public $fixtures = [
        'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Permissions',
        'app.Base/Profiles', 'app.Base/Resources', 'app.Base/Users', 'app.Base/Avatars'
    ];

    public function testPermissionsViewSuccess()
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

    public function testPermissionsViewApiV1Success()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->getJson("/permissions/resource/$resourceId.json");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);

        // Expected fields.
        $this->assertObjectHasAttribute('Permission', $this->_responseJsonBody[0]);
        $this->assertPermissionAttributes($this->_responseJsonBody[0]->Permission);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('User', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('Group', $this->_responseJsonBody[0]);
    }

    public function testPermissionsViewContainSuccess()
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

    public function testPermissionsViewContainApiV1Success()
    {
        $this->authenticateAs('ada');
        $urlParameter = 'contain[group]=1&contain[user]=1&contain[user.profile]=1';
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

    public function testPermissionsViewErrorNotAuthenticated()
    {
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->getJson("/permissions/resource/$resourceId.json");
        $this->assertAuthenticationError();
    }

    public function testPermissionsViewErrorNotValidId()
    {
        $this->authenticateAs('dame');
        $resourceId = 'invalid-id';
        $this->getJson("/permissions/resource/$resourceId.json");
        $this->assertError(400, 'The id is not valid for model Resource');
    }

    public function testPermissionsViewErrorSoftDeletedResource()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.jquery');
        $this->getJson("/permissions/resource/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testPermissionsViewErrorResourceAccessDenied()
    {
        $resourceId = UuidFactory::uuid('resource.id.canjs');

        // Check that the resource exists.
        $Resources = TableRegistry::getTableLocator()->get('Resources');
        $resource = $Resources->get($resourceId);
        $this->assertNotNull($resource);

        // Check that the user cannot access the resource
        $this->authenticateAs('dame');
        $this->getJson("/permissions/resource/$resourceId.json?api-version=2");
        $this->assertError(404, 'The resource does not exist.');
    }
}
