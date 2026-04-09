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
 * @since         2.0.0
 */
namespace App\Test\TestCase\Controller\Users;

use App\Model\Entity\Permission;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\GroupsUsersModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

/**
 * @covers \App\Controller\Users\UsersDeleteController
 */
class UsersDeleteControllerTest extends AppIntegrationTestCase
{
    use GroupsModelTrait;
    use GroupsUsersModelTrait;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    public $Permissions;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    public $Resources;

    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    public $GroupsUsers;

    public function setUp(): void
    {
        parent::setUp();

        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        RoleFactory::make()->guest()->persist();
    }

    public function testUsersDeleteController_DryRun_Success(): void
    {
        $this->logInAsAdmin();
        $userA = UserFactory::make()->user()->persist();
        $this->deleteJson('/users/' . $userA->id . '/dry-run.json');
        $this->assertSuccess();
        $this->assertUserIsNotSoftDeleted($userA->id);
    }

    public function testUsersDeleteController_DryRun_Error_MissingCsrfToken(): void
    {
        $this->disableCsrfToken();
        $this->logInAsAdmin();
        $userA = UserFactory::make()->user()->persist();
        $this->delete("/users/$userA->id/dry-run.json");
        $this->assertResponseCode(403);
    }

    public function testUsersDeleteController_DryRun_Error(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        $this->deleteJson("/users/$userA->id/dry-run.json");
        $this->assertError(400);
        $this->assertStringContainsString(
            'sole group manager',
            $this->_responseJsonHeader->message
        );
    }

    public function testUsersDeleteController_DryRun_Error_NotJson(): void
    {
        $this->logInAsAdmin();
        $userA = UserFactory::make()->user()->persist();
        $this->delete('/users/' . $userA->id . '/dry-run');
        $this->assertResponseCode(404);
    }

    public function testUsersDeleteController_Success(): void
    {
        $this->logInAsAdmin();
        $userA = UserFactory::make()->user()->persist();
        $this->deleteJson("/users/$userA->id.json");
        $this->assertSuccess();
        $this->assertUserIsSoftDeleted($userA->id);
    }

    public function testUsersDeleteController_Error_NotJson(): void
    {
        $this->logInAsAdmin();
        $userA = UserFactory::make()->user()->persist();
        $this->delete("/users/$userA->id");
        $this->assertResponseCode(404);
    }

    public function testUsersDeleteController_Error_MissingCsrfToken(): void
    {
        $this->disableCsrfToken();
        $this->logInAsAdmin();
        $userA = UserFactory::make()->user()->persist();
        $this->deleteJson("/users/$userA->id.json");
        $this->assertResponseCode(403);
    }

    public function testUsersDeleteController_Error_NotLoggedIn(): void
    {
        $userA = UserFactory::make()->user()->persist();
        $this->deleteJson("/users/$userA->id.json");
        $this->assertAuthenticationError();
    }

