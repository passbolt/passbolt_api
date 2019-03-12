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

use App\Model\Entity\Role;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;

class UsersEditControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/Users', 'app.Base/Roles', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/GroupsUsers', 'app.Base/Avatars'];

    public function testUsersEditMissingCsrfTokenError()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('ada');
        $userId = UuidFactory::uuid('user.id.ada');
        $this->post("/users/$userId.json?api-version=v2");
        $this->assertResponseCode(403);
    }

    public function testUsersEditNotLoggedInError()
    {
        $data = [];
        $this->postJson('/users/' . UuidFactory::uuid('user.id.nope') . '.json', $data);
        $this->assertAuthenticationError();
    }

    public function testUsersEditNotAdminError()
    {
        $this->authenticateAs('ada');
        $data = [];
        $this->postJson('/users/' . UuidFactory::uuid('user.id.nope') . '.json', $data);
        $this->assertError(403, 'You are not authorized to access that location.');
    }

    public function testUsersEditAdminBadIdError()
    {
        $this->authenticateAs('admin');
        $data = [];
        $this->postJson('/users/notauuid.json', $data);
        $this->assertError(400, 'The user id must be a valid uuid.');
    }

    public function testUsersEditUsersNoDataIdError()
    {
        $this->authenticateAs('ada');
        $data = [];
        $this->postJson('/users/' . UuidFactory::uuid('user.id.ada') . '.json', $data);
        $this->assertError(400, 'Some user data must be provided.');
    }

    public function testUsersEditUsersDataNotMatchError()
    {
        $this->authenticateAs('ada');
        $data = [
            'id' => UuidFactory::uuid('user.id.betty')
        ];
        $this->postJson('/users/' . UuidFactory::uuid('user.id.ada') . '.json', $data);
        $this->assertError(400, 'Some user data must be provided.');
    }

    public function testUsersEditUsersDoesNotExistError()
    {
        $this->authenticateAs('admin');
        $data = [
            'id' => UuidFactory::uuid('user.id.sofia'),
            'profile' => [
                'first_name' => 'sofia edited'
            ]
        ];
        $this->postJson('/users/' . UuidFactory::uuid('user.id.sofia') . '.json', $data);
        $this->assertError(400, 'The user does not exist or has been deleted.');
    }

    public function testUsersEditUsersValidationFailsError()
    {
        $this->authenticateAs('ada');
        $data = [
            'id' => UuidFactory::uuid('user.id.ada'),
            'profile' => [
                'first_name' => '💁'
            ]
        ];
        $this->postJson('/users/' . UuidFactory::uuid('user.id.ada') . '.json', $data);
        $this->assertError(400, 'Could not validate user data.');
        $error = $this->_responseJsonBody->User->profile->first_name->utf8;
        $this->assertEquals($error, 'First name should be a valid utf8 string.');
    }

    public function testUsersEditUsersSuccess()
    {
        $this->authenticateAs('ada');
        $data = [
            'id' => UuidFactory::uuid('user.id.ada'),
            'profile' => [
                'first_name' => 'ada edited'
            ],
        ];
        $this->postJson('/users/' . UuidFactory::uuid('user.id.ada') . '.json', $data);
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody->Profile->first_name, 'Ada edited');
    }

    public function testUsersEditUsersApiV1Success()
    {
        $this->authenticateAs('ada');
        $data = [
            'User' => [
                'id' => UuidFactory::uuid('user.id.ada'),
                'active' => 'false',
                'deleted' => 'true'
            ],
            'Profile' => [
                'first_name' => 'ada edited'
            ]
        ];
        $this->postJson('/users/' . UuidFactory::uuid('user.id.ada') . '.json', $data);
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody->Profile->first_name, 'Ada edited');
        $this->assertEquals($this->_responseJsonBody->User->active, true);
        $this->assertEquals($this->_responseJsonBody->User->deleted, false);
    }

    public function testUsersEditIgnoreNotAllowedFieldsSuccess()
    {
        $this->authenticateAs('ada');
        $data = [
            'id' => UuidFactory::uuid('user.id.ada'),
            'username' => 'adaedited@passbolt.com',
            'active' => false,
            'deleted' => true,
            'profile' => [
                'first_name' => 'ada edited',
            ]
        ];
        $this->postJson('/users/' . UuidFactory::uuid('user.id.ada') . '.json', $data);
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody->Profile->first_name, 'Ada edited');
        $this->assertEquals($this->_responseJsonBody->User->username, 'ada@passbolt.com');
        $this->assertEquals($this->_responseJsonBody->User->active, true);
        $this->assertEquals($this->_responseJsonBody->User->deleted, false);
    }

    public function testUsersEditAdminRoleEditSuccess()
    {
        $this->authenticateAs('admin');
        $data = [
            'id' => UuidFactory::uuid('user.id.ada'),
            'role_id' => UuidFactory::uuid('role.id.admin')
        ];
        $this->postJson('/users/' . UuidFactory::uuid('user.id.ada') . '.json', $data);
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody->Role->name, Role::ADMIN);
    }

    public function testUsersEditNotAdminApiv1IgnoreRoleEditSuccess()
    {
        $this->authenticateAs('ada');
        $data = [
            'User' => [
                'id' => UuidFactory::uuid('user.id.ada'),
                'role_id' => UuidFactory::uuid('role.id.admin')
            ],
            'Role' => [
                'id' => UuidFactory::uuid('role.id.admin')
            ]
        ];
        $this->postJson('/users/' . UuidFactory::uuid('user.id.ada') . '.json', $data);
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody->Role->name, Role::USER);
    }

    public function testUsersEditNotAdminRoleEditError()
    {
        $this->authenticateAs('ada');
        $data = [
            'id' => UuidFactory::uuid('user.id.ada'),
            'role_id' => UuidFactory::uuid('role.id.admin')
        ];
        $this->postJson('/users/' . UuidFactory::uuid('user.id.ada') . '.json?api-version=2', $data);
        $this->assertForbiddenError('You are not authorized to edit the role.');
    }

    public function testUsersEditGpgNotAllowedError()
    {
        $this->authenticateAs('ada');
        $data = [
            'id' => UuidFactory::uuid('user.id.ada'),
            'gpgkey' => []
        ];
        $this->postJson('/users/' . UuidFactory::uuid('user.id.ada') . '.json?api-version=2', $data);
        $this->assertBadRequestError('Updating the gpgkey is not allowed.');
    }

    public function testUsersEditGroupsNotAllowedError()
    {
        $this->authenticateAs('ada');
        $data = [
            'id' => UuidFactory::uuid('user.id.ada'),
            'groups_user' => []
        ];
        $this->postJson('/users/' . UuidFactory::uuid('user.id.ada') . '.json?api-version=2', $data);
        $this->assertBadRequestError('Updating the groups is not allowed.');
    }
}
