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

use App\Model\Entity\Role;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;

class UsersEditControllerTest extends AppIntegrationTestCase
{
    /**
     * @var \App\Model\Table\AvatarsTable $Avatars
     */
    public $Avatars;

    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
    }

    public function testUsersEditMissingCsrfTokenError()
    {
        $this->disableCsrfToken();
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);
        $userId = $user->id;
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
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);
        $data = [];
        $this->postJson('/users/' . UuidFactory::uuid('user.id.nope') . '.json', $data);
        $this->assertError(403, 'You are not authorized to access that location.');
    }

    public function testUsersEditAdminBadIdError()
    {
        $user = UserFactory::make()->admin()->persist();
        $this->logInAs($user);
        $data = [];
        $this->postJson('/users/notauuid.json', $data);
        $this->assertError(400, 'The user identifier should be a valid UUID.');
    }

    public function testUsersEditUsersNoDataIdError()
    {
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);
        $data = [];
        $this->postJson('/users/' . $user->id . '.json', $data);
        $this->assertError(400, 'Some user data should be provided.');
    }

    public function testUsersEditUsersDataNotMatchError()
    {
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);
        $data = [
            'id' => UuidFactory::uuid('user.id.betty'),
        ];
        $this->postJson('/users/' . $user->id . '.json', $data);
        $this->assertError(400, 'Some user data should be provided.');
    }

    public function testUsersEditUsersDoesNotExistError()
    {
        $user = UserFactory::make()->admin()->persist();
        $this->logInAs($user);
        $data = [
            'id' => UuidFactory::uuid('user.id.sofia'),
            'profile' => [
                'first_name' => 'sofia edited',
            ],
        ];
        $this->postJson('/users/' . UuidFactory::uuid('user.id.sofia') . '.json', $data);
        $this->assertError(400, 'The user does not exist or has been deleted.');
    }

    public function testUsersEditUsersValidationFailsError()
    {
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);
        $data = [
            'id' => $user->id,
            'profile' => [
                'first_name' => '💁',
            ],
        ];
        $this->postJson('/users/' . $user->id . '.json', $data);
        $this->assertError(400, 'Could not validate user data.');
        $error = $this->_responseJsonBody->profile->first_name->utf8;
        $this->assertNotNull($error, 'First name should be a valid utf8 string.');
    }

    public function testUsersEditUsersSuccess()
    {
        $user = UserFactory::make()->user()->with('Profiles')->persist();
        $this->logInAs($user);
        $data = [
            'id' => $user->id,
            'profile' => [
                'first_name' => 'ada edited',
            ],
        ];
        $this->postJson('/users/' . $user->id . '.json', $data);
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody->profile->first_name, 'Ada edited');
    }

    public function testUsersEditUsersSuccess_CannotEditProtectedFields()
    {
        $user = $this->logInAsUser();
        $data = [
            'id' => $user->id,
            'active' => false,
            'deleted' => true,
            'profile' => [
                'first_name' => 'ada edited',
            ],
        ];
        $this->postJson('/users/' . $user->id . '.json', $data);
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody->profile->first_name, 'Ada edited');
        $this->assertEquals($this->_responseJsonBody->active, true);
        $this->assertEquals($this->_responseJsonBody->deleted, false);
    }

    public function testUsersEditIgnoreNotAllowedFieldsSuccess()
    {
        $user = $this->logInAsUser();
        $data = [
            'id' => $user->id,
            'username' => 'adaedited@passbolt.com',
            'active' => false,
            'deleted' => true,
            'profile' => [
                'first_name' => 'ada edited',
            ],
        ];
        $this->postJson('/users/' . $user->id . '.json?api-version=v2', $data);
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody->profile->first_name, 'Ada edited');
        $this->assertEquals($this->_responseJsonBody->username, $user->username);
        $this->assertEquals($this->_responseJsonBody->active, true);
        $this->assertEquals($this->_responseJsonBody->deleted, false);
    }

    public function testUsersEditAdminRoleEditSuccess()
    {
        $admin = UserFactory::make()->admin()->persist();
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($admin);
        $data = [
            'id' => $user->id,
            'role_id' => $admin->role_id,
        ];
        $this->postJson('/users/' . $user->id . '.json?api-version=v2', $data);
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody->role->name, Role::ADMIN);
    }

    public function testUsersEditNotAdminRoleEditError()
    {
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);
        $data = [
            'id' => $user->id,
            'role_id' => RoleFactory::make()->admin()->persist()->id,
        ];
        $this->postJson('/users/' . $user->id . '.json?api-version=v2', $data);
        $this->assertForbiddenError('You are not authorized to edit the role.');
    }

    public function testUsersEditGpgNotAllowedError()
    {
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);
        $data = [
            'id' => $user->id,
            'gpgkey' => [],
        ];
        $this->postJson('/users/' . $user->id . '.json?api-version=v2', $data);
        $this->assertBadRequestError('Updating the OpenPGP key is not allowed.');
    }

    public function testUsersEditGroupsNotAllowedError()
    {
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);
        $data = [
            'id' => $user->id,
            'groups_user' => [],
        ];
        $this->postJson('/users/' . $user->id . '.json?api-version=v2', $data);
        $this->assertBadRequestError('Updating the groups is not allowed.');
    }
}
