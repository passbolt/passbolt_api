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

class FindResourcesUserIsOwnerTest extends AppTestCase
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

    public function testFindResourcesUserIsOwner_OwnsNothing_DelUserCase0()
    {
        $user = UserFactory::make()->persist();
        $resources = $this->Permissions->findAcosByAroIsOwner(PermissionsTable::RESOURCE_ACO, $user->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindResourcesUserIsOwner_SoleOwnerNotSharedResource_DelUserCase1()
    {
        $user = UserFactory::make()->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $resources = $this->Permissions->findAcosByAroIsOwner(PermissionsTable::RESOURCE_ACO, $user->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], $r1->id);
    }

    public function testFindResourcesUserIsOwner_SoleOwnerSharedResourceWithUser_DelUserCase2()
    {
        [$owner, $otherUser] = UserFactory::make(2)->persist();
        $r1 = ResourceFactory::make()
            ->withPermissionsFor([$owner])
            ->withPermissionsFor([$otherUser], Permission::READ)
            ->persist();
        $resources = $this->Permissions->findAcosByAroIsOwner(PermissionsTable::RESOURCE_ACO, $owner->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], $r1->id);
    }

    public function testFindResourcesUserIsOwner_SharedResourceWithMe_DelUserCase3()
    {
        [$owner, $otherUser] = UserFactory::make(2)->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$owner])
            ->withPermissionsFor([$otherUser], Permission::READ)
            ->persist();
        $resources = $this->Permissions->findAcosByAroIsOwner(PermissionsTable::RESOURCE_ACO, $otherUser->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindResourcesUserIsOwner_SoleOwnerSharedResourceWithGroup_DelUserCase4()
    {
        [$owner, $groupMember] = UserFactory::make(2)->persist();
        $group = GroupFactory::make()->withGroupsUsersFor([$groupMember])->persist();
        $r1 = ResourceFactory::make()
            ->withPermissionsFor([$owner])
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();
        $resources = $this->Permissions->findAcosByAroIsOwner(PermissionsTable::RESOURCE_ACO, $owner->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], $r1->id);

        // Check groups users
        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $owner->id, ['checkGroupsUsers' => true])->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], $r1->id);
    }

    public function testFindResourcesUserIsOwner_SoleOwnerSharedResourceWithSoleManagerEmptyGroup_DelUserCase5()
    {
        $user = UserFactory::make()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$user])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$group, $user])->persist();
        $resources = $this->Permissions->findAcosByAroIsOwner(PermissionsTable::RESOURCE_ACO, $user->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], $r1->id);

        // Check groups users
        $resources = $this->Permissions->findAcosByAroIsOwner(PermissionsTable::RESOURCE_ACO, $user->id, ['checkGroupsUsers' => true])->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], $r1->id);
    }

    public function testFindResourcesUserIsOwner_indirectlyOwnerSharedResourceWithSoleManagerEmptyGroup_DelUserCase6()
    {
        $user = UserFactory::make()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$user])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$group, $user])->persist();

        // CONTEXTUAL TEST CHANGES Remove the direct permission of nancy
        $this->Permissions->deleteAll(['aro_foreign_key IN' => $user->id, 'aco_foreign_key' => $r1->id]);
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $group->id,
            'aco_foreign_key' => $r1->id,
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        $resources = $this->Permissions->findAcosByAroIsOwner(PermissionsTable::RESOURCE_ACO, $user->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);

        // Check groups users
        $resources = $this->Permissions->findAcosByAroIsOwner(PermissionsTable::RESOURCE_ACO, $user->id, ['checkGroupsUsers' => true])->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], $r1->id);
    }
}
