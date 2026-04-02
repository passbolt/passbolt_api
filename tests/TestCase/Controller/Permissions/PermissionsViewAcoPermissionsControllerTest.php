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

use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use Cake\ORM\TableRegistry;

class PermissionsViewAcoPermissionsControllerTest extends AppIntegrationTestCase
{
    use GroupsModelTrait;

    public function testPermissionsViewSuccess(): void
    {
        $user = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $this->logInAs($user);
        $this->getJson("/permissions/resource/$resource->id.json");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);

        // Expected fields.
        $this->assertPermissionAttributes($this->_responseJsonBody[0]);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('user', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('group', $this->_responseJsonBody[0]);
    }

    public function testPermissionsViewContainSuccess(): void
    {
        $user = UserFactory::make()->persist();
        $group = GroupFactory::make()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user, $group])->persist();
        $this->logInAs($user);
        $urlParameter = 'contain[group]=1&contain[user]=1&contain[user.profile]=1';
        $this->getJson("/permissions/resource/$resource->id.json?$urlParameter&api-version=2");
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

    public function testPermissionsViewErrorNotAuthenticated(): void
    {
        $resource = ResourceFactory::make()->persist();
        $this->getJson("/permissions/resource/$resource->id.json");
        $this->assertAuthenticationError();
    }

    public function testPermissionsViewErrorNotValidId(): void
    {
        $this->logInAsUser();
        $resourceId = 'invalid-id';
        $this->getJson("/permissions/resource/$resourceId.json");
        $this->assertError(400, 'The identifier should be a valid UUID.');
    }

    public function testPermissionsViewErrorSoftDeletedResource(): void
    {
        $this->logInAsUser();
        $resource = ResourceFactory::make()->deleted()->persist();
        $this->getJson("/permissions/resource/$resource->id.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testPermissionsViewErrorResourceAccessDenied(): void
    {
        $resource = ResourceFactory::make()->persist();

        // Check that the resource exists.
        $Resources = TableRegistry::getTableLocator()->get('Resources');
        $resource = $Resources->get($resource->id);
        $this->assertNotNull($resource);

        // Check that the user cannot access the resource
        $this->logInAsUser();
        $this->getJson("/permissions/resource/$resource->id.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testPermissionsViewController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $resource = ResourceFactory::make()->persist();
        $this->get("/permissions/resource/$resource->id");
        $this->assertResponseCode(404);
    }
}
