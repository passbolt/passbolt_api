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
use App\Model\Entity\Role;
use App\Service\Groups\GroupsUpdateService;
use App\Service\GroupsUsers\GroupsUsersAddService;
use App\Service\GroupsUsers\GroupsUsersDeleteService;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Event\EventList;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * \App\Service\Groups\GroupsUpdateService Test Case
 *
 * @covers \App\Service\Groups\GroupsUpdateService
 */
class GroupsUpdateServiceTest extends AppTestCase
{
    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @var GroupsUpdateService
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->service = new GroupsUpdateService();
    }

    /* COMMON */

    public function testUpdateSuccess_Common_EventsAreFired()
    {
        // Enable event tracking
        $this->groupsUsersTable->getEventManager()->setEventList(new EventList());

        [$r1, $r2, $g1, $u1, $u2, $u3, $u4] = $this->insertFixture_AddGroupUser_HavingMultipleResourceSharedWith();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        // Events are triggered when a user is added or removed from a group
        $changes = [
            ['id' => $g1->groups_users[0]->id, 'delete' => true],
            ['user_id' => $u4->id, 'is_admin' => true],
        ];
        $secrets = [
            ['resource_id' => $r1->id, 'user_id' => $u4->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ['resource_id' => $r2->id, 'user_id' => $u4->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ];

        $this->service->update($uac, $g1->id, [], $changes, $secrets);
        $this->assertEventFired(GroupsUsersDeleteService::AFTER_GROUP_USER_DELETED_EVENT_NAME, $this->groupsUsersTable->getEventManager());
        $this->assertEventFired(GroupsUsersAddService::AFTER_GROUP_USER_ADDED_EVENT_NAME, $this->groupsUsersTable->getEventManager());
    }

    /* ******************************************
     * Success - mix add/update/delete group users
     ****************************************** */

    public function testUpdateSuccess_AddGroupUser_HavingMultipleResourceSharedWith()
    {
        [$r1, $r2, $g1, $userA, $userB, $userC, $userD] = $this->insertFixture_AddGroupUser_HavingMultipleResourceSharedWith();
        $uac = new UserAccessControl(Role::USER, $userA->id);
        $userAGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userA->id)->first()->id;
        $userBGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userB->id)->first()->id;
        $userCGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userC->id)->first()->id;
        $changes = [
            ['id' => $userAGroupUserId, 'delete' => true],
            ['id' => $userBGroupUserId, 'is_admin' => false],
            ['id' => $userCGroupUserId, 'delete' => true],
            ['user_id' => $userD->id, 'is_admin' => true],
        ];
        $secrets = [
            ['resource_id' => $r1->id, 'user_id' => $userD->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ['resource_id' => $r2->id, 'user_id' => $userD->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
        ];

        $this->service->update($uac, $g1->id, [], $changes, $secrets);

        $this->assertUserIsNotMemberOf($g1->id, $userA->id);
        $this->assertUserIsMemberOf($g1->id, $userB->id, false);
        $this->assertUserIsNotMemberOf($g1->id, $userC->id);
        $this->assertUserIsMemberOf($g1->id, $userD->id, true);
        // R1
        $this->assertSecretExists($r1->id, $userA->id);
        $this->assertSecretExists($r1->id, $userB->id);
        $this->assertSecretNotExist($r1->id, $userC->id);
        $this->assertSecretExists($r1->id, $userD->id);
        // R2
        $this->assertSecretExists($r2->id, $userA->id);
        $this->assertSecretExists($r2->id, $userB->id);
        $this->assertSecretNotExist($r2->id, $userC->id);
        $this->assertSecretExists($r2->id, $userD->id);
    }

    /* ******************************************
     * Success - add user to group
     ****************************************** */

    public function testUpdateSuccess_AddGroupUser_HavingOneResourceSharedWith()
    {
        [$r1, $g1, $userA, $userB, $userC, $userD] = $this->insertFixture_AddGroupUser_HavingOneResourceSharedWith();
        $uac = new UserAccessControl(Role::USER, $userA->id);
        $changes = [
            ['user_id' => $userC->id, 'is_admin' => true],
            ['user_id' => $userD->id, 'is_admin' => false],
        ];
        $secrets = [
            ['resource_id' => $r1->id, 'user_id' => $userC->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ['resource_id' => $r1->id, 'user_id' => $userD->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
        ];

        $this->service->update($uac, $g1->id, [], $changes, $secrets);

        $this->assertUserIsMemberOf($g1->id, $userA->id, true);
        $this->assertUserIsMemberOf($g1->id, $userB->id, false);
        $this->assertUserIsMemberOf($g1->id, $userC->id, true);
        $this->assertUserIsMemberOf($g1->id, $userD->id, false);
        $this->assertSecretExists($r1->id, $userA->id);
        $this->assertSecretExists($r1->id, $userB->id);
        $this->assertSecretExists($r1->id, $userC->id);
        $this->assertSecretExists($r1->id, $userD->id);
    }

    public function testUpdateSuccess_AddGroupUser_HavingMultipleResourcesSharedWith()
    {
        [$r1, $r2, $g1, $u1, $u2] = $this->insertFixture_AddGroupUser_HavingMultipleResourcesSharedWith();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $u3 = UserFactory::make()->persist();
        $u4 = UserFactory::make()->persist();

        $changes = [
            ['user_id' => $u3->id, 'is_admin' => true],
            ['user_id' => $u4->id, 'is_admin' => false],
        ];
        $secrets = [
            ['resource_id' => $r1->id, 'user_id' => $u3->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ['resource_id' => $r1->id, 'user_id' => $u4->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ['resource_id' => $r2->id, 'user_id' => $u3->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ['resource_id' => $r2->id, 'user_id' => $u4->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
        ];

        $this->service->update($uac, $g1->id, [], $changes, $secrets);

        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsMemberOf($g1->id, $u2->id, false);
        $this->assertUserIsMemberOf($g1->id, $u3->id, true);
        $this->assertUserIsMemberOf($g1->id, $u4->id, false);
        $this->assertSecretExists($r1->id, $u1->id);
        $this->assertSecretExists($r1->id, $u2->id);
        $this->assertSecretExists($r1->id, $u3->id);
        $this->assertSecretExists($r1->id, $u4->id);
        $this->assertSecretExists($r2->id, $u1->id);
        $this->assertSecretExists($r2->id, $u2->id);
        $this->assertSecretExists($r2->id, $u3->id);
        $this->assertSecretExists($r2->id, $u4->id);
    }

    public function testUpdateSuccess_AddGroupUser_UserHasAlreadyAccessToTheResource()
    {
        [$r1, $g1, $u1, $u2] = $this->insertFixture_AddGroupUser_UserHasAlreadyAccessToTheResource();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $changes = [['user_id' => $u2->id, 'is_admin' => true]];
        try {
            $this->service->update($uac, $g1->id, [], $changes);
        } catch (\Exception $e) {
            dd($e->getErrors());
        }

        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsMemberOf($g1->id, $u2->id, true);
        $this->assertSecretExists($r1->id, $u1->id);
        $this->assertSecretExists($r1->id, $u2->id);
    }

    /* ******************************************
     * Error - add user to group
     ****************************************** */

    public function testUpdateError_AddGroupUser_GroupUserValidation_InvalidUserId()
    {
        [$r1, $g1, $u1, $u2] = $this->insertFixture_AddGroupUser_HavingOneResourceSharedWith();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $u3 = UserFactory::make()->persist();

        $changes = [['user_id' => ['not-a-valid-uuid'], 'is_admin' => true]];
        try {
            $this->service->update($uac, $g1->id, [], $changes);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Could not validate group data.', 'groups_users.0.user_id.uuid');
        }

        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsMemberOf($g1->id, $u2->id, false);
        $this->assertUserIsNotMemberOf($g1->id, $u3->id);
        $this->assertSecretExists($r1->id, $u1->id);
        $this->assertSecretExists($r1->id, $u2->id);
        $this->assertSecretNotExist($r1->id, $u3->id);
    }

    public function testUpdateError_AddGroupUser_GroupUserValidation_InvalidGroupUserData()
    {
        [$r1, $g1, $u1, $u2] = $this->insertFixture_AddGroupUser_HavingOneResourceSharedWith();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $u3 = UserFactory::make()->persist();

        $changes = [['user_id' => $u3->id, 'is_admin' => 42]];
        try {
            $this->service->update($uac, $g1->id, [], $changes);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Could not validate group data.', 'groups_users.0.is_admin.boolean');
        }

        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsMemberOf($g1->id, $u2->id, false);
        $this->assertUserIsNotMemberOf($g1->id, $u3->id);
        $this->assertSecretExists($r1->id, $u1->id);
        $this->assertSecretExists($r1->id, $u2->id);
        $this->assertSecretNotExist($r1->id, $u3->id);
    }

    public function testUpdateError_AddGroupUser_GroupUserBuildRuleValidation_UserNotExist()
    {
        [$r1, $g1, $u1] = $this->insertFixture_AddGroupUser_HavingOneResourceSharedWith();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $u3 = UserFactory::make()->persist();
        $userNotExistId = UuidFactory::uuid();

        $changes = [
            ['user_id' => $u3->id, 'is_admin' => true],
            ['user_id' => $userNotExistId, 'is_admin' => false],
        ];
        $secrets = [['resource_id' => $r1->id, 'user_id' => $u3->id, 'data' => Hash::get($this->getDummySecretData(), 'data')]];

        try {
            $this->service->update($uac, $g1->id, [], $changes, $secrets);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Could not validate group data.', 'groups_users.1.user_id.user_exists');
        }
    }

    public function testUpdateError_AddGroupUser_SecretValidation_NotEnoughSecretsProvided()
    {
        [$r1, $g1, $u1] = $this->insertFixture_AddGroupUser_HavingOneResourceSharedWith();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $u3 = UserFactory::make()->persist();

        $changes = [['user_id' => $u3->id, 'is_admin' => true]];
        $secrets = [];

        try {
            $this->service->update($uac, $g1->id, [], $changes, $secrets);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            // @todo rename all_missing, maybe all_required?
            $this->assertValidationException($e, 'Could not validate group data.', 'groups_users.0.secrets.all_missing');
        }
    }

    public function testUpdateError_AddGroupUser_SecretValidation_SecretForAResourceTheGroupHasNoAccess()
    {
        [$r1, $r2, $g1, $u1, $u2] = $this->insertFixture_AddGroupUser_SecretValidation_SecretForAResourceTheGroupHasNoAccess();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $u3 = UserFactory::make()->persist();

        $changes = [['user_id' => $u3->id, 'is_admin' => true]];
        $secrets = [['resource_id' => $r2->id, 'user_id' => $u3->id, 'data' => Hash::get($this->getDummySecretData(), 'data')]];

        try {
            $this->service->update($uac, $g1->id, [], $changes, $secrets);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            // @todo resource_id ou directement secrets.0 et l'erreur, c'est pas vraiment un champs ici mais l'intégralité du secret qu'on ne prend pas.
            $this->assertValidationException($e, 'Could not validate group data.', 'groups_users.0.secrets.0.resource_id.only_missing');
        }
    }

    /* ******************************************
     * Success - update group user
     ****************************************** */

    public function testUpdateSuccess_UpdateGroupUser()
    {
        [$u1, $u2, $g1] = $this->insertFixture_GroupWithOneManagerAndOneMember();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $data = [
            ['id' => $g1->groups_users[0]->id, 'is_admin' => false],
            ['id' => $g1->groups_users[1]->id, 'is_admin' => true],
        ];
        $this->service->update($uac, $g1->id, $data);

        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsMemberOf($g1->id, $u2->id, false);
    }

    /* ******************************************
     * Error - update group user
     ****************************************** */

    public function testUpdateError_UpdateGroupUser_InvalidGroupUserData()
    {
        [$u1, $u2, $g1] = $this->insertFixture_GroupWithOneManagerAndOneMember();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $data = [['id' => $g1->groups_users[0]->id, 'is_admin' => 42]];
        try {
            $this->service->update($uac, $g1->id, [], $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Could not validate group data.', 'groups_users.0.is_admin.boolean');
        }
    }

    public function testUpdateError_UpdateGroupUser_NonExistingGroupUserRecord()
    {
        [$r1, $g1, $u1] = $this->insertFixture_AddGroupUser_HavingOneResourceSharedWith();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $userGroupNotExist = UuidFactory::uuid();
        $data = [['id' => $userGroupNotExist, 'is_admin' => true]];
        try {
            $this->service->update($uac, $g1->id, [], $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Could not validate group data.', 'groups_users.0.id.exists');
        }
    }

    public function testUpdateError_UpdateGroupUser_RemoveLastGroupManager()
    {
        [$u1, $u2, $g1] = $this->insertFixture_GroupWithOneManagerAndOneMember();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $data = [['id' => $g1->groups_users[0]->id, 'is_admin' => false]];
        try {
            $this->service->update($uac, $g1->id, [], $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Could not validate group data.', 'groups_users.0.is_admin.at_least_one_group_manager');
        }
    }

    public function testUpdateError_UpdateGroupUser_GroupUserFromAnotherGroup()
    {
        [$g1, $g2, $u1] = $this->insertFixture_TwoGroupsWithTwoMembers();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $data = [['id' => $g2->groups_users[1]->id, 'is_admin' => true]];

        try {
            $this->service->update($uac, $g1->id, [], $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Could not validate group data.', 'groups_users.0.id.exists');
        }
    }

    /* ******************************************
     * Success - remove user from group
     ****************************************** */

    public function testUpdateSuccess_DeleteSingleGroupUser()
    {
        [$u1, $u2, $g1] = $this->insertFixture_GroupWithOneManagerAndOneMember();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $data = [['id' => $g1->groups_users[1]->id, 'delete' => true]];
        $this->service->update($uac, $g1->id, [], $data);

        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsNotMemberOf($g1->id, $u2->id);
    }

    /* ******************************************
     * Error - remove user from group
     ****************************************** */

    public function testUpdateError_DeleteGroupUser_GroupUserValidation_GroupUserNotExist()
    {
        [$u1, $u2, $g1] = $this->insertFixture_GroupWithOneManagerAndOneMember();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $userGroupNotExist = UuidFactory::uuid();

        $data = [['id' => $userGroupNotExist, 'delete' => true]];
        try {
            $this->service->update($uac, $g1->id, [], $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Could not validate group data.', 'groups_users.0.id.exists');
        }
    }

    public function testUpdateError_DeleteGroupUser_RemoveLastGroupManager()
    {
        [$u1, $u2, $g1] = $this->insertFixture_GroupWithOneManagerAndOneMember();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $data = [['id' => $g1->groups_users[0]->id, 'delete' => true]];
        try {
            $this->service->update($uac, $g1->id, [], $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Could not validate group data.', 'groups_users.0.is_admin.at_least_one_group_manager');
        }
    }

    public function testUpdateError_DeleteGroupUser_GroupUserFromAnotherGroup()
    {
        [$g1, $g2, $u1] = $this->insertFixture_TwoGroupsWithTwoMembers();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $data = [['id' => $g2->groups_users[1]->id, 'delete' => true]];

        try {
            $this->service->update($uac, $g1->id, [], $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Could not validate group data.', 'groups_users.0.id.exists');
        }
    }

    /* ******************************************
     * Fixtures
     ****************************************** */

    private function insertFixture_AddGroupUser_HavingMultipleResourceSharedWith(): array
    {
        $u1 = UserFactory::make()->persist();
        $u2 = UserFactory::make()->persist();
        $u3 = UserFactory::make()->persist();
        $u4 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1])->withGroupsUsersFor([$u2, $u3])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$u1, $g1])->withSecretsFor([$u1, $u2, $u3])->persist();
        $r2 = ResourceFactory::make()->withPermissionsFor([$u1, $g1])->withSecretsFor([$u1, $u2, $u3])->persist();

        return [$r1, $r2, $g1, $u1, $u2, $u3, $u4];
    }

    private function insertFixture_AddGroupUser_HavingOneResourceSharedWith(): array
    {
        $u1 = UserFactory::make()->persist();
        $u2 = UserFactory::make()->persist();
        $u3 = UserFactory::make()->persist();
        $u4 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1])->withGroupsUsersFor([$u2])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$u1, $g1])->withSecretsFor([$u1, $u2])->persist();

        return [$r1, $g1, $u1, $u2, $u3, $u4];
    }

    private function insertFixture_AddGroupUser_HavingMultipleResourcesSharedWith()
    {
        $u1 = UserFactory::make()->persist();
        $u2 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1])->withGroupsUsersFor([$u2])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$u1, $g1])->withSecretsFor([$u1, $u2])->persist();
        $r2 = ResourceFactory::make()->withPermissionsFor([$u1, $g1])->withSecretsFor([$u1, $u2])->persist();

        return [$r1, $r2, $g1, $u1, $u2];
    }

    private function insertFixture_AddGroupUser_UserHasAlreadyAccessToTheResource()
    {
        $u1 = UserFactory::make()->persist();
        $u2 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$u2, $g1])->withSecretsFor([$u1, $u2])->persist();

        return [$r1, $g1, $u1, $u2];
    }

    private function insertFixture_AddGroupUser_SecretValidation_SecretForAResourceTheGroupHasNoAccess()
    {
        $u1 = UserFactory::make()->persist();
        $u2 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1])->withGroupsUsersFor([$u2])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$u1, $g1])->withSecretsFor([$u1, $u2])->persist();
        $r2 = ResourceFactory::make()->withPermissionsFor([$u1])->withSecretsFor([$u1])->persist();

        return [$r1, $r2, $g1, $u1, $u2];
    }

    private function insertFixture_GroupWithOneManagerAndOneMember(): array
    {
        $u1 = UserFactory::make()->persist();
        $u2 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1])->withGroupsUsersFor([$u2])->persist();

        return [$u1, $u2, $g1];
    }

    private function insertFixture_TwoGroupsWithTwoMembers()
    {
        $u1 = UserFactory::make()->persist();
        $u2 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1])->withGroupsUsersFor([$u2])->persist();
        $g2 = GroupFactory::make()->withGroupsManagersFor([$u1])->withGroupsUsersFor([$u2])->persist();

        return [$g1, $g2, $u1, $u2];
    }
}
