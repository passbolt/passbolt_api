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

use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\GroupsUsersModelTrait;
use Cake\Utility\Hash;

class GroupsIndexControllerTest extends AppIntegrationTestCase
{
    use GroupsModelTrait;
    use GroupsUsersModelTrait;

    public function testGroupsIndexSuccess(): void
    {
        GroupFactory::make(5)->persist();
        $this->logInAsUser();
        $this->getJson('/groups.json');
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

    public function testGroupsIndexContainSuccess(): void
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        $groupAId = GroupFactory::make()->withGroupsUsersFor([$userB])->persist()->id;
        $groupBId = GroupFactory::make(['modified_by' => $userA->id])->withGroupsManagersFor([$userA])->persist()->id;
        $this->logInAs($userA);
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
        $groupA = array_reduce($this->_responseJsonBody, function ($carry, $item) use ($groupAId) {
            if ($item->id == $groupAId) {
                $carry = $item;
            }

            return $carry;
        }, null);
        $this->assertNull($groupA->my_group_user);

        // A group Hedy is member
        $groupB = array_reduce($this->_responseJsonBody, function ($carry, $item) use ($groupBId) {
            if ($item->id == $groupBId) {
                $carry = $item;
            }

            return $carry;
        }, null);
        $this->assertObjectHasAttribute('my_group_user', $groupB);
        $this->assertGroupUserAttributes($groupB->my_group_user);
    }

    public function testGroupsIndexFilterHasUsersSuccess(): void
    {
        $this->logInAsUser();
        $user = UserFactory::make()->persist();
        [$groupA, $groupB, $groupC] = GroupFactory::make(3)->withGroupsUsersFor([$user])->persist();
        $urlParameter = 'filter[has-users]=' . $user->id;
        $this->getJson("/groups.json?$urlParameter&api-version=2");
        $this->assertSuccess();
        $this->assertCount(3, $this->_responseJsonBody);
        $groupsIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $expectedGroupsIds = [$groupA->id, $groupB->id, $groupC->id];
        $this->assertEquals(0, count(array_diff($expectedGroupsIds, $groupsIds)));
    }

    public function testGroupsIndexFilterHasUsers_UpperCase(): void
    {
        $this->logInAsUser();
        $user = UserFactory::make()->persist();
        GroupFactory::make(3)->withGroupsUsersFor([$user])->persist();
        $urlParameter = 'filter[has-users]=' . strtoupper($user->id);
        $this->getJson("/groups.json?$urlParameter&api-version=2");
        $this->assertSuccess();
        $this->assertCount(3, $this->_responseJsonBody);
    }

    public function testGroupsIndexFilterHasManagersSuccess(): void
    {
        $this->logInAsUser();
        $user = UserFactory::make()->persist();
        [$groupA, $groupB] = GroupFactory::make(2)->withGroupsManagersFor([$user])->persist();
        $urlParameter = 'filter[has-managers]=' . $user->id;
        $this->getJson("/groups.json?$urlParameter&api-version=2");
        $this->assertSuccess();
        $this->assertCount(2, $this->_responseJsonBody);
        $groupsIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $expectedGroupsIds = [$groupA->id, $groupB->id];
        $this->assertEquals(0, count(array_diff($expectedGroupsIds, $groupsIds)));
    }
}
