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

class UsersRecoverControllerTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;

    public $fixtures = ['app.Base/Users', 'app.Base/Roles', 'app.Base/Profiles', 'app.Base/AuthenticationTokens', 'app.Base/EmailQueue', 'app.Base/Avatars'];

    public function testUsersRecoverNotificationSuccess()
    {
        $this->setEmailNotificationSetting('send.user.recover', true);

        // setup
        $this->postJson('/users/recover.json', ['username' => 'ruth@passbolt.com']);
        $this->assertSuccess();
        $this->get('/seleniumtests/showLastEmail/ruth@passbolt.com');
        $this->assertResponseOk();
        $this->assertResponseContains('You just opened an account');

        // recovery
        $this->postJson('/users/recover.json', ['username' => 'ada@passbolt.com']);
        $this->assertSuccess();
        $this->get('/seleniumtests/showlastemail/ada@passbolt.com');
        $this->assertResponseOk();
        $this->assertResponseContains('You have initiated an account recovery!');
    }

    public function testUsersRecoverNotificationDisabled()
    {
        // setup
        $this->setEmailNotificationSetting('send.user.create', false);

        $this->postJson('/users/recover.json?api-version=v1', ['username' => 'ruth@passbolt.com']);
        $this->assertSuccess();
        $this->get('/seleniumtests/showLastEmail/ruth@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');

        // recovery
        $this->setEmailNotificationSetting('send.user.recover', false);

        $this->postJson('/users/recover.json?api-version=v1', ['username' => 'ada@passbolt.com']);
        $this->assertSuccess();
        $this->get('/seleniumtests/showlastemail/ada@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');
    }
}
