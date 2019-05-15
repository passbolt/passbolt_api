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

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class UsersRegisterNotificationTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;

    public $fixtures = ['app.Base/Users', 'app.Base/Roles', 'app.Base/Profiles', 'app.Base/AuthenticationTokens', 'app.Base/EmailQueue', 'app.Base/Avatars'];

    public function testUserRegisterNotificationDisabled()
    {
        $this->setEmailNotificationSetting('send.user.create', false);

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
        $this->setEmailNotificationSetting('send.user.create', true);

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
