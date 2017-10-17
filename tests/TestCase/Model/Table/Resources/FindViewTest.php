<?php
namespace App\Test\TestCase\Model\Table\Resources;

use App\Model\Table\ResourcesTable;
use App\Test\Lib\AppTestCase;
use App\Utility\Common;
use Cake\ORM\TableRegistry;
use PassboltTestData\Lib\PermissionMatrix;

class FindViewTest extends AppTestCase
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

    public function testSuccess()
    {
        $userId = Common::uuid('user.id.ada');
        $resourceId =  Common::uuid('resource.id.apache');
        $resources = $this->Resources->findView($userId, $resourceId);

        // Expected fields.
        $resource = $resources->first();
        $this->assertResourceAttributes($resource);
        $this->assertEquals($resourceId, $resource->id);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('secrets', $resource);
        $this->assertObjectNotHasAttribute('creator', $resource);
        $this->assertObjectNotHasAttribute('modifier', $resource);
        $this->assertObjectNotHasAttribute('favorite', $resource);
    }

    public function testPermissions()
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

    public function testErrorInvalidUserIdParameter()
    {
        try {
            $this->Resources->findView('not-valid', Common::uuid());
        } catch (\Exception $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }

    public function testErrorInvalidResourceIdParameter()
    {
        try {
            $this->Resources->findView(Common::uuid(), 'not-valid');
        } catch (\Exception $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }
}
