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

class UsersViewControllerTest extends AppIntegrationTestCase
{
    use GroupsUsersModelTrait;

    public $fixtures = ['app.Base/Users', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles', 'app.Base/Avatars', 'app.Base/GroupsUsers'];

    public function testUsersViewGetSuccess()
    {
        $this->authenticateAs('ursula');
        $uuid = UuidFactory::uuid('user.id.ursula');
        $this->getJson('/users/' . $uuid . '.json?api-version=2');
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);

        $this->assertUserAttributes($this->_responseJsonBody);
        $this->assertObjectHasAttribute('profile', $this->_responseJsonBody);
        $this->assertProfileAttributes($this->_responseJsonBody->profile);
        $this->assertObjectHasAttribute('avatar', $this->_responseJsonBody->profile);
        $this->assertAvatarAttributes($this->_responseJsonBody->profile->avatar);
        $this->assertObjectHasAttribute('gpgkey', $this->_responseJsonBody);
        $this->assertGpgkeyAttributes($this->_responseJsonBody->gpgkey);
        $this->assertObjectHasAttribute('role', $this->_responseJsonBody);
        $this->assertRoleAttributes($this->_responseJsonBody->role);
        $this->assertObjectHasAttribute('groups_users', $this->_responseJsonBody);
        $this->assertGroupUserAttributes($this->_responseJsonBody->groups_users[0]);
    }

    public function testUsersViewGetApiV1Success()
    {
        $this->authenticateAs('ursula');
        $uuid = UuidFactory::uuid('user.id.ursula');
        $this->getJson('/users/' . $uuid . '.json');
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);

        $this->assertObjectHasAttribute('User', $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->User);
        $this->assertObjectHasAttribute('Profile', $this->_responseJsonBody);
        $this->assertProfileAttributes($this->_responseJsonBody->Profile);
        $this->assertObjectHasAttribute('Avatar', $this->_responseJsonBody->Profile);
        $this->assertAvatarAttributes($this->_responseJsonBody->Profile->Avatar);
        $this->assertObjectHasAttribute('Gpgkey', $this->_responseJsonBody);
        $this->assertGpgkeyAttributes($this->_responseJsonBody->Gpgkey);
        $this->assertObjectHasAttribute('Role', $this->_responseJsonBody);
        $this->assertRoleAttributes($this->_responseJsonBody->Role);
        $this->assertObjectHasAttribute('GroupUser', $this->_responseJsonBody);
        $this->assertGroupUserAttributes($this->_responseJsonBody->GroupUser[0]);
    }

    public function testUsersViewGetMeSuccess()
    {
        $this->authenticateAs('ada');
        $uuid = UuidFactory::uuid('user.id.ada');
        $this->getJson('/users/me.json');
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);

        $this->assertObjectHasAttribute('User', $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->User);
        $this->assertEquals($this->_responseJsonBody->User->id, $uuid);
    }

    public function testUsersViewNotLoggedInError()
    {
        $this->getJson('/users/me.json');
        $this->assertAuthenticationError();
    }

    public function testUsersViewInvalidIdError()
    {
        $this->authenticateAs('ada');
        $this->getJson('/users/notuuid.json');
        $this->assertError(400, 'The user id should be a uuid or "me".');
    }

    public function testUsersViewUserDoesNotExistError()
    {
        $this->authenticateAs('ada');
        $uuid = UuidFactory::uuid('user.id.notauser');
        $this->getJson('/users/' . $uuid . '.json');
        $this->assertError(404, 'The user does not exist.');
    }
}
