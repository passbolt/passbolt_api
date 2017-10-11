<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ResourcesTable;
use App\Test\TestCase\ApplicationTest;
use App\Utility\Common;
use Cake\ORM\TableRegistry;
use PassboltTestData\Lib\PermissionMatrix;

/**
 * App\Model\Table\ResourcesTable Test Case
 */
class ResourcesTableTest extends ApplicationTest
{
    public $fixtures = ['app.users', 'app.groups', 'app.groups_users', 'app.resources', 'app.secrets', 'app.favorites', 'app.permissions'];

    /**
     * Test subject
     *
     * @var \App\Model\Table\ResourcesTable
     */
    public $Resources;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Resources') ? [] : ['className' => ResourcesTable::class];
        $this->Resources = TableRegistry::get('Resources', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Resources);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testFindIndexSuccess()
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

    public function testFindIndexSuccessContainsSecrets()
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

    public function testFindIndexSuccessContainsCreator()
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

    public function testFindIndexSuccessContainsModifier()
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

    public function testFindIndexSuccessContainsFavorite()
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

    public function testFindIndexSuccessFilterOnFavorite()
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

    public function testFindIndexSuccessFilterOutFavorite()
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

    public function testFindIndexAndPermissions()
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

    public function testFindIndexErrorInvalidUserIdParameter()
    {
        try {
            $this->Resources->findIndex('not-valid');
        } catch (\Exception $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }

    public function testFindViewErrorInvalidUserIdParameter()
    {
        try {
            $this->Resources->findView('not-valid', Common::uuid());
        } catch (\Exception $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }

    public function testFindViewSuccess()
    {
        $userId = Common::uuid('user.id.ada');
        $resourceId =  Common::uuid('resource.id.apache');
        $resources = $this->Resources->findView($userId, $resourceId);

        // Expected fields.
        $resource = $resources->first();
        $this->assertResourceAttributes($resource);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('secrets', $resource);
        $this->assertObjectNotHasAttribute('creator', $resource);
        $this->assertObjectNotHasAttribute('modifier', $resource);
        $this->assertObjectNotHasAttribute('favorite', $resource);
    }

    public function testFindViewAndPermissions()
    {
        $permissionsMatrix = PermissionMatrix::getCalculatedUsersResourcesPermissions('user');
        foreach ($permissionsMatrix as $userAlias => $usersExpectedPermissions) {
            $userId = Common::uuid("user.id.$userAlias");
            foreach ($usersExpectedPermissions as $resourceAlias => $permissionType) {
                $resourceId = Common::uuid("resource.id.$resourceAlias");
                $resource = $this->Resources->findView($userId, $resourceId)->first();
                if ($permissionType  == 0) {
                    $this->assertNull($resource);
                } else {
                    $this->assertNotNull($resource);
                }
            }
        }
    }

    public function testFindViewErrorInvalidResourceIdParameter()
    {
        try {
            $this->Resources->findView(Common::uuid(), 'not-valid');
        } catch (\Exception $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }
}
