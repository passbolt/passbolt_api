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

namespace App\Test\TestCase\Controller\Users;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;

class UsersIndexControllerGroupTest extends AppIntegrationTestCase
{
    public function testUsersIndexController_Success(): void
    {
        RoleFactory::make()->guest()->persist();
        [$userA, $userB, $userC] = UserFactory::make(3)->user()->withValidGpgKey()->persist();
        $inactiveUser = UserFactory::make()->user()->inactive()->persist();
        GroupFactory::make()->withGroupsManagersFor([$userA, $userB, $userC])->persist();

        $this->logInAs($userA);
        $this->getJson('/users.json');
        $this->assertSuccess();
        $this->assertCount(3, $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody[0]);

        // gpgkey
        $this->assertObjectHasAttribute('gpgkey', $this->_responseJsonBody[0]);
        $this->assertGpgkeyAttributes($this->_responseJsonBody[0]->gpgkey);
        // profile
        $this->assertObjectHasAttribute('profile', $this->_responseJsonBody[0]);
        $this->assertProfileAttributes($this->_responseJsonBody[0]->profile);
        // avatar
        $this->assertObjectHasAttribute('avatar', $this->_responseJsonBody[0]->profile);
        $this->assertObjectHasAttributes(['url'], $this->_responseJsonBody[0]->profile->avatar);
        // role
        $this->assertObjectHasAttribute('role', $this->_responseJsonBody[0]);
        $this->assertRoleAttributes($this->_responseJsonBody[0]->role);
        // groups users
        $this->assertObjectHasAttribute('groups_users', $this->_responseJsonBody[0]);

        // Should not contain inactive users.
        $usersIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $this->assertNotContains($inactiveUser->id, $usersIds);
    }

    public function testUsersIndexController_Succes_FilterByGroups(): void
    {
        RoleFactory::make()->guest()->persist();
        $this->logInAsUser();
        [$userA, $userB, $userC] = UserFactory::make(3)->user()->persist();
        $group = GroupFactory::make()->withGroupsUsersFor([$userA, $userB, $userC])->persist();
        $this->getJson('/users.json?filter[has-groups]=' . $group->id);
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 3);
    }

    public function testUsersIndexController_Success_FilterByMultipleGroups(): void
    {
        RoleFactory::make()->guest()->persist();
        $this->logInAsUser();
        [$userA, $userB, $userC, $userD] = UserFactory::make(4)->user()->withValidGpgKey()->persist();
        $groupA = GroupFactory::make()->withGroupsUsersFor([$userA, $userB, $userC, $userD])->persist();
        $groupB = GroupFactory::make()->withGroupsUsersFor([$userA, $userB, $userC, $userD])->persist();
        $this->getJson('/users.json?filter[has-groups]=' . $groupA->id . ',' . $groupB->id);
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 4);

        $this->getJson('/users.json?filter[has-groups][]=' . $groupA->id . '&filter[has-groups][]=' . $groupB->id);
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 4);
    }

    public function testUsersIndexController_Error_FilterByInvalidGroups(): void
    {
        RoleFactory::make()->guest()->persist();
        $this->logInAsUser();
        $group = GroupFactory::make()->persist();

        // Invalid format trigger BadRequest
        $this->getJson('/users.json?filter[has-groups]');
        $this->assertError(400);
        $this->getJson('/users.json?filter[has-groups]=');
        $this->assertError(400);
        $this->getJson('/users.json?filter[has-groups]=nope');
        $this->assertError(400);
        $this->getJson('/users.json?filter[has-groups]=' . $group->id . ',nope');
        $this->assertError(400);

        // non existing group triggers empty results set
        $this->getJson('/users.json?filter[has-groups]=' . UuidFactory::uuid());
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 0);
    }
}
