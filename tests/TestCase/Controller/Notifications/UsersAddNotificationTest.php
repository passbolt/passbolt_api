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
namespace App\Test\TestCase\Controller\Notifications;

use App\Model\Entity\Role;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class UsersAddNotificationTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.Base/users', 'app.Base/gpgkeys', 'app.Base/groups_users', 'app.Base/roles',
        'app.Base/profiles', 'app.Base/email_queue', 'app.Base/authentication_tokens', 'app.Base/avatars'
    ];

    public function testUserAddNotificationDisabled()
    {
        Configure::write('passbolt.email.send.user.create', false);
        $this->authenticateAs('admin');
        $roles = TableRegistry::get('Roles');
        $this->postJson('/users.json?api-version=v1', [
                'username' => 'new@passbolt.com',
                'role_id' => $roles->getIdByName(Role::ADMIN),
                'profile' => [
                    'first_name' => 'new',
                    'last_name' => 'user'
                ],
            ]);
        $this->assertResponseSuccess();

        // check email notification
        $this->get('/seleniumtests/showLastEmail/new@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');
    }

    public function testUserAddNotificationSuccess()
    {
        Configure::write('passbolt.email.send.user.create', true);
        $this->authenticateAs('admin');
        $roles = TableRegistry::get('Roles');
        $this->postJson('/users.json?api-version=v1', [
            'username' => 'new.user@passbolt.com',
            'role_id' => $roles->getIdByName(Role::ADMIN),
            'profile' => [
                'first_name' => 'new',
                'last_name' => 'user'
            ],
        ]);
        $this->assertResponseSuccess();

        // check email notification
        $this->get('/seleniumtests/showLastEmail/new.user@passbolt.com');
        $this->assertResponseOk();
        $this->assertResponseContains('just created an account for you');
    }
}
