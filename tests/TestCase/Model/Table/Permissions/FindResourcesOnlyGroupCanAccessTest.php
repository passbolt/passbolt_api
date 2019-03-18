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

class FindResourcesOnlyGroupCanAccessTest extends AppTestCase
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

    public function testFindOnlyGroupCanAccessSuccess()
    {
        // Creative is sole owner of apache
        $groupId = UuidFactory::uuid('group.id.creative');
        $resources = $this->Permissions->findResourcesOnlyGroupCanAccess($groupId)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], UuidFactory::uuid('resource.id.composer'));

        // Developer is sole owner of debian
        $groupId = UuidFactory::uuid('group.id.developer');
        $resources = $this->Permissions->findResourcesOnlyGroupCanAccess($groupId)->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], UuidFactory::uuid('resource.id.debian'));
    }

    public function testFindOnlyGroupCanAccessNoResult()
    {
        // freelancer not owner of anything
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $resources = $this->Permissions->findResourcesOnlyGroupCanAccess($groupId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);

        // ergonom is owner of of some resources but never alone
        $groupId = UuidFactory::uuid('group.id.ergonom');
        $resources = $this->Permissions->findResourcesOnlyGroupCanAccess($groupId)->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }
}
