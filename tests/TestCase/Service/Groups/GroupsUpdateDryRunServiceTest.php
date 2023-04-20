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

namespace App\Test\TestCase\Service\Groups;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Service\Groups\GroupsUpdateDryRunService;
use App\Test\Lib\AppTestCase;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * \App\Service\Groups\GroupsUpdateDryRunService Test Case
 *
 * @covers \App\Service\Groups\GroupsUpdateDryRunService
 */
class GroupsUpdateDryRunServiceTest extends AppTestCase
{
    public $fixtures = [
        'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Permissions',
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles',
        'app.Base/Favorites',
    ];

    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @var \App\Model\Table\SecretsTable
     */
    private $secretsTable;

    /**
     * @var GroupsUpdateDryRunService
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->secretsTable = TableRegistry::getTableLocator()->get('Secrets');
        $this->service = new GroupsUpdateDryRunService();
    }

    /* ADD/UPDATE/DELETE GROUP USER */

    public function testUpdateDryRunSuccess_AddUpdateRemoveGroupUsers_HavingMultipleResourcesSharedWith()
    {
        [$r1, $r2, $g1, $userAId, $userBId] = $this->insertFixture_AddUpdateRemoveGroupUsers_HavingMultipleResourcesSharedWith();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userAGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userAId)->first()->id;
        $userBGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userBId)->first()->id;
        $userCId = UuidFactory::uuid('user.id.carol');
        $userDId = UuidFactory::uuid('user.id.dame');
        $data = [
            ['id' => $userAGroupUserId, 'delete' => true],
            ['id' => $userBGroupUserId, 'is_admin' => false],
            ['user_id' => $userCId, 'is_admin' => true],
            ['user_id' => $userDId, 'is_admin' => false],
        ];

        $result = $this->service->dryRun($uac, $g1->id, $data);

        // Assert the changes have not been applied.
        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsMemberOf($g1->id, $userBId, true);
        $this->assertUserIsNotMemberOf($g1->id, $userCId);
        $this->assertUserIsNotMemberOf($g1->id, $userDId);

        // Assert the service result.
        $operatorSecret = $this->secretsTable->findByResourceIdAndUserId($r1->id, $userAId)->first();
        $this->assertCount(2, $result['secrets']);
        $this->assertEquals($operatorSecret->data, $result['secrets'][0]['data']);
        $this->assertEquals($operatorSecret->data, $result['secrets'][1]['data']);
        $this->assertCount(4, $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r1->id, 'user_id' => $userCId], $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r1->id, 'user_id' => $userDId,], $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r2->id, 'user_id' => $userCId,], $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r2->id, 'user_id' => $userDId,], $result['secretsNeeded']);
    }

    private function insertFixture_AddUpdateRemoveGroupUsers_HavingMultipleResourcesSharedWith()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => true],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);
        $r2 = $this->addResourceFor(['name' => 'R2'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$r1, $r2, $g1, $userAId, $userBId];
    }

    /* ADD GROUP USER */

    public function testUpdateDryRunSuccess_AddGroupUser_HavingOneResourceSharedWith()
    {
        [$r1, $g1, $userAId, $userBId] = $this->insertFixture_AddGroupUser_HavingOneResourceSharedWith();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userCId = UuidFactory::uuid('user.id.carol');
        $userDId = UuidFactory::uuid('user.id.dame');
        $data = [
            ['user_id' => $userCId, 'is_admin' => true],
            ['user_id' => $userDId, 'is_admin' => false],
        ];

        $result = $this->service->dryRun($uac, $g1->id, $data);

        // Assert the changes have not been applied.
        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsMemberOf($g1->id, $userBId, false);
        $this->assertUserIsNotMemberOf($g1->id, $userCId);
        $this->assertUserIsNotMemberOf($g1->id, $userDId);

        // Assert the service result.
        $operatorSecret = $this->secretsTable->findByResourceIdAndUserId($r1->id, $userAId)->first();
        $this->assertCount(1, $result['secrets']);
        $this->assertEquals($operatorSecret->data, $result['secrets'][0]['data']);
        $this->assertCount(2, $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r1->id, 'user_id' => $userCId], $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r1->id, 'user_id' => $userDId], $result['secretsNeeded']);
    }

    private function insertFixture_AddGroupUser_HavingOneResourceSharedWith()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => false],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$r1, $g1, $userAId, $userBId];
    }

    public function testUpdateDryRunSuccess_AddGroupUser_HavingMultipleResourceSharedWith()
    {
        [$r1, $r2, $g1, $userAId, $userBId] = $this->insertFixture_AddGroupUser_HavingMultipleResourceSharedWith();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userCId = UuidFactory::uuid('user.id.carol');
        $userDId = UuidFactory::uuid('user.id.dame');
        $data = [
            ['user_id' => $userCId, 'is_admin' => true],
            ['user_id' => $userDId, 'is_admin' => false],
        ];

        $result = $this->service->dryRun($uac, $g1->id, $data);

        // Assert the changes have not been applied.
        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsMemberOf($g1->id, $userBId, false);
        $this->assertUserIsNotMemberOf($g1->id, $userCId);
        $this->assertUserIsNotMemberOf($g1->id, $userDId);

        // Assert the service result.
        $operatorSecret = $this->secretsTable->findByResourceIdAndUserId($r1->id, $userAId)->first();
        $this->assertCount(2, $result['secrets']);
        $this->assertEquals($operatorSecret->data, $result['secrets'][0]['data']);
        $this->assertEquals($operatorSecret->data, $result['secrets'][1]['data']);
        $this->assertCount(4, $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r1->id, 'user_id' => $userCId], $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r1->id, 'user_id' => $userDId], $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r2->id, 'user_id' => $userCId], $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r2->id, 'user_id' => $userDId], $result['secretsNeeded']);
    }

    private function insertFixture_AddGroupUser_HavingMultipleResourceSharedWith()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => false],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);
        $r2 = $this->addResourceFor(['name' => 'R2'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$r1, $r2, $g1, $userAId, $userBId];
    }

    public function testUpdateDryRunSuccess_AddGroupUser_UserHasAlreadyAccessToTheResource()
    {
        [$r1, $g1, $userAId, $userBId] = $this->insertFixture_AddGroupUser_UserHasAlreadyAccessToTheResource();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $data = [
            ['user_id' => $userBId, 'is_admin' => true],
        ];

        $result = $this->service->dryRun($uac, $g1->id, $data);

        // Assert the changes have not been applied.
        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsNotMemberOf($g1->id, $userBId);

        // Assert the service result.
        $this->assertCount(0, $result['secrets']);
        $this->assertCount(0, $result['secretsNeeded']);
    }

    private function insertFixture_AddGroupUser_UserHasAlreadyAccessToTheResource()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userBId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$r1, $g1, $userAId, $userBId];
    }

    public function testUpdateDryRunError_AddGroupUser_GroupUserValidation()
    {
        [$r1, $g1, $userAId, $userBId] = $this->insertFixture_AddGroupUser_HavingOneResourceSharedWith();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userCId = UuidFactory::uuid('user.id.carol');
        $data = [
            ['user_id' => $userCId, 'is_admin' => 42],
        ];

        try {
            $this->service->dryRun($uac, $g1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertUpdateDryRunValidationException($e, 'groups_users.0.is_admin.boolean');
        }

        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsMemberOf($g1->id, $userBId, false);
        $this->assertUserIsNotMemberOf($g1->id, $userCId);
    }

    private function assertUpdateDryRunValidationException(ValidationException $e, string $errorFieldName)
    {
        $this->assertEquals('Could not validate group data.', $e->getMessage());
        $error = Hash::get($e->getErrors(), $errorFieldName);
        $this->assertNotNull($error, "Expected error not found : {$errorFieldName}. Errors: " . json_encode($e->getErrors()));
    }

    public function testUpdateDryRunError_AddGroupUser_GroupUserBuildRuleValidation_UserNotExist()
    {
        [$r1, $g1, $userAId, $userBId] = $this->insertFixture_AddGroupUser_HavingOneResourceSharedWith();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userCId = UuidFactory::uuid('user.id.carol');
        $userNotExistId = UuidFactory::uuid();
        $data = [
            ['user_id' => $userCId, 'is_admin' => true],
            ['user_id' => $userNotExistId, 'is_admin' => false],
        ];

        try {
            $this->service->dryRun($uac, $g1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertUpdateDryRunValidationException($e, 'groups_users.1.user_id.user_exists');
        }

        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsMemberOf($g1->id, $userBId, false);
        $this->assertUserIsNotMemberOf($g1->id, $userCId);
        $this->assertUserIsNotMemberOf($g1->id, $userNotExistId);
    }

    /* UPDATE GROUP USER */

    public function testUpdateDryRunSuccess_UpdateGroupUser()
    {
        [$r1, $g1, $userAId, $userBId] = $this->insertFixture_UpdateGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $userAGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userAId)->first()->id;
        $userBGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userBId)->first()->id;
        $data = [
            ['id' => $userAGroupUserId, 'is_admin' => false],
            ['id' => $userBGroupUserId, 'is_admin' => true],
        ];
        $result = $this->service->dryRun($uac, $g1->id, $data);

        $this->assertCount(0, $result['secrets']);
        $this->assertCount(0, $result['secretsNeeded']);

        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsMemberOf($g1->id, $userBId, false);
    }

    private function insertFixture_UpdateGroupUser()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => false],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$r1, $g1, $userAId, $userBId];
    }

    public function testUpdateDryRunError_UpdateGroupUser_GroupUserValidation()
    {
        [$r1, $g1, $userAId, $userBId] = $this->insertFixture_UpdateGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $userAGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userAId)->first()->id;
        $data = [
            ['id' => $userAGroupUserId, 'is_admin' => 42],
        ];
        try {
            $this->service->dryRun($uac, $g1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertUpdateDryRunValidationException($e, 'groups_users.0.is_admin.boolean');
        }
    }

    /* DELETE GROUP USER */

    public function testUpdateDryRunSuccess_DeleteGroupUser()
    {
        [$r1, $g1, $userAId, $userBId, $userCId] = $this->insertFixture_DeleteGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userBGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userBId)->first()->id;
        $userCGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userCId)->first()->id;
        $data = [
            ['id' => $userBGroupUserId, 'delete' => true],
            ['id' => $userCGroupUserId, 'delete' => true],
        ];
        $result = $this->service->dryRun($uac, $g1->id, $data);

        $this->assertCount(0, $result['secrets']);
        $this->assertCount(0, $result['secretsNeeded']);

        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsMemberOf($g1->id, $userBId, true);
        $this->assertUserIsMemberOf($g1->id, $userCId, false);
    }

    private function insertFixture_DeleteGroupUser()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => true],
            ['user_id' => $userCId, 'is_admin' => false],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$r1, $g1, $userAId, $userBId, $userCId];
    }
}
