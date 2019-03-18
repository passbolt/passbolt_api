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

class UsersAddControllerTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Gpgkeys', 'app.Base/GroupsUsers', 'app.Base/Roles',
        'app.Base/Profiles', 'app.Base/AuthenticationTokens', 'app.Base/Avatars', 'app.Base/EmailQueue'
    ];

    public function testUsersAddNotLoggedInError()
    {
        $data = [
            'username' => 'notallowed@passbolt.com',
            'profile' => [
                'first_name' => 'not',
                'last_name' => 'allowed'
            ]
        ];
        $this->postJson('/users.json', $data);
        $this->assertAuthenticationError();
    }

    public function testUsersAddNotAdminError()
    {
        $this->authenticateAs('ada');
        $data = [
            'username' => 'notallowed@passbolt.com',
            'profile' => [
                'first_name' => 'not',
                'last_name' => 'allowed'
            ]
        ];
        $this->postJson('/users.json', $data);
        $this->assertError(403, 'Only administrators can add new users.');
    }

    public function testUsersAddSuccess()
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
                    'last_name' => '苹'
                ],
            ],
            'user role' => [
                'username' => 'borka@passbolt.com',
                'role_id' => $userRoleId,
                'profile' => [
                    'first_name' => 'Borka',
                    'last_name' => 'Jerman Blažič'
                ],
            ],
            'no role' => [
                'username' => 'aurore@passbolt.com',
                'profile' => [
                    'first_name' => 'Aurore',
                    'last_name' => 'Avarguès-Weber'
                ],
            ]
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

    public function testErrorCsrfToken()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('admin');
        $this->post('/users.json?api-version=v1');
        $this->assertResponseCode(403);
    }

    public function testUsersAddCannotModifyNotAccessibleFields()
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
                'last_name' => 'Avarguès-Weber'
            ]
        ];

        $this->postJson('/users.json', $data);
        $this->assertResponseSuccess();

        $users = TableRegistry::getTableLocator()->get('Users');
        $user = $users->find()->where(['username' => $data['username']])->first();

        $this->assertNotEquals($user->id, $userId);
        $this->assertFalse($user->active);
        $this->assertFalse($user->deleted);
        $this->assertTrue($user->created->gt(FrozenTime::create($date)));
    }

    public function testUsersAddSuccessEmail()
    {
        $this->authenticateAs('admin');
        $data = [
            'username' => 'aurore@passbolt.com',
            'profile' => [
                'first_name' => 'Aurore',
                'last_name' => 'Avarguès-Weber'
            ]
        ];
        $this->postJson('/users.json', $data);
        $this->assertResponseSuccess();

        $this->get('/seleniumtests/showlastemail/aurore@passbolt.com');
        $this->assertResponseOk();
        $this->assertResponseContains('created an account for you');
    }

    public function testUsersAddRequestDataApiV1Success()
    {
        $this->authenticateAs('admin');
        $data = [
            'User' => [
                'username' => 'aurore@passbolt.com',
            ],
            'Profile' => [
                'first_name' => 'Aurore',
                'last_name' => 'Avarguès-Weber'
            ]
        ];
        $this->postJson('/users.json', $data);
        $this->assertResponseSuccess();

        $this->get('/seleniumtests/showlastemail/aurore@passbolt.com');
        $this->assertResponseOk();
        $this->assertResponseContains('created an account for you');
    }

    public function testUsersAddRequestDataApiV1Error()
    {
        $this->authenticateAs('admin');
        $data = [
            'User' => [
                'username' => 'ada@passbolt.com',
            ],
            'Profile' => [
                'first_name' => 'ada',
                'last_name' => 'lovelace'
            ]
        ];
        $this->postJson('/users.json', $data);
        $this->assertError(400, 'Could not validate user data.');
    }
}
