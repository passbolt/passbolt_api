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

namespace App\Test\TestCase\Model\Table\Permissions;

use App\Model\Entity\Permission;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class FindSharedResourcesUserIsSoleOwnerTest extends AppTestCase
{
    public $fixtures = ['app.Alt0/Permissions', 'app.Alt0/GroupsUsers', 'app.Base/Resources'];

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
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
    }

    public function testFindShardResourceUserIsSoleOwner_OwnsNothing_DelUserCase0()
    {
        $userId = UuidFactory::uuid('user.id.irene');
        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_SoleOwnerNotSharedResource_DelUserCase1()
    {
        $userId = UuidFactory::uuid('user.id.jean');
        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_SoleOwnerSharedResourceWithUser_DelUserCase2()
    {
        $userId = UuidFactory::uuid('user.id.kathleen');
        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], UuidFactory::uuid('resource.id.mocha'));
    }

    public function testFindShardResourceUserIsSoleOwner_SharedResourceWithMe_DelUserCase3()
    {
        $userId = UuidFactory::uuid('user.id.lynne');
        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_SoleOwnerSharedResourceWithGroup_DelUserCase4()
    {
        $userId = UuidFactory::uuid('user.id.marlyn');
        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], UuidFactory::uuid('resource.id.nodejs'));
    }

    public function testFindShardResourceUserIsSoleOwner_SoleOwnerSharedResourceWithSoleManagerEmptyGroup_DelUserCase5()
    {
        $userId = UuidFactory::uuid('user.id.nancy');
        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], UuidFactory::uuid('resource.id.openpgpjs'));
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_SoleOwnerSharedResourceWithSoleManagerEmptyGroup_DelUserCase5()
    {
        $userId = UuidFactory::uuid('user.id.nancy');
        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId, true)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_OwnerSharedResourceAlongWithSoleManagerEmptyGroup_DelUserCase6()
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

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_OwnerSharedResourceAlongWithSoleManagerEmptyGroup_DelUserCase6()
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

    public function testFindShardResourceUserIsSoleOwner_indirectlyOwnerSharedResourceWithSoleManagerEmptyGroup_DelUserCase7()
    {
        $userId = UuidFactory::uuid('user.id.nancy');
        $groupLId = UuidFactory::uuid('group.id.leadership_team');
        $resourceOId = UuidFactory::uuid('resource.id.openpgpjs');

        // CONTEXTUAL TEST CHANGES Remove the direct permission of nancy
        $this->Permissions->deleteAll(['aro_foreign_key IN' => $userId, 'aco_foreign_key' => $resourceOId]);
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $groupLId,
            'aco_foreign_key' => $resourceOId
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_indirectlyOwnerSharedResourceWithSoleManagerEmptyGroup_DelUserCase7()
    {
        $userId = UuidFactory::uuid('user.id.nancy');
        $groupLId = UuidFactory::uuid('group.id.leadership_team');
        $resourceOId = UuidFactory::uuid('resource.id.openpgpjs');

        // CONTEXTUAL TEST CHANGES Remove the direct permission of nancy
        $this->Permissions->deleteAll(['aro_foreign_key IN' => $userId, 'aco_foreign_key' => $resourceOId]);
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => $groupLId,
            'aco_foreign_key' => $resourceOId
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId, true)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_OwnerAlongWithSoleManagerOfNotEmptyGroup_DelUserCase10()
    {
        $userId = UuidFactory::uuid('user.id.orna');

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_OwnerAlongWithSoleManagerOfNotEmptyGroup_DelUserCase10()
    {
        $userId = UuidFactory::uuid('user.id.orna');

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId, true)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_indireclyOwnerWithSoleManagerOfNotEmptyGroup_DelUserCase11()
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

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_indireclyOwnerWithSoleManagerOfNotEmptyGroup_DelUserCase11()
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

    public function testFindShardResourceUserIsSoleOwner_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroup_DelUserCase12()
    {
        $userId = UuidFactory::uuid('user.id.ursula');

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroup_DelUserCase12()
    {
        $userId = UuidFactory::uuid('user.id.ursula');

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId, true)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertTrue(in_array(UuidFactory::uuid('resource.id.phpunit'), $resources));
    }

    public function testFindShardResourceUserIsSoleOwner_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroups_DelUserCase13()
    {
        $userId = UuidFactory::uuid('user.id.wang');

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_indirectlyOwnerSharedResourceWithSoleManagerOfEmptyGroups_DelUserCase13()
    {
        $userId = UuidFactory::uuid('user.id.wang');

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId, true)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_indirectlyOwnerSharedResourceWithSoleManagerOfNonEmptyGroup_DelUserCase14()
    {
        $userId = UuidFactory::uuid('user.id.yvonne');

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_indirectlyOwnerSharedResourceWithSoleManagerOfNonEmptyGroup_DelUserCase14()
    {
        $userId = UuidFactory::uuid('user.id.yvonne');

        $resources = $this->Permissions->findSharedResourcesUserIsSoleOwner($userId, true)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindShardResourceUserIsSoleOwner_SoleOwnerSharedResourceWithNotEmptyGroup_DelUserCase15()
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

    public function testFindShardResourceUserIsSoleOwner_CheckGroupsUsers_SoleOwnerSharedResourceWithNotEmptyGroup_DelUserCase15()
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
