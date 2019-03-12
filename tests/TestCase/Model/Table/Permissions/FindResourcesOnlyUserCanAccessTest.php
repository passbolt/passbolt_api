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

use App\Model\Table\PermissionsTable;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use PassboltTestData\Lib\PermissionMatrix;

class FindResourcesOnlyUserCanAccessTest extends AppTestCase
{
    public $fixtures = ['app.Alt0/Permissions', 'app.Alt0/GroupsUsers', 'app.Base/Resources', 'app.Base/Users', 'app.Base/Groups'];

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

    public function testFindOnlyUserCanAccess_SoleOwnerNotShared()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $resources = $this->Permissions->findResourcesOnlyUserCanAccess($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], UuidFactory::uuid('resource.id.apache'));
    }

    public function testFindOnlyUserCanAccess_OwnerAlongWithAnotherUser()
    {
        $userId = UuidFactory::uuid('user.id.orna');
        $resources = $this->Permissions->findResourcesOnlyUserCanAccess($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 0);
    }

    public function testFindOnlyUserCanAccess_SharedWithMe()
    {
        $userId = UuidFactory::uuid('user.id.lynne');
        $resources = $this->Permissions->findResourcesOnlyUserCanAccess($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 0);
    }

    public function testFindOnlyUserCanAccess_NoOwnerNoResourcesSharedNoGroupsMember()
    {
        $userId = UuidFactory::uuid('user.id.irene');
        $resources = $this->Permissions->findResourcesOnlyUserCanAccess($userId)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 0);
    }

    public function testFindOnlyUserCanAccess_NoOwner()
    {
        $userId = UuidFactory::uuid('user.id.betty');
        $resources = $this->Permissions->findResourcesOnlyUserCanAccess($userId, true)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 0);
    }

    public function testFindOnlyUserCanAccess_CheckGroupsUsers_OwnerAlongWithSoleManagerEmptyGroup()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $resources = $this->Permissions->findResourcesOnlyUserCanAccess($userId, true)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 2);
        $this->assertTrue(in_array(UuidFactory::uuid('resource.id.apache'), $resources));
        $this->assertTrue(in_array(UuidFactory::uuid('resource.id.composer'), $resources));
    }

    public function testFindOnlyUserCanAccess_CheckGroupsUsers_SoleOwnerSharedWithSoleManagerEmptyGroup()
    {
        $userId = UuidFactory::uuid('user.id.nancy');
        $resources = $this->Permissions->findResourcesOnlyUserCanAccess($userId, true)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertTrue(in_array(UuidFactory::uuid('resource.id.openpgpjs'), $resources));
    }

    public function testFindOnlyUserCanAccess_CheckGroupsUsers_IndirectlyOwnerSharedWithSoleManagerEmptyGroup()
    {
        $userNId = UuidFactory::uuid('user.id.nancy');
        $resourceOId = UuidFactory::uuid('resource.id.openpgpjs');
        // CONTEXTUAL TEST CHANGES Remove the direct permission of nancy
        $this->Permissions->deleteAll(['aro_foreign_key IN' => $userNId, 'aco_foreign_key' => $resourceOId]);

        $userId = UuidFactory::uuid('user.id.nancy');
        $resources = $this->Permissions->findResourcesOnlyUserCanAccess($userId, true)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertTrue(in_array(UuidFactory::uuid('resource.id.openpgpjs'), $resources));
    }
}
