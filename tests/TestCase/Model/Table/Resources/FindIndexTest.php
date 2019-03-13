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

namespace App\Test\TestCase\Model\Table\Resources;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Model\Table\ResourcesTable;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FavoritesModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use PassboltTestData\Lib\PermissionMatrix;

class FindIndexTest extends AppTestCase
{
    public $Resources;
    use FavoritesModelTrait;

    public $fixtures = ['app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Secrets', 'app.Base/Favorites', 'app.Base/Permissions'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Resources') ? [] : ['className' => ResourcesTable::class];
        $this->Resources = TableRegistry::getTableLocator()->get('Resources', $config);
    }

    public function testSuccess()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $resources = $this->Resources->findIndex($userId)->all();
        $this->assertGreaterThan(1, count($resources));

        // Expected fields.
        $resource = $resources->first();
        $this->assertResourceAttributes($resource);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('secrets', $resource);
        $this->assertObjectNotHasAttribute('creator', $resource);
        $this->assertObjectNotHasAttribute('modifier', $resource);
        $this->assertObjectNotHasAttribute('favorite', $resource);
    }

    public function testExcludeSoftDeletedResources()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testContainSecrets()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $options['contain']['secret'] = true;
        $resources = $this->Resources->findIndex($userId, $options)->all();
        $resource = $resources->first();

        // Expected fields.
        $this->assertResourceAttributes($resource);
        $this->assertObjectHasAttribute('secrets', $resource);
        $this->assertCount(1, $resource->secrets);
        $this->assertSecretAttributes($resource->secrets[0]);
    }

    public function testContainCreator()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $options['contain']['creator'] = true;
        $resources = $this->Resources->findIndex($userId, $options)->all();
        $resource = $resources->first();

        // Expected fields.
        $this->assertResourceAttributes($resource);
        $this->assertObjectHasAttribute('creator', $resource);
        $this->assertUserAttributes($resource->creator);
    }

    public function testContainModifier()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $options['contain']['modifier'] = true;
        $resources = $this->Resources->findIndex($userId, $options)->all();
        $resource = $resources->first();

        // Expected fields.
        $this->assertResourceAttributes($resource);
        $this->assertObjectHasAttribute('modifier', $resource);
        $this->assertUserAttributes($resource->modifier);
    }

    public function testContainFavorite()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $options['contain']['favorite'] = true;
        $resources = $this->Resources->findIndex($userId, $options)->all();
        $resource = $resources->first();

        // Expected fields.
        $this->assertResourceAttributes($resource);
        $this->assertObjectHasAttribute('favorite', $resource);
        $this->assertFavoriteAttributes($resource->favorite);
    }

    public function testContainPermission()
    {
        $findIndexOptions['contain']['permission'] = true;
        $permissionsMatrix = PermissionMatrix::getCalculatedUsersResourcesPermissions('user');
        foreach ($permissionsMatrix as $userAlias => $usersExpectedPermissions) {
            // Find all the resources for the current user.
            $userId = UuidFactory::uuid("user.id.$userAlias");
            $resources = $this->Resources->findIndex($userId, $findIndexOptions)->all();

            // Check expected permissions are there.
            foreach ($usersExpectedPermissions as $resourceAlias => $expectedPermissionType) {
                $resourceId = UuidFactory::uuid("resource.id.$resourceAlias");
                $resource = @Hash::extract($resources->toArray(), "{n}[id=$resourceId]")[0]; // phpcs:ignore
                if ($expectedPermissionType == 0) {
                    $this->assertEmpty($resource, "$userAlias should not have a permission [$expectedPermissionType] for $resourceAlias");
                } else {
                    $this->assertNotEmpty($resource, "$userAlias should have a permission [$expectedPermissionType] for $resourceAlias");
                    $this->assertPermissionAttributes($resource->permission);
                    $this->assertEquals($expectedPermissionType, $resource->permission->type, "$userAlias should have a permission [$expectedPermissionType] for $resourceAlias");
                }
            }
        }
    }

    public function testFilterIsFavorite()
    {
        $userId = UuidFactory::uuid('user.id.dame');
        $options['filter']['is-favorite'] = true;
        $resources = $this->Resources->findIndex($userId, $options)->all();

        // Check that the result contain only the expected favorite resources.
        $favoriteResourcesIds = $resources->reduce(function ($result, $row) {
            $result[] = $row->id;

            return $result;
        }, []);
        $expectedResources = [UuidFactory::uuid('resource.id.apache'), UuidFactory::uuid('resource.id.april')];
        $this->assertEquals(0, count(array_diff($expectedResources, $favoriteResourcesIds)));
    }

    public function testFilterIsNotFavorite()
    {
        $userId = UuidFactory::uuid('user.id.dame');
        $options['filter']['is-favorite'] = false;
        $resources = $this->Resources->findIndex($userId, $options)->all();

        // Check that the result contain only the expected favorite resources.
        $favoriteResourcesIds = $resources->reduce(function ($result, $row) {
            $result[] = $row->id;

            return $result;
        }, []);
        $expectedResources = [UuidFactory::uuid('resource.id.apache'), UuidFactory::uuid('resource.id.april')];
        $this->assertEquals(0, count(array_intersect($expectedResources, $favoriteResourcesIds)));
    }

    public function testFilterIsSharedWithGroup()
    {
        $permissionsMatrix = PermissionMatrix::getGroupsResourcesPermissions('group');
        $userId = UuidFactory::uuid('user.id.jean');
        $groupFId = UuidFactory::uuid('group.id.freelancer');

        // Filter resources which are shared with the target group;
        $options['filter']['is-shared-with-group'] = $groupFId;
        $resourcesIds = $this->Resources->findIndex($userId, $options)
            ->extract('id')
            ->toArray();
        sort($resourcesIds);

        // Extract the resource the group should have access.
        $expectedResourcesIds = [];
        foreach ($permissionsMatrix['freelancer'] as $resourceAlias => $resourcePermission) {
            if ($resourcePermission > 0) {
                $expectedResourcesIds[] = UuidFactory::uuid("resource.id.$resourceAlias");
            }
        }
        sort($expectedResourcesIds);

        $this->assertCount(count($expectedResourcesIds), $resourcesIds);
        $this->assertEmpty(array_diff($expectedResourcesIds, $resourcesIds));
    }

    public function testFilterIsOwnedByMe()
    {
        $permissionsMatrix = PermissionMatrix::getCalculatedUsersResourcesPermissions('user');
        $userId = UuidFactory::uuid('user.id.ada');

        // Filter resources which are shared with the target group;
        $options['filter']['is-owned-by-me'] = 1;
        $resourcesIds = $this->Resources->findIndex($userId, $options)
            ->extract('id')
            ->toArray();
        sort($resourcesIds);

        $expectedResourcesIds = [];
        foreach ($permissionsMatrix['ada'] as $resourceAlias => $resourcePermission) {
            if ($resourcePermission == 15) {
                $expectedResourcesIds[] = UuidFactory::uuid("resource.id.$resourceAlias");
            }
        }
        sort($expectedResourcesIds);

        $this->assertCount(count($expectedResourcesIds), $resourcesIds);
        $this->assertEmpty(array_diff($expectedResourcesIds, $resourcesIds));
    }

    public function testFilterIsSharedWithMe()
    {
        $permissionsMatrix = PermissionMatrix::getCalculatedUsersResourcesPermissions('user');
        $userId = UuidFactory::uuid('user.id.ada');

        // Filter resources which are shared with the target group;
        $options['filter']['is-shared-with-me'] = 1;
        $resourcesIds = $this->Resources->findIndex($userId, $options)
            ->extract('id')
            ->toArray();
        sort($resourcesIds);

        // Get all resources with permissions.
        $expectedResourcesIds = [];
        foreach ($permissionsMatrix['ada'] as $resourceAlias => $resourcePermission) {
            if ($resourcePermission >= Permission::READ && $resourcePermission < Permission::OWNER) {
                $expectedResourcesIds[] = UuidFactory::uuid("resource.id.$resourceAlias");
            }
        }
        sort($expectedResourcesIds);

        $this->assertEquals($resourcesIds, $expectedResourcesIds);
    }

    public function testPermissions()
    {
        $permissionsMatrix = PermissionMatrix::getCalculatedUsersResourcesPermissions('user');
        foreach ($permissionsMatrix as $userAlias => $usersExpectedPermissions) {
            $expectedResourcesIds = array_reduce(array_keys($usersExpectedPermissions), function ($result, $key) use ($usersExpectedPermissions) {
                if ($usersExpectedPermissions[$key] == 0) {
                    return $result;
                }
                $result[] = UuidFactory::uuid("resource.id.$key");

                return $result;
            }, []);

            // Find all the resources for the current user.
            $userId = UuidFactory::uuid("user.id.$userAlias");
            $resources = $this->Resources->findIndex($userId)->all();
            $resourcesIds = $resources->reduce(function ($result, $row) {
                $result[] = $row->id;

                return $result;
            }, []);

            $this->assertEmpty(array_diff($expectedResourcesIds, $resourcesIds), "There is a problem with the permissions of $userAlias");
            $this->assertEmpty(array_diff($resourcesIds, $expectedResourcesIds), "There is a problem with the permissions of $userAlias");
        }
    }

    public function testErrorInvalidUserIdParameter()
    {
        try {
            $this->Resources->findIndex('not-valid');
        } catch (\InvalidArgumentException $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }
}
