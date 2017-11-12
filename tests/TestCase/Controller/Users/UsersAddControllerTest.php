<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
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
    public $fixtures = ['app.users', 'app.roles', 'app.profiles', 'app.email_queue', 'app.authentication_tokens'];

    public function testUserAddNotLoggedInError()
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

    public function testUserAddNotAdminError()
    {
        $this->authenticateAs('ada', Role::USER);
        $data = [
            'username' => 'notallowed@passbolt.com',
            'profile' => [
                'first_name' => 'not',
                'last_name' => 'allowed'
            ]
        ];
        $this->postJson('/users.json', $data);
        $this->assertError('403', 'Only administrators can add new users.');
    }

    public function testUserAddSuccess()
    {
        $this->authenticateAs('admin');
        $roles = TableRegistry::get('Roles');
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
            'not role' => [
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
            $users = TableRegistry::get('Users');
            $query = $users->find()->where(['username' => $data['username']]);
            $this->assertEquals(1, $query->count());
            $user = $query->first();
            $this->assertFalse($user->active);
            $this->assertFalse($user->deleted);

            // Check profile exist
            $profiles = TableRegistry::get('Profiles');
            $query = $profiles->find()->where(['first_name' => $data['profile']['first_name']]);
            $this->assertEquals(1, $query->count());

            // Check role exist
            $roles = TableRegistry::get('Roles');
            $role = $roles->get($user->get('role_id'));
            if (!isset($data['role_id'])) {
                $data['role_id'] = $userRoleId;
            }
            $this->assertEquals($role->id, $data['role_id']);
        }
    }

    public function testUserAddCannotModifyNotAccessibleFields()
    {
        $this->authenticateAs('admin', Role::ADMIN);
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

        $users = TableRegistry::get('Users');
        $user = $users->find()->where(['username' => $data['username']])->first();

        $this->assertNotEquals($user->id, $userId);
        $this->assertFalse($user->active);
        $this->assertFalse($user->deleted);
        $this->assertTrue($user->created->gt(FrozenTime::create($date)));
    }
}
