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
namespace App\Test\TestCase\Controller\Notifications;

use App\Model\Entity\Role;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\ORM\TableRegistry;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class UsersAddNotificationTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Gpgkeys', 'app.Base/GroupsUsers', 'app.Base/Roles',
        'app.Base/Profiles', 'app.Base/EmailQueue', 'app.Base/AuthenticationTokens', 'app.Base/Avatars'
    ];

    public function testUserAddNotificationDisabled()
    {
        $this->setEmailNotificationSetting('send.user.create', false);

        $this->authenticateAs('admin');
        $roles = TableRegistry::getTableLocator()->get('Roles');
        $this->postJson('/users.json', [
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
        $this->setEmailNotificationSetting('send.user.create', true);

        $this->authenticateAs('admin');
        $roles = TableRegistry::getTableLocator()->get('Roles');
        $this->postJson('/users.json', [
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
