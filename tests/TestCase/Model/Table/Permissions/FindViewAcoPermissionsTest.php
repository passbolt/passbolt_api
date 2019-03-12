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

namespace App\Test\TestCase\Model\Table\Permissions;

use App\Model\Table\PermissionsTable;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use PassboltTestData\Lib\PermissionMatrix;

class FindViewAcoPermissionsTest extends AppTestCase
{
    public $fixtures = ['app.Base/Groups', 'app.Base/Permissions', 'app.Base/Profiles', 'app.Base/Resources', 'app.Base/Users'];

    /**
     * Test subject
     *
     * @var \App\Model\Table\PermissionsTable
     */
    public $Permissions;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Permissions') ? [] : ['className' => PermissionsTable::class];
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions', $config);
    }

    public function testContainUser()
    {
        $resourceId = UuidFactory::uuid('resource.id.debian');
        $options['contain']['user'] = true;
        $permissions = $this->Permissions->findViewAcoPermissions($resourceId, $options)->all();

        // retrieve a direct user permission
        $permission = Hash::extract($permissions->toArray(), "{n}[aro=User]")[0];
        $this->assertPermissionAttributes($permission);
        $this->assertUserAttributes($permission->user);
    }

    public function testContainUserProfile()
    {
        $resourceId = UuidFactory::uuid('resource.id.debian');
        $options['contain']['user.profile'] = true;
        $permissions = $this->Permissions->findViewAcoPermissions($resourceId, $options)->all();

        // retrieve a direct user permission
        $permission = Hash::extract($permissions->toArray(), "{n}[aro=User]")[0];
        $this->assertPermissionAttributes($permission);
        $this->assertProfileAttributes($permission->user->profile);
    }

    public function testContainUserProfileAvatar()
    {
        $this->markTestIncomplete();
    }

    public function testPermissions()
    {
        $resources = $this->Permissions->getAssociation('Resources')->find()->all();
        foreach ($resources as $resource) {
            // Retrieve the expected users & groups permissions for the resource.
            $expectedUsersResourcesPermissions = PermissionMatrix::getUsersResourcePermissions($resource->id);
            $this->assertNotNull($expectedUsersResourcesPermissions, "A resource [$resource->name] is missing in the users resources permissions matrix.");
            $expectedGroupsResourcesPermissions = PermissionMatrix::getGroupsResourcePermissions($resource->id);
            $this->assertNotNull($expectedGroupsResourcesPermissions, "A resource [$resource->name] is missing in the groups resources permissions matrix.");

            // Retrieve the permissions from passbolt.
            $permissions = $this->Permissions->findViewAcoPermissions($resource->id)->all();

            // Check the users permissions.
            foreach ($expectedUsersResourcesPermissions as $userAlias => $permissionType) {
                $userId = UuidFactory::uuid("user.id.$userAlias");
                $extractedPermissions = Hash::extract($permissions->toArray(), "{n}[aco=Resource][aco_foreign_key=$resource->id][aro=User][aro_foreign_key=$userId]");
                if ($permissionType == 0) {
                    $this->assertEmpty($extractedPermissions, "No permission should be defined for the user [$userAlias] and the resource [$resource->id]");
                } else {
                    $this->assertNotEmpty($extractedPermissions, "A permission should be defined for the user [$userAlias] and the resource [$resource->id]");
                    $this->assertCount(1, $extractedPermissions);
                    $this->assertEquals($permissionType, $extractedPermissions[0]->type);
                }
            }

            // Check the groups permissions.
            foreach ($expectedGroupsResourcesPermissions as $groupAlias => $permissionType) {
                $groupId = UuidFactory::uuid("group.id.$groupAlias");
                $extractedPermissions = Hash::extract($permissions->toArray(), "{n}[aco=Resource][aco_foreign_key=$resource->id][aro=Group][aro_foreign_key=$groupId]");
                if ($permissionType == 0) {
                    $this->assertEmpty($extractedPermissions, "No permission should be defined for the group [$userAlias] and the resource [$resource->id]");
                } else {
                    $this->assertNotEmpty($extractedPermissions, "A permission should be defined for the group [$userAlias] and the resource [$resource->id]");
                    $this->assertCount(1, $extractedPermissions);
                    $this->assertEquals($permissionType, $extractedPermissions[0]->type);
                }
            }
        }
    }

    public function testErrorInvalidAcoForeignKeyParameter()
    {
        try {
            $this->Permissions->findViewAcoPermissions('not-valid');
        } catch (\Exception $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }
}
