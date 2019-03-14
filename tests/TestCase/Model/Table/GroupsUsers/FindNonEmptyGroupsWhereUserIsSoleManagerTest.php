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

namespace App\Test\TestCase\Model\Table\GroupsUsers;

use App\Model\Table\PermissionsTable;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use PassboltTestData\Lib\PermissionMatrix;

class FindNonEmptyGroupsWhereUserIsSoleManagerTest extends AppTestCase
{
    public $fixtures = ['app.Base/Groups', 'app.Base/Users', 'app.Base/GroupsUsers'];

    public function setUp()
    {
        parent::setUp();
        $this->GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
    }

    public function tearDown()
    {
        unset($this->GroupsUsers);
        parent::tearDown();
    }

    public function testNotAManager()
    {
        // Ada is not manager of any group
        $userId = UuidFactory::uuid('user.id.ada');
        $result = $this->GroupsUsers->findNonEmptyGroupsWhereUserIsSoleManager($userId)->extract('group_id')->toArray();
        $this->assertEmpty($result);
    }

    public function testOtherManagersPresent()
    {
        // Ursula is manager of it_support group where ping is also manager
        $userId = UuidFactory::uuid('user.id.ursula');
        $result = $this->GroupsUsers->findNonEmptyGroupsWhereUserIsSoleManager($userId)->extract('group_id')->toArray();
        $this->assertEmpty($result);
    }

    public function testOnlyManagerButEmptyGroup()
    {
        // Admin is manager of a lot of empty groups and no active groups
        $userId = UuidFactory::uuid('user.id.admin');
        $result = $this->GroupsUsers->findNonEmptyGroupsWhereUserIsSoleManager($userId)->extract('group_id')->toArray();
        $this->assertEmpty($result);

        // Same for hedy but only 1 group
        $userId = UuidFactory::uuid('user.id.hedy');
        $result = $this->GroupsUsers->findNonEmptyGroupsWhereUserIsSoleManager($userId)->extract('group_id')->toArray();
        $this->assertEmpty($result);
    }

    public function testOnlyManagerInNonEmptyGroup()
    {
        // Frances is admin of the accounting group where grace is also a user
        $userId = UuidFactory::uuid('user.id.frances');
        $result = $this->GroupsUsers->findNonEmptyGroupsWhereUserIsSoleManager($userId)->extract('group_id')->toArray();
        $this->assertNotEmpty($result);
        $this->assertEquals($result[0], UuidFactory::uuid('group.id.accounting'));

        // Same for jean with freelancer group but with more members
        $userId = UuidFactory::uuid('user.id.jean');
        $result = $this->GroupsUsers->findNonEmptyGroupsWhereUserIsSoleManager($userId)->extract('group_id')->toArray();
        $this->assertNotEmpty($result);
        $this->assertEquals($result[0], UuidFactory::uuid('group.id.freelancer'));
    }
}
