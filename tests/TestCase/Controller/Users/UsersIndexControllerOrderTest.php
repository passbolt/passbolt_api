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
 * @since         3.6.0
 */

namespace App\Test\TestCase\Controller\Users;

use App\Test\Factory\ProfileFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsUsersModelTrait;
use App\Test\Lib\Utility\PaginationTestTrait;
use Cake\Chronos\Date;

class UsersIndexControllerOrderTest extends AppIntegrationTestCase
{
    use GroupsUsersModelTrait;
    use PaginationTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
    }

    public function testUsersIndexOrderByUsername()
    {
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
        ProfileFactory::make($this->getArrayOfDistinctRandomStrings(5, 'first_name'))
            ->with('Users', UserFactory::make()->user()->without('Profiles'))
            ->persist();

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
        ProfileFactory::make($this->getArrayOfDistinctRandomStrings(5, 'last_name'))
            ->with('Users', UserFactory::make()->user()->without('Profiles'))
            ->persist();

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
        $yesterday = Date::yesterday();
        $userOnYesterdayA = UserFactory::make(['username' => 'A@test.test', 'created' => $yesterday])->user()->persist();
        $userOnYesterdayB = UserFactory::make(['username' => 'B@test.test', 'created' => $yesterday])->user()->persist();
        $userTodayZ = UserFactory::make(['username' => 'Z@test-test', 'created' => Date::today()])->user()->persist();

        $this->logInAsUser();

        $this->getJson('/users.json?api-version=v2&order[]=User.created DESC&order[]=User.username ASC');
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody[0]->id, $userTodayZ->id);
        $this->assertEquals($this->_responseJsonBody[1]->id, $userOnYesterdayA->id);
        $this->assertEquals($this->_responseJsonBody[2]->id, $userOnYesterdayB->id);
    }

    public function testUsersIndexOrderByModifiedAndUsername()
    {
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
        $this->logInAsUser();

        $this->getJson('/users.json?order[]=Users.modi');
        $this->assertBadRequestError('Invalid order. "Users.modi" is not in the list of allowed order.');
        $this->getJson('/users.json?order[]=User.modified RAND');
        $this->assertBadRequestError('Invalid order. "RAND" is not a valid order.');
        $this->getJson('/users.json?order[]=');
        $this->assertBadRequestError('Invalid order. "" is not a valid field.');
    }
}
