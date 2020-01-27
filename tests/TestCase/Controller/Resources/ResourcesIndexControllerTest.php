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

namespace App\Test\TestCase\Controller\Resources;

use App\Model\Entity\Permission;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\FavoritesModelTrait;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;
use PassboltTestData\Lib\PermissionMatrix;

class ResourcesIndexControllerTest extends AppIntegrationTestCase
{
    use FavoritesModelTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Avatars', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources',
        'app.Base/Secrets', 'app.Base/Favorites', 'app.Base/Permissions', 'app.Base/Avatars'
    ];

    public function testSuccess()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?api-version=2');
        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));

        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody[0]);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('secrets', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('creator', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('modifier', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('favorite', $this->_responseJsonBody[0]);
    }

    public function testApiV1Success()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json');
        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));

        // Expected fields.
        $this->assertObjectHasAttribute('Resource', $this->_responseJsonBody[0]);
        $this->assertResourceAttributes($this->_responseJsonBody[0]->Resource);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('Secret', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('Creator', $this->_responseJsonBody[0]);
    }

    public function testContainSuccess()
    {
        $this->authenticateAs('ada');
        $urlParameter = 'contain[creator]=1&contain[favorite]=1&contain[modifier]=1&contain[permission]=1&contain[permissions.user.profile]=1&contain[permissions.group]=1&contain[secret]=1';
        $this->getJson("/resources.json?$urlParameter&api-version=2");
        $this->assertSuccess();

        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody[0]);
        // Contain creator.
        $this->assertObjectHasAttribute('creator', $this->_responseJsonBody[0]);
        $this->assertUserAttributes($this->_responseJsonBody[0]->creator);
        // Contain modifier.
        $this->assertObjectHasAttribute('modifier', $this->_responseJsonBody[0]);
        $this->assertUserAttributes($this->_responseJsonBody[0]->modifier);
        // Contain permission.
        $this->assertObjectHasAttribute('permission', $this->_responseJsonBody[0]);
        $this->assertPermissionAttributes($this->_responseJsonBody[0]->permission);
        // Contain permission user.
        $this->assertObjectHasAttribute('permissions', $this->_responseJsonBody[0]);
        foreach ($this->_responseJsonBody[0]->permissions as $permission) {
            $this->assertPermissionAttributes($permission);
            if ($permission->user) {
                $this->assertUserAttributes($permission->user);
            } else {
                $this->assertGroupAttributes($permission->group);
            }
        }
        // Contain secret.
        $this->assertObjectHasAttribute('secrets', $this->_responseJsonBody[0]);
        $this->assertCount(1, $this->_responseJsonBody[0]->secrets);
        $this->assertSecretAttributes($this->_responseJsonBody[0]->secrets[0]);
        // Contain favorite.
        $this->assertObjectHasAttribute('favorite', $this->_responseJsonBody[0]);
        // A resource marked as favorite contains the favorite data.
        $favoriteResourceId = UuidFactory::uuid('resource.id.apache');
        $favoriteResource = current(array_filter($this->_responseJsonBody, function ($resource) use ($favoriteResourceId) {
            return $resource->id == $favoriteResourceId;
        }));
        $this->assertObjectHasAttribute('favorite', $favoriteResource);
        $this->assertFavoriteAttributes($favoriteResource->favorite);
    }

    public function testContainApiV1Success()
    {
        $this->authenticateAs('ada');
        $urlParameter = 'contain[creator]=1&contain[favorite]=1&contain[modifier]=1&contain[permission]=1&contain[secret]=1';
        $this->getJson("/resources.json?$urlParameter");
        $this->assertSuccess();

        // Expected fields.
        $this->assertObjectHasAttribute('Resource', $this->_responseJsonBody[0]);
        $this->assertResourceAttributes($this->_responseJsonBody[0]->Resource);
        // Contain creator.
        $this->assertObjectHasAttribute('Creator', $this->_responseJsonBody[0]);
        $this->assertUserAttributes($this->_responseJsonBody[0]->Creator);
        // Contain modifier.
        $this->assertObjectHasAttribute('Modifier', $this->_responseJsonBody[0]);
        $this->assertUserAttributes($this->_responseJsonBody[0]->Modifier);
        // Contain permission.
        $this->assertObjectHasAttribute('Permission', $this->_responseJsonBody[0]);
        $this->assertPermissionAttributes($this->_responseJsonBody[0]->Permission);
        // Contain secret.
        $this->assertObjectHasAttribute('Secret', $this->_responseJsonBody[0]);
        $this->assertCount(1, $this->_responseJsonBody[0]->Secret);
        $this->assertSecretAttributes($this->_responseJsonBody[0]->Secret[0]);
        // Contain favorite.
        $this->assertObjectHasAttribute('Favorite', $this->_responseJsonBody[0]);
        // A resource marked as favorite contains the favorite data.
        $favoriteResourceId = UuidFactory::uuid('resource.id.apache');
        $favoriteResource = current(array_filter($this->_responseJsonBody, function ($resource) use ($favoriteResourceId) {
            return $resource->Resource->id == $favoriteResourceId;
        }));
        $this->assertObjectHasAttribute('Favorite', $favoriteResource);
        $this->assertFavoriteAttributes($favoriteResource->Favorite);
    }

    public function testFilterIsFavoriteSuccess()
    {
        $this->authenticateAs('dame');
        $urlParameter = 'filter[is-favorite]=1';
        $this->getJson("/resources.json?$urlParameter&api-version=2");
        $this->assertSuccess();
        $this->assertCount(2, $this->_responseJsonBody);

        // Check that the result contain only the expected favorite resources.
        $favoriteResourcesIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $expectedResources = [UuidFactory::uuid('resource.id.apache'), UuidFactory::uuid('resource.id.april')];
        $this->assertEquals(0, count(array_diff($expectedResources, $favoriteResourcesIds)));

        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody[0]);

        // Favorite field shouldn't be present by default even when filtering by favorite.
        $this->assertObjectNotHasAttribute('favorite', $this->_responseJsonBody[0]);
    }

    public function testFilterIsSharedWithGroupSuccess()
    {
        $this->authenticateAs('irene');
        $groupDId = UuidFactory::uuid('group.id.developer');
        $urlParameter = "filter[is-shared-with-group]=$groupDId";
        $this->getJson("/resources.json?$urlParameter&api-version=2");
        $this->assertSuccess();
        $resourcesIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        sort($resourcesIds);

        // Extract the resource the group should have access.
        $permissionsMatrix = PermissionMatrix::getGroupsResourcesPermissions('group');
        $expectedResourcesIds = [];
        foreach ($permissionsMatrix['developer'] as $resourceAlias => $resourcePermission) {
            if ($resourcePermission > 0) {
                $expectedResourcesIds[] = UuidFactory::uuid("resource.id.$resourceAlias");
            }
        }
        sort($expectedResourcesIds);

        $this->assertCount(count($expectedResourcesIds), $resourcesIds);
        $this->assertEmpty(array_diff($expectedResourcesIds, $resourcesIds));
    }

    public function testFilterIsSharedWithMeSuccess()
    {
        $this->authenticateAs('ada');
        $urlParameter = "filter[is-shared-with-me]=1";
        $this->getJson("/resources.json?$urlParameter&api-version=2");
        $this->assertSuccess();
        $resourcesIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        sort($resourcesIds);

        // Get all resources with permissions.
        $permissionsMatrix = PermissionMatrix::getCalculatedUsersResourcesPermissions('user');
        $expectedResourcesIds = [];
        foreach ($permissionsMatrix['ada'] as $resourceAlias => $resourcePermission) {
            if ($resourcePermission >= Permission::READ && $resourcePermission < Permission::OWNER) {
                $expectedResourcesIds[] = UuidFactory::uuid("resource.id.$resourceAlias");
            }
        }
        sort($expectedResourcesIds);

        $this->assertEquals($resourcesIds, $expectedResourcesIds);
    }

    public function testFilterHasIdSuccess()
    {
        $this->authenticateAs('ada');
        $resourceAId = UuidFactory::uuid('resource.id.apache');
        $resourceBId = UuidFactory::uuid('resource.id.bower');
        $urlParameter = "filter[has-id][]=$resourceAId&filter[has-id][]=$resourceBId";
        $this->getJson("/resources.json?$urlParameter&api-version=2");
        $this->assertSuccess();

        $this->assertCount(2, $this->_responseJsonBody);
        $resourcesIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $this->assertContains($resourceAId, $resourcesIds);
        $this->assertContains($resourceBId, $resourcesIds);
    }

    public function testIndexErrorNotAuthenticated()
    {
        $this->getJson('/resources.json');
        $this->assertAuthenticationError();
    }
}
