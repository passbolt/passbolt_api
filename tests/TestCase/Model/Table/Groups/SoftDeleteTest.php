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

namespace App\Test\TestCase\Model\Table\Groups;

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
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Profiles',
        'app.Base/Gpgkeys', 'app.Base/Resources', 'app.Base/Favorites', 'app.Alt0/Secrets',
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
    }

    public function testGroupsSoftDeleteSuccess_NoOwnerNoResourcesSharedNoGroupsMember_DelGroupCase0()
    {
        $groupId = UuidFactory::uuid('group.id.procurement');
        $group = $this->Groups->get($groupId);
        $this->assertTrue($this->Groups->softDelete($group));
        $this->assertGroupIsSoftDeleted($groupId);
    }

    public function testGroupsSoftDeleteSuccess_SharedResourceWithMe_DelGroupCase1()
    {
        $groupId = UuidFactory::uuid('group.id.quality_assurance');
        $resourceId = UuidFactory::uuid('resource.id.nodejs');
        $userHId = UuidFactory::uuid('user.id.hedy');
        $userMId = UuidFactory::uuid('user.id.marlyn');
        $group = $this->Groups->get($groupId);
        $this->assertTrue($this->Groups->softDelete($group));
        $this->assertGroupIsSoftDeleted($groupId);
        $this->assertResourceIsNotSoftDeleted($resourceId);
        $this->assertPermissionNotExist($resourceId, $groupId);
        $this->assertSecretNotExist($resourceId, $userHId);
        $this->assertSecretExists($resourceId, $userMId);
    }

    public function testGroupsSoftDeleteSuccess_SoleOwnerNotSharedResource_DelGroupCase2()
    {
        $groupId = UuidFactory::uuid('group.id.resource_planning');
        $resourceId = UuidFactory::uuid('resource.id.stealjs');
        $userId = UuidFactory::uuid('user.id.adele');
        $group = $this->Groups->get($groupId);
        $this->assertTrue($this->Groups->softDelete($group));
        $this->assertGroupIsSoftDeleted($groupId);
        $this->assertResourceIsSoftDeleted($resourceId);
        $this->assertPermissionNotExist($resourceId, $groupId);
        $this->assertSecretNotExist($resourceId, $userId);
    }

    public function testGroupsSoftDeleteError_SoleOwnerSharedResource_DelGroupCase3()
    {
        $groupId = UuidFactory::uuid('group.id.quality_assurance');
        $resourceId = UuidFactory::uuid('resource.id.nodejs');
        $userId = UuidFactory::uuid('user.id.marlyn');

        // CONTEXTUAL TEST CHANGES Make the group sole owner of the resource
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

        $group = $this->Groups->get($groupId);
        $this->assertFalse($this->Groups->softDelete($group));
        $errors = $group->getErrors();
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedResource']);
        $this->assertGroupIsNotSoftDeleted($groupId);
        $this->assertResourceIsNotSoftDeleted($resourceId);
        $this->assertPermission($resourceId, $groupId, Permission::OWNER);
        $this->assertPermission($resourceId, $userId, Permission::READ);
    }

    public function testGroupsSoftDeleteSuccess_SoleOwnerSharedResource_DelGroupCase3()
    {
        $groupId = UuidFactory::uuid('group.id.quality_assurance');
        $resourceId = UuidFactory::uuid('resource.id.nodejs');
        $userMId = UuidFactory::uuid('user.id.marlyn');
        $userHId = UuidFactory::uuid('user.id.hedy');

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

        // FIX
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $userMId,
            'aco_foreign_key' => $resourceId
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        $group = $this->Groups->get($groupId);
        $this->assertTrue($this->Groups->softDelete($group));
        $this->assertGroupIsSoftDeleted($groupId);
        $this->assertResourceIsNotSoftDeleted($resourceId);
        $this->assertPermissionNotExist($resourceId, $groupId);
        $this->assertPermission($resourceId, $userMId, Permission::OWNER);
        $this->assertSecretNotExist($resourceId, $userHId);
        $this->assertSecretExists($resourceId, $userMId);
    }

    public function testGroupsSoftDeleteSuccess_OwnerAlongWithAnotherUser_DelGroupCase4()
    {
        $groupId = UuidFactory::uuid('group.id.management');
        $resourceId = UuidFactory::uuid('resource.id.linux');
        $userId = UuidFactory::uuid('user.id.orna');
        $this->assertPermission($resourceId, $groupId, Permission::OWNER);
        $group = $this->Groups->get($groupId);
        $this->assertTrue($this->Groups->softDelete($group));
        $this->assertGroupIsSoftDeleted($groupId);
        $this->assertResourceIsNotSoftDeleted($resourceId);
        $this->assertPermission($resourceId, $userId, Permission::OWNER);
        $this->assertPermissionNotExist($resourceId, $groupId);
        $this->assertSecretExists($resourceId, $userId);
    }
}
