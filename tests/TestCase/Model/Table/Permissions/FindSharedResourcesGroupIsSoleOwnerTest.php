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

class FindSharedResourcesGroupIsSoleOwnerTest extends AppTestCase
{
    public $fixtures = ['app.Alt0/Permissions', 'app.Alt0/GroupsUsers'];

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
    }

    public function testFindSharedResourceGroupIsSoleOwner_OwnsNothing_DelGroupCase0()
    {
        $groupId = UuidFactory::uuid('group.id.procurement');
        $resources = $this->Permissions->findSharedResourcesGroupIsSoleOwner($groupId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindSharedResourceGroupIsSoleOwner_SharedResourceWithMe_DelGroupCase1()
    {
        $groupId = UuidFactory::uuid('group.id.quality_assurance');
        $resources = $this->Permissions->findSharedResourcesGroupIsSoleOwner($groupId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testFindSharedResourceGroupIsSoleOwner_SoleOwnerNotSharedResource_DelGroupCase2()
    {
        $groupId = UuidFactory::uuid('group.id.resource_planning');
        $resources = $this->Permissions->findSharedResourcesGroupIsSoleOwner($groupId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }

    public function testGroupsSoftDelete_SoleOwnerSharedResource_DelGroupCase3()
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

        $resources = $this->Permissions->findSharedResourcesGroupIsSoleOwner($groupId)->extract('aco_foreign_key')->toArray();
        $this->assertNotEmpty($resources);
        $this->assertCount(1, $resources);
        $this->assertTrue(in_array(UuidFactory::uuid('resource.id.nodejs'), $resources));
    }

    public function testFindSharedResourceGroupIsSoleOwner_OwnerAlongWithAnotherUser_DelGroupCase4()
    {
        $groupId = UuidFactory::uuid('group.id.management');
        $resources = $this->Permissions->findSharedResourcesGroupIsSoleOwner($groupId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }
}
