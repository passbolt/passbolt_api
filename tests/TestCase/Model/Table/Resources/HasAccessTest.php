<?php
namespace App\Test\TestCase\Model\Table\Resources;

use App\Model\Table\ResourcesTable;
use App\Test\Lib\AppTestCase;
use App\Utility\Common;
use Cake\ORM\TableRegistry;
use PassboltTestData\Lib\PermissionMatrix;

class HasAccessTest extends AppTestCase
{
    public $Resources;

    public $fixtures = ['app.users', 'app.groups', 'app.groups_users', 'app.resources', 'app.permissions'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Resources') ? [] : ['className' => ResourcesTable::class];
        $this->Resources = TableRegistry::get('Resources', $config);
    }

    public function tearDown()
    {
        unset($this->Resources);

        parent::tearDown();
    }

    public function testPermissions()
    {
        $permissionsMatrix = PermissionMatrix::getCalculatedUsersResourcesPermissions('user');
        foreach ($permissionsMatrix as $userAlias => $usersExpectedPermissions) {
            $userId = Common::uuid("user.id.$userAlias");
            foreach ($usersExpectedPermissions as $resourceAlias => $permissionType) {
                $resourceId = Common::uuid("resource.id.$resourceAlias");
                $hasAccess = $this->Resources->hasAccess($userId, $resourceId);
                if ($permissionType  == 0) {
                    $this->assertFalse($hasAccess);
                } else {
                    $this->assertTrue($hasAccess);
                }
            }
        }
    }

    public function testErrorInvalidArgumentUserId()
    {
        try {
            $this->Resources->hasAccess('not-valid', Common::uuid());
        } catch (\InvalidArgumentException $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }

    public function testErrorInvalidArgumentResourceId()
    {
        try {
            $this->Resources->hasAccess(Common::uuid(), 'not-valid');
        } catch (\InvalidArgumentException $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }
}
