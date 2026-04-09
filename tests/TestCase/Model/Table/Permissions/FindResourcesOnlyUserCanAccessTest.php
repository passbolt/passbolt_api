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

class FindResourcesOnlyUserCanAccessTest extends AppTestCase
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

    public function testFindOnlyUserCanAccess_SoleOwnerNotShared()
    {
        $user = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $resources = $this->Permissions->findAcosOnlyAroCanAccess(PermissionsTable::RESOURCE_ACO, $user->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], $resource->id);
    }

    public function testFindOnlyUserCanAccess_OwnerAlongWithAnotherUser()
    {
        [$owner, $otherUser] = UserFactory::make(2)->persist();
        ResourceFactory::make()->withPermissionsFor([$owner, $otherUser])->persist();
        $resources = $this->Permissions->findAcosOnlyAroCanAccess(PermissionsTable::RESOURCE_ACO, $otherUser->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 0);
    }

    public function testFindOnlyUserCanAccess_SharedWithMe()
    {
        [$owner, $otherUser] = UserFactory::make(2)->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$owner])
            ->withPermissionsFor([$otherUser], Permission::READ)
            ->persist();
        $resources = $this->Permissions->findAcosOnlyAroCanAccess(PermissionsTable::RESOURCE_ACO, $otherUser->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 0);
    }

    public function testFindOnlyUserCanAccess_NoOwnerNoResourcesSharedNoGroupsMember()
    {
        $user = UserFactory::make()->persist();
        ResourceFactory::make()->persist();
        $resources = $this->Permissions->findAcosOnlyAroCanAccess(PermissionsTable::RESOURCE_ACO, $user->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 0);
    }

    public function testFindOnlyUserCanAccess_NoOwner()
    {
        [$user, $otherUser] = UserFactory::make(2)->persist();
        $group = GroupFactory::make()->withGroupsUsersFor([$user, $otherUser])->persist();
        ResourceFactory::make()->withPermissionsFor([$group])->persist();
        $resources = $this->Permissions->findAcosOnlyAroCanAccess(PermissionsTable::RESOURCE_ACO, $user->id, ['checkGroupsUsers' => true])->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 0);
    }

    public function testFindOnlyUserCanAccess_CheckGroupsUsers_OwnerAlongWithSoleManagerEmptyGroup()
    {
        $user = UserFactory::make()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$user])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$group])->persist();
        $r2 = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $resources = $this->Permissions->findAcosOnlyAroCanAccess(PermissionsTable::RESOURCE_ACO, $user->id, ['checkGroupsUsers' => true])->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 2);
        $this->assertTrue(in_array($r1->id, $resources));
        $this->assertTrue(in_array($r2->id, $resources));
    }

    public function testFindOnlyUserCanAccess_CheckGroupsUsers_SoleOwnerSharedWithSoleManagerEmptyGroup()
    {
        $user = UserFactory::make()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$user])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$group, $user])->persist();
        $resources = $this->Permissions->findAcosOnlyAroCanAccess(PermissionsTable::RESOURCE_ACO, $user->id, ['checkGroupsUsers' => true])->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertTrue(in_array($r1->id, $resources));
    }

    public function testFindOnlyUserCanAccess_CheckGroupsUsers_IndirectlyOwnerSharedWithSoleManagerEmptyGroup()
    {
        $user = UserFactory::make()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$user])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$group, $user])->persist();
        // CONTEXTUAL TEST CHANGES Remove the direct permission of nancy
        $this->Permissions->deleteAll(['aro_foreign_key IN' => $user->id, 'aco_foreign_key' => $r1->id]);

        $resources = $this->Permissions->findAcosOnlyAroCanAccess(PermissionsTable::RESOURCE_ACO, $user->id, ['checkGroupsUsers' => true])->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertTrue(in_array($r1->id, $resources));
    }
}
