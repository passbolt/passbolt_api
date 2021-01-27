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
 * @since         2.13.0
 */

namespace App\Test\TestCase\Service\Permissions;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Service\Permissions\PermissionsUpdatePermissionsService;
use App\Test\Lib\AppTestCase;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * \App\Test\TestCase\Service\Permissions\PermissionsUpdatePermissionsService Test Case
 *
 * @covers \App\Test\TestCase\Service\Permissions\PermissionsUpdatePermissionsService
 */
class PermissionsUpdatePermissionsServiceTest extends AppTestCase
{
    /**
     * @var ResourcesTable
     */
    public $Resources;

    /**
     * @var PermissionsTable
     */
    public $Permissions;

    /**
     * @var PermissionsUpdatePermissionsServiceTest
     */
    public $service;

    public $fixtures = [
        'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Permissions', 'app.Base/Resources', 'app.Base/Secrets',
        'app.Base/Users',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->service = new PermissionsUpdatePermissionsService();
    }

    /* UPDATE PERMISSION */

    public function testUpdatePermissionsSuccess_UpdateUserPermissions()
    {
        [$resource1, $userAId, $userBId] = $this->insertFixture_UpdatePermissionsSuccess_UpdateUserPermissions();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $data = [
            ['id' => UuidFactory::uuid("permission.id.{$resource1->id}-$userBId"), 'type' => Permission::READ],
        ];

        $this->service->updatePermissions($uac, 'Resource', $resource1->id, $data);

        // Assert permissions
        $permissions = $this->Permissions->findByAcoForeignKey($resource1->id)->toArray();
        $this->assertCount(2, $permissions);
        $this->assertPermission($resource1->id, $userAId, Permission::OWNER);
        $this->assertPermission($resource1->id, $userBId, Permission::READ);
    }

    private function insertFixture_UpdatePermissionsSuccess_UpdateUserPermissions()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $for = [$userAId => Permission::OWNER, $userBId => Permission::OWNER];
        $resource1 = $this->addResourceFor(['name' => 'R1'], $for);

