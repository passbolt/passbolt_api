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

class FindSharedResourcesGroupIsSoleOwnerTest extends AppTestCase
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

    public function testFindSharedResourceGroupDoesNotOwnAnything()
    {
        // Freelancer group does not anything
        $groupId = UuidFactory::uuid('user.id.freelancer');
        $resources = $this->Permissions->findSharedResourcesGroupIsSoleOwner($groupId);
        $this->assertEmpty($resources);
    }

    public function testFindSharedResourceGroupIsSoleOwner()
    {
        // Ada is sole owner of april that is shared with betty
        $groupId = UuidFactory::uuid('group.id.accounting');
        $resources = $this->Permissions->findSharedResourcesGroupIsSoleOwner($groupId);
        $this->assertNotEmpty($resources);
        $this->assertEquals($resources[0], UuidFactory::uuid('resource.id.kde'));
        $this->assertEquals($resources[1], UuidFactory::uuid('resource.id.enlightenment'));

        // Only kde and enlightenment are in this case, all other resources have some other owner
        // or are not shared with anybody
        $this->assertEquals(count($resources), 2);
    }
}
