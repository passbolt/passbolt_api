<?php
declare(strict_types=1);

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
        'app.Base/Profiles', 'app.Base/Resources', 'app.Base/Users', 'app.Base/Roles',
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

        // Search a group permission.
        $key = array_search('Group', array_column($this->_responseJsonBody, 'aro'));
        $permission = $this->_responseJsonBody[$key];
        $this->assertPermissionAttributes($permission);
        // Contain group.
        $this->assertObjectHasAttribute('group', $permission);
        $this->assertGroupAttributes($permission->group);
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
        $this->assertError(400, 'The identifier should be a valid UUID.');
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
