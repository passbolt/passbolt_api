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

use App\Model\Table\PermissionsTable;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use PassboltTestData\Lib\PermissionMatrix;

class FindResourcesOnlyUserCanAccessTest extends AppTestCase
{
    public $fixtures = ['app.Alt0/permissions', 'app.Alt0/groups_users'];

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
    }

    public function testFindOnlyUserCanAccessSuccess()
    {
        // Ada has sole owner of with apache
        $userId = UuidFactory::uuid('user.id.ada');
        $resources = $this->Permissions->findResourcesOnlyUserCanAccess($userId);
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], UuidFactory::uuid('resource.id.apache'));
    }

    public function testFindOnlyUserCanAccessWitGroupsSuccess()
    {
        // Ada is also indirectly sole owner of composer because she is alone in group creative
        $userId = UuidFactory::uuid('user.id.ada');
        $resources = $this->Permissions->findResourcesOnlyUserCanAccess($userId, true);
        $this->assertEquals(count($resources), 2);
        $this->assertEquals($resources[1], UuidFactory::uuid('resource.id.apache'));
        $this->assertEquals($resources[0], UuidFactory::uuid('resource.id.composer'));
    }

    public function testFindOnlyUserCanAccessNoDirectResources()
    {
        $userId = UuidFactory::uuid('user.id.betty');
        $resources = $this->Permissions->findResourcesOnlyUserCanAccess($userId);
        $this->assertEquals(count($resources), 0);
    }

    public function testFindOnlyUserCanAccessNoDirectResourcesWithGroups()
    {
        $userId = UuidFactory::uuid('user.id.betty');
        $resources = $this->Permissions->findResourcesOnlyUserCanAccess($userId, true);
        $this->assertEquals(count($resources), 0);
    }
}
