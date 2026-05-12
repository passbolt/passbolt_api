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

namespace App\Test\TestCase\Model\Table\Users;

use App\Model\Entity\Permission;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionFactory;

class SoftDeleteTest extends AppTestCase
{
    public $GroupsUsers;
    public $Permissions;
    public $Users;

    public function setUp(): void
    {
        parent::setUp();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $this->GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
    }

    public function testUsersSoftDeleteSuccess_NoOwnerNoResourcesSharedNoGroupsMember_DelUserCase0()
    {
        $userA = UserFactory::make()->user()->persist();
        $this->assertNotFalse($this->Users->softDelete($userA));
        $this->assertUserIsSoftDeleted($userA->id);
    }

    public function testUsersSoftDeleteSuccess_SoleOwnerNotSharedResource_DelUserCase1()
    {
        $userA = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->persist();
        $this->assertNotFalse($this->Users->softDelete($userA));
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertResourceIsSoftDeleted($resource->id);
    }

    public function testUsersSoftDeleteError_SoleOwnerSharedResourceWithUser_DelUserCase2()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();
        $this->assertFalse($this->Users->softDelete($userA));
        $this->assertUserIsNotSoftDeleted($userA->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);
        $errors = $userA->getErrors();
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedContent']);
        $this->assertFalse(isset($errors['id']['soleManagerOfNonEmptyGroup']));
        $this->assertFalse(isset($errors['id']['soleManagerOfGroupOwnerOfSharedResource']));
    }

    public function testUsersSoftDeleteSuccess_SoleOwnerSharedResourceWithUser_DelUserCase2()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $userB->id,
            'aco_foreign_key' => $resource->id,
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);
        $this->assertNotFalse($this->Users->softDelete($userA));
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);
        $this->assertPermission($resource->id, $userB->id, Permission::OWNER);
    }

    public function testUsersSoftDeleteSuccess_SharedResourceWithMe_DelUserCase3()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();
        $this->assertNotFalse($this->Users->softDelete($userB));
        $this->assertUserIsSoftDeleted($userB->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);
    }

    public function testUsersSoftDeleteError_SoleOwnerSharedResourceWithGroup_DelUserCase4()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userB])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();
        $this->assertFalse($this->Users->softDelete($userA));
        $this->assertUserIsNotSoftDeleted($userA->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);
        $errors = $userA->getErrors();
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedContent']);
        $this->assertFalse(isset($errors['id']['soleManagerOfNonEmptyGroup']));
        $this->assertFalse(isset($errors['id']['soleManagerOfGroupOwnerOfSharedResource']));
    }

    public function testUsersSoftDeleteSuccess_SoleOwnerSharedResourceWithGroup_DelUserCase4()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userB])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $group->id,
            'aco_foreign_key' => $resource->id,
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);
        $this->assertNotFalse($this->Users->softDelete($userA));
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);
    }

    public function testUsersSoftDeleteSuccess_SoleOwnerSharedResourceWithSoleManagerEmptyGroup_DelUserCase5()
    {
        $userA = UserFactory::make()->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();
        $this->assertNotFalse($this->Users->softDelete($userA));
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertResourceIsSoftDeleted($resource->id);
        $this->assertGroupIsSoftDeleted($group->id);
    }

    public function testUsersSoftDeleteSuccess_ownerSharedResourceAlongWithSoleManagerEmptyGroup_DelUserCase6()
    {
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

        $this->assertNotFalse($this->Users->softDelete($userA));
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertResourceIsSoftDeleted($resource->id);
        $this->assertGroupIsSoftDeleted($group->id);
    }

    public function testUsersSoftDeleteSuccess_indirectlyOwnerSharedResourceWithSoleManagerEmptyGroup_DelUserCase7()
    {
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

        $this->assertNotFalse($this->Users->softDelete($userA));
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertResourceIsSoftDeleted($resource->id);
        $this->assertGroupIsSoftDeleted($group->id);
    }

    public function testUsersSoftDeleteError_soleManagerOfNotEmptyGroup_DelUserCase9()
    {
        [$userA, $userB, $userC] = UserFactory::make(3)->user()->persist();
        GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB, $userC])
            ->persist();
        $this->assertFalse($this->Users->softDelete($userA));
        $this->assertUserIsNotSoftDeleted($userA->id);
        $errors = $userA->getErrors();
        $this->assertFalse(isset($errors['id']['soleOwnerOfSharedResource']));
        $this->assertNotEmpty($errors['id']['soleManagerOfNonEmptyGroup']);
        $this->assertFalse(isset($errors['id']['soleManagerOfGroupOwnerOfSharedResource']));
    }

    public function testUsersSoftDeleteSuccess_soleManagerOfNotEmptyGroup_DelUserCase9()
    {
        [$userA, $userB, $userC] = UserFactory::make(3)->user()->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB, $userC])
            ->persist();
        $groupUser = $this->GroupsUsers->find()->select()->where([
            'user_id' => $userB->id,
            'group_id' => $group->id,
        ])->first();
        $groupUser->is_admin = true;
        $this->GroupsUsers->save($groupUser);
        $this->assertNotFalse($this->Users->softDelete($userA));
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertGroupIsNotSoftDeleted($group->id);
        $this->assertUserIsAdmin($group->id, $userB->id);
    }

    public function testUsersSoftDeleteError_ownerAlongWithSoleManagerOfNotEmptyGroup_DelUserCase10()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        ResourceFactory::make()->withPermissionsFor([$userA, $group])->persist();
        $this->assertFalse($this->Users->softDelete($userA));
        $this->assertUserIsNotSoftDeleted($userA->id);
        $errors = $userA->getErrors();
        $this->assertFalse(isset($errors['id']['soleOwnerOfSharedResource']));
        $this->assertNotEmpty($errors['id']['soleManagerOfNonEmptyGroup']);
        $this->assertFalse(isset($errors['id']['soleManagerOfGroupOwnerOfSharedResource']));
    }

    public function testUsersSoftDeleteSuccess_ownerAlongWithSoleManagerOfNotEmptyGroup_DelUserCase10()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        ResourceFactory::make()->withPermissionsFor([$userA, $group])->persist();
        $groupUser = $this->GroupsUsers->find()->select()->where([
            'user_id' => $userB->id,
            'group_id' => $group->id,
        ])->first();
        $groupUser->is_admin = true;
        $this->GroupsUsers->save($groupUser);
        $this->assertNotFalse($this->Users->softDelete($userA));
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertGroupIsNotSoftDeleted($group->id);
        $this->assertUserIsAdmin($group->id, $userB->id);
    }

    public function testUsersSoftDeleteError_indireclyOwnerWithSoleManagerOfNotEmptyGroup_DelUserCase11()
    {
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

        $this->assertFalse($this->Users->softDelete($userA));
        $this->assertUserIsNotSoftDeleted($userA->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);
        $errors = $userA->getErrors();
        $this->assertFalse(isset($errors['id']['soleOwnerOfSharedResource']));
        $this->assertNotEmpty($errors['id']['soleManagerOfNonEmptyGroup']);
        $this->assertFalse(isset($errors['id']['soleManagerOfGroupOwnerOfSharedResource']));
    }

    public function testUsersSoftDeleteSuccess_indireclyOwnerWithSoleManagerOfNotEmptyGroup_DelUserCase11()
    {
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

        $groupUser = $this->GroupsUsers->find()->select()->where([
            'user_id' => $userB->id,
            'group_id' => $group->id,
        ])->first();
        $groupUser->is_admin = true;
        $this->GroupsUsers->save($groupUser);

        $this->assertNotFalse($this->Users->softDelete($userA));
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertGroupIsNotSoftDeleted($group->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);
        $this->assertUserIsAdmin($group->id, $userB->id);
    }

    public function testUsersSoftDeleteError_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroup_DelUserCase12()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();
        $this->assertFalse($this->Users->softDelete($userA));
        $this->assertUserIsNotSoftDeleted($userA->id);
        $errors = $userA->getErrors();
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedContent']);
        $this->assertFalse(isset($errors['id']['soleManagerOfNonEmptyGroup']));
    }

    public function testUsersSoftDeleteSuccess_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroup_DelUserCase12()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $userB->id,
            'aco_foreign_key' => $resource->id,
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);
        $this->assertNotFalse($this->Users->softDelete($userA));
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertGroupIsSoftDeleted($group->id);
    }

    public function testUsersSoftDeleteSuccess_indirectlyOwnerResourceWithSoleManagerOfEmptyGroups_DelUserCase13()
    {
        $userA = UserFactory::make()->user()->persist();
        [$groupA, $groupB] = GroupFactory::make(2)->withGroupsManagersFor([$userA])->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$groupA, $groupB])->persist();
        $this->assertNotFalse($this->Users->softDelete($userA));
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertGroupIsSoftDeleted($groupA->id);
        $this->assertGroupIsSoftDeleted($groupB->id);
        $this->assertResourceIsSoftDeleted($resource->id);
    }

    public function testUsersSoftDeleteError_indirectlyOwnerSharedResourceWithSoleManagerOfNonEmptyGroup_DelUserCase14()
    {
        [$userA, $userB, $userC] = UserFactory::make(3)->user()->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->withPermissionsFor([$userC], Permission::READ)
            ->persist();
        $this->assertFalse($this->Users->softDelete($userA));
        $this->assertUserIsNotSoftDeleted($userA->id);
        $errors = $userA->getErrors();
        $this->assertFalse(isset($errors['id']['soleOwnerOfSharedResource']));
        $this->assertNotEmpty($errors['id']['soleManagerOfNonEmptyGroup']);
    }

    public function testUsersSoftDeleteSuccess_indirectlyOwnerSharedResourceWithSoleManagerOfNonEmptyGroup_DelUserCase14()
    {
        [$userA, $userB, $userC] = UserFactory::make(3)->user()->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->withPermissionsFor([$userC], Permission::READ)
            ->persist();

        $groupUser = $this->GroupsUsers->find()->select()->where([
            'user_id' => $userB->id,
            'group_id' => $group->id,
        ])->first();
        $groupUser->is_admin = true;
        $this->GroupsUsers->save($groupUser);

        $this->assertNotFalse($this->Users->softDelete($userA));
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertGroupIsNotSoftDeleted($group->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);
    }

    public function testUsersSoftDeleteError_SoleOwnerSharedResourceWithNotEmptyGroup_DelUserCase15()
    {
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

        $this->assertFalse($this->Users->softDelete($userA));
        $errors = $userA->getErrors();
        $this->assertNotEmpty($errors['id']['soleManagerOfNonEmptyGroup']);
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedContent']);
    }

    public function testUsersSoftDeleteSuccess_SoleOwnerSharedResourceWithNotEmptyGroup_DelUserCase15()
    {
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

        // FIX
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $group->id,
            'aco_foreign_key' => $resource->id,
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);
        $groupUser = $this->GroupsUsers->find()->select()->where([
            'user_id' => $userB->id,
            'group_id' => $group->id,
        ])->first();
        $groupUser->is_admin = true;
        $this->GroupsUsers->save($groupUser);

        $this->assertNotFalse($this->Users->softDelete($userA));
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertGroupIsNotSoftDeleted($group->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);
    }

    public function testUsersSoftDeleteSuccess_Delete_Revision_Of_Personal_Resource_But_Keep_Revision_Of_Shared_Resource()
    {
        $users = [$user1,] = UserFactory::make(2)->persist();
        $resourceToDelete = ResourceFactory::make()
            ->withSecretRevisions()
            ->withSecretsFor([$user1])
            ->withPermissionsFor([$user1])
            ->persist();
        $resourceToMaintain = ResourceFactory::make()
            ->withSecretRevisions()
            ->withSecretsFor($users)
            ->withPermissionsFor($users)
            ->persist();

        $this->Users->softDelete($user1);

        $this->assertSame(0, SecretRevisionFactory::find()->where(['id' => $resourceToDelete->secret_revisions[0]->id])->count());
        $this->assertSame(1, SecretRevisionFactory::find()->where(['id' => $resourceToMaintain->secret_revisions[0]->id])->count());
        $this->assertSame(1, SecretFactory::find()->where(['resource_id' => $resourceToMaintain->id])->count());
    }
}
