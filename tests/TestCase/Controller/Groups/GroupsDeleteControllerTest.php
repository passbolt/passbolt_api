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
namespace App\Test\TestCase\Controller\Groups;

use App\Model\Entity\Permission;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class GroupsDeleteControllerTest extends AppIntegrationTestCase
{
    use GroupsModelTrait;

    public $Groups;
    public $GroupsGroups;
    public $Permissions;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles',
        'app.Base/Resources', 'app.Base/Favorites', 'app.Base/Secrets',
        'app.Alt0/GroupsUsers', 'app.Alt0/Permissions', 'app.Base/Avatars',
        'app.Base/EmailQueue'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
        $this->GroupsGroups = TableRegistry::getTableLocator()->get('GroupsGroups');
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
    }

    public function testGroupsDeleteDryRunSuccess()
    {
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->deleteJson('/groups/' . $groupId . '/dry-run.json?api-version=v2');
        $this->assertSuccess();
        $group = $this->Groups->get($groupId);
        $this->assertFalse($group->deleted);
    }

    public function testGroupsDeleteDryRunError_MissingCsrfToken()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->delete('/groups/' . $groupId . '/dry-run.json?api-version=v2');
        $this->assertResponseCode(403);
    }

    public function testGroupsDeleteDryRunError()
    {
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.creative');
        $this->deleteJson('/groups/' . $groupId . '/dry-run.json?api-version=v2');
        $this->assertError(400);
        $this->assertContains(
            'You need to transfer the ownership for the shared passwords',
            $this->_responseJsonHeader->message
        );
    }

    public function testGroupsDeleteNotLoggedInError()
    {
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->deleteJson('/groups/' . $groupId . '.json?api-version=v2');
        $this->assertAuthenticationError();
    }

    public function testGroupsDeleteNotAdminError()
    {
        $this->authenticateAs('ada');
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->deleteJson('/groups/' . $groupId . '.json?api-version=v2');
        $this->assertForbiddenError('You are not authorized to access that location.');
    }

    public function testGroupsDeleteInvalidGroupError()
    {
        $this->authenticateAs('admin');
        $bogusId = '0';
        $this->deleteJson('/groups/' . $bogusId . '.json?api-version=v2');
        $this->assertError(400, 'The group id must be a valid uuid.');

        $this->authenticateAs('admin');
        $bogusId = 'true';
        $this->deleteJson('/groups/' . $bogusId . '.json?api-version=v2');
        $this->assertError(400, 'The group id must be a valid uuid.');

        $this->authenticateAs('admin');
        $bogusId = 'null';
        $this->deleteJson('/groups/' . $bogusId . '.json?api-version=v2');
        $this->assertError(400, 'The group id must be a valid uuid.');

        $this->authenticateAs('admin');
        $bogusId = '🔥';
        $this->deleteJson('/groups/' . $bogusId . '.json?api-version=v2');
        $this->assertError(400, 'The group id must be a valid uuid.');
    }

    public function testGroupsDeleteGroupDoesNotExistError()
    {
        $this->authenticateAs('admin');
        $bogusId = UuidFactory::uuid('group.id.bogus');
        $this->deleteJson('/groups/' . $bogusId . '.json?api-version=v2');
        $this->assertError(404, 'The group does not exist or has been already deleted.');
    }

    public function testGroupsDeleteGroupAlreadyDeletedError()
    {
        // Delete the group twice
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->deleteJson('/groups/' . $groupId . '.json?api-version=v2');
        $this->deleteJson('/groups/' . $groupId . '.json?api-version=v2');
        $this->assertError(404, 'The group does not exist or has been already deleted.');
    }

    public function testGroupsDeleteSuccess_NoOwnerNoResourcesSharedNoGroupsMember_DelGroupCase0()
    {
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.procurement');
        $this->deleteJson("/groups/$groupId.json?api-version=v2");
        $this->assertSuccess();
        $this->assertGroupIsSoftDeleted($groupId);
    }

    public function testGroupsDeleteSucces_SharedResourceWithMe_DelGroupCase1()
    {
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.quality_assurance');
        $this->deleteJson("/groups/$groupId.json?api-version=v2");
        $this->assertSuccess();
        $this->assertGroupIsSoftDeleted($groupId);
    }

    public function testGroupsDeleteSucces_SoleOwnerNotSharedResource_DelGroupCase2()
    {
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.resource_planning');
        $this->deleteJson("/groups/$groupId.json?api-version=v2");
        $this->assertSuccess();
        $this->assertGroupIsSoftDeleted($groupId);
    }

    private function applyPermissionChangesForCase3($resourceId, $groupId, $userId)
    {
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $userId,
            'aco_foreign_key' => $resourceId
        ])->first();
        $permission->type = Permission::READ;
        $this->Permissions->save($permission);
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $groupId,
            'aco_foreign_key' => $resourceId
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);
    }

    public function testGroupsDeleteError_SoleOwnerSharedResource_DelGroupCase3()
    {
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.quality_assurance');
        $resourceId = UuidFactory::uuid('resource.id.nodejs');
        $userId = UuidFactory::uuid('user.id.marlyn');

        // CONTEXTUAL TEST CHANGES Make the group sole owner of the resource
        $this->applyPermissionChangesForCase3($resourceId, $groupId, $userId);

        $this->deleteJson("/groups/$groupId.json?api-version=v2");
        $this->assertError(400);
        $this->assertGroupIsNotSoftDeleted($groupId);
        $this->assertContains('You need to transfer the ownership for the shared passwords', $this->_responseJsonHeader->message);

        $errors = $this->_responseJsonBody->errors;
        $this->assertEquals(1, count($errors->resources->sole_owner));

        $resource = $errors->resources->sole_owner[0];
        $this->assertResourceAttributes($resource);
        $this->assertEquals($resource->id, $resourceId);
    }

    public function testGroupsDeleteError_TransferOwnersOfAnotherResource_SoleOwnerSharedResource_DelGroupCase3()
    {
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.quality_assurance');
        $resourceId = UuidFactory::uuid('resource.id.nodejs');
        $resourceSId = UuidFactory::uuid('resource.id.selenium');
        $userId = UuidFactory::uuid('user.id.marlyn');

        // CONTEXTUAL TEST CHANGES Make the group sole owner of the resource
        $this->applyPermissionChangesForCase3($resourceId, $groupId, $userId);

        $transfer['owners'][] = ['id' => UuidFactory::uuid('permission.id.selenium-margaret'), 'aco_foreign_key' => $resourceSId];
        $this->deleteJson("/groups/$groupId.json?api-version=v2", ['transfer' => $transfer]);
        $this->assertError(400, 'The transfer is not authorized');
        $this->assertGroupIsNotSoftDeleted($groupId);
    }

    public function testGroupsDeleteError_TransferOwnersBadGroupUserId_SoleOwnerSharedResource_DelGroupCase3()
    {
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.quality_assurance');
        $resourceId = UuidFactory::uuid('resource.id.nodejs');
        $userId = UuidFactory::uuid('user.id.marlyn');

        // CONTEXTUAL TEST CHANGES Make the group sole owner of the resource
        $this->applyPermissionChangesForCase3($resourceId, $groupId, $userId);

        $transfer['owners'][] = ['id' => 'invalid-uuid', 'aco_foreign_key' => $resourceId];
        $this->deleteJson("/groups/$groupId.json?api-version=v2", ['transfer' => $transfer]);
        $this->assertError(400, 'The permissions ids must be valid uuids.');
        $this->assertGroupIsNotSoftDeleted($groupId);
    }

    public function testGroupsDeleteSuccess_SoleOwnerSharedResource_DelGroupCase3()
    {
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.quality_assurance');
        $resourceId = UuidFactory::uuid('resource.id.nodejs');
        $userMId = UuidFactory::uuid('user.id.marlyn');

        // CONTEXTUAL TEST CHANGES Make the group sole owner of the resource
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $userMId,
            'aco_foreign_key' => $resourceId
        ])->first();
        $permission->type = Permission::READ;
        $this->Permissions->save($permission);
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $groupId,
            'aco_foreign_key' => $resourceId
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        $transfer['owners'][] = ['id' => UuidFactory::uuid('permission.id.nodejs-marlyn'), 'aco_foreign_key' => $resourceId];
        $this->deleteJson("/groups/$groupId.json?api-version=v2", ['transfer' => $transfer]);
        $this->assertSuccess();
        $this->assertGroupIsSoftDeleted($groupId);
        $this->assertPermission($resourceId, $userMId, Permission::OWNER);
    }

    public function testGroupsSoftDeleteSuccess_OwnerAlongWithAnotherUser_DelGroupCase4()
    {
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.management');
        $this->deleteJson("/groups/$groupId.json?api-version=v2");
        $this->assertSuccess();
        $this->assertGroupIsSoftDeleted($groupId);
    }

    public function testGroupsDeleteAsGroupOwnerSuccess()
    {
        $this->authenticateAs('edith');
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->deleteJson('/groups/' . $groupId . '.json?api-version=v2');
        $this->assertSuccess();
        $this->assertGroupIsSoftDeleted($groupId);
    }
}
