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

class FindSharedResourcesGroupIsSoleOwnerTest extends AppTestCase
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

    public function testFindSharedResourceGroupIsSoleOwner_OwnsNothing_DelGroupCase0()
    {
        $userA = UserFactory::make()->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $group->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindSharedResourceGroupIsSoleOwner_SharedResourceWithMe_DelGroupCase1()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userB])->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $group->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindSharedResourceGroupIsSoleOwner_SoleOwnerNotSharedResource_DelGroupCase2()
    {
        $userA = UserFactory::make()->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        ResourceFactory::make()->withPermissionsFor([$group])->persist();

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $group->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testGroupsSoftDelete_SoleOwnerSharedResource_DelGroupCase3()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userB])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();

        // CONTEXTUAL TEST CHANGES Make the group sole owner of the resource
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $userA->id,
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

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $group->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertNotEmpty($resources);
        $this->assertCount(1, $resources);
        $this->assertTrue(in_array($resource->id, $resources));
    }

    public function testFindSharedResourceGroupIsSoleOwner_OwnerAlongWithAnotherUser_DelGroupCase4()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        ResourceFactory::make()->withPermissionsFor([$userA, $group])->persist();

        $resources = $this->Permissions->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $group->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }
}
