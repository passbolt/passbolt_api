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
namespace App\Test\TestCase\Controller\Groups;

use App\Model\Entity\Permission;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class GroupsDeleteControllerTest extends AppIntegrationTestCase
{
    use GroupsModelTrait;

    public $Groups;
    public $Permissions;

    public function setUp(): void
    {
        parent::setUp();
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
    }

    public function testGroupsDeleteDryRunSuccess(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $this->loginAs($admin);

        $user = UserFactory::make()->user()->persist();
        $groupId = GroupFactory::make()->withGroupsManagersFor([$user])->persist()->get('id');

        $this->deleteJson('/groups/' . $groupId . '/dry-run.json');
        $this->assertSuccess();
        $group = $this->Groups->get($groupId);
        $this->assertFalse($group->deleted);
    }

    public function testGroupsDeleteDryRunError_MissingCsrfToken(): void
    {
        $this->disableCsrfToken();

        $admin = UserFactory::make()->admin()->persist();
        $this->loginAs($admin);
        $user = UserFactory::make()->user()->persist();
        $groupId = GroupFactory::make()->withGroupsManagersFor([$user])->persist()->get('id');

        $this->delete('/groups/' . $groupId . '/dry-run.json');
        $this->assertResponseCode(403);
    }

    public function testGroupsDeleteDryRunError(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $this->loginAs($admin);

        $user = UserFactory::make()->user()->persist();
        $userCanRead = UserFactory::make()->user()->persist();

        $group = GroupFactory::make()->withGroupsManagersFor([$user])->persist();
        $groupId = $group->get('id');

        $resource = ResourceFactory::make()->withPermissionsFor([$group], Permission::OWNER)->withPermissionsFor([$userCanRead], Permission::READ)->persist();

        $this->deleteJson('/groups/' . $groupId . '/dry-run.json');
        $this->assertError(400);
        $this->assertStringContainsString(
            'transfer the ownership',
            $this->_responseJsonHeader->message
        );
    }

    public function testGroupsDeleteNotLoggedInError(): void
    {
        $user = UserFactory::make()->user()->persist();

        $groupId = GroupFactory::make()->withGroupsManagersFor([$user])->persist()->get('id');

        $this->deleteJson('/groups/' . $groupId . '.json');
        $this->assertAuthenticationError();
    }

    public function testGroupsDeleteNotAdminError(): void
    {
        $authentifiedUser = UserFactory::make()->user()->persist();
        $this->loginAs($authentifiedUser);

        $user = UserFactory::make()->user()->persist();
        $groupId = GroupFactory::make()->withGroupsManagersFor([$user])->persist()->get('id');

        $this->deleteJson('/groups/' . $groupId . '.json');
        $this->assertForbiddenError('You are not authorized to access that location.');
    }

    public function testGroupsDeleteInvalidGroupError(): void
    {
        $admin = UserFactory::make()->admin()->persist();

        $this->loginAs($admin);
        $groupId = '0';
        $this->deleteJson('/groups/' . $groupId . '.json?api-version=v2');
        $this->assertError(400, 'The group identifier should be a valid UUID.');

        $this->loginAs($admin);
        $groupId = 'true';
        $this->deleteJson('/groups/' . $groupId . '.json?api-version=v2');
        $this->assertError(400, 'The group identifier should be a valid UUID.');

        $this->loginAs($admin);
        $groupId = 'null';
        $this->deleteJson('/groups/' . $groupId . '.json?api-version=v2');
        $this->assertError(400, 'The group identifier should be a valid UUID.');

        $this->loginAs($admin);
        $groupId = '🔥';
        $this->deleteJson('/groups/' . $groupId . '.json');
        $this->assertError(400, 'The group identifier should be a valid UUID.');
    }

    public function testGroupsDeleteGroupDoesNotExistError(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $this->loginAs($admin);

        $groupId = UuidFactory::uuid();

        $this->deleteJson('/groups/' . $groupId . '.json?api-version=v2');
        $this->assertError(404, 'The group does not exist or has been already deleted.');
    }

    public function testGroupsDeleteGroupAlreadyDeletedError(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $this->loginAs($admin);

        $user = UserFactory::make()->user()->persist();
        $groupId = GroupFactory::make()->withGroupsManagersFor([$user])->persist()->get('id');

        $this->deleteJson('/groups/' . $groupId . '.json');
        $this->deleteJson('/groups/' . $groupId . '.json');
        $this->assertError(404, 'The group does not exist or has been already deleted.');
    }

    public function testGroupsDeleteSuccess_NoOwnerNoResourcesSharedNoGroupsMember_DelGroupCase0(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $this->loginAs($admin);

        $groupId = GroupFactory::make()->withGroupsManagersFor([$admin])->persist()->get('id');

        $this->deleteJson("/groups/$groupId.json");
        $this->assertSuccess();
        $this->assertGroupIsSoftDeleted($groupId);
    }

    public function testGroupsDeleteSucces_SharedResourceWithMe_DelGroupCase1(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $user = UserFactory::make()->user()->persist();
        $this->loginAs($admin);

        $groupId = GroupFactory::make()->withGroupsManagersFor([$admin])->persist()->get('id');
        $resource = ResourceFactory::make()->withCreatorAndPermission($admin)->withPermissionsFor([$user], Permission::READ)->persist();

        $this->deleteJson("/groups/$groupId.json");
        $this->assertSuccess();
        $this->assertGroupIsSoftDeleted($groupId);
    }

    public function testGroupsDeleteSucces_SoleOwnerNotSharedResource_DelGroupCase2(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $this->loginAs($admin);

        $groupId = GroupFactory::make()->withGroupsManagersFor([$admin])->persist()->get('id');
        $resource = ResourceFactory::make()->withCreatorAndPermission($admin)->persist();

        $this->deleteJson("/groups/$groupId.json");
        $this->assertSuccess();
        $this->assertGroupIsSoftDeleted($groupId);
    }

    private function applyPermissionChangesForCase3($resourceId, $groupId, $userId): void
    {
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $userId,
            'aco_foreign_key' => $resourceId,
        ])->first();
        $permission->type = Permission::READ;
        $this->Permissions->save($permission);
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $groupId,
            'aco_foreign_key' => $resourceId,
            ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);
    }

    public function testGroupsDeleteError_SoleOwnerSharedResource_DelGroupCase3(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $this->loginAs($admin);

        $group = GroupFactory::make()->withGroupsManagersFor([$admin])->persist();
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user, $group], Permission::OWNER)->persist();

        $userId = $user->get('id');
        $resourceId = $resource->get('id');
        $groupId = $group->get('id');

        // CONTEXTUAL TEST CHANGES Make the group sole owner of the resource
        $this->applyPermissionChangesForCase3($resourceId, $groupId, $userId);

        $this->deleteJson("/groups/$groupId.json");
        $this->assertError(400);
        $this->assertGroupIsNotSoftDeleted($groupId);
        $this->assertStringContainsString('transfer the ownership', $this->_responseJsonHeader->message);

        $errors = $this->_responseJsonBody->errors;
        $this->assertEquals(1, count($errors->resources->sole_owner));

        $resource = $errors->resources->sole_owner[0];
        $this->assertResourceAttributes($resource);
        $this->assertEquals($resource->id, $resourceId);
    }

    public function testGroupsDeleteError_TransferOwnersOfAnotherResource_SoleOwnerSharedResource_DelGroupCase3(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $this->loginAs($admin);

        $group = GroupFactory::make()->withGroupsManagersFor([$admin])->persist();
        $user = UserFactory::make()->user()->persist();
        $resourceN = ResourceFactory::make()->withPermissionsFor([$user], Permission::OWNER)->withPermissionsFor([$group], Permission::READ)->persist();
        $resourceSId = ResourceFactory::make()->withPermissionsFor([$group], Permission::READ)->persist()->get('id');

        $userId = $user->get('id');
        $resourceId = $resourceN->get('id');
        $groupId = $group->get('id');

        // CONTEXTUAL TEST CHANGES Make the group sole owner of the resource
        $this->applyPermissionChangesForCase3($resourceId, $groupId, $userId);

        $transfer['owners'][] = ['id' => $userId, 'aco_foreign_key' => $resourceSId];
        $this->deleteJson("/groups/$groupId.json", ['transfer' => $transfer]);
        $this->assertError(400, 'The transfer is not authorized');
        $this->assertGroupIsNotSoftDeleted($groupId);
    }

    public function testGroupsDeleteError_TransferOwnersBadGroupUserId_SoleOwnerSharedResource_DelGroupCase3(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $this->loginAs($admin);

        $group = GroupFactory::make()->withGroupsManagersFor([$admin])->persist();
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user, $group], Permission::OWNER)->persist();

        $userId = $user->get('id');
        $resourceId = $resource->get('id');
        $groupId = $group->get('id');

        // CONTEXTUAL TEST CHANGES Make the group sole owner of the resource
        $this->applyPermissionChangesForCase3($resourceId, $groupId, $userId);

        $transfer['owners'][] = ['id' => 'invalid-uuid', 'aco_foreign_key' => $resourceId];
        $this->deleteJson("/groups/$groupId.json", ['transfer' => $transfer]);
        $this->assertError(400, 'The permissions identifiers must be valid UUID.');
        $this->assertGroupIsNotSoftDeleted($groupId);
    }

    #WIP

    public function testGroupsDeleteSuccess_SoleOwnerSharedResource_DelGroupCase3(): void
    {
        // A group which is sole owner of a resource shared with another user
        // Exc "The group cannot be deleted. The group should not be sole owner of shared content, transfer the ownership to other users."
        $admin = UserFactory::make()->admin()->persist();
        $this->loginAs($admin);
        $user = UserFactory::make()->user()->persist();
        $userId = $user->get('id');

        $group = GroupFactory::make()->withGroupsManagersFor([$admin])->persist();
        $groupId = $group->get('id');

        $resource = ResourceFactory::make()->withPermissionsFor([$group], Permission::OWNER)->withPermissionsFor([$user], Permission::READ)->persist();
        $resourceId = $resource->get('id');

        $permissionUserId = PermissionFactory::make()->aroUser($user)->acoResource($resource)->persist()->get('id');

        // CONTEXTUAL TEST CHANGES Make the group sole owner of the resource
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $userId,
            'aco_foreign_key' => $resourceId,
        ])->first();
        $permission->type = Permission::READ;
        $this->Permissions->save($permission);
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $groupId,
            'aco_foreign_key' => $resourceId,
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        $transfer['owners'][] = ['id' => $permissionUserId, 'aco_foreign_key' => $resourceId];
        $this->deleteJson("/groups/$groupId.json", ['transfer' => $transfer]);
        $this->assertSuccess();
        $this->assertGroupIsSoftDeleted($groupId);
        $this->assertPermission($resourceId, $userId, Permission::OWNER);
    }

    public function testGroupsSoftDeleteSuccess_OwnerAlongWithAnotherUser_DelGroupCase4(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $this->loginAs($admin);

        $group = GroupFactory::make()->withGroupsManagersFor([$admin])->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$admin], Permission::OWNER)->withPermissionsFor([$group], Permission::READ)->persist();

        $groupId = $group->get('id');

        $this->deleteJson("/groups/$groupId.json");
        $this->assertSuccess();
        $this->assertGroupIsSoftDeleted($groupId);
    }

    public function testGroupsDeleteAsGroupOwnerSuccess(): void
    {
        $user = UserFactory::make()->user()->persist();
        $this->loginAs($user);

        $groupId = GroupFactory::make()->withGroupsManagersFor([$user])->persist()->get('id');

        $this->deleteJson('/groups/' . $groupId . '.json');
        $this->assertSuccess();
        $this->assertGroupIsSoftDeleted($groupId);
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testGroupsDeleteController_Error_NotJson(): void
    {
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.procurement');
        $this->delete("/groups/$groupId");
        $this->assertResponseCode(404);
    }
}
