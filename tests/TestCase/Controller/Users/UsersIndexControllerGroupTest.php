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

class UsersIndexControllerGroupTest extends AppIntegrationTestCase
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
}
