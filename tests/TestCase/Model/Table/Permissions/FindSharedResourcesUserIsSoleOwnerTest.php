<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace App\Test\TestCase\Model\Table\Permissions;

use App\Model\Entity\Permission;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class FindSharedResourcesUserIsSoleOwnerTest extends AppTestCase
{
    public $fixtures = ['app.Alt0/permissions', 'app.Alt0/groups_users', 'app.Base/resources'];

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
    public function setUp()
    {
        parent::setUp();
        $this->Permissions = TableRegistry::get('Permissions');
        $this->Resources = TableRegistry::get('Resources');
    }

    public function testFindShardResourceUserIsSoleOwner_OwnsNothing_Case0()
    {
        $userId = UuidFactory::uuid('user.id.irene');
        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_SoleOwnerNotSharedResource_Case1()
    {
        $userId = UuidFactory::uuid('user.id.jean');
        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_SoleOwnerSharedResourceWithUser_Case2()
    {
        $userId = UuidFactory::uuid('user.id.kathleen');
        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], UuidFactory::uuid('resource.id.mocha'));
    }

    public function testFindShardResourceUserIsSoleOwner_SharedResourceWithMe_Case3()
    {
        $userId = UuidFactory::uuid('user.id.lynne');
        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_SoleOwnerSharedResourceWithGroup_Case4()
    {
        $userId = UuidFactory::uuid('user.id.marlyn');
        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], UuidFactory::uuid('resource.id.nodejs'));
    }

    public function testFindShardResourceUserIsSoleOwner_SoleOwnerSharedResourceWithEmptyGroup_Case5()
    {
        $userId = UuidFactory::uuid('user.id.nancy');
        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], UuidFactory::uuid('resource.id.openpgpjs'));
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_SoleOwnerSharedResourceWithEmptyGroup_Case5()
    {
        $userId = UuidFactory::uuid('user.id.nancy');
        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId, true)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_OwnerSharedResourceAlongWithSoleManagerEmptyGroup_Case6()
    {
        $userId = UuidFactory::uuid('user.id.nancy');
        $groupLId = UuidFactory::uuid('group.id.leadership_team');
        $resourceOId = UuidFactory::uuid('resource.id.openpgpjs');

        // CONTEXTUAL TEST CHANGES Make the group also owner of the resource
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $groupLId,
            'aco_foreign_key' => $resourceOId
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_OwnerSharedResourceAlongWithSoleManagerEmptyGroup_Case6()
    {
        $userId = UuidFactory::uuid('user.id.nancy');
        $groupLId = UuidFactory::uuid('group.id.leadership_team');
        $resourceOId = UuidFactory::uuid('resource.id.openpgpjs');

        // CONTEXTUAL TEST CHANGES Make the group also owner of the resource
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $groupLId,
            'aco_foreign_key' => $resourceOId
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId, true)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_indirectlyOwnerSharedResourceWithSoleManagerEmptyGroup_Case7()
    {
        $userId = UuidFactory::uuid('user.id.nancy');
        $resourceOId = UuidFactory::uuid('resource.id.openpgpjs');

        // CONTEXTUAL TEST CHANGES Remove the direct permission of nancy
        $this->Permissions->deleteAll(['aro_foreign_key IN' => $userId, 'aco_foreign_key' => $resourceOId]);

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_indirectlyOwnerSharedResourceWithSoleManagerEmptyGroup_Case7()
    {
        $userId = UuidFactory::uuid('user.id.nancy');
        $resourceOId = UuidFactory::uuid('resource.id.openpgpjs');

        // CONTEXTUAL TEST CHANGES Remove the direct permission of nancy
        $this->Permissions->deleteAll(['aro_foreign_key IN' => $userId, 'aco_foreign_key' => $resourceOId]);

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId, true)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_OwnerAlongWithSoleManagerOfNotEmptyGroup_Case10()
    {
        $userId = UuidFactory::uuid('user.id.orna');

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_OwnerAlongWithSoleManagerOfNotEmptyGroup_Case10()
    {
        $userId = UuidFactory::uuid('user.id.orna');

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId, true)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_indireclyOwnerWithSoleManagerOfNotEmptyGroup_Case11()
    {
        $userId = UuidFactory::uuid('user.id.orna');

        // CONTEXTUAL TEST CHANGES Remove The permissions of Orna
        $this->Permissions->deleteAll([
            'aro_foreign_key' => $userId,
            'aco_foreign_key' => UuidFactory::uuid('resource.id.linux')
        ]);

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_indireclyOwnerWithSoleManagerOfNotEmptyGroup_Case11()
    {
        $userId = UuidFactory::uuid('user.id.orna');

        // CONTEXTUAL TEST CHANGES Remove The permissions of Orna
        $this->Permissions->deleteAll([
            'aro_foreign_key' => $userId,
            'aco_foreign_key' => UuidFactory::uuid('resource.id.linux')
        ]);

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroup_Case12()
    {
        $userId = UuidFactory::uuid('user.id.ursula');

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroup_Case12()
    {
        $userId = UuidFactory::uuid('user.id.ursula');

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId, true)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertTrue(in_array(UuidFactory::uuid('resource.id.phpunit'), $resources));
    }

    public function testFindShardResourceUserIsSoleOwner_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroups_Case13()
    {
        $userId = UuidFactory::uuid('user.id.wang');

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroups_Case13()
    {
        $userId = UuidFactory::uuid('user.id.wang');

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId, true)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_indirectlyOwnerSharedResourceWithSoleManagerOfNonEmptyGroup_Case14()
    {
        $userId = UuidFactory::uuid('user.id.yvonne');

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_indirectlyOwnerSharedResourceWithSoleManagerOfNonEmptyGroup_Case14()
    {
        $userId = UuidFactory::uuid('user.id.yvonne');

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId, true)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_SoleOwnerSharedResourceWithNotEmptyGroup_Case15()
    {
        $userId = UuidFactory::uuid('user.id.orna');
        $groupMId = UuidFactory::uuid('group.id.management');
        $resourceLId = UuidFactory::uuid('resource.id.linux');

        // CONTEXTUAL TEST CHANGES Change the permission of the group to READ
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $groupMId,
            'aco_foreign_key' => $resourceLId
        ])->first();
        $permission->type = Permission::READ;
        $this->Permissions->save($permission);

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertTrue(in_array($resourceLId, $resources));
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_SoleOwnerSharedResourceWithNotEmptyGroup_Case15()
    {
        $userId = UuidFactory::uuid('user.id.orna');
        $groupMId = UuidFactory::uuid('group.id.management');
        $resourceLId = UuidFactory::uuid('resource.id.linux');

        // CONTEXTUAL TEST CHANGES Change the permission of the group to READ
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $groupMId,
            'aco_foreign_key' => $resourceLId
        ])->first();
        $permission->type = Permission::READ;
        $this->Permissions->save($permission);

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId, true)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertTrue(in_array($resourceLId, $resources));
    }
}