        return [$resource1, $userAId, $userBId];
    }

    public function testUpdatePermissionsSuccess_UpdateGroupPermissions()
    {
        [$resource1, $userAId, $groupAId] = $this->insertFixture_UpdatePermissionsSuccess_UpdateGroupPermissions();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $data = [
            ['id' => UuidFactory::uuid("permission.id.{$resource1->id}-$groupAId"), 'type' => Permission::READ],
        ];

        $this->service->updatePermissions($uac, 'Resource', $resource1->id, $data);

        // Assert permissions
        $permissions = $this->Permissions->findByAcoForeignKey($resource1->id)->toArray();
        $this->assertCount(2, $permissions);
        $this->assertPermission($resource1->id, $userAId, Permission::OWNER);
        $this->assertPermission($resource1->id, $groupAId, Permission::READ);
    }

    private function insertFixture_UpdatePermissionsSuccess_UpdateGroupPermissions()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $groupAId = UuidFactory::uuid('group.id.accounting');
        $resource1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER], [$groupAId => Permission::OWNER]);

        return [$resource1, $userAId, $groupAId];
    }

    public function testUpdatePermissionsError_UpdateUserPermission_CannotUpdateNotExistingPermission()
    {
        [$resource1, $userAId] = $this->insertFixture_UpdatePermissionsSuccess_UpdateUserPermissions();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $data = [
            ['id' => UuidFactory::uuid(), 'type' => Permission::OWNER],
        ];

        try {
            $this->service->updatePermissions($uac, 'Resource', $resource1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, '0.id.exists');
        }
    }

    private function assertUpdatePermissionsValidationException(CustomValidationException $e, string $errorFieldName)
    {
        $this->assertEquals('Could not validate permissions data.', $e->getMessage());
        $error = Hash::get($e->getErrors(), $errorFieldName);
        $this->assertNotNull($error, "Expected error not found : {$errorFieldName}. Errors: " . json_encode($e->getErrors()));
    }

    public function testUpdatePermissionsError_UpdateUserPermission_CannotUpdateOtherResourcePermission()
    {
        [$resource1, $resource2, $userAId] = $this->insertFixture_UpdatePermissionsError_UpdateUserPermission_CannotUpdateOtherResourcePermission();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $data = [
            ['id' => UuidFactory::uuid("permission.id.{$resource2->id}-$userAId"), 'type' => Permission::OWNER],
        ];

        try {
            $this->service->updatePermissions($uac, 'Resource', $resource1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, '0.id.exists');
        }
    }

    private function insertFixture_UpdatePermissionsError_UpdateUserPermission_CannotUpdateOtherResourcePermission()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $resource1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER, $userBId => Permission::READ]);
        $resource2 = $this->addResourceFor(['name' => 'R2'], [$userAId => Permission::OWNER, $userBId => Permission::READ]);

        return [$resource1, $resource2, $userAId, $userBId];
    }

    public function testUpdatePermissionsError_UpdateUserPermission_CannotLetResourceWithoutOwner()
    {
        [$resource1, $userAId] = $this->insertFixture_UpdateUserPermission_CannotLetResourceWithoutOwner();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $data = [
            ['id' => UuidFactory::uuid("permission.id.{$resource1->id}-$userAId"), 'type' => Permission::READ],
        ];

        try {
            $this->service->updatePermissions($uac, 'Resource', $resource1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, 'at_least_one_owner');
        }
    }

    private function insertFixture_UpdateUserPermission_CannotLetResourceWithoutOwner()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $for = [$userAId => Permission::OWNER, $userBId => Permission::READ];
        $resource1 = $this->addResourceFor(['name' => 'R1'], $for);

        return [$resource1, $userAId, $userBId];
    }

    public function testUpdatePermissionsError_UpdateUserPermission_PermissionValidationExceptions()
    {
        [$resource1, $userAId, $userBId] = $this->insertFixture_UpdateUserPermission_PermissionValidationExceptions();
        $uac = new UserAccessControl(Role::USER, $userAId);

        // Type is tested
        $data = [
            ['id' => UuidFactory::uuid("permission.id.{$resource1->id}-$userBId"), 'type' => 10000],
        ];
        try {
            $this->service->updatePermissions($uac, 'Resource', $resource1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, '0.type.inList');
        }
    }

    private function insertFixture_UpdateUserPermission_PermissionValidationExceptions()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $for = [$userAId => Permission::OWNER, $userBId => Permission::READ];
        $resource1 = $this->addResourceFor(['name' => 'R1'], $for);

        return [$resource1, $userAId, $userBId];
    }

    /* DELETE PERMISSION */

    public function testUpdatePermissionsSuccess_DeleteUserPermissions()
    {
        [$resource1, $userAId, $userBId] = $this->insertFixture_UpdatePermissionsSuccess_DeleteUserPermissions();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $data = [
            ['id' => UuidFactory::uuid("permission.id.{$resource1->id}-$userBId"), 'delete' => true],
        ];

        $this->service->updatePermissions($uac, 'Resource', $resource1->id, $data);

        // Assert permissions
        $permissions = $this->Permissions->findByAcoForeignKey($resource1->id)->toArray();
        $this->assertCount(1, $permissions);
        $this->assertPermission($resource1->id, $userAId, Permission::OWNER);
    }

    private function insertFixture_UpdatePermissionsSuccess_DeleteUserPermissions()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $resource1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER, $userBId => Permission::READ]);

        return [$resource1, $userAId, $userBId];
    }

    public function testUpdatePermissionsSuccess_DeleteGroupPermissions()
    {
        [$resource1, $userAId, $groupAId] = $this->insertFixture_UpdatePermissionsSuccess_DeleteGroupPermissions();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $data = [
            ['id' => UuidFactory::uuid("permission.id.{$resource1->id}-$groupAId"), 'delete' => true],
        ];

        $this->service->updatePermissions($uac, 'Resource', $resource1->id, $data);

        // Assert permissions
        $permissions = $this->Permissions->findByAcoForeignKey($resource1->id)->toArray();
        $this->assertCount(1, $permissions);
        $this->assertPermission($resource1->id, $userAId, Permission::OWNER);
    }

    private function insertFixture_UpdatePermissionsSuccess_DeleteGroupPermissions()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $groupAId = UuidFactory::uuid('group.id.accounting');
        $resource1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER], [$groupAId => Permission::READ]);

        return [$resource1, $userAId, $groupAId];
    }

    public function testUpdatePermissionsError_DeleteUserPermission_CannotUpdateNotExistingPermission()
    {
        [$resource1, $userAId] = $this->insertFixture_UpdatePermissionsSuccess_DeleteUserPermissions();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $data = [
            ['id' => UuidFactory::uuid(), 'delete' => true],
        ];

        try {
            $this->service->updatePermissions($uac, 'Resource', $resource1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, '0.id.exists');
        }
    }

    public function testUpdatePermissionsError_DeleteUserPermission_CannotUpdateOtherResourcePermission()
    {
        [$resource1, $resource2, $userAId] = $this->insertFixture_UpdatePermissionsError_DeleteUserPermission_CannotUpdateOtherResourcePermission();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $data = [
            ['id' => UuidFactory::uuid("permission.id.{$resource2->id}-$userAId"), 'delete' => true],
        ];

        try {
            $this->service->updatePermissions($uac, 'Resource', $resource1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, '0.id.exists');
        }
    }

    private function insertFixture_UpdatePermissionsError_DeleteUserPermission_CannotUpdateOtherResourcePermission()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $resource1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER, $userBId => Permission::READ]);
        $resource2 = $this->addResourceFor(['name' => 'R2'], [$userAId => Permission::OWNER, $userBId => Permission::READ]);

        return [$resource1, $resource2, $userAId, $userBId];
    }

    public function testUpdatePermissionsError_DeleteUserPermission_CannotLetResourceWithoutOwner()
    {
        [$resource1, $userAId] = $this->insertFixture_DeleteUserPermission_CannotLetResourceWithoutOwner();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $data = [
            ['id' => UuidFactory::uuid("permission.id.{$resource1->id}-$userAId"), 'delete' => true],
        ];

        try {
            $this->service->updatePermissions($uac, 'Resource', $resource1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, 'at_least_one_owner');
        }
    }

    private function insertFixture_DeleteUserPermission_CannotLetResourceWithoutOwner()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $for = [$userAId => Permission::OWNER, $userBId => Permission::READ];
        $resource1 = $this->addResourceFor(['name' => 'R1'], $for);

        return [$resource1, $userAId, $userBId];
    }

    /* ADD PERMISSION */

    public function testUpdatePermissionsSuccess_AddUserPermissions()
    {
        [$resource1, $userAId] = $this->insertFixture_UpdatePermissionsSuccess_AddUserPermissions();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userBId = UuidFactory::uuid('user.id.betty');
        $data = [
            ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::READ],
        ];

        $this->service->updatePermissions($uac, 'Resource', $resource1->id, $data);

        // Assert permissions
        $permissions = $this->Permissions->findByAcoForeignKey($resource1->id)->toArray();
        $this->assertCount(2, $permissions);
        $this->assertPermission($resource1->id, $userAId, Permission::OWNER);
        $this->assertPermission($resource1->id, $userBId, Permission::READ);
    }

    private function insertFixture_UpdatePermissionsSuccess_AddUserPermissions()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $resource1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER]);

        return [$resource1, $userAId];
    }

    public function testUpdatePermissionsSuccess_AddGroupPermissions()
    {
        [$resource1, $userAId] = $this->insertFixture_UpdatePermissionsSuccess_AddGroupPermissions();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $groupAId = UuidFactory::uuid('group.id.accounting');
        $data = [
            ['aro' => 'Group', 'aro_foreign_key' => $groupAId, 'type' => Permission::READ],
        ];

        $this->service->updatePermissions($uac, 'Resource', $resource1->id, $data);

        // Assert permissions
        $permissions = $this->Permissions->findByAcoForeignKey($resource1->id)->toArray();
        $this->assertCount(2, $permissions);
        $this->assertPermission($resource1->id, $userAId, Permission::OWNER);
        $this->assertPermission($resource1->id, $groupAId, Permission::READ);
    }

    private function insertFixture_UpdatePermissionsSuccess_AddGroupPermissions()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $resource1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER]);

        return [$resource1, $userAId];
    }

    public function testUpdatePermissionsError_AddUserPermission_PermissionValidationExceptions()
    {
        [$resource1, $userAId] = $this->insertFixture_AddUserPermission_PermissionValidationExceptions();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userBId = UuidFactory::uuid('user.id.betty');

        // Permission aro is tested
        $data = [['aro' => '', 'aro_foreign_key' => $userBId, 'type' => Permission::OWNER]];
        try {
            $this->service->updatePermissions($uac, 'Resource', $resource1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, '0.aro._empty');
        }

        // Permission aro foreign key is tested
        $data = [['aro' => 'User', 'aro_foreign_key' => UuidFactory::uuid(), 'type' => Permission::OWNER]];
        try {
            $this->service->updatePermissions($uac, 'Resource', $resource1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, '0.aro_foreign_key._existsIn');
        }

        // Type is tested
        $data = [['aro' => 'User', 'aro_foreign_key' => UuidFactory::uuid(), 'type' => 42]];
        try {
            $this->service->updatePermissions($uac, 'Resource', $resource1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, '0.type.inList');
        }
    }

    private function insertFixture_AddUserPermission_PermissionValidationExceptions()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $for = [$userAId => Permission::OWNER];
        $resource1 = $this->addResourceFor(['name' => 'R1'], $for);

        return [$resource1, $userAId];
    }
}