    public function testUsersDeleteController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $userA = UserFactory::make()->user()->persist();
        $this->deleteJson("/users/$userA->id.json");
        $this->assertForbiddenError('You are not authorized to access that location.');
    }

    public function testUsersDeleteController_Error_InvalidUser(): void
    {
        $this->logInAsAdmin();
        $userId = '0';
        $this->deleteJson("/users/$userId.json");
        $this->assertError(400, 'The user identifier should be a valid UUID.');

        $this->logInAsAdmin();
        $userId = 'true';
        $this->deleteJson("/users/$userId.json");
        $this->assertError(400, 'The user identifier should be a valid UUID.');

        $this->logInAsAdmin();
        $userId = 'null';
        $this->deleteJson("/users/$userId.json");
        $this->assertError(400, 'The user identifier should be a valid UUID.');

        $this->logInAsAdmin();
        $userId = '🔥';
        $this->deleteJson("/users/$userId.json");
        $this->assertError(400, 'The user identifier should be a valid UUID.');
    }

    public function testUsersDeleteController_Error_UserDoesNotExist(): void
    {
        $this->logInAsAdmin();
        $userId = UuidFactory::uuid('user.id.bogus');
        $this->deleteJson("/users/$userId.json");
        $this->assertError(404, 'The user does not exist or has been already deleted.');
    }

    public function testUsersDeleteController_Error_UserAlreadyDeleted(): void
    {
        $this->logInAsAdmin();
        $userDeleted = UserFactory::make()->user()->deleted()->persist();
        $this->deleteJson("/users/$userDeleted->id.json");
        $this->assertError(404, 'The user does not exist or has been already deleted.');
    }

    public function testUsersDeleteController_Error_CannotDeleteSelf(): void
    {
        $admin = $this->logInAsAdmin();
        $userId = $admin->id;
        $this->deleteJson("/users/{$userId}.json");
        $this->assertError(400, 'You are not allowed to delete yourself.');
    }

    public function testUsersDeleteController_Error_CannotDeleteSelf_UpperCase(): void
    {
        $admin = $this->logInAsAdmin();
        $userId = strtoupper($admin->id);
        $this->deleteJson("/users/{$userId}.json");
        $this->assertError(400, 'You are not allowed to delete yourself.');
    }

    public function testUsersDeleteController_Success_NoOwnerNoResourcesSharedNoGroupsMember_DelUserCase0(): void
    {
        $this->logInAsAdmin();
        $userA = UserFactory::make()->user()->persist();
        $this->deleteJson("/users/$userA->id.json");
        $this->assertSuccess();
        $this->assertUserIsSoftDeleted($userA->id);
    }

    public function testUsersDeleteController_Success_SoleOwnerNotSharedResource_DelUserCase1(): void
    {
        $this->logInAsAdmin();
        $userA = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->persist();
        $this->deleteJson("/users/$userA->id.json");
        $this->assertSuccess();
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertResourceIsSoftDeleted($resource->id);
    }

    public function testUsersDeleteController_Error_SoleOwnerSharedResourceWithUser_DelUserCase2(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();
        $this->deleteJson("/users/$userA->id.json");

        $this->assertError(400);
        $this->assertUserIsNotSoftDeleted($userA->id);
        $this->assertResourceIsNotSoftDeleted($resourceA->id);
        $this->assertStringContainsString('sole owner of shared content', $this->_responseJsonHeader->message);

        $errors = $this->_responseJsonBody->errors;
        $this->assertFalse(isset($errors->groups));
        $this->assertEquals(1, count($errors->resources->sole_owner));

        $resource = $errors->resources->sole_owner[0];
        $this->assertResourceAttributes($resource);
        $this->assertEquals($resource->id, $resourceA->id);
    }

    public function testUsersDeleteController_Error_TransferOwnersOfAnotherResource_SoleOwnerSharedResourceWithUser_DelUserCase2(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB, $userC] = UserFactory::make(3)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userC])->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();
        $resourceB = ResourceFactory::make()
            ->withPermissionsFor([$userC])
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();

        $permission = $this->Permissions
            ->find()
            ->where([
                'aco_foreign_key' => $resourceB->id,
                'aro_foreign_key' => $group->id,
            ])
            ->firstOrFail();

        $transfer['owners'][] = ['id' => $permission->id, 'aco_foreign_key' => $resourceB->id];
        $this->deleteJson("/users/$userA->id.json", ['transfer' => $transfer]);

        $this->assertError(400, 'The transfer is not authorized');
        $this->assertUserIsNotSoftDeleted($userA->id);
    }

    public function testUsersDeleteController_Error_TransferOwnersBadGroupUserId_SoleOwnerSharedResourceWithUser_DelUserCase2(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB, $userC] = UserFactory::make(3)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userC])->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();
        $resourceB = ResourceFactory::make()
            ->withPermissionsFor([$userC])
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();

        $transfer['owners'][] = ['id' => 'invalid-uuid', 'aco_foreign_key' => $resourceB->id];
        $this->deleteJson("/users/$userA->id.json", ['transfer' => $transfer]);

        $this->assertError(400, 'The permissions identifiers must be valid UUID.');
        $this->assertUserIsNotSoftDeleted($userA->id);
    }

    public function testUsersDeleteController_Success_SoleOwnerSharedResourceWithUser_DelUserCase2(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();

        $permission = $this->Permissions
            ->find()
            ->where([
                'aco_foreign_key' => $resource->id,
                'aro_foreign_key' => $userB->id,
            ])
            ->firstOrFail();

        $transfer['owners'][] = ['id' => $permission->id, 'aco_foreign_key' => $resource->id];
        $this->deleteJson("/users/$userA->id.json", ['transfer' => $transfer]);
        $this->assertSuccess();

        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);
        $this->assertPermission($resource->id, $userB->id, Permission::OWNER);
    }

    public function testUsersDeleteController_Success_SoftDeleteSharedResourceWithMe_DelUserCase3(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();

        $this->deleteJson("/users/$userB->id.json");
        $this->assertSuccess();
        $this->assertUserIsSoftDeleted($userB->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);
    }

    public function testUsersDeleteController_Error_SoleOwnerSharedResourceWithGroup_DelUserCase4(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userB])->persist();
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();

        $this->deleteJson("/users/$userA->id.json");
        $this->assertError(400);

        $this->assertUserIsNotSoftDeleted($userA->id);
        $this->assertResourceIsNotSoftDeleted($resourceA->id);

        $errors = $this->_responseJsonBody->errors;
        $this->assertFalse(isset($errors->groups));
        $this->assertEquals(1, count($errors->resources->sole_owner));

        $resource = $errors->resources->sole_owner[0];
        $this->assertResourceAttributes($resource);
        $this->assertEquals($resource->id, $resourceA->id);
    }

    public function testUsersDeleteController_Success_SoleOwnerSharedResourceWithGroup_DelUserCase4(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userB])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();

        $permission = $this->Permissions
            ->find()
            ->where([
                'aco_foreign_key' => $resource->id,
                'aro_foreign_key' => $group->id,
            ])
            ->firstOrFail();

        $transfer['owners'][] = ['id' => $permission->id, 'aco_foreign_key' => $resource->id];
        $this->deleteJson("/users/$userA->id.json", ['transfer' => $transfer]);

        $this->assertSuccess();
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);
        $this->assertPermission($resource->id, $group->id, Permission::OWNER);
    }

    public function testUsersDeleteController_Success_SoleOwnerSharedResourceWithSoleManageEmptyGroup_DelUserCase5(): void
    {
        $this->logInAsAdmin();
        $userA = UserFactory::make()->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();

        $this->deleteJson("/users/$userA->id.json");
        $this->assertSuccess();

        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertResourceIsSoftDeleted($resource->id);
        $this->assertGroupIsSoftDeleted($group->id);
    }

    public function testUsersDeleteController_Success_ownerSharedResourceAlongWithSoleManagerEmptyGroup_DelUserCase6(): void
    {
        $this->logInAsAdmin();
        $userA = UserFactory::make()->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();

        // CONTEXTUAL TEST CHANGES Make the group also owner of the resource
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $group->id,
            'aco_foreign_key' => $resource->id,
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        $this->deleteJson("/users/$userA->id.json");
        $this->assertSuccess();

        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertResourceIsSoftDeleted($resource->id);
        $this->assertGroupIsSoftDeleted($group->id);
    }

    public function testUsersDeleteController_Success_indirectlyOwnerSharedResourceWithSoleManagerEmptyGroup_DelUserCase7(): void
    {
        $this->logInAsAdmin();
        $userA = UserFactory::make()->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();

        // CONTEXTUAL TEST CHANGES Remove the direct permission of nancy
        $this->Permissions->deleteAll(['aro_foreign_key IN' => $userA->id, 'aco_foreign_key' => $resource->id]);
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $group->id,
            'aco_foreign_key' => $resource->id,
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        $this->deleteJson("/users/$userA->id.json");
        $this->assertSuccess();

        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertResourceIsSoftDeleted($resource->id);
        $this->assertGroupIsSoftDeleted($group->id);
    }

    public function testUsersDeleteController_Error_soleManagerOfNotEmptyGroup_DelUserCase9(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB, $userC] = UserFactory::make(3)->user()->persist();
        $groupA = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB, $userC])
            ->persist();

        $this->deleteJson("/users/$userA->id.json");
        $this->assertError(400);
        $this->assertUserIsNotSoftDeleted($userA->id);

        $errors = $this->_responseJsonBody->errors;
        $this->assertCount(1, $errors->groups->sole_manager);
        $this->assertFalse(isset($errors->resources));

        $group = $errors->groups->sole_manager[0];
        $this->assertGroupAttributes($group);
        $this->assertEquals($group->id, $groupA->id);
    }

    public function testUsersDeleteController_Success_soleManagerOfNotEmptyGroup_DelUserCase9(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB, $userC] = UserFactory::make(3)->user()->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB, $userC])
            ->persist();

        $groupUser = $this->GroupsUsers
            ->find()
            ->where([
            'user_id' => $userB->id,
            'group_id' => $group->id,
        ])->firstOrFail();

        $transfer['managers'][] = ['id' => $groupUser->id, 'group_id' => $group->id];
        $this->deleteJson("/users/$userA->id.json", ['transfer' => $transfer]);
        $this->assertSuccess();

        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertGroupIsNotSoftDeleted($group->id);
        $this->assertUserIsAdmin($group->id, $userB->id);
    }

    public function testUsersDeleteController_Error_ownerAlongWithSoleManagerOfNotEmptyGroup_DelUserCase10(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $groupA = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$userA, $groupA])->persist();

        $this->deleteJson("/users/$userA->id.json");
        $this->assertError(400);
        $this->assertUserIsNotSoftDeleted($userA->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);

        $errors = $this->_responseJsonBody->errors;
        $this->assertCount(1, $errors->groups->sole_manager);
        $this->assertFalse(isset($errors->resources));

        $group = $errors->groups->sole_manager[0];
        $this->assertGroupAttributes($group);
        $this->assertEquals($group->id, $groupA->id);
    }

    public function testUsersDeleteController_Success_ownerAlongWithSoleManagerOfNotEmptyGroup_DelUserCase10(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        ResourceFactory::make()->withPermissionsFor([$userA, $group])->persist();

        $groupUser = $this->GroupsUsers
            ->find()
            ->where([
                'user_id' => $userB->id,
                'group_id' => $group->id,
            ])->firstOrFail();

        $transfer['managers'][] = ['id' => $groupUser->id, 'group_id' => $group->id];
        $this->deleteJson("/users/$userA->id.json", ['transfer' => $transfer]);
        $this->assertSuccess();

        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertGroupIsNotSoftDeleted($group->id);
        $this->assertUserIsAdmin($group->id, $userB->id);
    }

    public function testUsersDeleteController_Error_indireclyOwnerWithSoleManagerOfNotEmptyGroup_DelUserCase11(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $groupA = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$userA, $groupA])->persist();

        // CONTEXTUAL TEST CHANGES Remove The permissions of Orna
        $this->Permissions->deleteAll([
            'aro_foreign_key' => $userA->id,
            'aco_foreign_key' => $resource->id,
        ]);

        $this->deleteJson("/users/$userA->id.json");
        $this->assertError(400);
        $this->assertUserIsNotSoftDeleted($userA->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);

        $errors = $this->_responseJsonBody->errors;
        $this->assertCount(1, $errors->groups->sole_manager);
        $this->assertFalse(isset($errors->resources));

        $group = $errors->groups->sole_manager[0];
        $this->assertGroupAttributes($group);
        $this->assertEquals($group->id, $groupA->id);
    }

    public function testUsersDeleteController_Error_TransferManagersBadPermissionId_indireclyOwnerWithSoleManagerOfNotEmptyGroup_DelUserCase11(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$userA, $group])->persist();

        // CONTEXTUAL TEST CHANGES Remove The permissions of Orna
        $this->Permissions->deleteAll([
            'aro_foreign_key' => $userA->id,
            'aco_foreign_key' => $resource->id,
        ]);

        $transfer['managers'][] = ['id' => 'invalid-uuid', 'group_id' => $group->id];
        $this->deleteJson("/users/$userA->id.json", ['transfer' => $transfer]);
        $this->assertError(400, 'The groups users identifiers must be valid UUID.');
        $this->assertUserIsNotSoftDeleted($userA->id);
    }

    public function testUsersDeleteController_Success_indireclyOwnerWithSoleManagerOfNotEmptyGroup_DelUserCase11(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$userA, $group])->persist();

        // CONTEXTUAL TEST CHANGES Remove The permissions of Orna
        $this->Permissions->deleteAll([
            'aro_foreign_key' => $userA->id,
            'aco_foreign_key' => $resource->id,
        ]);

        $groupUser = $this->GroupsUsers
            ->find()
            ->where([
                'user_id' => $userB->id,
                'group_id' => $group->id,
            ])->firstOrFail();

        $transfer['managers'][] = ['id' => $groupUser->id, 'group_id' => $group->id];
        $this->deleteJson("/users/$userA->id.json", ['transfer' => $transfer]);
        $this->assertSuccess();

        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertGroupIsNotSoftDeleted($group->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);
        $this->assertUserIsAdmin($group->id, $userB->id);
    }

    public function testUsersDeleteController_Error_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroup_DelUserCase12(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();

        $this->deleteJson("/users/$userA->id.json");
        $this->assertError(400);
        $this->assertUserIsNotSoftDeleted($userA->id);

        $errors = $this->_responseJsonBody->errors;
        $this->assertFalse(isset($errors->groups));
        $this->assertCount(1, $errors->resources->sole_owner);

        $resource = $errors->resources->sole_owner[0];
        $this->assertGroupAttributes($resource);
        $this->assertEquals($resource->id, $resourceA->id);
    }

    public function testUsersDeleteController_Success_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroup_DelUserCase12(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();

        // CONTEXTUAL TEST CHANGES Remove The permissions of Orna
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $userB->id,
            'aco_foreign_key' => $resource->id,
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        $this->deleteJson("/users/$userA->id.json");
        $this->assertSuccess();

        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertGroupIsSoftDeleted($group->id);
    }

    public function testUsersDeleteController_Success_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroups_DelUserCase13(): void
    {
        $this->logInAsAdmin();
        $userA = UserFactory::make()->user()->persist();
        [$groupA, $groupB] = GroupFactory::make(2)->withGroupsManagersFor([$userA])->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$groupA, $groupB])->persist();

        $this->deleteJson("/users/$userA->id.json");
        $this->assertSuccess();
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertGroupIsSoftDeleted($groupA->id);
        $this->assertGroupIsSoftDeleted($groupB->id);
        $this->assertResourceIsSoftDeleted($resource->id);
    }

    public function testUsersDeleteController_Error_indirectlyOwnerSharedResourceWithSoleManagerOfNonEmptyGroup_DelUserCase14(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB, $userC] = UserFactory::make(3)->user()->persist();
        $groupA = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$groupA])
            ->withPermissionsFor([$userC], Permission::READ)
            ->persist();

        $this->deleteJson("/users/$userA->id.json");
        $this->assertError(400);
        $this->assertUserIsNotSoftDeleted($userA->id);

        $errors = $this->_responseJsonBody->errors;
        $this->assertCount(1, $errors->groups->sole_manager);
        $this->assertFalse(isset($errors->resources));

        $group = $errors->groups->sole_manager[0];
        $this->assertGroupAttributes($group);
        $this->assertEquals($group->id, $groupA->id);
    }

    public function testUsersDeleteController_Success_indirectlyOwnerSharedResourceWithSoleManagerOfNonEmptyGroup_DelUserCase14(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB, $userC] = UserFactory::make(3)->user()->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->withPermissionsFor([$userC], Permission::READ)
            ->persist();

        $groupUser = $this->GroupsUsers
            ->find()
            ->where([
                'user_id' => $userB->id,
                'group_id' => $group->id,
            ])->firstOrFail();

        $transfer['managers'][] = ['id' => $groupUser->id, 'group_id' => $group->id];
        $this->deleteJson("/users/$userA->id.json", ['transfer' => $transfer]);

        $this->assertSuccess();
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertGroupIsNotSoftDeleted($group->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);
        $this->assertUserIsAdmin($group->id, $userB->id);
    }

    public function testUsersDeleteController_Error_SoleOwnerSharedResourceWithNotEmptyGroup_DelUserCase15(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $groupA = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        $resourceA = ResourceFactory::make()->withPermissionsFor([$userA, $groupA])->persist();

        // CONTEXTUAL TEST CHANGES Change the permission of the group to READ
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $groupA->id,
            'aco_foreign_key' => $resourceA->id,
        ])->first();
        $permission->type = Permission::READ;
        $this->Permissions->save($permission);

        $this->deleteJson("/users/$userA->id.json");
        $this->assertError(400);
        $this->assertUserIsNotSoftDeleted($userA->id);

        $errors = $this->_responseJsonBody->errors;
        $this->assertCount(1, $errors->groups->sole_manager);
        $this->assertCount(1, $errors->resources->sole_owner);

        $group = $errors->groups->sole_manager[0];
        $this->assertGroupAttributes($group);
        $this->assertEquals($group->id, $groupA->id);

        $resource = $errors->resources->sole_owner[0];
        $this->assertGroupAttributes($resource);
        $this->assertEquals($resource->id, $resourceA->id);
    }

    public function testUsersDeleteController_Success_SoleOwnerSharedResourceWithNotEmptyGroup_DelUserCase15(): void
    {
        $this->logInAsAdmin();
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$userA, $group])->persist();

        // CONTEXTUAL TEST CHANGES Change the permission of the group to READ
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $group->id,
            'aco_foreign_key' => $resource->id,
        ])->first();
        $permission->type = Permission::READ;
        $this->Permissions->save($permission);

        $permission = $this->Permissions
            ->find()
            ->where([
                'aco_foreign_key' => $resource->id,
                'aro_foreign_key' => $group->id,
            ])
            ->firstOrFail();
        $groupUser = $this->GroupsUsers
            ->find()
            ->where([
                'user_id' => $userB->id,
                'group_id' => $group->id,
            ])->firstOrFail();

        $transfer['owners'][] = ['id' => $permission->id, 'aco_foreign_key' => $resource->id];
        $transfer['managers'][] = ['id' => $groupUser->id, 'group_id' => $group->id];
        $this->deleteJson("/users/$userA->id.json", ['transfer' => $transfer]);
        $this->assertSuccess();
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertUserIsAdmin($group->id, $userB->id);
        $this->assertPermission($resource->id, $group->id, Permission::OWNER);
    }
}
