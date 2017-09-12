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
namespace App\Test\TestCase\Controller;

use App\Model\Entity\Role;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class UsersRegisterControllerTest extends IntegrationTestCase
{
    public $fixtures = ['app.users', 'app.roles', 'app.profiles', 'app.authentication_tokens'];

    public function testUserRegisterGetSuccess()
    {
        $this->get('/users/register');
        $this->assertResponseOk();
    }

    public function testUserRegisterPostSuccess()
    {
        $data = [
            'username' => 'username@passbolt.com',
            'profile' => [
                'first_name' => 'test_first_name',
                'last_name' => 'test_last_name'
            ],
        ];
        $this->post('/users/register', $data);
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
        $this->assertEquals(Role::USER, $role->name);
    }

    public function testUserRegisterPostFailValidation()
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
        ];
        foreach ($fails as $case => $data) {
            $this->post('/users/register.json', $data);
            $result = json_decode($this->_getBodyAsString());
            $this->assertEquals('400', $result->header->code, 'Validation should fail when ' . $case);
            $this->assertResponseError();
        }
    }

    public function testUserRegisterPostSafeguard()
    {
        // TODO check one cannot override the following fields
        // id, active, deleted, created, modified, role
        $this->markTestIncomplete();
    }
}
