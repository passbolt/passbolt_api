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

namespace App\Test\TestCase\Model\Table\Resources;

use App\Model\Table\ResourcesTable;
use App\Test\Lib\AppTestCase;
use App\Utility\Common;
use Cake\ORM\TableRegistry;
use PassboltTestData\Lib\PermissionMatrix;

class FindIndexTest extends AppTestCase
{
    public $Resources;

    public $fixtures = ['app.users', 'app.groups', 'app.groups_users', 'app.resources', 'app.secrets', 'app.favorites', 'app.permissions'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Resources') ? [] : ['className' => ResourcesTable::class];
        $this->Resources = TableRegistry::get('Resources', $config);
    }

    public function testSuccess()
    {
        $userId = Common::uuid('user.id.ada');
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
        $userId = Common::uuid('user.id.ada');
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
        $userId = Common::uuid('user.id.ada');
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
        $userId = Common::uuid('user.id.ada');
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
        $userId = Common::uuid('user.id.ada');
        $options['contain']['favorite'] = true;
        $resources = $this->Resources->findIndex($userId, $options)->all();
        $resource = $resources->first();

        // Expected fields.
        $this->assertResourceAttributes($resource);
        $this->assertObjectHasAttribute('favorite', $resource);
        $this->assertFavoriteAttributes($resource->favorite);
    }

    public function testFilterIsFavorite()
    {
        $userId = Common::uuid('user.id.dame');
        $options['filter']['is-favorite'] = true;
        $resources = $this->Resources->findIndex($userId, $options)->all();

        // Check that the result contain only the expected favorite resources.
        $favoriteResourcesIds = $resources->reduce(function($result, $row) {
            $result[] = $row->id;
            return $result;
        }, []);
        $expectedResources = [Common::uuid('resource.id.apache'), Common::uuid('resource.id.april')];
        $this->assertEquals(0, count(array_diff($expectedResources, $favoriteResourcesIds)));
    }

    public function testFilterIsNotFavorite()
    {
        $userId = Common::uuid('user.id.dame');
        $options['filter']['is-favorite'] = false;
        $resources = $this->Resources->findIndex($userId, $options)->all();

        // Check that the result contain only the expected favorite resources.
        $favoriteResourcesIds = $resources->reduce(function($result, $row) {
            $result[] = $row->id;
            return $result;
        }, []);
        $expectedResources = [Common::uuid('resource.id.apache'), Common::uuid('resource.id.april')];
        $this->assertEquals(0, count(array_intersect($expectedResources, $favoriteResourcesIds)));
    }

    public function testPermissions()
    {
        $permissionsMatrix = PermissionMatrix::getCalculatedUsersResourcesPermissions('user');
        foreach ($permissionsMatrix as $userAlias => $usersExpectedPermissions) {
            $expectedResourcesIds = array_reduce(array_keys($usersExpectedPermissions), function($result, $key) use($usersExpectedPermissions) {
                if ($usersExpectedPermissions[$key] == 0) return $result;
                $result[] = Common::uuid("resource.id.$key");
                return $result;
            }, []);

            // Find all the resources for the current user.
            $userId = Common::uuid("user.id.$userAlias");
            $resources = $this->Resources->findIndex($userId)->all();
            $resourcesIds = $resources->reduce(function($result, $row) {
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
