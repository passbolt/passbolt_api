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

use App\Test\Factory\ProfileFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsUsersModelTrait;
use App\Test\Lib\Utility\PaginationTestTrait;
use App\Utility\UuidFactory;
use Cake\Chronos\Date;
use Cake\Utility\Hash;
use Faker\Generator;

class UsersIndexControllerTest extends AppIntegrationTestCase
{
    use GroupsUsersModelTrait;
    use PaginationTestTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles',
        'app.Base/GroupsUsers',
    ];

    public $autoFixtures = false;

    public function testUsersIndexGetSuccess()
    {
        $this->loadFixtures();
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
        // avatar
        $this->assertObjectHasAttribute('avatar', $this->_responseJsonBody[0]->profile);
        $this->assertAvatarUrlAttributes($this->_responseJsonBody[0]->profile->avatar);
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
        $this->loadFixtures();
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

    public function testUsersIndexOrderByUsername()
    {
        RoleFactory::make()->guest()->persist();
        UserFactory::make(5)->user()->persist();

        $this->logInAsUser();

        $this->getJson('/users.json?api-version=v2&order=User.username');
        $this->assertSuccess();
        $this->assertBodyContentIsSorted('username');

        $this->getJson('/users.json?api-version=v2&order[]=User.username DESC');
        $this->assertSuccess();
        $this->assertBodyContentIsSorted('username', 'desc');
    }

    public function testUsersIndexOrderByFirstName()
    {
        RoleFactory::make()->guest()->persist();
        UserFactory::make(5)->user()->with(
            'Profiles',
            function (ProfileFactory $factory, Generator $faker) {
                // Makes sure that all first name are distinct
                return ['first_name' => $faker->unique()->firstName()];
            }
        )->persist();

        $this->logInAsUser();

        $this->getJson('/users.json?api-version=v2&order[]=Profile.first_name');
        $this->assertSuccess();
        $this->assertBodyContentIsSorted('profile.first_name');

        $this->getJson('/users.json?api-version=v2&order=Profile.first_name DESC');
        $this->assertSuccess();
        $this->assertBodyContentIsSorted('profile.first_name', 'desc');
    }

    public function testUsersIndexOrderByLastName()
    {
        RoleFactory::make()->guest()->persist();
        UserFactory::make(5)->user()->with(
            'Profiles',
            function (ProfileFactory $factory, Generator $faker) {
                // Makes sure that all last name are distinct
                return ['last_name' => $faker->unique()->lastName(),];
            }
        )->persist();

        $this->logInAsUser();

        $this->getJson('/users.json?api-version=v2&order=Profile.last_name');
        $this->assertSuccess();
        $this->assertBodyContentIsSorted('profile.last_name');

        $this->getJson('/users.json?api-version=v2&order[]=Profile.last_name DESC');
        $this->assertSuccess();
        $this->assertBodyContentIsSorted('profile.last_name', 'desc');
    }

    public function testUsersIndexOrderByCreated()
    {
        RoleFactory::make()->guest()->persist();
        $userOnYesterdayA = UserFactory::make(['username' => 'A@test.test', 'created' => Date::now()->subDays(1)])->user()->persist();
        $userOnYesterdayB = UserFactory::make($userOnYesterdayA)->patchData(['username' => 'B@test.test'])->user()->persist();
        $userTodayZ = UserFactory::make(['username' => 'Z@test-test', 'created' => Date::now()])->user()->persist();

        $this->logInAsUser();

        $this->getJson('/users.json?api-version=v2&order[]=User.created DESC&order[]=User.username ASC');
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody[0]->id, $userTodayZ->id);
        $this->assertEquals($this->_responseJsonBody[1]->id, $userOnYesterdayA->id);
        $this->assertEquals($this->_responseJsonBody[2]->id, $userOnYesterdayB->id);
    }

    public function testUsersIndexOrderByModifiedAndUsername()
    {
        RoleFactory::make()->guest()->persist();
        $userOnBeforeYesterday = UserFactory::make(['modified' => Date::now()->subDays(2)])->user()->persist();
        $userOnYesterdayB = UserFactory::make(['username' => 'B@test.test', 'modified' => Date::now()->subDays(1)])->user()->persist();
        $userOnYesterdayA = UserFactory::make(['username' => 'A@test.test', 'modified' => Date::now()->subDays(1)])->user()->persist();
        $userOnYesterdayC = UserFactory::make(['username' => 'C@test.test', 'modified' => Date::now()->subDays(1)])->user()->persist();
        $userToday = UserFactory::make(['modified' => Date::now()])->user()->persist();

        $this->logInAs($userOnBeforeYesterday);

        $this->getJson('/users.json?api-version=v2&order[]=User.modified');
        $this->assertSuccess();
        $this->assertBodyContentIsSorted('modified');

        $this->getJson('/users.json?api-version=v2&order[]=User.modified DESC&order[]=User.username ASC');
        $this->assertSuccess();

        $this->assertEquals($this->_responseJsonBody[0]->id, $userToday->id);
        $this->assertEquals($this->_responseJsonBody[1]->id, $userOnYesterdayA->id);
        $this->assertEquals($this->_responseJsonBody[2]->id, $userOnYesterdayB->id);
        $this->assertEquals($this->_responseJsonBody[3]->id, $userOnYesterdayC->id);
        $this->assertEquals($this->_responseJsonBody[4]->id, $userOnBeforeYesterday->id);
    }

    public function testUsersIndexOrderByError()
    {
        RoleFactory::make()->guest()->persist();
        $this->logInAsUser();

        $this->getJson('/users.json?order[]=Users.modi');
        $this->assertBadRequestError('Invalid order. "Users.modi" is not in the list of allowed order.');
        $this->getJson('/users.json?order[]=User.modified RAND');
        $this->assertBadRequestError('Invalid order. "RAND" is not a valid order.');
        $this->getJson('/users.json?order[]=');
        $this->assertBadRequestError('Invalid order. "" is not a valid field.');
    }

    public function testUsersIndexFilterByGroupsSuccess()
    {
        $this->loadFixtures();
        $this->authenticateAs('ada');
        $freelancersId = UuidFactory::uuid('group.id.freelancer');
        $this->getJson('/users.json?filter[has-groups]=' . $freelancersId);
        $this->assertSuccess();
        $freelancers = ['jean', 'kathleen', 'lynne', 'marlyn', 'nancy'];
        $this->assertEquals(count($this->_responseJsonBody), count($freelancers));
    }

    public function testUsersIndexFilterByMultipleGroupsSuccess()
    {
        $this->loadFixtures();
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
        $this->loadFixtures();
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
        $this->loadFixtures();
        $this->authenticateAs('ada');
        $this->getJson('/users.json?api-version=v2&filter[search]=ovela');
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 1);
        $this->assertEquals($this->_responseJsonBody[0]->profile->last_name, 'Lovelace');

        $this->getJson('/users.json?api-version=v2&filter[search]=wang@passbolt');
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 1);
        $this->assertEquals($this->_responseJsonBody[0]->profile->last_name, 'Xiaoyun');

        // Deleted user should not be shown
        $this->getJson('/users.json?api-version=v2&filter[search]=sofia');
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 0);
    }

    public function testUsersIndexFilterByInvalidSearchError()
    {
        $this->loadFixtures();
        $this->authenticateAs('ada');
        // too long
        $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
        $this->getJson('/users.json?api-version=v2&filter[search]=' . $lorem);
        $this->assertError(400);
        // not utf8
        $emo = '🔥🔥🔥';
        $this->getJson('/users.json?api-version=v2&filter[search]=' . $emo);
        $this->assertError(400);
    }

    public function testUsersIndexFilterByHasAccessSuccess()
    {
        RoleFactory::make()->guest()->persist();
        $user = UserFactory::make(2)->user()->persist()[0];
        $resourceFactory = ResourceFactory::make();
        $resource = $resourceFactory->withCreatorAndPermission($user)->persist();
        $resourceFactory->persist();

        $this->logInAs($user);
        $this->getJson('/users.json?api-version=v2&filter[has-access]=' . $resource->id);
        $this->assertResponseOk();
        $this->assertCount(1, $this->_responseJsonBody);
        $this->assertSame($user->id, $this->_responseJsonBody[0]->id);
    }

    public function testUsersIndexFilterActiveAsAdminSuccess()
    {
        $this->loadFixtures();
        $this->authenticateAs('admin');
        $this->getJson('/users.json?api-version=v2&filter[is-active]=0');
        $this->assertEquals($this->_responseJsonBody[0]->profile->first_name, 'Ruth');
        $this->assertSuccess();
    }

    public function testUsersIndexFilterActiveNonAdmin()
    {
        $this->loadFixtures();
        $this->authenticateAs('ada');
        $this->getJson('/users.json?api-version=v2&filter[is-active]=0');
        $this->assertNotEquals(count($this->_responseJsonBody), 1);
        $this->assertSuccess();
    }

    public function testUsersIndexErrorNotAuthenticated()
    {
        $this->getJson('/users.json');
        $this->assertAuthenticationError();
    }
}
