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
 * @since         3.7.0
 */

namespace App\Test\TestCase\Model\Table\Traits\PermissionsFinder;

use App\Model\Table\PermissionsTable;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;

/**
 * @see \App\Model\Traits\Permissions\PermissionsFindersTrait::findAcosAccessesDiffBetweenGroupAndUser()
 */
class FindAcosAccessesDiffBetweenGroupAndUserTest extends AppTestCase
{
    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private $permissionsTable;

    public function setUp(): void
    {
        parent::setUp();
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
    }

    /**
     * No diff if no resources are shared with the group or the user.
     */
    public function testFindAcosAccessesDiffBetweenGroupAndUser_NoDiff_NoResources()
    {
        $u1 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->persist();

        $query = $this->permissionsTable->findAcosAccessesDiffBetweenGroupAndUser(
            PermissionsTable::RESOURCE_ACO,
            $g1->id,
            $u1->id
        );
        $result = $query->count();

        $this->assertEquals(0, ResourceFactory::count());
        $this->assertEquals(0, PermissionFactory::count());
        $this->assertEquals(0, $result);
    }

    /**
     * A single diff when a resource is shared with the group but not with the user.
     */
    public function testFindAcosAccessesDiffBetweenGroupAndUser_SingleDiff_NoResourceSharedWithUser()
    {
        $u1 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$g1])->persist();

        $query = $this->permissionsTable->findAcosAccessesDiffBetweenGroupAndUser(
            PermissionsTable::RESOURCE_ACO,
            $g1->id,
            $u1->id
        );
        $diff = $query->all()->extract('aco_foreign_key')->toArray();

        $this->assertEquals(1, ResourceFactory::count());
        $this->assertEquals(1, PermissionFactory::count());
        $this->assertEquals(1, count($diff));
        $this->assertEquals($r1->id, $diff[0]);
    }

    /**
     * No diff when a resource is shared with the user but not with the group.
     */
    public function testFindAcosAccessesDiffBetweenGroupAndUser_NoDiff_NoResourceSharedWithGroup()
    {
        $u1 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$u1])->persist();

        $query = $this->permissionsTable->findAcosAccessesDiffBetweenGroupAndUser(
            PermissionsTable::RESOURCE_ACO,
            $g1->id,
            $u1->id
        );
        $diff = $query->all()->extract('aco_foreign_key')->toArray();

        $this->assertEquals(1, ResourceFactory::count());
        $this->assertEquals(1, PermissionFactory::count());
        $this->assertEquals(0, count($diff));
    }

    /**
     * No diff when a resource is shared with the group and also with the user.
     */
    public function testFindAcosAccessesDiffBetweenGroupAndUser_NoDiff_SingleResourceSharedWithBoth()
    {
        $u1 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$g1, $u1])->persist();

        $query = $this->permissionsTable->findAcosAccessesDiffBetweenGroupAndUser(
            PermissionsTable::RESOURCE_ACO,
            $g1->id,
            $u1->id
        );
        $diff = $query->all()->extract('aco_foreign_key')->toArray();

        $this->assertEquals(1, ResourceFactory::count());
        $this->assertEquals(2, PermissionFactory::count());
        $this->assertEquals(0, count($diff));
    }

    /**
     * No diff when a resource is shared with the group and the user is member of the group.
     */
    public function testFindAcosAccessesDiffBetweenGroupAndUser_NoDiff_SingleResourceSharedWithGroup_UserMemberOfGroup()
    {
        $u1 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$g1])->persist();

        $query = $this->permissionsTable->findAcosAccessesDiffBetweenGroupAndUser(
            PermissionsTable::RESOURCE_ACO,
            $g1->id,
            $u1->id
        );
        $diff = $query->all()->extract('aco_foreign_key')->toArray();

        $this->assertEquals(1, ResourceFactory::count());
        $this->assertEquals(1, PermissionFactory::count());
        $this->assertEquals(0, count($diff));
    }

    /**
     * A single diff 2 resources are shared with the group and only one is shared with the user
     */
    public function testFindAcosAccessesDiffBetweenGroupAndUser_SingleDiff_2ResourcesSharedWithGroupOnlyOneWithUser()
    {
        $u1 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$g1])->persist();
        $r2 = ResourceFactory::make()->withPermissionsFor([$g1, $u1])->persist();

        $query = $this->permissionsTable->findAcosAccessesDiffBetweenGroupAndUser(
            PermissionsTable::RESOURCE_ACO,
            $g1->id,
            $u1->id
        );
        $diff = $query->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(2, ResourceFactory::count());
        $this->assertEquals(3, PermissionFactory::count());
        $this->assertEquals(1, count($diff));
        $this->assertEquals($r1->id, $diff[0]);
    }

    /**
     * A single diff 2 resources are shared with the group and only one is shared with the user
     */
    public function testFindAcosAccessesDiffBetweenGroupAndUser_SingleDiff_3ResourcesSharedWithGroupOnlyTwoWithUser()
    {
        $u1 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$g1])->persist();
        $r2 = ResourceFactory::make()->withPermissionsFor([$g1, $u1])->persist();
        $r3 = ResourceFactory::make()->withPermissionsFor([$g1, $u1])->persist();

        $query = $this->permissionsTable->findAcosAccessesDiffBetweenGroupAndUser(
            PermissionsTable::RESOURCE_ACO,
            $g1->id,
            $u1->id
        );
        $diff = $query->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(3, ResourceFactory::count());
        $this->assertEquals(5, PermissionFactory::count());
        $this->assertEquals(1, count($diff));
        $this->assertEquals($r1->id, $diff[0]);
    }

    /**
     * Multiple diffs 2 resources are shared with the group and none shared with the user
     */
    public function testFindAcosAccessesDiffBetweenGroupAndUser_MultipleDiff_2ResourcesSharedWithGroupNoneWithUser()
    {
        $u1 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$g1])->persist();
        $r2 = ResourceFactory::make()->withPermissionsFor([$g1])->persist();

        $query = $this->permissionsTable->findAcosAccessesDiffBetweenGroupAndUser(
            PermissionsTable::RESOURCE_ACO,
            $g1->id,
            $u1->id
        );
        $diff = $query->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(2, ResourceFactory::count());
        $this->assertEquals(2, PermissionFactory::count());
        $this->assertEquals(2, count($diff));
        $this->assertContains($r1->id, $diff);
        $this->assertContains($r2->id, $diff);
    }

    /**
     * Multiple diffs 4 resources are shared with the group and only one shared with the user
     */
    public function testFindAcosAccessesDiffBetweenGroupAndUser_MultipleDiff_3ResourcesSharedWithGroupAndOnlyOneWithUser()
    {
        $u1 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$g1])->persist();
        $r2 = ResourceFactory::make()->withPermissionsFor([$g1])->persist();
        $r3 = ResourceFactory::make()->withPermissionsFor([$g1, $u1])->persist();

        $query = $this->permissionsTable->findAcosAccessesDiffBetweenGroupAndUser(
            PermissionsTable::RESOURCE_ACO,
            $g1->id,
            $u1->id
        );
        $diff = $query->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(3, ResourceFactory::count());
        $this->assertEquals(4, PermissionFactory::count());
        $this->assertEquals(2, count($diff));
        $this->assertContains($r1->id, $diff);
        $this->assertContains($r2->id, $diff);
    }
}
