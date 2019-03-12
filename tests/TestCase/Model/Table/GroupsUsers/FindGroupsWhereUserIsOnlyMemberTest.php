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

class FindGroupsWhereUserIsOnlyMemberTest extends AppTestCase
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

    public function testFindGroupsWhereUserIsNotInAnyGroupsTest()
    {
        // Ada is not manager of any group
        $userId = UuidFactory::uuid('user.id.ada');
        $result = $this->GroupsUsers->findGroupsWhereUserOnlyMember($userId)->extract('group_id')->toArray();
        $this->assertEmpty($result);
    }

    public function testFindGroupsWhereUserIsNotAloneInAnyGroupsTest()
    {
        // Ping is many groups but not alone
        $userId = UuidFactory::uuid('user.id.ping');
        $result = $this->GroupsUsers->findGroupsWhereUserOnlyMember($userId)->extract('group_id')->toArray();
        $this->assertEmpty($result);
    }

    public function testFindGroupsWhereUserIsAloneInManyGroupsTest()
    {
        // Admin is manager of a bunch of empty groups
        $userId = UuidFactory::uuid('user.id.admin');
        $result = $this->GroupsUsers->findGroupsWhereUserOnlyMember($userId)->extract('group_id')->toArray();
        $this->assertNotEmpty($result);
        $this->assertGreaterThan(5, count($result));
    }
}
