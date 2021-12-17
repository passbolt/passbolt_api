<?php
declare(strict_types=1);

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

namespace App\Test\TestCase\Controller\Groups;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\GroupsUsersModelTrait;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;

class GroupsIndexControllerTest extends AppIntegrationTestCase
{
    use GroupsModelTrait;
    use GroupsUsersModelTrait;

    public $fixtures = ['app.Base/Users', 'app.Base/Profiles', 'app.Base/Roles', 'app.Base/Profiles', 'app.Base/Groups',
        'app.Base/GroupsUsers', 'app.Base/Permissions'];

    public function testGroupsIndexSuccess()
    {
        $this->authenticateAs('ada');
        $this->getJson('/groups.json?api-version=2');
        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));

        // Expected content.
        $this->assertGroupAttributes($this->_responseJsonBody[0]);
        // Not expected content.
        $this->assertObjectNotHasAttribute('modifier', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('users', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('groups_users', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('my_group_user', $this->_responseJsonBody[0]);
    }

    public function testGroupsIndexContainSuccess()
    {
        $this->authenticateAs('hedy');
        $urlParameter = 'contain[modifier]=1';
        $urlParameter .= '&contain[modifier.profile]=1';
        $urlParameter .= '&contain[user]=1';
        $urlParameter .= '&contain[group_user]=1';
        $urlParameter .= '&contain[my_group_user]=1';
        $this->getJson("/groups.json?$urlParameter&api-version=2");
        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));

        // Expected content.
        $this->assertGroupAttributes($this->_responseJsonBody[0]);
        $this->assertObjectHasAttribute('modifier', $this->_responseJsonBody[0]);
        $this->assertUserAttributes($this->_responseJsonBody[0]->modifier);
        $this->assertObjectHasAttribute('profile', $this->_responseJsonBody[0]->modifier);
        $this->assertProfileAttributes($this->_responseJsonBody[0]->modifier->profile);
        $this->assertObjectHasAttribute('users', $this->_responseJsonBody[0]);
        $this->assertUserAttributes($this->_responseJsonBody[0]->users[0]);
        $this->assertObjectHasAttribute('groups_users', $this->_responseJsonBody[0]);
        $this->assertGroupUserAttributes($this->_responseJsonBody[0]->groups_users[0]);

        // A group Hedy is not member
        $groupAId = UuidFactory::uuid('group.id.accounting');
        $groupA = array_reduce($this->_responseJsonBody, function ($carry, $item) use ($groupAId) {
            if ($item->id == $groupAId) {
                $carry = $item;
            }

            return $carry;
        }, null);
        $this->assertNull($groupA->my_group_user);

        // A group Hedy is member
        $groupBId = UuidFactory::uuid('group.id.board');
        $groupB = array_reduce($this->_responseJsonBody, function ($carry, $item) use ($groupBId) {
            if ($item->id == $groupBId) {
                $carry = $item;
            }

            return $carry;
        }, null);
        $this->assertObjectHasAttribute('my_group_user', $groupB);
        $this->assertGroupUserAttributes($groupB->my_group_user);
    }

    public function testGroupsIndexFilterHasUsersSuccess()
    {
        $this->authenticateAs('ada');
        $urlParameter = 'filter[has-users]=' . UuidFactory::uuid('user.id.irene');
        $this->getJson("/groups.json?$urlParameter&api-version=2");
        $this->assertSuccess();
        $this->assertCount(3, $this->_responseJsonBody);
        $groupsIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $expectedGroupsIds = [UuidFactory::uuid('group.id.creative'), UuidFactory::uuid('group.id.developer'), UuidFactory::uuid('group.id.ergonom')];
        $this->assertEquals(0, count(array_diff($expectedGroupsIds, $groupsIds)));
    }

    public function testGroupsIndexFilterHasManagersSuccess()
    {
        $this->authenticateAs('ada');
        $urlParameter = 'filter[has-managers]=' . UuidFactory::uuid('user.id.ping');
        $this->getJson("/groups.json?$urlParameter&api-version=2");
        $this->assertSuccess();
        $this->assertCount(2, $this->_responseJsonBody);
        $groupsIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $expectedGroupsIds = [UuidFactory::uuid('group.id.human_resource'), UuidFactory::uuid('group.id.it_support')];
        $this->assertEquals(0, count(array_diff($expectedGroupsIds, $groupsIds)));
    }

    public function testGroupsIndexErrorNotAuthenticated()
    {
        $this->getJson('/groups.json');
        $this->assertAuthenticationError();
    }
}
