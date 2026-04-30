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

namespace App\Test\TestCase\Model\Table\Permissions;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;

class FindSharedResourcesUserIsSoleOwnerTest extends AppTestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PermissionsTable
     */
    public $Permissions;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
    }

    public function testFindShardResourceUserIsSoleOwner_OwnsNothing_DelUserCase0()
    {
        $userA = UserFactory::make()->user()->persist();
        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_SoleOwnerNotSharedResource_DelUserCase1()
    {
        $userA = UserFactory::make()->user()->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->persist();
        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_SoleOwnerSharedResourceWithUser_DelUserCase2()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();
        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], $resource->id);
    }

    public function testFindShardResourceUserIsSoleOwner_SharedResourceWithMe_DelUserCase3()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();
        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userB->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_SoleOwnerSharedResourceWithGroup_DelUserCase4()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userB])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();
        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], $resource->id);
    }

    public function testFindShardResourceUserIsSoleOwner_SoleOwnerSharedResourceWithSoleManagerEmptyGroup_DelUserCase5()
    {
        $userA = UserFactory::make()->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();
        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], $resource->id);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_SoleOwnerSharedResourceWithSoleManagerEmptyGroup_DelUserCase5()
    {
        $userA = UserFactory::make()->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();
        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id, ['checkGroupsUsers' => true])->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_OwnerSharedResourceAlongWithSoleManagerEmptyGroup_DelUserCase6()
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

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_OwnerSharedResourceAlongWithSoleManagerEmptyGroup_DelUserCase6()
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

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id, ['checkGroupsUsers' => true])->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_indirectlyOwnerSharedResourceWithSoleManagerEmptyGroup_DelUserCase7()
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

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_indirectlyOwnerSharedResourceWithSoleManagerEmptyGroup_DelUserCase7()
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

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id, ['checkGroupsUsers' => true])->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_OwnerAlongWithSoleManagerOfNotEmptyGroup_DelUserCase10()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        ResourceFactory::make()->withPermissionsFor([$userA, $group])->persist();

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_OwnerAlongWithSoleManagerOfNotEmptyGroup_DelUserCase10()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        ResourceFactory::make()->withPermissionsFor([$userA, $group])->persist();

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id, ['checkGroupsUsers' => true])->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_indireclyOwnerWithSoleManagerOfNotEmptyGroup_DelUserCase11()
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

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_indireclyOwnerWithSoleManagerOfNotEmptyGroup_DelUserCase11()
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

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroup_DelUserCase12()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroup_DelUserCase12()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id, ['checkGroupsUsers' => true])->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertTrue(in_array($resource->id, $resources));
    }

    public function testFindShardResourceUserIsSoleOwner_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroups_DelUserCase13()
    {
        $userA = UserFactory::make()->user()->persist();
        [$groupA, $groupB] = GroupFactory::make(2)->withGroupsManagersFor([$userA])->persist();
        ResourceFactory::make()->withPermissionsFor([$groupA, $groupB])->persist();

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroups_DelUserCase13()
    {
        $userA = UserFactory::make()->user()->persist();
        [$groupA, $groupB] = GroupFactory::make(2)->withGroupsManagersFor([$userA])->persist();
        ResourceFactory::make()->withPermissionsFor([$groupA, $groupB])->persist();

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id, ['checkGroupsUsers' => true])->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_indirectlyOwnerSharedResourceWithSoleManagerOfNonEmptyGroup_DelUserCase14()
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

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_indirectlyOwnerSharedResourceWithSoleManagerOfNonEmptyGroup_DelUserCase14()
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

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id, ['checkGroupsUsers' => true])->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_SoleOwnerSharedResourceWithNotEmptyGroup_DelUserCase15()
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

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertTrue(in_array($resource->id, $resources));
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_SoleOwnerSharedResourceWithNotEmptyGroup_DelUserCase15()
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

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $userA->id, ['checkGroupsUsers' => true])->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertTrue(in_array($resource->id, $resources));
    }
}
