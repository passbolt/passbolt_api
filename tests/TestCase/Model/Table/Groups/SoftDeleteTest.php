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

namespace App\Test\TestCase\Model\Table\Groups;

use App\Model\Entity\Permission;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;

class SoftDeleteTest extends AppTestCase
{
    public $Groups;
    public $Permissions;

    public function setUp(): void
    {
        parent::setUp();
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
    }

    public function testGroupsSoftDeleteSuccess_NoOwnerNoResourcesSharedNoGroupsMember_DelGroupCase0()
    {
        $user = UserFactory::make()->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$user])->persist();
        $this->assertNotFalse($this->Groups->softDelete($group));
        $this->assertGroupIsSoftDeleted($group->id);
    }

    public function testGroupsSoftDeleteSuccess_SharedResourceWithMe_DelGroupCase1()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userB])
            ->withPermissionsFor([$group], Permission::READ)
            ->withSecretsFor([$userB, $group])
            ->persist();
        $this->assertNotFalse($this->Groups->softDelete($group));
        $this->assertGroupIsSoftDeleted($group->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);
        $this->assertPermissionNotExist($resource->id, $group->id);
        $this->assertSecretNotExist($resource->id, $userA->id);
        $this->assertSecretExists($resource->id, $userB->id);
    }

    public function testGroupsSoftDeleteSuccess_SoleOwnerNotSharedResource_DelGroupCase2()
    {
        $user = UserFactory::make()->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$user])->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$group])->withSecretsFor([$group])->persist();
        $this->assertNotFalse($this->Groups->softDelete($group));
        $this->assertGroupIsSoftDeleted($group->id);
        $this->assertResourceIsSoftDeleted($resource->id);
        $this->assertPermissionNotExist($resource->id, $group->id);
        $this->assertSecretNotExist($resource->id, $user->id);
    }

    public function testGroupsSoftDeleteError_SoleOwnerSharedResource_DelGroupCase3()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userB])
            ->withPermissionsFor([$group], Permission::READ)
            ->withSecretsFor([$userB, $group])
            ->persist();

        // CONTEXTUAL TEST CHANGES Make the group sole owner of the resource
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $userB->id,
            'aco_foreign_key' => $resource->id,
        ])->first();
        $permission->type = Permission::READ;
        $this->Permissions->save($permission);
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $group->id,
            'aco_foreign_key' => $resource->id,
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        $this->assertFalse($this->Groups->softDelete($group));
        $errors = $group->getErrors();
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedContent']);
        $this->assertGroupIsNotSoftDeleted($group->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);
        $this->assertPermission($resource->id, $group->id, Permission::OWNER);
        $this->assertPermission($resource->id, $userB->id, Permission::READ);
    }

    public function testGroupsSoftDeleteSuccess_SoleOwnerSharedResource_DelGroupCase3()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userB])
            ->withPermissionsFor([$group], Permission::READ)
            ->withSecretsFor([$userB, $group])
            ->persist();

        // CONTEXTUAL TEST CHANGES Make the group sole owner of the resource
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $userB->id,
            'aco_foreign_key' => $resource->id,
        ])->first();
        $permission->type = Permission::READ;
        $this->Permissions->save($permission);
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $group->id,
            'aco_foreign_key' => $resource->id,
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        // FIX
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $userB->id,
            'aco_foreign_key' => $resource->id,
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        $group = $this->Groups->get($group->id);
        $this->assertNotFalse($this->Groups->softDelete($group));
        $this->assertGroupIsSoftDeleted($group->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);
        $this->assertPermissionNotExist($resource->id, $group->id);
        $this->assertPermission($resource->id, $userB->id, Permission::OWNER);
        $this->assertSecretNotExist($resource->id, $userA->id);
        $this->assertSecretExists($resource->id, $userB->id);
    }

    public function testGroupsSoftDeleteSuccess_OwnerAlongWithAnotherUser_DelGroupCase4()
    {
        $user = UserFactory::make()->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$user])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user, $group])
            ->withSecretsFor([$user, $group])
            ->persist();
        $this->assertPermission($resource->id, $group->id, Permission::OWNER);
        $this->assertNotFalse($this->Groups->softDelete($group));
        $this->assertGroupIsSoftDeleted($group->id);
        $this->assertResourceIsNotSoftDeleted($resource->id);
        $this->assertPermission($resource->id, $user->id, Permission::OWNER);
        $this->assertPermissionNotExist($resource->id, $group->id);
        $this->assertSecretExists($resource->id, $user->id);
    }
}
