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
namespace App\Test\TestCase\Controller\Users;

use App\Model\Entity\Permission;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\GroupsUsersModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class UsersDeleteControllerTest extends AppIntegrationTestCase
{
    public $Users;
    public $GroupsUsers;
    public $Permissions;

    use GroupsModelTrait;
    use GroupsUsersModelTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles',
        'app.Base/Resources', 'app.Base/Secrets',
        'app.Alt0/GroupsUsers', 'app.Alt0/Permissions', 'app.Base/Avatars', 'app.Base/Favorites', 'app.Base/EmailQueue'
    ];

    public function setUp()
    {
            parent::setUp();
            $this->Users = TableRegistry::getTableLocator()->get('Users');
            $this->GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
            $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
            $this->Resources = TableRegistry::getTableLocator()->get('Resources');
            $this->Secrets = TableRegistry::getTableLocator()->get('Secrets');
            $this->Favorites = TableRegistry::getTableLocator()->get('Favorites');
            $this->Groups = TableRegistry::getTableLocator()->get('Groups');
    }

    public function testUsersDeleteDryRunSuccess()
    {
            $this->authenticateAs('admin');
            $userFId = UuidFactory::uuid('user.id.frances');
            $this->deleteJson('/users/' . $userFId . '/dry-run.json?api-version=v2');
            $this->assertSuccess();
            $frances = $this->Users->get($userFId);
            $this->assertFalse($frances->deleted);
    }

    public function tesUsersDeleteDryRunMissingCsrfTokenError()
    {
            $this->disableCsrfToken();
            $this->authenticateAs('admin');
            $userAId = UuidFactory::uuid('user.id.ada');
            $this->delete("/users/$userAId/dry-run.json?api-version=v2");
            $this->assertResponseCode(403);
    }

    public function testUsersDeleteDryRunError()
    {
            $this->authenticateAs('admin');
            $userAId = UuidFactory::uuid('user.id.ada');
            $this->deleteJson("/users/$userAId/dry-run.json?api-version=v2");
            $this->assertError(400);
            $this->assertContains(
                'You need to transfer the user group manager role',
                $this->_responseJsonHeader->message
            );
    }

    public function testUsersDeleteSuccess()
    {
            $this->authenticateAs('admin');
            $userFId = UuidFactory::uuid('user.id.frances');
            $this->deleteJson("/users/$userFId.json?api-version=v2");
            $this->assertSuccess();
            $frances = $this->Users->get($userFId);
            $this->assertTrue($frances->deleted);
    }

    public function tesUsersDeleteMissingCsrfTokenError()
    {
            $this->disableCsrfToken();
            $this->authenticateAs('admin');
            $userAId = UuidFactory::uuid('user.id.ada');
            $this->deleteJson("/users/$userAId.json?api-version=v2");
            $this->assertResponseCode(403);
    }

    public function testUsersDeleteNotLoggedInError()
    {
            $userFId = UuidFactory::uuid('user.id.frances');
            $this->deleteJson("/users/$userFId.json?api-version=v2");
            $this->assertAuthenticationError();
    }

    public function testUsersDeleteNotAdminError()
    {
            $this->authenticateAs('ada');
            $userFId = UuidFactory::uuid('user.id.frances');
            $this->deleteJson("/users/$userFId.json?api-version=v2");
            $this->assertForbiddenError('You are not authorized to access that location.');
    }

    public function testUsersDeleteInvalidUserError()
    {
            $this->authenticateAs('admin');
            $userId = '0';
            $this->deleteJson("/users/$userId.json?api-version=v2");
            $this->assertError(400, 'The user id must be a valid uuid.');

            $this->authenticateAs('admin');
            $userId = 'true';
            $this->deleteJson("/users/$userId.json?api-version=v2");
            $this->assertError(400, 'The user id must be a valid uuid.');

            $this->authenticateAs('admin');
            $userId = 'null';
            $this->deleteJson("/users/$userId.json?api-version=v2");
            $this->assertError(400, 'The user id must be a valid uuid.');

            $this->authenticateAs('admin');
            $userId = '🔥';
            $this->deleteJson("/users/$userId.json?api-version=v2");
            $this->assertError(400, 'The user id must be a valid uuid.');
    }

    public function testUsersDeleteUserDoesNotExistError()
    {
            $this->authenticateAs('admin');
            $userId = UuidFactory::uuid('user.id.bogus');
            $this->deleteJson("/users/$userId.json?api-version=v2");
            $this->assertError(404, 'The user does not exist or has been already deleted.');
    }

    public function testUsersDeleteUserAlreadyDeletedError()
    {
            $this->authenticateAs('admin');
            $userSId = UuidFactory::uuid('user.id.sofia');
            $this->deleteJson("/users/$userSId.json?api-version=v2");
            $this->assertError(404, 'The user does not exist or has been already deleted.');
    }

    public function testUsersDeleteCannotDeleteSelfError()
    {
            $this->authenticateAs('admin');
            $userAId = UuidFactory::uuid('user.id.admin');
            $this->deleteJson("/users/$userAId.json?api-version=v2");
            $this->assertError(400, 'You are not allowed to delete yourself.');
    }

    public function testUsersDeleteSuccess_NoOwnerNoResourcesSharedNoGroupsMember_DelUserCase0()
    {
            $this->authenticateAs('admin');
            $userIId = UuidFactory::uuid('user.id.irene');
            $this->deleteJson("/users/$userIId.json?api-version=v2");
            $this->assertSuccess();
            $this->assertUserIsSoftDeleted($userIId);
    }

    public function testUsersDeleteSuccess_SoleOwnerNotSharedResource_DelUserCase1()
    {
            $this->authenticateAs('admin');
            $userJId = UuidFactory::uuid('user.id.jean');
            $this->deleteJson("/users/$userJId.json?api-version=v2");
            $this->assertSuccess();
            $this->assertUserIsSoftDeleted($userJId);
            $this->assertResourceIsSoftDeleted(UuidFactory::uuid('resource.id.mailvelope'));
    }

    public function testUsersDeleteError_SoleOwnerSharedResourceWithUser_DelUserCase2()
    {
            $this->authenticateAs('admin');
            $userKId = UuidFactory::uuid('user.id.kathleen');
            $resourceMId = UuidFactory::uuid('resource.id.mocha');
            $this->deleteJson("/users/$userKId.json?api-version=v2");

            $this->assertError(400);
            $this->assertUserIsNotSoftDeleted($userKId);
            $this->assertResourceIsNotSoftDeleted($resourceMId);
            $this->assertContains('You need to transfer the ownership for the shared passwords', $this->_responseJsonHeader->message);

            $errors = $this->_responseJsonBody->errors;
            $this->assertFalse(isset($errors->groups));
            $this->assertEquals(1, count($errors->resources->sole_owner));

            $resource = $errors->resources->sole_owner[0];
            $this->assertResourceAttributes($resource);
            $this->assertEquals($resource->id, $resourceMId);
    }

    public function testUsersDeleteError_TransferOwnersOfAnotherResource_SoleOwnerSharedResourceWithUser_DelUserCase2()
    {
            $this->authenticateAs('admin');
            $userKId = UuidFactory::uuid('user.id.kathleen');
            $resourceOId = UuidFactory::uuid('resource.id.openpgpjs');

            $transfer['owners'][] = ['id' => UuidFactory::uuid('permission.id.openpgpjs-leadership_team'), 'aco_foreign_key' => $resourceOId];
            $this->deleteJson("/users/$userKId.json?api-version=v2", ['transfer' => $transfer]);

            $this->assertError(400, 'The transfer is not authorized');
            $this->assertUserIsNotSoftDeleted($userKId);
    }

    public function testUsersDeleteError_TransferOwnersBadGroupUserId_SoleOwnerSharedResourceWithUser_DelUserCase2()
    {
            $this->authenticateAs('admin');
            $userKId = UuidFactory::uuid('user.id.kathleen');
            $resourceOId = UuidFactory::uuid('resource.id.openpgpjs');

            $transfer['owners'][] = ['id' => 'invalid-uuid', 'aco_foreign_key' => $resourceOId];
            $this->deleteJson("/users/$userKId.json?api-version=v2", ['transfer' => $transfer]);

            $this->assertError(400, 'The permissions ids must be valid uuids.');
            $this->assertUserIsNotSoftDeleted($userKId);
    }

    public function testUsersDeleteSuccess_SoleOwnerSharedResourceWithUser_DelUserCase2()
    {
            $this->authenticateAs('admin');
            $userKId = UuidFactory::uuid('user.id.kathleen');
            $userLId = UuidFactory::uuid('user.id.lynne');
            $resourceMId = UuidFactory::uuid('resource.id.mocha');

            $transfer['owners'][] = ['id' => UuidFactory::uuid('permission.id.mocha-lynne'), 'aco_foreign_key' => $resourceMId];
            $this->deleteJson("/users/$userKId.json?api-version=v2", ['transfer' => $transfer]);
            $this->assertSuccess();

            $this->assertUserIsSoftDeleted($userKId);
            $this->assertResourceIsNotSoftDeleted($resourceMId);
            $this->assertPermission($resourceMId, $userLId, Permission::OWNER);
    }

    public function testUsersSoftDeleteSuccess_SharedResourceWithMe_DelUserCase3()
    {
            $this->authenticateAs('admin');
            $userLId = UuidFactory::uuid('user.id.lynne');
            $this->deleteJson("/users/$userLId.json?api-version=v2");
            $this->assertSuccess();
            $this->assertUserIsSoftDeleted($userLId);
            $this->assertResourceIsNotSoftDeleted(UuidFactory::uuid('resource.id.mocha'));
    }

    public function testUsersDeleteError_SoleOwnerSharedResourceWithGroup_DelUserCase4()
    {
            $this->authenticateAs('admin');
            $userMId = UuidFactory::uuid('user.id.marlyn');
            $resourceNId = UuidFactory::uuid('resource.id.nodejs');

            $this->deleteJson("/users/$userMId.json?api-version=v2");
            $this->assertError(400);

            $this->assertUserIsNotSoftDeleted($userMId);
            $this->assertResourceIsNotSoftDeleted($resourceNId);

            $errors = $this->_responseJsonBody->errors;
            $this->assertFalse(isset($errors->groups));
            $this->assertEquals(1, count($errors->resources->sole_owner));

            $resource = $errors->resources->sole_owner[0];
            $this->assertResourceAttributes($resource);
            $this->assertEquals($resource->id, $resourceNId);
    }

    public function testUsersDeleteSuccess_SoleOwnerSharedResourceWithGroup_DelUserCase4()
    {
            $this->authenticateAs('admin');
            $userMId = UuidFactory::uuid('user.id.marlyn');
            $groupQId = UuidFactory::uuid('group.id.quality_assurance');
            $resourceNId = UuidFactory::uuid('resource.id.nodejs');

            $transfer['owners'][] = ['id' => UuidFactory::uuid('permission.id.nodejs-quality_assurance'), 'aco_foreign_key' => $resourceNId];
            $this->deleteJson("/users/$userMId.json?api-version=v2", ['transfer' => $transfer]);

            $this->assertSuccess();
            $this->assertUserIsSoftDeleted($userMId);
            $this->assertResourceIsNotSoftDeleted($resourceNId);
            $this->assertPermission($resourceNId, $groupQId, Permission::OWNER);
    }

    public function testUsersDeleteSuccess_SoleOwnerSharedResourceWithSoleManageEmptyGroup_DelUserCase5()
    {
            $this->authenticateAs('admin');
            $userNId = UuidFactory::uuid('user.id.nancy');
            $groupLId = UuidFactory::uuid('group.id.leadership_team');
            $resourceOId = UuidFactory::uuid('resource.id.openpgpjs');

            $this->deleteJson("/users/$userNId.json?api-version=v2");
            $this->assertSuccess();

            $this->assertUserIsSoftDeleted($userNId);
            $this->assertResourceIsSoftDeleted($resourceOId);
            $this->assertGroupIsSoftDeleted($groupLId);
    }

    public function testUsersDeleteSuccess_ownerSharedResourceAlongWithSoleManagerEmptyGroup_DelUserCase6()
    {
            $this->authenticateAs('admin');
            $userNId = UuidFactory::uuid('user.id.nancy');
            $groupLId = UuidFactory::uuid('group.id.leadership_team');
            $resourceOId = UuidFactory::uuid('resource.id.openpgpjs');

            // CONTEXTUAL TEST CHANGES Make the group also owner of the resource
            $permission = $this->Permissions->find()->select()->where([
                'aro_foreign_key' => $groupLId,
                'aco_foreign_key' => $resourceOId
            ])->first();
            $permission->type = Permission::OWNER;
            $this->Permissions->save($permission);

            $this->deleteJson("/users/$userNId.json?api-version=v2");
            $this->assertSuccess();

            $this->assertUserIsSoftDeleted($userNId);
            $this->assertResourceIsSoftDeleted($resourceOId);
            $this->assertGroupIsSoftDeleted($groupLId);
    }

    public function testUsersDeleteSuccess_indirectlyOwnerSharedResourceWithSoleManagerEmptyGroup_DelUserCase7()
    {
            $this->authenticateAs('admin');
            $userNId = UuidFactory::uuid('user.id.nancy');
            $groupLId = UuidFactory::uuid('group.id.leadership_team');
            $resourceOId = UuidFactory::uuid('resource.id.openpgpjs');

            // CONTEXTUAL TEST CHANGES Remove the direct permission of nancy
            $this->Permissions->deleteAll(['aro_foreign_key IN' => $userNId, 'aco_foreign_key' => $resourceOId]);
            $permission = $this->Permissions->find()->select()->where([
                'aro_foreign_key' => $groupLId,
                'aco_foreign_key' => $resourceOId
            ])->first();
            $permission->type = Permission::OWNER;
            $this->Permissions->save($permission);

            $this->deleteJson("/users/$userNId.json?api-version=v2");
            $this->assertSuccess();

            $this->assertUserIsSoftDeleted($userNId);
            $this->assertResourceIsSoftDeleted($resourceOId);
            $this->assertGroupIsSoftDeleted($groupLId);
    }

    public function testUsersDeleteError_soleManagerOfNotEmptyGroup_DelUserCase9()
    {
            $this->authenticateAs('admin');
            $userEId = UuidFactory::uuid('user.id.edith');
            $groupFId = UuidFactory::uuid('group.id.freelancer');

            $this->deleteJson("/users/$userEId.json?api-version=v2");
            $this->assertError(400);
            $this->assertUserIsNotSoftDeleted($userEId);

            $errors = $this->_responseJsonBody->errors;
            $this->assertCount(1, $errors->groups->sole_manager);
            $this->assertFalse(isset($errors->resources));

            $group = $errors->groups->sole_manager[0];
            $this->assertGroupAttributes($group);
            $this->assertEquals($group->id, $groupFId);
    }

    public function testUsersDeleteSuccess_soleManagerOfNotEmptyGroup_DelUserCase9()
    {
            $this->authenticateAs('admin');
            $userEId = UuidFactory::uuid('user.id.edith');
            $userFId = UuidFactory::uuid('user.id.frances');
            $groupFId = UuidFactory::uuid('group.id.freelancer');

            $transfer['managers'][] = ['id' => UuidFactory::uuid('group_user.id.freelancer-frances'), 'group_id' => $groupFId];
            $this->deleteJson("/users/$userEId.json?api-version=v2", ['transfer' => $transfer]);
            $this->assertSuccess();

            $this->assertUserIsSoftDeleted($userEId);
            $this->assertGroupIsNotSoftDeleted($groupFId);
            $this->assertUserIsAdmin($groupFId, $userFId);
    }

    public function testUsersDeleteError_ownerAlongWithSoleManagerOfNotEmptyGroup_DelUserCase10()
    {
            $this->authenticateAs('admin');
            $userOId = UuidFactory::uuid('user.id.orna');
            $resourceLId = UuidFactory::uuid('resource.id.linux');
            $groupMId = UuidFactory::uuid('group.id.management');

            $this->deleteJson("/users/$userOId.json?api-version=v2");
            $this->assertError(400);
            $this->assertUserIsNotSoftDeleted($userOId);
            $this->assertResourceIsNotSoftDeleted($resourceLId);

            $errors = $this->_responseJsonBody->errors;
            $this->assertCount(1, $errors->groups->sole_manager);
            $this->assertFalse(isset($errors->resources));

            $group = $errors->groups->sole_manager[0];
            $this->assertGroupAttributes($group);
            $this->assertEquals($group->id, $groupMId);
    }

    public function testUsersDeleteSuccess_ownerAlongWithSoleManagerOfNotEmptyGroup_DelUserCase10()
    {
            $this->authenticateAs('admin');
            $userOId = UuidFactory::uuid('user.id.orna');
            $userPId = UuidFactory::uuid('user.id.ping');
            $groupMId = UuidFactory::uuid('group.id.management');

            $transfer['managers'][] = ['id' => UuidFactory::uuid('group_user.id.management-ping'), 'group_id' => $groupMId];
            $this->deleteJson("/users/$userOId.json?api-version=v2", ['transfer' => $transfer]);
            $this->assertSuccess();

            $this->assertUserIsSoftDeleted($userOId);
            $this->assertGroupIsNotSoftDeleted($groupMId);
            $this->assertUserIsAdmin($groupMId, $userPId);
    }

    public function testUsersDeleteError_indireclyOwnerWithSoleManagerOfNotEmptyGroup_DelUserCase11()
    {
            $this->authenticateAs('admin');
            $userOId = UuidFactory::uuid('user.id.orna');
            $resourceLId = UuidFactory::uuid('resource.id.linux');
            $groupMId = UuidFactory::uuid('group.id.management');

            // CONTEXTUAL TEST CHANGES Remove The permissions of Orna
            $this->Permissions->deleteAll([
                'aro_foreign_key' => $userOId,
                'aco_foreign_key' => UuidFactory::uuid('resource.id.linux')
            ]);

            $this->deleteJson("/users/$userOId.json?api-version=v2");
            $this->assertError(400);
            $this->assertUserIsNotSoftDeleted($userOId);
            $this->assertResourceIsNotSoftDeleted($resourceLId);

            $errors = $this->_responseJsonBody->errors;
            $this->assertCount(1, $errors->groups->sole_manager);
            $this->assertFalse(isset($errors->resources));

            $group = $errors->groups->sole_manager[0];
            $this->assertGroupAttributes($group);
            $this->assertEquals($group->id, $groupMId);
    }

    public function testUsersDeleteError_TransferManagersBadPermissionId_indireclyOwnerWithSoleManagerOfNotEmptyGroup_DelUserCase11()
    {
            $this->authenticateAs('admin');
            $userOId = UuidFactory::uuid('user.id.orna');
            $groupBId = UuidFactory::uuid('group.id.board');

            // CONTEXTUAL TEST CHANGES Remove The permissions of Orna
            $this->Permissions->deleteAll([
                'aro_foreign_key' => $userOId,
                'aco_foreign_key' => UuidFactory::uuid('resource.id.linux')
            ]);

            $transfer['managers'][] = ['id' => 'invalid-uuid', 'group_id' => $groupBId];
            $this->deleteJson("/users/$userOId.json?api-version=v2", ['transfer' => $transfer]);
            $this->assertError(400, 'The groups users ids must be valid uuids.');
            $this->assertUserIsNotSoftDeleted($userOId);
    }

    public function testUsersDeleteSuccess_indireclyOwnerWithSoleManagerOfNotEmptyGroup_DelUserCase11()
    {
            $this->authenticateAs('admin');
            $userOId = UuidFactory::uuid('user.id.orna');
            $userPId = UuidFactory::uuid('user.id.ping');
            $groupMId = UuidFactory::uuid('group.id.management');
            $resourceLId = UuidFactory::uuid('resource.id.linux');

            // CONTEXTUAL TEST CHANGES Remove The permissions of Orna
            $this->Permissions->deleteAll([
                'aro_foreign_key' => $userOId,
                'aco_foreign_key' => UuidFactory::uuid('resource.id.linux')
            ]);

            $transfer['managers'][] = ['id' => UuidFactory::uuid('group_user.id.management-ping'), 'group_id' => $groupMId];
            $this->deleteJson("/users/$userOId.json?api-version=v2", ['transfer' => $transfer]);
            $this->assertSuccess();

            $this->assertUserIsSoftDeleted($userOId);
            $this->assertGroupIsNotSoftDeleted($groupMId);
            $this->assertResourceIsNotSoftDeleted($resourceLId);
            $this->assertUserIsAdmin($groupMId, $userPId);
    }

    public function testUsersDeleteError_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroup_DelUserCase12()
    {
            $this->authenticateAs('admin');
            $userUId = UuidFactory::uuid('user.id.ursula');
            $resourcePId = UuidFactory::uuid('resource.id.phpunit');

            $this->deleteJson("/users/$userUId.json?api-version=v2");
            $this->assertError(400);
            $this->assertUserIsNotSoftDeleted($userUId);

            $errors = $this->_responseJsonBody->errors;
            $this->assertFalse(isset($errors->groups));
            $this->assertCount(1, $errors->resources->sole_owner);

            $resource = $errors->resources->sole_owner[0];
            $this->assertGroupAttributes($resource);
            $this->assertEquals($resource->id, $resourcePId);
    }

    public function testUsersDeleteSuccess_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroup_DelUserCase12()
    {
            $this->authenticateAs('admin');
            $userTId = UuidFactory::uuid('user.id.thelma');
            $userUId = UuidFactory::uuid('user.id.ursula');
            $groupNId = UuidFactory::uuid('group.id.network');
            $resourcePId = UuidFactory::uuid('resource.id.phpunit');

            // CONTEXTUAL TEST CHANGES Remove The permissions of Orna
            $permission = $this->Permissions->find()->select()->where([
                'aro_foreign_key' => $userTId,
                'aco_foreign_key' => $resourcePId
            ])->first();
            $permission->type = Permission::OWNER;
            $this->Permissions->save($permission);

            $this->deleteJson("/users/$userUId.json?api-version=v2");
            $this->assertSuccess();

            $this->assertUserIsSoftDeleted($userUId);
            $this->assertGroupIsSoftDeleted($groupNId);
    }

    public function testUsersDeleteSuccess_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroups_DelUserCase13()
    {
            $this->authenticateAs('admin');
            $userWId = UuidFactory::uuid('user.id.wang');
            $resourceQId = UuidFactory::uuid('resource.id.qgis');
            $groupOId = UuidFactory::uuid('group.id.operations');
            $groupPId = UuidFactory::uuid('group.id.procurement');

            $this->deleteJson("/users/$userWId.json?api-version=v2");
            $this->assertSuccess();
            $this->assertUserIsSoftDeleted($userWId);
            $this->assertGroupIsSoftDeleted($groupOId);
            $this->assertGroupIsSoftDeleted($groupPId);
            $this->assertResourceIsSoftDeleted($resourceQId);
    }

    public function testUsersDeleteError_indirectlyOwnerSharedResourceWithSoleManagerOfNonEmptyGroup_DelUserCase14()
    {
            $this->authenticateAs('admin');
            $userYId = UuidFactory::uuid('user.id.yvonne');
            $groupHId = UuidFactory::uuid('group.id.human_resource');

            $this->deleteJson("/users/$userYId.json?api-version=v2");
            $this->assertError(400);
            $this->assertUserIsNotSoftDeleted($userYId);

            $errors = $this->_responseJsonBody->errors;
            $this->assertCount(1, $errors->groups->sole_manager);
            $this->assertFalse(isset($errors->resources));

            $group = $errors->groups->sole_manager[0];
            $this->assertGroupAttributes($group);
            $this->assertEquals($group->id, $groupHId);
    }

    public function testUsersDeleteSuccess_indirectlyOwnerSharedResourceWithSoleManagerOfNonEmptyGroup_DelUserCase14()
    {
            $this->authenticateAs('admin');
            $userYId = UuidFactory::uuid('user.id.yvonne');
            $userJId = UuidFactory::uuid('user.id.joan');
            $groupHId = UuidFactory::uuid('group.id.human_resource');
            $resourceSId = UuidFactory::uuid('resource.id.selenium');

            $transfer['managers'][] = ['id' => UuidFactory::uuid('group_user.id.human_resource-joan'), 'group_id' => $groupHId];
            $this->deleteJson("/users/$userYId.json?api-version=v2", ['transfer' => $transfer]);

            $this->assertSuccess();
            $this->assertUserIsSoftDeleted($userYId);
            $this->assertGroupIsNotSoftDeleted($groupHId);
            $this->assertResourceIsNotSoftDeleted($resourceSId);
            $this->assertUserIsAdmin($groupHId, $userJId);
    }

    public function testUsersDeleteError_SoleOwnerSharedResourceWithNotEmptyGroup_DelUserCase15()
    {
            $this->authenticateAs('admin');
            $userOId = UuidFactory::uuid('user.id.orna');
            $groupMId = UuidFactory::uuid('group.id.management');
            $resourceLId = UuidFactory::uuid('resource.id.linux');

            // CONTEXTUAL TEST CHANGES Change the permission of the group to READ
            $permission = $this->Permissions->find()->select()->where([
                'aro_foreign_key' => $groupMId,
                'aco_foreign_key' => $resourceLId
            ])->first();
            $permission->type = Permission::READ;
            $this->Permissions->save($permission);

            $this->deleteJson("/users/$userOId.json?api-version=v2");
            $this->assertError(400);
            $this->assertUserIsNotSoftDeleted($userOId);

            $errors = $this->_responseJsonBody->errors;
            $this->assertCount(1, $errors->groups->sole_manager);
            $this->assertCount(1, $errors->resources->sole_owner);

            $group = $errors->groups->sole_manager[0];
            $this->assertGroupAttributes($group);
            $this->assertEquals($group->id, $groupMId);

            $resource = $errors->resources->sole_owner[0];
            $this->assertGroupAttributes($resource);
            $this->assertEquals($resource->id, $resourceLId);
    }

    public function testUsersDeleteSuccess_SoleOwnerSharedResourceWithNotEmptyGroup_DelUserCase15()
    {
            $this->authenticateAs('admin');
            $userOId = UuidFactory::uuid('user.id.orna');
            $userPId = UuidFactory::uuid('user.id.ping');
            $groupMId = UuidFactory::uuid('group.id.management');
            $resourceLId = UuidFactory::uuid('resource.id.linux');

            // CONTEXTUAL TEST CHANGES Change the permission of the group to READ
            $permission = $this->Permissions->find()->select()->where([
                'aro_foreign_key' => $groupMId,
                'aco_foreign_key' => $resourceLId
            ])->first();
            $permission->type = Permission::READ;
            $this->Permissions->save($permission);

            $transfer['owners'][] = ['id' => UuidFactory::uuid('permission.id.linux-management'), 'aco_foreign_key' => $resourceLId];
            $transfer['managers'][] = ['id' => UuidFactory::uuid('group_user.id.management-ping'), 'group_id' => $groupMId];
            $this->deleteJson("/users/$userOId.json?api-version=v2", ['transfer' => $transfer]);
            $this->assertSuccess();
            $this->assertUserIsSoftDeleted($userOId);
            $this->assertUserIsAdmin($groupMId, $userPId);
            $this->assertPermission($resourceLId, $groupMId, Permission::OWNER);
    }
}
