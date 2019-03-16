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

class UsersRegisterControllerTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Roles', 'app.Base/Profiles', 'app.Base/Permissions',
        'app.Base/GroupsUsers', 'app.Base/Groups', 'app.Base/Favorites', 'app.Base/Secrets',
        'app.Base/AuthenticationTokens', 'app.Base/Avatars', 'app.Base/EmailQueue'
    ];

    public function testUsersRegisterGetSuccess()
    {
        $this->get('/users/register');
        $this->assertResponseOk();
    }

    public function testUsersRegisterPostSuccess()
    {
        $success = [
            'chinese_name' => [
                'username' => 'ping.fu@passbolt.com',
                'profile' => [
                    'first_name' => '傅',
                    'last_name' => '苹'
                ],
            ],
            'slavic_name' => [
                'username' => 'borka@passbolt.com',
                'profile' => [
                    'first_name' => 'Borka',
                    'last_name' => 'Jerman Blažič'
                ],
            ],
            'french_name' => [
                'username' => 'aurore@passbolt.com',
                'profile' => [
                    'first_name' => 'Aurore',
                    'last_name' => 'Avarguès-Weber'
                ],
            ]
        ];

        foreach ($success as $case => $data) {
            $this->post('/users/register', $data);
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
            $this->assertEquals(Role::USER, $role->name);
        }
    }

    public function testUsersRegisterPostApiV1Success()
    {
        $success = [
            'legacy format' => [
                'User' => [
                    'username' => 'anna@passbolt.com'
                ],
                'Profile' => [
                    'first_name' => 'Anna',
                    'last_name' => 'Fisher'
                ],
            ]
        ];

        foreach ($success as $case => $data) {
            $this->post('/users/register', $data);
            $this->assertResponseSuccess();
        }
    }

    public function testUsersRegisterPostFailValidation()
    {
        $fails = [
            'username is missing' => [
                'username' => '',
                'profile' => [
                    'first_name' => 'valid_first_name',
                    'last_name' => 'valid_last_name'
                ]
            ],
            'username is not an email' => [
                'username' => 'invalid@passbolt',
                'profile' => [
                    'first_name' => 'valid_first_name',
                    'last_name' => 'valid_last_name'
                ]
            ],
            'profile is missing' => [
                'username' => 'valid@passbolt.com',
            ],
            'last name is missing' => [
                'username' => 'valid@passbolt.com',
                'profile' => [
                    'first_name' => 'valid_first_name'
                ]
            ],
            'first name is missing' => [
                'username' => 'valid@passbolt.com',
                'profile' => [
                    'last_name' => 'valid_last_name'
                ]
            ],
            'first name is not a utf8 string' => [
                'username' => 'valid@passbolt.com',
                'profile' => [
                    'first_name' => '🙈🙉🙊',
                    'last_name' => 'valid_last_name'
                ]
            ],
            'email already in use' => [
                'username' => 'ada@passbolt.com',
                'profile' => [
                    'first_name' => 'ada',
                    'last_name' => 'lovelace'
                ]
            ],
        ];
        foreach ($fails as $case => $data) {
            $this->post('/users/register.json', $data);
            $result = json_decode($this->_getBodyAsString());
            $this->assertEquals('400', $result->header->code, 'Validation should fail when ' . $case);
            $this->assertResponseError();
        }
    }

    public function testUsersRegisterPostError_MissingCsrfTokenError()
    {
        $this->disableCsrfToken();
        $this->post('/users/register');
        $this->assertResponseCode(403);
        $result = ($this->_getBodyAsString());
        $this->assertContains('Missing CSRF token cookie', $result);
    }

    public function testUsersRegisterPostExistingDeletedUserWithSameUsername()
    {
        // 1) Try to create a user with same username as an existing one.
        $data = [
            'username' => 'ping@passbolt.com',
            'profile' => [
                'first_name' => 'Ping',
                'last_name' => 'Duplicate'
            ],
        ];

        $this->post('/users/register.json', $data);
        $result = json_decode($this->_getBodyAsString());
        $this->assertEquals('400', $result->header->code, 'Validation should fail when the username already exists in db');
        $this->assertResponseError();

        // 2) Soft delete the existing user.
        $users = TableRegistry::getTableLocator()->get('Users');
        $user = $users->find()
            ->where(['username' => 'ping@passbolt.com'])
            ->first();
        $users->softDelete($user, ['checkRules' => false]);

        // 3) Try again with same data, it should be successful.
        $this->post('/users/register.json', $data);
        $result = json_decode($this->_getBodyAsString());
        $this->assertEquals('200', $result->header->code, 'Validation should be successful when a similar username exists but is soft deleted');
        $this->assertResponseSuccess();
    }

    public function testUsersRegisterCannotModifyNotAccessibleFields()
    {
        // Not allowed to edit: id, active, deleted, created, modified, role_id
        $roles = TableRegistry::getTableLocator()->get('Roles');
        $adminRoleId = $roles->getIdByName(Role::ADMIN);
        $userRoleId = $roles->getIdByName(Role::USER);
        $date = '1983-04-01 23:34:45';
        $userId = UuidFactory::uuid('user.id.aurore');

        $data = [
            'id' => $userId,
            'active' => 1,
            'deleted' => 1,
            'created' => $date,
            'modified' => $date,
            'username' => 'aurore@passbolt.com',
            'role_id' => $adminRoleId,
            'profile' => [
                'first_name' => 'Aurore',
                'last_name' => 'Avarguès-Weber'
            ]
        ];

        $this->post('/users/register', $data);
        $this->assertResponseSuccess();

        $users = TableRegistry::getTableLocator()->get('Users');
        $user = $users->find()->where(['username' => $data['username']])->first();

        $this->assertNotEquals($user->id, $userId);
        $this->assertFalse($user->active);
        $this->assertFalse($user->deleted);
        $this->assertEquals($user->role_id, $userRoleId);
        $this->assertTrue($user->created->gt(FrozenTime::create($date)));
    }
}
