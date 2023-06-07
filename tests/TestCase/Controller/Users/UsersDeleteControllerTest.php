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
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\GroupsUsersModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class UsersDeleteControllerTest extends AppIntegrationTestCase
{
    use GroupsModelTrait;
    use GroupsUsersModelTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles',
        'app.Base/Resources', 'app.Base/Secrets',
        'app.Alt0/GroupsUsers', 'app.Alt0/Permissions', 'app.Base/Favorites',
    ];

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    public $Permissions;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    public $Resources;

    public function setUp(): void
    {
            parent::setUp();

            $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
            $this->Resources = TableRegistry::getTableLocator()->get('Resources');
    }

    public function testUsersDeleteController_DryRun_Success(): void
    {
            $this->authenticateAs('admin');
            $userFId = UuidFactory::uuid('user.id.frances');
            $this->deleteJson('/users/' . $userFId . '/dry-run.json');
            $this->assertSuccess();
            $frances = UserFactory::get($userFId);
            $this->assertFalse($frances->deleted);
    }

    public function testUsersDeleteController_DryRun_Error_MissingCsrfToken(): void
    {
            $this->disableCsrfToken();
            $this->authenticateAs('admin');
            $userAId = UuidFactory::uuid('user.id.ada');
            $this->delete("/users/$userAId/dry-run.json");
            $this->assertResponseCode(403);
    }

    public function testUsersDeleteController_DryRun_Error(): void
    {
            $this->authenticateAs('admin');
            $userAId = UuidFactory::uuid('user.id.ada');
            $this->deleteJson("/users/$userAId/dry-run.json");
            $this->assertError(400);
            $this->assertStringContainsString(
                'sole group manager',
                $this->_responseJsonHeader->message
            );
    }

    public function testUsersDeleteController_DryRun_Error_NotJson(): void
    {
        $this->authenticateAs('admin');
        $userFId = UuidFactory::uuid('user.id.frances');
        $this->delete('/users/' . $userFId . '/dry-run');
        $this->assertResponseCode(404);
    }

    public function testUsersDeleteController_Success(): void
    {
        $this->authenticateAs('admin');
        $userFId = UuidFactory::uuid('user.id.frances');
        $this->deleteJson("/users/$userFId.json");
        $this->assertSuccess();
        $frances = UserFactory::get($userFId);
        $this->assertTrue($frances->deleted);
    }

    public function testUsersDeleteController_Error_NotJson(): void
    {
        $this->authenticateAs('admin');
        $userFId = UuidFactory::uuid('user.id.frances');
        $this->delete("/users/$userFId");
        $this->assertResponseCode(404);
    }

    public function testUsersDeleteController_Error_MissingCsrfToken(): void
    {
        $this->disableCsrfToken();
        $this->authenticateAs('admin');
        $userAId = UuidFactory::uuid('user.id.ada');
        $this->deleteJson("/users/$userAId.json");
        $this->assertResponseCode(403);
    }

    public function testUsersDeleteController_Error_NotLoggedIn(): void
    {
        $userFId = UuidFactory::uuid('user.id.frances');
        $this->deleteJson("/users/$userFId.json");
        $this->assertAuthenticationError();
    }

    public function testUsersDeleteController_Error_NotAdmin(): void
    {
        $this->authenticateAs('ada');
        $userFId = UuidFactory::uuid('user.id.frances');
        $this->deleteJson("/users/$userFId.json");
        $this->assertForbiddenError('You are not authorized to access that location.');
    }

    public function testUsersDeleteController_Error_InvalidUser(): void
    {
        $this->authenticateAs('admin');
        $userId = '0';
        $this->deleteJson("/users/$userId.json");
        $this->assertError(400, 'The user identifier should be a valid UUID.');

        $this->authenticateAs('admin');
        $userId = 'true';
        $this->deleteJson("/users/$userId.json");
        $this->assertError(400, 'The user identifier should be a valid UUID.');

        $this->authenticateAs('admin');
        $userId = 'null';
        $this->deleteJson("/users/$userId.json");
        $this->assertError(400, 'The user identifier should be a valid UUID.');

        $this->authenticateAs('admin');
        $userId = '🔥';
        $this->deleteJson("/users/$userId.json");
        $this->assertError(400, 'The user identifier should be a valid UUID.');
    }

    public function testUsersDeleteController_Error_UserDoesNotExist(): void
    {
        $this->authenticateAs('admin');
        $userId = UuidFactory::uuid('user.id.bogus');
        $this->deleteJson("/users/$userId.json");
        $this->assertError(404, 'The user does not exist or has been already deleted.');
    }

    public function testUsersDeleteController_Error_UserAlreadyDeleted(): void
    {
        $this->authenticateAs('admin');
        $userSId = UuidFactory::uuid('user.id.sofia');
        $this->deleteJson("/users/$userSId.json");
        $this->assertError(404, 'The user does not exist or has been already deleted.');
    }

    public function testUsersDeleteController_Error_CannotDeleteSelf(): void
    {
        $this->authenticateAs('admin');
        $userAId = UuidFactory::uuid('user.id.admin');
        $this->deleteJson("/users/$userAId.json");
        $this->assertError(400, 'You are not allowed to delete yourself.');
    }

    public function testUsersDeleteController_Success_NoOwnerNoResourcesSharedNoGroupsMember_DelUserCase0(): void
    {
        $this->authenticateAs('admin');
        $userIId = UuidFactory::uuid('user.id.irene');
        $this->deleteJson("/users/$userIId.json");
        $this->assertSuccess();
        $this->assertUserIsSoftDeleted($userIId);
    }

    public function testUsersDeleteController_Success_SoleOwnerNotSharedResource_DelUserCase1(): void
    {
        $this->authenticateAs('admin');
        $userJId = UuidFactory::uuid('user.id.jean');
        $this->deleteJson("/users/$userJId.json");
        $this->assertSuccess();
        $this->assertUserIsSoftDeleted($userJId);
        $this->assertResourceIsSoftDeleted(UuidFactory::uuid('resource.id.mailvelope'));
    }

    public function testUsersDeleteController_Error_SoleOwnerSharedResourceWithUser_DelUserCase2(): void
    {
        $this->authenticateAs('admin');
        $userKId = UuidFactory::uuid('user.id.kathleen');
        $resourceMId = UuidFactory::uuid('resource.id.mocha');
        $this->deleteJson("/users/$userKId.json");

        $this->assertError(400);
        $this->assertUserIsNotSoftDeleted($userKId);
        $this->assertResourceIsNotSoftDeleted($resourceMId);
        $this->assertStringContainsString('sole owner of shared content', $this->_responseJsonHeader->message);

        $errors = $this->_responseJsonBody->errors;
        $this->assertFalse(isset($errors->groups));
        $this->assertEquals(1, count($errors->resources->sole_owner));

        $resource = $errors->resources->sole_owner[0];
        $this->assertResourceAttributes($resource);
        $this->assertEquals($resource->id, $resourceMId);
    }

    public function testUsersDeleteController_Error_TransferOwnersOfAnotherResource_SoleOwnerSharedResourceWithUser_DelUserCase2(): void
    {
        $this->authenticateAs('admin');
        $userKId = UuidFactory::uuid('user.id.kathleen');
        $resourceOId = UuidFactory::uuid('resource.id.openpgpjs');

        $transfer['owners'][] = ['id' => UuidFactory::uuid('permission.id.openpgpjs-leadership_team'), 'aco_foreign_key' => $resourceOId];
        $this->deleteJson("/users/$userKId.json", ['transfer' => $transfer]);

        $this->assertError(400, 'The transfer is not authorized');
        $this->assertUserIsNotSoftDeleted($userKId);
    }

    public function testUsersDeleteController_Error_TransferOwnersBadGroupUserId_SoleOwnerSharedResourceWithUser_DelUserCase2(): void
    {
        $this->authenticateAs('admin');
        $userKId = UuidFactory::uuid('user.id.kathleen');
        $resourceOId = UuidFactory::uuid('resource.id.openpgpjs');

        $transfer['owners'][] = ['id' => 'invalid-uuid', 'aco_foreign_key' => $resourceOId];
        $this->deleteJson("/users/$userKId.json", ['transfer' => $transfer]);

        $this->assertError(400, 'The permissions identifiers must be valid UUID.');
        $this->assertUserIsNotSoftDeleted($userKId);
    }

    public function testUsersDeleteController_Success_SoleOwnerSharedResourceWithUser_DelUserCase2(): void
    {
        $this->authenticateAs('admin');
        $userKId = UuidFactory::uuid('user.id.kathleen');
        $userLId = UuidFactory::uuid('user.id.lynne');
        $resourceMId = UuidFactory::uuid('resource.id.mocha');

        $transfer['owners'][] = ['id' => UuidFactory::uuid('permission.id.mocha-lynne'), 'aco_foreign_key' => $resourceMId];
        $this->deleteJson("/users/$userKId.json", ['transfer' => $transfer]);
        $this->assertSuccess();

        $this->assertUserIsSoftDeleted($userKId);
        $this->assertResourceIsNotSoftDeleted($resourceMId);
        $this->assertPermission($resourceMId, $userLId, Permission::OWNER);
    }

    public function testUsersDeleteController_Success_SoftDeleteSharedResourceWithMe_DelUserCase3(): void
    {
        $this->authenticateAs('admin');
        $userLId = UuidFactory::uuid('user.id.lynne');
        $this->deleteJson("/users/$userLId.json");
        $this->assertSuccess();
        $this->assertUserIsSoftDeleted($userLId);
        $this->assertResourceIsNotSoftDeleted(UuidFactory::uuid('resource.id.mocha'));
    }

    public function testUsersDeleteController_Error_SoleOwnerSharedResourceWithGroup_DelUserCase4(): void
    {
        $this->authenticateAs('admin');
        $userMId = UuidFactory::uuid('user.id.marlyn');
        $resourceNId = UuidFactory::uuid('resource.id.nodejs');

        $this->deleteJson("/users/$userMId.json");
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

    public function testUsersDeleteController_Success_SoleOwnerSharedResourceWithGroup_DelUserCase4(): void
    {
        $this->authenticateAs('admin');
        $userMId = UuidFactory::uuid('user.id.marlyn');
        $groupQId = UuidFactory::uuid('group.id.quality_assurance');
        $resourceNId = UuidFactory::uuid('resource.id.nodejs');

        $transfer['owners'][] = ['id' => UuidFactory::uuid('permission.id.nodejs-quality_assurance'), 'aco_foreign_key' => $resourceNId];
        $this->deleteJson("/users/$userMId.json", ['transfer' => $transfer]);

        $this->assertSuccess();
        $this->assertUserIsSoftDeleted($userMId);
        $this->assertResourceIsNotSoftDeleted($resourceNId);
        $this->assertPermission($resourceNId, $groupQId, Permission::OWNER);
    }

    public function testUsersDeleteController_Success_SoleOwnerSharedResourceWithSoleManageEmptyGroup_DelUserCase5(): void
    {
        $this->authenticateAs('admin');
        $userNId = UuidFactory::uuid('user.id.nancy');
        $groupLId = UuidFactory::uuid('group.id.leadership_team');
        $resourceOId = UuidFactory::uuid('resource.id.openpgpjs');

        $this->deleteJson("/users/$userNId.json");
        $this->assertSuccess();

        $this->assertUserIsSoftDeleted($userNId);
        $this->assertResourceIsSoftDeleted($resourceOId);
        $this->assertGroupIsSoftDeleted($groupLId);
    }

    public function testUsersDeleteController_Success_ownerSharedResourceAlongWithSoleManagerEmptyGroup_DelUserCase6(): void
    {
        $this->authenticateAs('admin');
        $userNId = UuidFactory::uuid('user.id.nancy');
        $groupLId = UuidFactory::uuid('group.id.leadership_team');
        $resourceOId = UuidFactory::uuid('resource.id.openpgpjs');

        // CONTEXTUAL TEST CHANGES Make the group also owner of the resource
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $groupLId,
            'aco_foreign_key' => $resourceOId,
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        $this->deleteJson("/users/$userNId.json");
        $this->assertSuccess();

        $this->assertUserIsSoftDeleted($userNId);
        $this->assertResourceIsSoftDeleted($resourceOId);
        $this->assertGroupIsSoftDeleted($groupLId);
    }

    public function testUsersDeleteController_Success_indirectlyOwnerSharedResourceWithSoleManagerEmptyGroup_DelUserCase7(): void
    {
        $this->authenticateAs('admin');
        $userNId = UuidFactory::uuid('user.id.nancy');
        $groupLId = UuidFactory::uuid('group.id.leadership_team');
        $resourceOId = UuidFactory::uuid('resource.id.openpgpjs');

        // CONTEXTUAL TEST CHANGES Remove the direct permission of nancy
        $this->Permissions->deleteAll(['aro_foreign_key IN' => $userNId, 'aco_foreign_key' => $resourceOId]);
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $groupLId,
            'aco_foreign_key' => $resourceOId,
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        $this->deleteJson("/users/$userNId.json");
        $this->assertSuccess();

        $this->assertUserIsSoftDeleted($userNId);
        $this->assertResourceIsSoftDeleted($resourceOId);
        $this->assertGroupIsSoftDeleted($groupLId);
    }

    public function testUsersDeleteController_Error_soleManagerOfNotEmptyGroup_DelUserCase9(): void
    {
        $this->authenticateAs('admin');
        $userEId = UuidFactory::uuid('user.id.edith');
        $groupFId = UuidFactory::uuid('group.id.freelancer');

        $this->deleteJson("/users/$userEId.json");
        $this->assertError(400);
        $this->assertUserIsNotSoftDeleted($userEId);

        $errors = $this->_responseJsonBody->errors;
        $this->assertCount(1, $errors->groups->sole_manager);
        $this->assertFalse(isset($errors->resources));

        $group = $errors->groups->sole_manager[0];
        $this->assertGroupAttributes($group);
        $this->assertEquals($group->id, $groupFId);
    }

    public function testUsersDeleteController_Success_soleManagerOfNotEmptyGroup_DelUserCase9(): void
    {
        $this->authenticateAs('admin');
        $userEId = UuidFactory::uuid('user.id.edith');
        $userFId = UuidFactory::uuid('user.id.frances');
        $groupFId = UuidFactory::uuid('group.id.freelancer');

        $transfer['managers'][] = ['id' => UuidFactory::uuid('group_user.id.freelancer-frances'), 'group_id' => $groupFId];
        $this->deleteJson("/users/$userEId.json", ['transfer' => $transfer]);
        $this->assertSuccess();

        $this->assertUserIsSoftDeleted($userEId);
        $this->assertGroupIsNotSoftDeleted($groupFId);
        $this->assertUserIsAdmin($groupFId, $userFId);
    }

    public function testUsersDeleteController_Error_ownerAlongWithSoleManagerOfNotEmptyGroup_DelUserCase10(): void
    {
        $this->authenticateAs('admin');
        $userOId = UuidFactory::uuid('user.id.orna');
        $resourceLId = UuidFactory::uuid('resource.id.linux');
        $groupMId = UuidFactory::uuid('group.id.management');

        $this->deleteJson("/users/$userOId.json");
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

    public function testUsersDeleteController_Success_ownerAlongWithSoleManagerOfNotEmptyGroup_DelUserCase10(): void
    {
        $this->authenticateAs('admin');
        $userOId = UuidFactory::uuid('user.id.orna');
        $userPId = UuidFactory::uuid('user.id.ping');
        $groupMId = UuidFactory::uuid('group.id.management');

        $transfer['managers'][] = ['id' => UuidFactory::uuid('group_user.id.management-ping'), 'group_id' => $groupMId];
        $this->deleteJson("/users/$userOId.json", ['transfer' => $transfer]);
        $this->assertSuccess();

        $this->assertUserIsSoftDeleted($userOId);
        $this->assertGroupIsNotSoftDeleted($groupMId);
        $this->assertUserIsAdmin($groupMId, $userPId);
    }

    public function testUsersDeleteController_Error_indireclyOwnerWithSoleManagerOfNotEmptyGroup_DelUserCase11(): void
    {
        $this->authenticateAs('admin');
        $userOId = UuidFactory::uuid('user.id.orna');
        $resourceLId = UuidFactory::uuid('resource.id.linux');
        $groupMId = UuidFactory::uuid('group.id.management');

        // CONTEXTUAL TEST CHANGES Remove The permissions of Orna
        $this->Permissions->deleteAll([
            'aro_foreign_key' => $userOId,
            'aco_foreign_key' => UuidFactory::uuid('resource.id.linux'),
        ]);

        $this->deleteJson("/users/$userOId.json");
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

    public function testUsersDeleteController_Error_TransferManagersBadPermissionId_indireclyOwnerWithSoleManagerOfNotEmptyGroup_DelUserCase11(): void
    {
        $this->authenticateAs('admin');
        $userOId = UuidFactory::uuid('user.id.orna');
        $groupBId = UuidFactory::uuid('group.id.board');

        // CONTEXTUAL TEST CHANGES Remove The permissions of Orna
        $this->Permissions->deleteAll([
            'aro_foreign_key' => $userOId,
            'aco_foreign_key' => UuidFactory::uuid('resource.id.linux'),
        ]);

        $transfer['managers'][] = ['id' => 'invalid-uuid', 'group_id' => $groupBId];
        $this->deleteJson("/users/$userOId.json", ['transfer' => $transfer]);
        $this->assertError(400, 'The groups users identifiers must be valid UUID.');
        $this->assertUserIsNotSoftDeleted($userOId);
    }

    public function testUsersDeleteController_Success_indireclyOwnerWithSoleManagerOfNotEmptyGroup_DelUserCase11(): void
    {
        $this->authenticateAs('admin');
        $userOId = UuidFactory::uuid('user.id.orna');
        $userPId = UuidFactory::uuid('user.id.ping');
        $groupMId = UuidFactory::uuid('group.id.management');
        $resourceLId = UuidFactory::uuid('resource.id.linux');

        // CONTEXTUAL TEST CHANGES Remove The permissions of Orna
        $this->Permissions->deleteAll([
            'aro_foreign_key' => $userOId,
            'aco_foreign_key' => UuidFactory::uuid('resource.id.linux'),
        ]);

        $transfer['managers'][] = ['id' => UuidFactory::uuid('group_user.id.management-ping'), 'group_id' => $groupMId];
        $this->deleteJson("/users/$userOId.json", ['transfer' => $transfer]);
        $this->assertSuccess();

        $this->assertUserIsSoftDeleted($userOId);
        $this->assertGroupIsNotSoftDeleted($groupMId);
        $this->assertResourceIsNotSoftDeleted($resourceLId);
        $this->assertUserIsAdmin($groupMId, $userPId);
    }

    public function testUsersDeleteController_Error_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroup_DelUserCase12(): void
    {
        $this->authenticateAs('admin');
        $userUId = UuidFactory::uuid('user.id.ursula');
        $resourcePId = UuidFactory::uuid('resource.id.phpunit');

        $this->deleteJson("/users/$userUId.json");
        $this->assertError(400);
        $this->assertUserIsNotSoftDeleted($userUId);

        $errors = $this->_responseJsonBody->errors;
        $this->assertFalse(isset($errors->groups));
        $this->assertCount(1, $errors->resources->sole_owner);

        $resource = $errors->resources->sole_owner[0];
        $this->assertGroupAttributes($resource);
        $this->assertEquals($resource->id, $resourcePId);
    }

    public function testUsersDeleteController_Success_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroup_DelUserCase12(): void
    {
        $this->authenticateAs('admin');
        $userTId = UuidFactory::uuid('user.id.thelma');
        $userUId = UuidFactory::uuid('user.id.ursula');
        $groupNId = UuidFactory::uuid('group.id.network');
        $resourcePId = UuidFactory::uuid('resource.id.phpunit');

        // CONTEXTUAL TEST CHANGES Remove The permissions of Orna
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $userTId,
            'aco_foreign_key' => $resourcePId,
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        $this->deleteJson("/users/$userUId.json");
        $this->assertSuccess();

        $this->assertUserIsSoftDeleted($userUId);
        $this->assertGroupIsSoftDeleted($groupNId);
    }

    public function testUsersDeleteController_Success_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroups_DelUserCase13(): void
    {
        $this->authenticateAs('admin');
        $userWId = UuidFactory::uuid('user.id.wang');
        $resourceQId = UuidFactory::uuid('resource.id.qgis');
        $groupOId = UuidFactory::uuid('group.id.operations');
        $groupPId = UuidFactory::uuid('group.id.procurement');

        $this->deleteJson("/users/$userWId.json");
        $this->assertSuccess();
        $this->assertUserIsSoftDeleted($userWId);
        $this->assertGroupIsSoftDeleted($groupOId);
        $this->assertGroupIsSoftDeleted($groupPId);
        $this->assertResourceIsSoftDeleted($resourceQId);
    }

    public function testUsersDeleteController_Error_indirectlyOwnerSharedResourceWithSoleManagerOfNonEmptyGroup_DelUserCase14(): void
    {
        $this->authenticateAs('admin');
        $userYId = UuidFactory::uuid('user.id.yvonne');
        $groupHId = UuidFactory::uuid('group.id.human_resource');

        $this->deleteJson("/users/$userYId.json");
        $this->assertError(400);
        $this->assertUserIsNotSoftDeleted($userYId);

        $errors = $this->_responseJsonBody->errors;
        $this->assertCount(1, $errors->groups->sole_manager);
        $this->assertFalse(isset($errors->resources));

        $group = $errors->groups->sole_manager[0];
        $this->assertGroupAttributes($group);
        $this->assertEquals($group->id, $groupHId);
    }

    public function testUsersDeleteController_Success_indirectlyOwnerSharedResourceWithSoleManagerOfNonEmptyGroup_DelUserCase14(): void
    {
        $this->authenticateAs('admin');
        $userYId = UuidFactory::uuid('user.id.yvonne');
        $userJId = UuidFactory::uuid('user.id.joan');
        $groupHId = UuidFactory::uuid('group.id.human_resource');
        $resourceSId = UuidFactory::uuid('resource.id.selenium');

        $transfer['managers'][] = ['id' => UuidFactory::uuid('group_user.id.human_resource-joan'), 'group_id' => $groupHId];
        $this->deleteJson("/users/$userYId.json", ['transfer' => $transfer]);

        $this->assertSuccess();
        $this->assertUserIsSoftDeleted($userYId);
        $this->assertGroupIsNotSoftDeleted($groupHId);
        $this->assertResourceIsNotSoftDeleted($resourceSId);
        $this->assertUserIsAdmin($groupHId, $userJId);
    }

    public function testUsersDeleteController_Error_SoleOwnerSharedResourceWithNotEmptyGroup_DelUserCase15(): void
    {
        $this->authenticateAs('admin');
        $userOId = UuidFactory::uuid('user.id.orna');
        $groupMId = UuidFactory::uuid('group.id.management');
        $resourceLId = UuidFactory::uuid('resource.id.linux');

        // CONTEXTUAL TEST CHANGES Change the permission of the group to READ
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $groupMId,
            'aco_foreign_key' => $resourceLId,
        ])->first();
        $permission->type = Permission::READ;
        $this->Permissions->save($permission);

        $this->deleteJson("/users/$userOId.json");
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

    public function testUsersDeleteController_Success_SoleOwnerSharedResourceWithNotEmptyGroup_DelUserCase15(): void
    {
        $this->authenticateAs('admin');
        $userOId = UuidFactory::uuid('user.id.orna');
        $userPId = UuidFactory::uuid('user.id.ping');
        $groupMId = UuidFactory::uuid('group.id.management');
        $resourceLId = UuidFactory::uuid('resource.id.linux');

        // CONTEXTUAL TEST CHANGES Change the permission of the group to READ
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $groupMId,
            'aco_foreign_key' => $resourceLId,
        ])->first();
        $permission->type = Permission::READ;
        $this->Permissions->save($permission);

        $transfer['owners'][] = ['id' => UuidFactory::uuid('permission.id.linux-management'), 'aco_foreign_key' => $resourceLId];
        $transfer['managers'][] = ['id' => UuidFactory::uuid('group_user.id.management-ping'), 'group_id' => $groupMId];
        $this->deleteJson("/users/$userOId.json", ['transfer' => $transfer]);
        $this->assertSuccess();
        $this->assertUserIsSoftDeleted($userOId);
        $this->assertUserIsAdmin($groupMId, $userPId);
        $this->assertPermission($resourceLId, $groupMId, Permission::OWNER);
    }
}
