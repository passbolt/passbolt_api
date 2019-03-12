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

use App\Model\Table\ResourcesTable;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use PassboltTestData\Lib\PermissionMatrix;

class FindViewTest extends AppTestCase
{
    public $fixtures = ['app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Secrets', 'app.Base/Favorites', 'app.Base/Permissions'];

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
        $config = TableRegistry::getTableLocator()->exists('Resources') ? [] : ['className' => ResourcesTable::class];
        $this->Resources = TableRegistry::getTableLocator()->get('Resources', $config);
    }

    public function testSuccess()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $resourceId = UuidFactory::uuid('resource.id.apache');
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
            $userId = UuidFactory::uuid("user.id.$userAlias");
            foreach ($usersExpectedPermissions as $resourceAlias => $permissionType) {
                $resourceId = UuidFactory::uuid("resource.id.$resourceAlias");
                $resource = $this->Resources->findView($userId, $resourceId)->first();
                if ($permissionType == 0) {
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
            $this->Resources->findView('not-valid', UuidFactory::uuid());
        } catch (\Exception $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }

    public function testErrorInvalidResourceIdParameter()
    {
        try {
            $this->Resources->findView(UuidFactory::uuid(), 'not-valid');
        } catch (\Exception $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }
}
