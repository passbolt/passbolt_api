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

namespace App\Test\TestCase\Controller\Users;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsUsersModelTrait;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;

class UsersIndexControllerTest extends AppIntegrationTestCase
{
    use GroupsUsersModelTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles',
        'app.Base/GroupsUsers', 'app.Base/Avatars'
    ];

    public function testUsersIndexGetSuccess()
    {
        $this->authenticateAs('ada');
        $this->getJson('/users.json?api-version=2');
        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));
        $this->assertUserAttributes($this->_responseJsonBody[0]);

        // gpgkey
        $this->assertObjectHasAttribute('gpgkey', $this->_responseJsonBody[0]);
        $this->assertGpgkeyAttributes($this->_responseJsonBody[0]->gpgkey);
        // profile
        $this->assertObjectHasAttribute('profile', $this->_responseJsonBody[0]);
        $this->assertProfileAttributes($this->_responseJsonBody[0]->profile);
        // role
        $this->assertObjectHasAttribute('role', $this->_responseJsonBody[0]);
        $this->assertRoleAttributes($this->_responseJsonBody[0]->role);
        // groups users
        $this->assertObjectHasAttribute('groups_users', $this->_responseJsonBody[0]);

        // Should not contain inactive users.
        $usersIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $notActiveUserId = UuidFactory::uuid('user.id.ruth');
        $this->assertNotContains($notActiveUserId, $usersIds);
    }

    // As admin the request should also contain not active users.
    public function testUsersIndexGetAsAdminSuccess()
    {
        $this->authenticateAs('admin');
        $this->getJson('/users.json?api-version=2');
        $usersIds = Hash::extract($this->_responseJsonBody, '{n}.id');

        // Should not contain inactive users.
        $notActiveUserId = UuidFactory::uuid('user.id.ruth');
        $this->assertContains($notActiveUserId, $usersIds);

        // Should contain active users.
        $activeUserId = UuidFactory::uuid('user.id.ada');
        $this->assertContains($activeUserId, $usersIds);
    }

    public function testUsersIndexGetApiV1Success()
    {
        $this->authenticateAs('ada');
        $this->getJson('/users.json');
        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));
        $foundAda = $foundThelma = false;

        foreach ($this->_responseJsonBody as $user) {
            if ($user->User->id === UuidFactory::uuid('user.id.ada')) {
                $foundAda = true;
                $this->assertObjectHasAttribute('User', $user);
                $this->assertUserAttributes($user->User);

                // Gpgkey
                $this->assertObjectHasAttribute('Gpgkey', $user);
                $this->assertGpgkeyAttributes($user->Gpgkey);
                // Profile
                $this->assertObjectHasAttribute('Profile', $user);
                $this->assertProfileAttributes($user->Profile);
                // Role
                $this->assertObjectHasAttribute('Role', $user);
                $this->assertRoleAttributes($user->Role);

                // GroupUser
                $this->assertObjectHasAttribute('GroupUser', $user);
                $this->assertEquals(count($user->GroupUser), 0, 'Add should not belong to any group');
            } elseif ($user->User->id === UuidFactory::uuid('user.id.thelma')) {
                $foundThelma = true;
                // GroupUser
                $this->assertObjectHasAttribute('GroupUser', $user);
                $this->assertGreaterThan(1, count($user->GroupUser));
                $this->assertGroupUserAttributes($user->GroupUser[0]);
            }
            if ($foundAda && $foundThelma) {
                break;
            }
        }
        $this->assertTrue($foundThelma && $foundAda);
    }

    public function testUsersIndexOrderByUsername()
    {
        $this->authenticateAs('ada');
        $this->getJson('/users.json?order=User.username');
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody[0]->User->id, UuidFactory::uuid('user.id.ada'));

        $this->getJson('/users.json?order[]=User.username DESC');
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody[0]->User->id, UuidFactory::uuid('user.id.yvonne'));
    }

    public function testUsersIndexOrderByFirstName()
    {
        $this->authenticateAs('ada');
        $this->getJson('/users.json?order[]=Profile.first_name');
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody[0]->User->id, UuidFactory::uuid('user.id.ada'));

        $this->getJson('/users.json?order=Profile.first_name DESC');
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody[0]->User->id, UuidFactory::uuid('user.id.yvonne'));
    }

    public function testUsersIndexOrderByLastName()
    {
        $this->authenticateAs('ada');
        $this->getJson('/users.json?order=Profile.last_name');
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody[0]->User->id, UuidFactory::uuid('user.id.frances'));

        $this->getJson('/users.json?order[]=Profile.last_name DESC');
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody[0]->User->id, UuidFactory::uuid('user.id.wang'));
    }

    public function testUsersIndexOrderByCreated()
    {
        $this->authenticateAs('ada');
        $this->getJson('/users.json?order[]=User.created');
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody[0]->User->id, UuidFactory::uuid('user.id.ada'));

        $this->getJson('/users.json?order[]=User.created DESC&order[]=User.username ASC');
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody[0]->User->id, UuidFactory::uuid('user.id.admin'));
    }

    public function testUsersIndexOrderByModified()
    {
        $this->authenticateAs('ada');
        $this->getJson('/users.json?order[]=User.modified');
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody[0]->User->id, UuidFactory::uuid('user.id.ada'));

        $this->getJson('/users.json?order[]=User.modified DESC&order[]=User.username ASC');
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody[0]->User->id, UuidFactory::uuid('user.id.admin'));
    }

    public function testUsersIndexOrderByError()
    {
        $this->authenticateAs('ada');
        $this->getJson('/users.json?order[]=Users.modified');
        $this->assertResponseError(400);
        $this->getJson('/users.json?order[]=User.modified RAND');
        $this->assertResponseError(400);
        $this->getJson('/users.json?order[]=');
        $this->assertResponseError(400);
    }

    public function testUsersIndexFilterByGroupsSuccess()
    {
        $this->authenticateAs('ada');
        $freelancersId = UuidFactory::uuid('group.id.freelancer');
        $this->getJson('/users.json?filter[has-groups]=' . $freelancersId);
        $this->assertSuccess();
        $freelancers = ['jean', 'kathleen', 'lynne', 'marlyn', 'nancy'];
        $this->assertEquals(count($this->_responseJsonBody), count($freelancers));
    }

    public function testUsersIndexFilterByMultipleGroupsSuccess()
    {
        $this->authenticateAs('ada');
        $hr = UuidFactory::uuid('group.id.human_resource');
        $it = UuidFactory::uuid('group.id.it_support');
        $this->getJson('/users.json?filter[has-groups]=' . $it . ',' . $hr);
        $this->assertSuccess();
        $freelancers = ['ping', 'thelma', 'ursula', 'wang'];
        $this->assertEquals(count($this->_responseJsonBody), count($freelancers));

        $this->getJson('/users.json?filter[has-groups][]=' . $it . '&filter[has-groups][]=' . $hr);
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), count($freelancers));
    }

    public function testUsersIndexFilterByInvalidGroupsError()
    {
        $this->authenticateAs('ada');
        $hr = UuidFactory::uuid('group.id.human_resource');
        $no = UuidFactory::uuid('group.id.nobueno');

        // Invalid format trigger BadRequest
        $this->getJson('/users.json?filter[has-groups]');
        $this->assertError(400);
        $this->getJson('/users.json?filter[has-groups]=');
        $this->assertError(400);
        $this->getJson('/users.json?filter[has-groups]=nope');
        $this->assertError(400);
        $this->getJson('/users.json?filter[has-groups]=' . $hr . ',nope');
        $this->assertError(400);

        // non existing group triggers empty results set
        $this->getJson('/users.json?filter[has-groups]=' . $no);
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 0);
    }

    public function testUsersIndexFilterBySearchSuccess()
    {
        $this->authenticateAs('ada');
        $this->getJson('/users.json?filter[search]=ovela');
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 1);
        $this->assertEquals($this->_responseJsonBody[0]->Profile->last_name, 'Lovelace');

        $this->getJson('/users.json?filter[search]=wang@passbolt');
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 1);
        $this->assertEquals($this->_responseJsonBody[0]->Profile->last_name, 'Xiaoyun');

        // Deleted user should not be shown
        $this->getJson('/users.json?filter[search]=sofia');
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 0);
    }

    public function testUsersIndexFilterByInvalidSearchError()
    {
        $this->authenticateAs('ada');
        // too long
        $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
        $this->getJson('/users.json?filter[search]=' . $lorem);
        $this->assertError(400);
        // not utf8
        $emo = '🔥🔥🔥';
        $this->getJson('/users.json?filter[search]=' . $emo);
        $this->assertError(400);
    }

    public function testUsersIndexFilterByHasAccessSuccess()
    {
        $this->markTestIncomplete();
    }

    public function testUsersIndexFilterActiveAsAdminSuccess()
    {
        $this->authenticateAs('admin');
        $this->getJson('/users.json?filter[is-active]=0');
        $this->assertEquals($this->_responseJsonBody[0]->Profile->first_name, 'Ruth');
        $this->assertSuccess();
    }

    public function testUsersIndexFilterActiveNonAdmin()
    {
        $this->authenticateAs('ada');
        $this->getJson('/users.json?filter[is-active]=0');
        $this->assertNotEquals(count($this->_responseJsonBody), 1);
        $this->assertSuccess();
    }

    public function testUsersIndexErrorNotAuthenticated()
    {
        $this->getJson('/users.json');
        $this->assertAuthenticationError();
    }
}
