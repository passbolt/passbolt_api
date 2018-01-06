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

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;

class UsersRegisterNotificationTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/users', 'app.Base/roles', 'app.Base/profiles', 'app.Base/authentication_tokens', 'app.Base/email_queue', 'app.Base/avatars'];

    public function testUserRegisterNotificationDisabled()
    {
        Configure::write('passbolt.email.send.user.create', false);
        $this->post('/users/register', [
            'username' => 'aurore@passbolt.com',
            'profile' => [
                'first_name' => 'Aurore',
                'last_name' => 'Avarguès-Weber'
            ],
        ]);
        $this->assertResponseSuccess();

        // check email notification
        $this->get('/seleniumtests/showLastEmail/aurore@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');
    }

    public function testUserRegisterNotificationSuccess()
    {
        Configure::write('passbolt.email.send.user.create', true);
        $this->post('/users/register', [
            'username' => 'aurore@passbolt.com',
            'profile' => [
                'first_name' => 'Aurore',
                'last_name' => 'Avarguès-Weber'
            ],
        ]);
        $this->assertResponseSuccess();

        // check email notification
        $this->get('/seleniumtests/showLastEmail/aurore@passbolt.com');
        $this->assertResponseOk();
        $this->assertResponseContains('You just opened an account');
    }
}
