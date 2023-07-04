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
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class UsersAddControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Gpgkeys', 'app.Base/GroupsUsers', 'app.Base/Roles',
        'app.Base/Profiles',
    ];

    public function testUsersAddController_Success(): void
    {
        $this->authenticateAs('admin');
        $roles = TableRegistry::getTableLocator()->get('Roles');
        $adminRoleId = $roles->getIdByName(Role::ADMIN);
        $userRoleId = $roles->getIdByName(Role::USER);
        $success = [
            'admin role' => [
                'username' => 'ping.fu@passbolt.com',
                'role_id' => $adminRoleId,
                'profile' => [
                    'first_name' => '傅',
                    'last_name' => '苹',
                ],
            ],
            'user role' => [
                'username' => 'borka@passbolt.com',
                'role_id' => $userRoleId,
                'profile' => [
                    'first_name' => 'Borka',
                    'last_name' => 'Jerman Blažič',
                ],
            ],
            'no role' => [
                'username' => 'aurore@passbolt.com',
                'profile' => [
                    'first_name' => 'Aurore',
                    'last_name' => 'Avarguès-Weber',
                ],
            ],
        ];

        foreach ($success as $case => $data) {
            $this->postJson('/users.json', $data);
            $this->assertResponseSuccess();

            // Check user was saved
            $users = TableRegistry::getTableLocator()->get('Users');
            $query = $users->find()->where(['username' => $data['username']]);
            $this->assertEquals(1, $query->count());
            $user = $query->first();
            $this->assertFalse($user->active);
            $this->assertFalse($user->deleted);

            // Check profile exist
            $profiles = TableRegistry::getTableLocator()->get('Profiles');
            $query = $profiles->find()->where(['first_name' => $data['profile']['first_name']]);
            $this->assertEquals(1, $query->count());

            // Check role exist
            $roles = TableRegistry::getTableLocator()->get('Roles');
            $role = $roles->get($user->get('role_id'));
            if (!isset($data['role_id'])) {
                $data['role_id'] = $userRoleId;
            }
            $this->assertEquals($role->id, $data['role_id']);
        }
    }

    public function testUsersAddController_Success_CannotModifyNotAccessibleFields(): void
    {
        $this->authenticateAs('admin');
        $date = '1983-04-01 23:34:45';
        $userId = UuidFactory::uuid('user.id.aurore');

        $data = [
            'id' => $userId,
            'active' => 1,
            'deleted' => 1,
            'created' => $date,
            'modified' => $date,
            'username' => 'aurore@passbolt.com',
            'profile' => [
                'first_name' => 'Aurore',
                'last_name' => 'Avarguès-Weber',
            ],
        ];

        $this->postJson('/users.json', $data);
        $this->assertResponseSuccess();

        $users = TableRegistry::getTableLocator()->get('Users');
        $user = $users->find()->where(['username' => $data['username']])->first();

        $this->assertNotEquals($user->id, $userId);
        $this->assertFalse($user->active);
        $this->assertFalse($user->deleted);
        $this->assertTrue($user->created->gt(FrozenTime::parseDateTime($date, 'Y-M-d h:m:s')));
    }

    public function testUsersAddController_Success_EmailSent(): void
    {
        $this->authenticateAs('admin');
        $data = [
            'username' => 'aurore@passbolt.com',
            'profile' => [
                'first_name' => 'Aurore',
                'last_name' => 'Avarguès-Weber',
            ],
        ];
        $this->postJson('/users.json', $data);
        $this->assertResponseSuccess();

        $this->assertEmailInBatchContains('created an account for you', 'aurore@passbolt.com');
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::find()->firstOrFail();
        $user = Router::url('/setup/start/' . $token->user_id . '/' . $token->token, true);
        $this->assertEmailInBatchContains($user, 'aurore@passbolt.com');
    }

    public function testUsersAddController_Error_NotLoggedIn(): void
    {
        $data = [
            'username' => 'notallowed@passbolt.com',
            'profile' => [
                'first_name' => 'not',
                'last_name' => 'allowed',
            ],
        ];
        $this->postJson('/users.json', $data);
        $this->assertAuthenticationError();
    }

    public function testUsersAddController_Error_NotAdmin(): void
    {
        $this->authenticateAs('ada');
        $data = [
            'username' => 'notallowed@passbolt.com',
            'profile' => [
                'first_name' => 'not',
                'last_name' => 'allowed',
            ],
        ];
        $this->postJson('/users.json', $data);
        $this->assertError(403, 'Only administrators can add new users.');
    }

    public function testUsersAddController_Error_CsrfToken(): void
    {
        $this->disableCsrfToken();
        $this->authenticateAs('admin');
        $this->post('/users.json');
        $this->assertResponseCode(403);
    }

    public function testUsersAddController_Errror_RequestDataApiUserExist(): void
    {
        $this->authenticateAs('admin');
        $data = [
            'username' => 'ada@passbolt.com',
            'profile' => [
                'first_name' => 'ada',
                'last_name' => 'lovelace',
            ],
        ];
        $this->postJson('/users.json', $data);
        $this->assertError(400, 'Could not validate user data.');
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testUsersAddController_Error_NotJson(): void
    {
        $this->authenticateAs('admin');
        $data = [
            'username' => 'ada@passbolt.com',
            'profile' => [
                'first_name' => 'ada',
                'last_name' => 'lovelace',
            ],
        ];
        $this->post('/users', $data);
        $this->assertResponseCode(404);
    }
}
