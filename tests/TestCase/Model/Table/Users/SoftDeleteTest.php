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

namespace App\Test\TestCase\Model\Table\Users;

use App\Model\Entity\Permission;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class SoftDeleteTest extends AppTestCase
{
    public $Groups;
    public $GroupsUsers;
    public $Permissions;
    public $Resources;
    public $Users;
    public $Secrets;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Favorites',
        'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Resources', 'app.Base/Secrets',
        'app.Alt0/GroupsUsers', 'app.Alt0/Permissions'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $this->GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
        $this->Secrets = TableRegistry::getTableLocator()->get('Secrets');
        $this->Favorites = TableRegistry::getTableLocator()->get('Favorites');
    }

    public function testUsersSoftDeleteSuccess_NoOwnerNoResourcesSharedNoGroupsMember_DelUserCase0()
    {
        $userIId = UuidFactory::uuid('user.id.irene');
        $user = $this->Users->get($userIId);
        $this->assertTrue($this->Users->softDelete($user));
        $this->assertUserIsSoftDeleted($userIId);
    }

    public function testUsersSoftDeleteSuccess_SoleOwnerNotSharedResource_DelUserCase1()
    {
        $userJId = UuidFactory::uuid('user.id.jean');
        $user = $this->Users->get($userJId);
        $this->assertTrue($this->Users->softDelete($user));
        $this->assertUserIsSoftDeleted($userJId);
        $this->assertResourceIsSoftDeleted(UuidFactory::uuid('resource.id.mailvelope'));
    }

    public function testUsersSoftDeleteError_SoleOwnerSharedResourceWithUser_DelUserCase2()
    {
        $userKId = UuidFactory::uuid('user.id.kathleen');
        $user = $this->Users->get($userKId);
        $this->assertFalse($this->Users->softDelete($user));
        $this->assertUserIsNotSoftDeleted($userKId);
        $this->assertResourceIsNotSoftDeleted(UuidFactory::uuid('resource.id.mocha'));
        $errors = $user->getErrors();
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedResource']);
        $this->assertFalse(isset($errors['id']['soleManagerOfNonEmptyGroup']));
        $this->assertFalse(isset($errors['id']['soleManagerOfGroupOwnerOfSharedResource']));
    }

    public function testUsersSoftDeleteSuccess_SoleOwnerSharedResourceWithUser_DelUserCase2()
    {
        $userKId = UuidFactory::uuid('user.id.kathleen');
        $userLId = UuidFactory::uuid('user.id.lynne');
        $resourceMId = UuidFactory::uuid('resource.id.mocha');
        $user = $this->Users->get($userKId);
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $userLId,
            'aco_foreign_key' => $resourceMId
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);
        $this->assertTrue($this->Users->softDelete($user));
        $this->assertUserIsSoftDeleted($userKId);
        $this->assertResourceIsNotSoftDeleted($resourceMId);
        $this->assertPermission($resourceMId, $userLId, Permission::OWNER);
    }

    public function testUsersSoftDeleteSuccess_SharedResourceWithMe_DelUserCase3()
    {
        $userLId = UuidFactory::uuid('user.id.lynne');
        $user = $this->Users->get($userLId);
        $this->assertTrue($this->Users->softDelete($user));
        $this->assertUserIsSoftDeleted($userLId);
        $this->assertResourceIsNotSoftDeleted(UuidFactory::uuid('resource.id.mocha'));
    }

    public function testUsersSoftDeleteError_SoleOwnerSharedResourceWithGroup_DelUserCase4()
    {
        $userMId = UuidFactory::uuid('user.id.marlyn');
        $resourceNId = UuidFactory::uuid('resource.id.nodejs');
        $user = $this->Users->get($userMId);
        $this->assertFalse($this->Users->softDelete($user));
        $this->assertUserIsNotSoftDeleted($userMId);
        $this->assertResourceIsNotSoftDeleted($resourceNId);
        $errors = $user->getErrors();
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedResource']);
        $this->assertFalse(isset($errors['id']['soleManagerOfNonEmptyGroup']));
        $this->assertFalse(isset($errors['id']['soleManagerOfGroupOwnerOfSharedResource']));
    }

    public function testUsersSoftDeleteSuccess_SoleOwnerSharedResourceWithGroup_DelUserCase4()
    {
        $userMId = UuidFactory::uuid('user.id.marlyn');
        $groupQId = UuidFactory::uuid('group.id.quality_assurance');
        $resourceNId = UuidFactory::uuid('resource.id.nodejs');
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $groupQId,
            'aco_foreign_key' => $resourceNId
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);
        $user = $this->Users->get($userMId);
        $this->assertTrue($this->Users->softDelete($user));
        $this->assertUserIsSoftDeleted($userMId);
        $this->assertResourceIsNotSoftDeleted($resourceNId);
    }

    public function testUsersSoftDeleteSuccess_SoleOwnerSharedResourceWithSoleManagerEmptyGroup_DelUserCase5()
    {
        $userNId = UuidFactory::uuid('user.id.nancy');
        $groupLId = UuidFactory::uuid('group.id.leadership_team');
        $resourceOId = UuidFactory::uuid('resource.id.openpgpjs');
        $user = $this->Users->get($userNId);
        $this->assertTrue($this->Users->softDelete($user));
        $this->assertUserIsSoftDeleted($userNId);
        $this->assertResourceIsSoftDeleted($resourceOId);
        $this->assertGroupIsSoftDeleted($groupLId);
    }

    public function testUsersSoftDeleteSuccess_ownerSharedResourceAlongWithSoleManagerEmptyGroup_DelUserCase6()
    {
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

        $user = $this->Users->get($userNId);
        $this->assertTrue($this->Users->softDelete($user));
        $this->assertUserIsSoftDeleted($userNId);
        $this->assertResourceIsSoftDeleted($resourceOId);
        $this->assertGroupIsSoftDeleted($groupLId);
    }

    public function testUsersSoftDeleteSuccess_indirectlyOwnerSharedResourceWithSoleManagerEmptyGroup_DelUserCase7()
    {
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

        $user = $this->Users->get($userNId);
        $this->assertTrue($this->Users->softDelete($user));
        $this->assertUserIsSoftDeleted($userNId);
        $this->assertResourceIsSoftDeleted($resourceOId);
        $this->assertGroupIsSoftDeleted($groupLId);
    }

    public function testUsersSoftDeleteError_soleManagerOfNotEmptyGroup_DelUserCase9()
    {
        $userEId = UuidFactory::uuid('user.id.edith');
        $user = $this->Users->get($userEId);
        $this->assertFalse($this->Users->softDelete($user));
        $this->assertUserIsNotSoftDeleted($userEId);
        $errors = $user->getErrors();
        $this->assertFalse(isset($errors['id']['soleOwnerOfSharedResource']));
        $this->assertNotEmpty($errors['id']['soleManagerOfNonEmptyGroup']);
        $this->assertFalse(isset($errors['id']['soleManagerOfGroupOwnerOfSharedResource']));
    }

    public function testUsersSoftDeleteSuccess_soleManagerOfNotEmptyGroup_DelUserCase9()
    {
        $userEId = UuidFactory::uuid('user.id.edith');
        $userFId = UuidFactory::uuid('user.id.frances');
        $groupFId = UuidFactory::uuid('group.id.freelancer');
        $groupUser = $this->GroupsUsers->find()->select()->where([
            'user_id' => $userFId,
            'group_id' => $groupFId
        ])->first();
        $groupUser->is_admin = true;
        $this->GroupsUsers->save($groupUser);
        $user = $this->Users->get($userEId);
        $this->assertTrue($this->Users->softDelete($user));
        $this->assertUserIsSoftDeleted($userEId);
        $this->assertGroupIsNotSoftDeleted($groupFId);
        $this->assertUserIsAdmin($groupFId, $userFId);
    }

    public function testUsersSoftDeleteError_ownerAlongWithSoleManagerOfNotEmptyGroup_DelUserCase10()
    {
        $userOId = UuidFactory::uuid('user.id.orna');
        $user = $this->Users->get($userOId);
        $this->assertFalse($this->Users->softDelete($user));
        $this->assertUserIsNotSoftDeleted($userOId);
        $errors = $user->getErrors();
        $this->assertFalse(isset($errors['id']['soleOwnerOfSharedResource']));
        $this->assertNotEmpty($errors['id']['soleManagerOfNonEmptyGroup']);
        $this->assertFalse(isset($errors['id']['soleManagerOfGroupOwnerOfSharedResource']));
    }

    public function testUsersSoftDeleteSuccess_ownerAlongWithSoleManagerOfNotEmptyGroup_DelUserCase10()
    {
        $userOId = UuidFactory::uuid('user.id.orna');
        $userPId = UuidFactory::uuid('user.id.ping');
        $groupMId = UuidFactory::uuid('group.id.management');
        $groupUser = $this->GroupsUsers->find()->select()->where([
            'user_id' => $userPId,
            'group_id' => $groupMId
        ])->first();
        $groupUser->is_admin = true;
        $this->GroupsUsers->save($groupUser);
        $user = $this->Users->get($userOId);
        $this->assertTrue($this->Users->softDelete($user));
        $this->assertUserIsSoftDeleted($userOId);
        $this->assertGroupIsNotSoftDeleted($groupMId);
        $this->assertUserIsAdmin($groupMId, $userPId);
    }

    public function testUsersSoftDeleteError_indireclyOwnerWithSoleManagerOfNotEmptyGroup_DelUserCase11()
    {
        $userOId = UuidFactory::uuid('user.id.orna');
        $resourceLId = UuidFactory::uuid('resource.id.linux');

        // CONTEXTUAL TEST CHANGES Remove The permissions of Orna
        $this->Permissions->deleteAll([
            'aro_foreign_key' => $userOId,
            'aco_foreign_key' => UuidFactory::uuid('resource.id.linux')
        ]);

        $user = $this->Users->get($userOId);
        $this->assertFalse($this->Users->softDelete($user));
        $this->assertUserIsNotSoftDeleted($userOId);
        $this->assertResourceIsNotSoftDeleted($resourceLId);
        $errors = $user->getErrors();
        $this->assertFalse(isset($errors['id']['soleOwnerOfSharedResource']));
        $this->assertNotEmpty($errors['id']['soleManagerOfNonEmptyGroup']);
        $this->assertFalse(isset($errors['id']['soleManagerOfGroupOwnerOfSharedResource']));
    }

    public function testUsersSoftDeleteSuccess_indireclyOwnerWithSoleManagerOfNotEmptyGroup_DelUserCase11()
    {
        $userOId = UuidFactory::uuid('user.id.orna');
        $userPId = UuidFactory::uuid('user.id.ping');
        $groupMId = UuidFactory::uuid('group.id.management');
        $resourceLId = UuidFactory::uuid('resource.id.linux');

        // CONTEXTUAL TEST CHANGES Remove The permissions of Orna
        $this->Permissions->deleteAll([
            'aro_foreign_key' => $userOId,
            'aco_foreign_key' => UuidFactory::uuid('resource.id.linux')
        ]);

        $groupUser = $this->GroupsUsers->find()->select()->where([
            'user_id' => $userPId,
            'group_id' => $groupMId
        ])->first();
        $groupUser->is_admin = true;
        $this->GroupsUsers->save($groupUser);

        $user = $this->Users->get($userOId);
        $this->assertTrue($this->Users->softDelete($user));
        $this->assertUserIsSoftDeleted($userOId);
        $this->assertGroupIsNotSoftDeleted($groupMId);
        $this->assertResourceIsNotSoftDeleted($resourceLId);
        $this->assertUserIsAdmin($groupMId, $userPId);
    }

    public function testUsersSoftDeleteError_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroup_DelUserCase12()
    {
        $userUId = UuidFactory::uuid('user.id.ursula');
        $user = $this->Users->get($userUId);
        $this->assertFalse($this->Users->softDelete($user));
        $this->assertUserIsNotSoftDeleted($userUId);
        $errors = $user->getErrors();
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedResource']);
        $this->assertFalse(isset($errors['id']['soleManagerOfNonEmptyGroup']));
    }

    public function testUsersSoftDeleteSuccess_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroup_DelUserCase12()
    {
        $userTId = UuidFactory::uuid('user.id.thelma');
        $userUId = UuidFactory::uuid('user.id.ursula');
        $groupNId = UuidFactory::uuid('group.id.network');
        $resourcePId = UuidFactory::uuid('resource.id.phpunit');
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $userTId,
            'aco_foreign_key' => $resourcePId
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);
        $user = $this->Users->get($userUId);
        $this->assertTrue($this->Users->softDelete($user));
        $this->assertUserIsSoftDeleted($userUId);
        $this->assertGroupIsSoftDeleted($groupNId);
    }

    public function testUsersSoftDeleteSuccess_indirectlyOwnerResourceWithSoleManagerOfEmptyGroups_DelUserCase13()
    {
        $userWId = UuidFactory::uuid('user.id.wang');
        $resourceQId = UuidFactory::uuid('resource.id.qgis');
        $groupOId = UuidFactory::uuid('group.id.operations');
        $groupPId = UuidFactory::uuid('group.id.procurement');
        $user = $this->Users->get($userWId);
        $this->assertTrue($this->Users->softDelete($user));
        $this->assertUserIsSoftDeleted($userWId);
        $this->assertGroupIsSoftDeleted($groupOId);
        $this->assertGroupIsSoftDeleted($groupPId);
        $this->assertResourceIsSoftDeleted($resourceQId);
    }

    public function testUsersSoftDeleteError_indirectlyOwnerSharedResourceWithSoleManagerOfNonEmptyGroup_DelUserCase14()
    {
        $userYId = UuidFactory::uuid('user.id.yvonne');
        $user = $this->Users->get($userYId);
        $this->assertFalse($this->Users->softDelete($user));
        $this->assertUserIsNotSoftDeleted($userYId);
        $errors = $user->getErrors();
        $this->assertFalse(isset($errors['id']['soleOwnerOfSharedResource']));
        $this->assertNotEmpty($errors['id']['soleManagerOfNonEmptyGroup']);
    }

    public function testUsersSoftDeleteSuccess_indirectlyOwnerSharedResourceWithSoleManagerOfNonEmptyGroup_DelUserCase14()
    {
        $userYId = UuidFactory::uuid('user.id.yvonne');
        $userJId = UuidFactory::uuid('user.id.joan');
        $resourceSId = UuidFactory::uuid('resource.id.selenium');
        $groupHId = UuidFactory::uuid('group.id.human_resource');

        $groupUser = $this->GroupsUsers->find()->select()->where([
            'user_id' => $userJId,
            'group_id' => $groupHId
        ])->first();
        $groupUser->is_admin = true;
        $this->GroupsUsers->save($groupUser);

        $user = $this->Users->get($userYId);
        $this->assertTrue($this->Users->softDelete($user));
        $this->assertUserIsSoftDeleted($userYId);
        $this->assertGroupIsNotSoftDeleted($groupHId);
        $this->assertResourceIsNotSoftDeleted($resourceSId);
    }

    public function testUsersSoftDeleteError_SoleOwnerSharedResourceWithNotEmptyGroup_DelUserCase15()
    {
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

        $user = $this->Users->get($userOId);
        $this->assertFalse($this->Users->softDelete($user));
        $errors = $user->getErrors();
        $this->assertNotEmpty($errors['id']['soleManagerOfNonEmptyGroup']);
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedResource']);
    }

    public function testUsersSoftDeleteSuccess_SoleOwnerSharedResourceWithNotEmptyGroup_DelUserCase15()
    {
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

        // FIX
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $groupMId,
            'aco_foreign_key' => $resourceLId
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);
        $groupUser = $this->GroupsUsers->find()->select()->where([
            'user_id' => $userPId,
            'group_id' => $groupMId
        ])->first();
        $groupUser->is_admin = true;
        $this->GroupsUsers->save($groupUser);

        $user = $this->Users->get($userOId);
        $this->assertTrue($this->Users->softDelete($user));
        $this->assertUserIsSoftDeleted($userOId);
        $this->assertGroupIsNotSoftDeleted($groupMId);
        $this->assertResourceIsNotSoftDeleted($resourceLId);
    }
}
