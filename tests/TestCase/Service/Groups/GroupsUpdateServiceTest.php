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
use App\Service\Groups\GroupsUpdateService;
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
    public $fixtures = [
        'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Permissions',
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles',
        'app.Base/Favorites',
    ];

    /**
     * @var GroupsTable
     */
    private $groupsTable;

    /**
     * @var GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @var GroupsUpdateService
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->groupsTable = TableRegistry::getTableLocator()->get('Groups');
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->service = new GroupsUpdateService();
    }

    /* COMMON */

    public function testUpdateSuccess_Common_EventsAreFired()
    {
        // Enable event tracking
        $this->groupsTable->getEventManager()->setEventList(new EventList());

        [$r1, $r2, $g1, $userAId, $userBId, $userCId] = $this->insertFixture_AddGroupUser_HavingMultipleResourceSharedWith();
        $uac = new UserAccessControl(Role::USER, $userAId);

        // Events are trigger when a user is added or removed from a group
        $userAGroupUser = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userAId)->first();
        $userDId = UuidFactory::uuid('user.id.dame');
        $changes = [
            ['id' => $userAGroupUser->id, 'delete' => true],
            ['user_id' => $userDId, 'is_admin' => true],
        ];
        $secrets = [
            ['resource_id' => $r1->id, 'user_id' => $userDId, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ['resource_id' => $r2->id, 'user_id' => $userDId, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ];

        $this->service->update($uac, $g1->id, [], $changes, $secrets);
        $this->assertEventFired(GroupsUpdateService::AFTER_GROUP_USER_REMOVED_EVENT_NAME, $this->groupsTable->getEventManager());
        $this->assertEventFired(GroupsUpdateService::AFTER_GROUP_USER_ADDED_EVENT_NAME, $this->groupsTable->getEventManager());
    }

    /* ADD/UPDATE/DELETE GROUP USER */

    public function testUpdateSuccess_AddGroupUser_HavingMultipleResourceSharedWith()
    {
        [$r1, $r2, $g1, $userAId, $userBId, $userCId] = $this->insertFixture_AddGroupUser_HavingMultipleResourceSharedWith();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userAGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userAId)->first()->id;
        $userBGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userBId)->first()->id;
        $userCGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userCId)->first()->id;
        $userDId = UuidFactory::uuid('user.id.dame');
        $changes = [
            ['id' => $userAGroupUserId, 'delete' => true],
            ['id' => $userBGroupUserId, 'is_admin' => false],
            ['id' => $userCGroupUserId, 'delete' => true],
            ['user_id' => $userDId, 'is_admin' => true],
        ];
        $secrets = [
            ['resource_id' => $r1->id, 'user_id' => $userDId, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ['resource_id' => $r2->id, 'user_id' => $userDId, 'data' => Hash::get($this->getDummySecretData(), 'data')],
        ];

        $this->service->update($uac, $g1->id, [], $changes, $secrets);

        $this->assertUserIsNotMemberOf($g1->id, $userAId);
        $this->assertUserIsMemberOf($g1->id, $userBId, false);
        $this->assertUserIsNotMemberOf($g1->id, $userCId);
        $this->assertUserIsMemberOf($g1->id, $userDId, true);
        // R1
        $this->assertSecretExists($r1->id, $userAId);
        $this->assertSecretExists($r1->id, $userBId);
        $this->assertSecretNotExist($r1->id, $userCId);
        $this->assertSecretExists($r1->id, $userDId);
        // R2
        $this->assertSecretExists($r1->id, $userAId);
        $this->assertSecretExists($r1->id, $userBId);
        $this->assertSecretNotExist($r1->id, $userCId);
        $this->assertSecretExists($r1->id, $userDId);
    }

    private function insertFixture_AddGroupUser_HavingMultipleResourceSharedWith()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => true],
            ['user_id' => $userCId, 'is_admin' => true],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);
        $r2 = $this->addResourceFor(['name' => 'R2'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$r1, $r2, $g1, $userAId, $userBId, $userCId];
    }

    /* ADD GROUP USER */

    public function testUpdateSuccess_AddGroupUser_HavingOneResourceSharedWith()
    {
        [$r1, $g1, $userAId, $userBId] = $this->insertFixture_AddGroupUser_HavingOneResourceSharedWith();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userCId = UuidFactory::uuid('user.id.carol');
        $userDId = UuidFactory::uuid('user.id.dame');
        $changes = [
            ['user_id' => $userCId, 'is_admin' => true],
            ['user_id' => $userDId, 'is_admin' => false],
        ];
        $secrets = [
            ['resource_id' => $r1->id, 'user_id' => $userCId, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ['resource_id' => $r1->id, 'user_id' => $userDId, 'data' => Hash::get($this->getDummySecretData(), 'data')],
        ];

        $this->service->update($uac, $g1->id, [], $changes, $secrets);

        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsMemberOf($g1->id, $userBId, false);
        $this->assertUserIsMemberOf($g1->id, $userCId, true);
        $this->assertUserIsMemberOf($g1->id, $userDId, false);
        $this->assertSecretExists($r1->id, $userAId);
        $this->assertSecretExists($r1->id, $userBId);
        $this->assertSecretExists($r1->id, $userCId);
        $this->assertSecretExists($r1->id, $userDId);
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

    public function testUpdateSuccess_AddGroupUser_HavingMultipleResourcesSharedWith()
    {
        [$r1, $r2, $g1, $userAId, $userBId] = $this->insertFixture_AddGroupUser_HavingMultipleResourcesSharedWith();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userCId = UuidFactory::uuid('user.id.carol');
        $userDId = UuidFactory::uuid('user.id.dame');
        $changes = [
            ['user_id' => $userCId, 'is_admin' => true],
            ['user_id' => $userDId, 'is_admin' => false],
        ];
        $secrets = [
            ['resource_id' => $r1->id, 'user_id' => $userCId, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ['resource_id' => $r1->id, 'user_id' => $userDId, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ['resource_id' => $r2->id, 'user_id' => $userCId, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ['resource_id' => $r2->id, 'user_id' => $userDId, 'data' => Hash::get($this->getDummySecretData(), 'data')],
        ];

        $this->service->update($uac, $g1->id, [], $changes, $secrets);

        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsMemberOf($g1->id, $userBId, false);
        $this->assertUserIsMemberOf($g1->id, $userCId, true);
        $this->assertUserIsMemberOf($g1->id, $userDId, false);
        $this->assertSecretExists($r1->id, $userAId);
        $this->assertSecretExists($r1->id, $userBId);
        $this->assertSecretExists($r1->id, $userCId);
        $this->assertSecretExists($r1->id, $userDId);
        $this->assertSecretExists($r2->id, $userAId);
        $this->assertSecretExists($r2->id, $userBId);
        $this->assertSecretExists($r2->id, $userCId);
        $this->assertSecretExists($r2->id, $userDId);
    }

    private function insertFixture_AddGroupUser_HavingMultipleResourcesSharedWith()
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

    public function testUpdateSuccess_AddGroupUser_UserHasAlreadyAccessToTheResource()
    {
        [$r1, $g1, $userAId, $userBId] = $this->insertFixture_AddGroupUser_UserHasAlreadyAccessToTheResource();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $data = [
            ['user_id' => $userBId, 'is_admin' => true],
        ];

        $this->service->update($uac, $g1->id, [], $data);

        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsMemberOf($g1->id, $userBId, true);
        $this->assertSecretExists($r1->id, $userAId);
        $this->assertSecretExists($r1->id, $userBId);
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

    public function testUpdateError_AddGroupUser_GroupUserValidation()
    {
        [$r1, $g1, $userAId, $userBId] = $this->insertFixture_AddGroupUser_HavingOneResourceSharedWith();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userCId = UuidFactory::uuid('user.id.carol');
        $data = [
            ['user_id' => $userCId, 'is_admin' => 42],
        ];

        try {
            $this->service->update($uac, $g1->id, [], $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertUpdateDryRunValidationException($e, 'groups_users.0.is_admin.boolean');
        }

        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsMemberOf($g1->id, $userBId, false);
        $this->assertUserIsNotMemberOf($g1->id, $userCId);
        $this->assertSecretExists($r1->id, $userAId);
        $this->assertSecretExists($r1->id, $userBId);
        $this->assertSecretNotExist($r1->id, $userCId);
    }

    private function assertUpdateDryRunValidationException(ValidationException $e, string $errorFieldName)
    {
        $this->assertEquals('Could not validate group data.', $e->getMessage());
        $error = Hash::get($e->getErrors(), $errorFieldName);
        $this->assertNotNull($error, "Expected error not found : {$errorFieldName}. Errors: " . json_encode($e->getErrors()));
    }

    public function testUpdateError_AddGroupUser_GroupUserBuildRuleValidation_UserNotExist()
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
            $this->service->update($uac, $g1->id, [], $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertUpdateDryRunValidationException($e, 'groups_users.1.user_id.user_exists');
        }
    }

    public function testUpdateError_AddGroupUser_SecretValidation_NotEnoughSecretsProvided()
    {
        [$r1, $g1, $userAId, $userBId] = $this->insertFixture_AddGroupUser_HavingOneResourceSharedWith();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userCId = UuidFactory::uuid('user.id.carol');
        $changes = [
            ['user_id' => $userCId, 'is_admin' => true],
        ];
        $secrets = [];

        try {
            $this->service->update($uac, $g1->id, [], $changes, $secrets);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertUpdateDryRunValidationException($e, 'secrets.secrets_provided');
        }
    }

    public function testUpdateError_AddGroupUser_SecretValidation_SecretForAResourceTheGroupHasNoAccess()
    {
        [$r1, $r2, $g1, $userAId, $userBId] = $this->insertFixture_AddGroupUser_SecretValidation_SecretForAResourceTheGroupHasNoAccess();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userCId = UuidFactory::uuid('user.id.carol');
        $changes = [
            ['user_id' => $userCId, 'is_admin' => true],
        ];
        $secrets = [
            ['resource_id' => $r2->id, 'user_id' => $userCId, 'data' => Hash::get($this->getDummySecretData(), 'data')],
        ];

        try {
            $this->service->update($uac, $g1->id, [], $changes, $secrets);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertUpdateDryRunValidationException($e, 'secrets.secrets_provided');
        }
    }

    private function insertFixture_AddGroupUser_SecretValidation_SecretForAResourceTheGroupHasNoAccess()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => false],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);
        $r2 = $this->addResourceFor(['name' => 'R2'], [$userAId => Permission::OWNER]);

        return [$r1, $r2, $g1, $userAId, $userBId];
    }

    /* UPDATE GROUP USER */

    public function testUpdateSuccess_UpdateGroupUser()
    {
        [$r1, $g1, $userAId, $userBId] = $this->insertFixture_UpdateGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $userAGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userAId)->first()->id;
        $userBGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userBId)->first()->id;
        $data = [
            ['id' => $userAGroupUserId, 'is_admin' => false],
            ['id' => $userBGroupUserId, 'is_admin' => true],
        ];
        $this->service->update($uac, $g1->id, $data);

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

    public function testUpdateError_UpdateGroupUser_GroupUserValidation()
    {
        [$r1, $g1, $userAId, $userBId] = $this->insertFixture_UpdateGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $userAGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userAId)->first()->id;
        $data = [
            ['id' => $userAGroupUserId, 'is_admin' => 42],
        ];
        try {
            $this->service->update($uac, $g1->id, [], $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertUpdateDryRunValidationException($e, 'groups_users.0.is_admin.boolean');
        }
    }

    public function testUpdateError_UpdateGroupUser_GroupUserBuildRuleValidation_GroupUserNotExist()
    {
        [$r1, $g1, $userAId, $userBId] = $this->insertFixture_AddGroupUser_HavingOneResourceSharedWith();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userGroupNotExist = UuidFactory::uuid();
        $data = [
            ['id' => $userGroupNotExist, 'is_admin' => true],
        ];
        try {
            $this->service->update($uac, $g1->id, [], $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertUpdateDryRunValidationException($e, 'groups_users.0.id.exists');
        }
    }

    public function testUpdateError_UpdateGroupUser_GroupUserBuildRuleValidation_AtLeastOneGroupManager()
    {
        [$r1, $g1, $userAId, $userBId] = $this->insertFixture_UpdateGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $userAGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userAId)->first()->id;
        $data = [
            ['id' => $userAGroupUserId, 'is_admin' => false],
        ];
        try {
            $this->service->update($uac, $g1->id, [], $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertUpdateDryRunValidationException($e, 'groups_users.at_least_one_group_manager');
        }
    }

    public function testUpdateError_UpdateGroupUser_Validation_UpdateGroupUserOfAnotherGroup()
    {
        [$r1, $g1, $g2, $userAId, $userBId] = $this->insertFixture_UpdateGroupUser_Validation_UpdateGroupUserOfAnotherGroup();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userAGroup2UserId = $this->groupsUsersTable->findByGroupIdAndUserId($g2->id, $userAId)->first()->id;
        $data = [
            ['id' => $userAGroup2UserId, 'is_admin' => false],
        ];

        try {
            $this->service->update($uac, $g1->id, [], $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertUpdateDryRunValidationException($e, 'groups_users.0.id.exists');
        }
    }

    private function insertFixture_UpdateGroupUser_Validation_UpdateGroupUserOfAnotherGroup()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
        ]]);
        $g2 = $this->addGroup(['name' => 'G2', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => true],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$r1, $g1, $g2, $userAId, $userBId];
    }

    /* DELETE GROUP USER */

    public function testUpdateSuccess_DeleteGroupUser()
    {
        [$r1, $g1, $userAId, $userBId, $userCId] = $this->insertFixture_DeleteGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userBGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userBId)->first()->id;
        $userCGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userCId)->first()->id;
        $data = [
            ['id' => $userBGroupUserId, 'delete' => true],
            ['id' => $userCGroupUserId, 'delete' => true],
        ];
        $this->service->update($uac, $g1->id, [], $data);

        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsNotMemberOf($g1->id, $userBId);
        $this->assertUserIsNotMemberOf($g1->id, $userCId);
        $this->assertSecretExists($r1->id, $userAId);
        $this->assertSecretNotExist($r1->id, $userBId);
        $this->assertSecretNotExist($r1->id, $userCId);
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

    public function testUpdateSuccess_DeleteGroupUser_UserHasOtherAccessToTheResource()
    {
        [$r1, $g1, $userAId, $userBId] = $this->insertFixture_DeleteGroupUser_UserHasOtherAccessToTheResource();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userBGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userBId)->first()->id;
        $data = [
            ['id' => $userBGroupUserId, 'delete' => true],
        ];
        $this->service->update($uac, $g1->id, [], $data);

        $this->assertUserIsMemberOf($g1->id, $userAId, true);
        $this->assertUserIsNotMemberOf($g1->id, $userBId);
        $this->assertSecretExists($r1->id, $userAId);
        $this->assertSecretExists($r1->id, $userBId);
    }

    private function insertFixture_DeleteGroupUser_UserHasOtherAccessToTheResource()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => true],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userBId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$r1, $g1, $userAId, $userBId];
    }

    public function testUpdateError_DeleteGroupUser_GroupUserValidation_GroupUserNotExist()
    {
        [$r1, $g1, $userAId, $userBId, $userCId] = $this->insertFixture_DeleteGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userGroupNotExist = UuidFactory::uuid();
        $data = [
            ['id' => $userGroupNotExist, 'delete' => true],
        ];

        try {
            $this->service->update($uac, $g1->id, [], $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertUpdateDryRunValidationException($e, 'groups_users.0.id.exists');
        }
    }

    public function testUpdateError_DeleteGroupUser_GroupUserBuildRuleValidation_AtLeastOneGroupManager()
    {
        [$r1, $g1, $userAId, $userBId, $userCId] = $this->insertFixture_DeleteGroupUser();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userAGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userAId)->first()->id;
        $userBGroupUserId = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userBId)->first()->id;
        $data = [
            ['id' => $userAGroupUserId, 'delete' => true],
            ['id' => $userBGroupUserId, 'delete' => true],
        ];

        try {
            $this->service->update($uac, $g1->id, [], $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertUpdateDryRunValidationException($e, 'groups_users.at_least_one_group_manager');
        }
    }

    public function testUpdateError_DeleteGroupUser_Validation_DeleteGroupUserOfAnotherGroup()
    {
        [$r1, $g1, $g2, $userAId, $userBId] = $this->insertFixture_DeleteGroupUser_Validation_DeleteGroupUserOfAnotherGroup();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userAGroup2UserId = $this->groupsUsersTable->findByGroupIdAndUserId($g2->id, $userAId)->first()->id;
        $data = [
            ['id' => $userAGroup2UserId, 'delete' => true],
        ];

        try {
            $this->service->update($uac, $g1->id, [], $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertUpdateDryRunValidationException($e, 'groups_users.0.id.exists');
        }
    }

    private function insertFixture_DeleteGroupUser_Validation_DeleteGroupUserOfAnotherGroup()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
        ]]);
        $g2 = $this->addGroup(['name' => 'G2', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => true],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$r1, $g1, $g2, $userAId, $userBId];
    }
}
