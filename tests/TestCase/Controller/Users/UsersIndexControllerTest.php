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

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsUsersModelTrait;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;

class UsersIndexControllerTest extends AppIntegrationTestCase
{
    use GroupsUsersModelTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles',
        'app.Base/GroupsUsers',
    ];

    public function testUsersIndexController_Success(): void
    {
        $this->authenticateAs('ada');
        $this->getJson('/users.json');
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

    public function testUsersIndexController_Success_AsAdmin(): void
    {
        $this->authenticateAs('admin');
        $this->getJson('/users.json');
        $usersIds = Hash::extract($this->_responseJsonBody, '{n}.id');

        // Should not contain inactive users.
        $notActiveUserId = UuidFactory::uuid('user.id.ruth');
        $this->assertContains($notActiveUserId, $usersIds);

        // Should contain active users.
        $activeUserId = UuidFactory::uuid('user.id.ada');
        $this->assertContains($activeUserId, $usersIds);
    }

    public function testUsersIndexController_Succes_FilterByGroups(): void
    {
        $this->authenticateAs('ada');
        $freelancersId = UuidFactory::uuid('group.id.freelancer');
        $this->getJson('/users.json?filter[has-groups]=' . $freelancersId);
        $this->assertSuccess();
        $freelancers = ['jean', 'kathleen', 'lynne', 'marlyn', 'nancy'];
        $this->assertEquals(count($this->_responseJsonBody), count($freelancers));
    }

    public function testUsersIndexController_Success_FilterByMultipleGroups(): void
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

    public function testUsersIndexController_Error_FilterByInvalidGroups(): void
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

    public function testUsersIndexController_Success_FilterBySearch(): void
    {
        $this->authenticateAs('ada');
        $this->getJson('/users.json?filter[search]=ovela');
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 1);
        $this->assertEquals($this->_responseJsonBody[0]->profile->last_name, 'Lovelace');

        $this->getJson('/users.json?filter[search]=wang@passbolt');
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 1);
        $this->assertEquals($this->_responseJsonBody[0]->profile->last_name, 'Xiaoyun');

        // Deleted user should not be shown
        $this->getJson('/users.json?filter[search]=sofia');
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 0);
    }

    public function testUsersIndexController_Error_FilterByInvalidSearch(): void
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

    public function testUsersIndexController_Success_FilterActiveAsAdmin(): void
    {
        $this->authenticateAs('admin');
        $this->getJson('/users.json?filter[is-active]=0');
        $this->assertEquals($this->_responseJsonBody[0]->profile->first_name, 'Ruth');
        $this->assertSuccess();
    }

    public function testUsersIndexController_Success_FilterActiveNonAdmin(): void
    {
        $this->authenticateAs('ada');
        $this->getJson('/users.json?filter[is-active]=0');
        $this->assertNotEquals(count($this->_responseJsonBody), 1);
        $this->assertSuccess();
    }

    public function testUsersIndexController_Error_NotAuthenticated(): void
    {
        $this->getJson('/users.json');
        $this->assertAuthenticationError();
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testUsersIndexController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $this->get('/users');
        $this->assertResponseCode(404);
    }
}
