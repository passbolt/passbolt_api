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

use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class FindSharedResourcesSoleGroupManagerSoleOwnerTest extends AppTestCase
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

    public function testFindSharedResourcesSoleGroupManagerSoleOwner()
    {
        // Ada is sole member of group creative and creative owns framasoft shared with carol
        $userId = UuidFactory::uuid('user.id.ada');
        $resources = $this->Permissions->findSharedResourcesSoleGroupManagerIsSoleOwner($userId);
        $this->assertNotEmpty($resources);
        $this->assertEquals($resources[0], UuidFactory::uuid('resource.id.framasoft'));

        // Should be the only one, e.g.
        // - composer is owned by creative group by not shared with anybody
        // - inkscape is also owned by accounting
        $this->assertEquals(count($resources), 1);
    }

    public function testFindSharedResourcesNotGroupManager()
    {
        // Freelancer group does not anything
        $userId = UuidFactory::uuid('user.id.ada');
        $resources = $this->Permissions->findSharedResourcesSoleGroupManagerIsSoleOwner($userId);
        $this->assertNotEmpty($resources);
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], UuidFactory::uuid('resource.id.framasoft'));
    }
}
