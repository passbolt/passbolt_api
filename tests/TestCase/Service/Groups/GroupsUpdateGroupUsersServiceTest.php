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
 * @since         2.13.0
 */

namespace App\Test\TestCase\Service\Groups;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Role;
use App\Model\Table\GroupsUsersTable;
use App\Service\Groups\GroupsUpdateGroupUsersService;
use App\Test\Lib\AppTestCase;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * \App\Service\Groups\GroupsUpdateGroupUsersService Test Case
 *
 * @covers \App\Service\Groups\GroupsUpdateGroupUsersService
 */
class GroupsUpdateGroupUsersServiceTest extends AppTestCase
{
    public $fixtures = [
        'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Users',
    ];

    /**
     * @var GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @var GroupsUpdateGroupUsersService
     */
    private $service;

    public function setUp()
    {
        parent::setUp();
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->service = new GroupsUpdateGroupUsersService();
    }

    /* ************************************************************** */
    /* ADD/UPDATE/DELETE GROUP USER */
    /* ************************************************************** */

    public function testUpdateDryRunSuccess_AddUpdateRemoveGroupUsers_HavingMultipleResourcesSharedWith()
    {
        list($g1, $userAId, $userBId) = $this->insertFixture_AddUpdateRemoveGroupUsers_HavingMultipleResourcesSharedWith();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userAGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userAId)->first()->id;
        $userBGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userBId)->first()->id;
        $userCId = UuidFactory::uuid('user.id.carol');
        $data = [
            ['id' => $userAGroupUserId, 'delete' => true],
            ['id' => $userBGroupUserId, 'is_admin' => false],
            ['user_id' => $userCId, 'is_admin' => true],
        ];

        $this->service->updateGroupUsers($uac, $g1->id, $data);

        $this->assertUserIsNotMemberOf($g1->id, $userAId);
        $this->assertUserIsMemberOf($g1->id, $userBId, false);
        $this->assertUserIsMemberOf($g1->id, $userCId, true);
    }

    private function insertFixture_AddUpdateRemoveGroupUsers_HavingMultipleResourcesSharedWith()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => true],
        ]]);

        return [$g1, $userAId, $userBId];
    }

    /* ************************************************************** */
    /* ADD GROUP USER */
    /* ************************************************************** */

    public function testUpdateGroupUsersSuccess_AddGroupUser()
    {
        list($g1, $userAId, $userBId) = $this->insertFixture_AddGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userCId = UuidFactory::uuid('user.id.carol');
        $userDId = UuidFactory::uuid('user.id.dame');
        $data = [
            ['user_id' => $userCId, 'is_admin' => true],
            ['user_id' => $userDId, 'is_admin' => false],
        ];
        $this->service->updateGroupUsers($uac, $g1->id, $data);

        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsMemberOf($g1->id, $userBId, false);
        $this->assertUserIsMemberOf($g1->id, $userCId, true);
        $this->assertUserIsMemberOf($g1->id, $userDId, false);
    }

    private function insertFixture_AddGroupUser()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => false],
        ]]);

        return [$g1, $userAId, $userBId];
    }

    public function testUpdateGroupUsersError_AddGroupUser_GroupUserValidation()
    {
        list($g1, $userAId, $userBId) = $this->insertFixture_AddGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userCId = UuidFactory::uuid('user.id.carol');
        $userNotExistId = UuidFactory::uuid();
        $data = [
            ['user_id' => $userCId, 'is_admin' => 42],
        ];
        try {
            $this->service->updateGroupUsers($uac, $g1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdateGroupUsersValidationException($e, '0.is_admin.boolean');
        }

        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsMemberOf($g1->id, $userBId, false);
        $this->assertUserIsNotMemberOf($g1->id, $userCId);
        $this->assertUserIsNotMemberOf($g1->id, $userNotExistId);
    }

    private function assertUpdateGroupUsersValidationException(CustomValidationException $e, string $errorFieldName)
    {
        $this->assertEquals("Could not validate group user data.", $e->getMessage());
        $error = Hash::get($e->getErrors(), $errorFieldName);
        $this->assertNotNull($error, "Expected error not found : {$errorFieldName}. Errors: " . json_encode($e->getErrors()));
    }

    public function testUpdateGroupUsersError_AddGroupUser_GroupUserBuildRuleValidation_UserNotExist()
    {
        list($g1, $userAId, $userBId) = $this->insertFixture_AddGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userCId = UuidFactory::uuid('user.id.carol');
        $userNotExistId = UuidFactory::uuid();
        $data = [
            ['user_id' => $userCId, 'is_admin' => true],
            ['user_id' => $userNotExistId, 'is_admin' => false],
        ];
        try {
            $this->service->updateGroupUsers($uac, $g1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdateGroupUsersValidationException($e, '1.user_id.user_exists');
        }

        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsMemberOf($g1->id, $userBId, false);
        $this->assertUserIsNotMemberOf($g1->id, $userCId);
        $this->assertUserIsNotMemberOf($g1->id, $userNotExistId);
    }

    public function testUpdateGroupUsersError_AddGroupUser_GroupUserBuildRuleValidation_GroupUserIsUnique()
    {
        list($g1, $userAId, $userBId) = $this->insertFixture_AddGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userCId = UuidFactory::uuid('user.id.carol');
        $userNotExistId = UuidFactory::uuid();
        $data = [
            ['user_id' => $userBId, 'is_admin' => true],
        ];
        try {
            $this->service->updateGroupUsers($uac, $g1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdateGroupUsersValidationException($e, '0.group_id.group_user_unique');
        }

        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsMemberOf($g1->id, $userBId, false);
        $this->assertUserIsNotMemberOf($g1->id, $userCId);
        $this->assertUserIsNotMemberOf($g1->id, $userNotExistId);
    }

    /* ************************************************************** */
    /* UPDATE GROUP USER */
    /* ************************************************************** */

    public function testUpdateGroupUsersSuccess_UpdateGroupUser()
    {
        list($g1, $userAId, $userBId) = $this->insertFixture_UpdateGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $userAGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userAId)->first()->id;
        $userBGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userBId)->first()->id;
        $data = [
            ['id' => $userAGroupUserId, 'is_admin' => false],
            ['id' => $userBGroupUserId, 'is_admin' => true],
        ];
        $this->service->updateGroupUsers($uac, $g1->id, $data);

        $this->assertUserIsMemberOf($g1->id, $userAId, false);
        $this->assertUserIsMemberOf($g1->id, $userBId, true);
    }

    private function insertFixture_UpdateGroupUser()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => false],
        ]]);

        return [$g1, $userAId, $userBId];
    }

    public function testUpdateGroupUsersError_UpdateGroupUser_GroupUserValidation()
    {
        list($g1, $userAId, $userBId) = $this->insertFixture_UpdateGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $userAGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userAId)->first()->id;
        $data = [
            ['id' => $userAGroupUserId, 'is_admin' => "NOT A BOOLEAN"],
        ];
        try {
            $this->service->updateGroupUsers($uac, $g1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdateGroupUsersValidationException($e, '0.is_admin.boolean');
        }
    }

    public function testUpdateGroupUsersError_UpdateGroupUser_GroupUserBuildRuleValidation_GroupUserNotExist()
    {
        list($g1, $userAId, $userBId) = $this->insertFixture_AddGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userGroupNotExist = UuidFactory::uuid();
        $data = [
            ['id' => $userGroupNotExist, 'is_admin' => true],
        ];
        try {
            $this->service->updateGroupUsers($uac, $g1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdateGroupUsersValidationException($e, '0.id.exists');
        }
    }

    public function testUpdateGroupUsersError_UpdateGroupUser_GroupUserBuildRuleValidation_AtLeastOneGroupManager()
    {
        list($g1, $userAId, $userBId) = $this->insertFixture_UpdateGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $userAGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userAId)->first()->id;
        $data = [
            ['id' => $userAGroupUserId, 'is_admin' => false],
        ];
        try {
            $this->service->updateGroupUsers($uac, $g1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdateGroupUsersValidationException($e, 'at_least_one_group_manager');
        }
    }

    public function testUpdateGroupUsersError_UpdateGroupUser_Validation_UpdateGroupUserOfAnotherGroup()
    {
        list($g1, $g2, $userAId) = $this->insertFixture_UpdateGroupUser_Validation_UpdateGroupUserOfAnotherGroup();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userAGroup2UserId = $this->groupsUsersTable->findByGroupIdAndUserId($g2->id, $userAId)->first()->id;
        $data = [
            ['id' => $userAGroup2UserId, 'is_admin' => false],
        ];

        try {
            $this->service->updateGroupUsers($uac, $g1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdateGroupUsersValidationException($e, '0.id.exists');
        }
    }

    private function insertFixture_UpdateGroupUser_Validation_UpdateGroupUserOfAnotherGroup()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
        ]]);
        $g2 = $this->addGroup(['name' => 'G2', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],

        ]]);

        return [$g1, $g2, $userAId];
    }

    /* ************************************************************** */
    /* DELETE GROUP USER */
    /* ************************************************************** */

    public function testUpdateGroupUsersSuccess_DeleteGroupUser()
    {
        list($g1, $userAId, $userBId, $userCId) = $this->insertFixture_DeleteGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userBGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userBId)->first()->id;
        $userCGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userCId)->first()->id;
        $data = [
            ['id' => $userBGroupUserId, 'delete' => true],
            ['id' => $userCGroupUserId, 'delete' => true],
        ];
        $this->service->updateGroupUsers($uac, $g1->id, $data);

        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsNotMemberOf($g1->id, $userBId);
        $this->assertUserIsNotMemberOf($g1->id, $userCId);
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

        return [$g1, $userAId, $userBId, $userCId];
    }

    public function testUpdateGroupUsersError_DeleteGroupUser_GroupUserValidation_GroupUserNotExist()
    {
        list($g1, $userAId, $userBId, $userCId) = $this->insertFixture_DeleteGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userGroupNotExist = UuidFactory::uuid();
        $data = [
            ['id' => $userGroupNotExist, 'delete' => true],
        ];

        try {
            $this->service->updateGroupUsers($uac, $g1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdateGroupUsersValidationException($e, '0.id.exists');
        }
    }

    public function testUpdateGroupUsersError_DeleteGroupUser_GroupUserBuildRuleValidation_AtLeastOneGroupManager()
    {
        list($g1, $userAId, $userBId) = $this->insertFixture_DeleteGroupUser_GroupUserBuildRuleValidation();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userAGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userAId)->first()->id;
        $data = [
            ['id' => $userAGroupUserId, 'delete' => true],
        ];

        try {
            $this->service->updateGroupUsers($uac, $g1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdateGroupUsersValidationException($e, 'at_least_one_group_manager');
        }
    }

    private function insertFixture_DeleteGroupUser_GroupUserBuildRuleValidation()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => false],
        ]]);

        return [$g1, $userAId, $userBId];
    }

    public function testUpdateGroupUsersError_DeleteGroupUser_Validation_DeleteGroupUserOfAnotherGroup()
    {
        list($g1, $g2, $userAId) = $this->insertFixture_DeleteGroupUser_Validation_DeleteGroupUserOfAnotherGroup();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userAGroup2UserId = $this->groupsUsersTable->findByGroupIdAndUserId($g2->id, $userAId)->first()->id;
        $data = [
            ['id' => $userAGroup2UserId, 'delete' => true],
        ];

        try {
            $this->service->updateGroupUsers($uac, $g1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdateGroupUsersValidationException($e, '0.id.exists');
        }
    }

    private function insertFixture_DeleteGroupUser_Validation_DeleteGroupUserOfAnotherGroup()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
        ]]);
        $g2 = $this->addGroup(['name' => 'G2', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],

        ]]);

        return [$g1, $g2, $userAId];
    }
}
