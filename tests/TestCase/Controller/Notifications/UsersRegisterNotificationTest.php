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
namespace App\Test\TestCase\Controller\Notifications;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

class UsersRegisterNotificationTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;
    use SelfRegistrationTestTrait;

    public $fixtures = ['app.Base/Users', 'app.Base/Roles', 'app.Base/Profiles',];

    public function testUserRegisterNotificationDisabled()
    {
        $this->setSelfRegistrationSettingsData();
        $this->setEmailNotificationSetting('send.user.create', false);

        $this->postJson('/users/register.json', [
            'username' => 'aurore@passbolt.com',
            'profile' => [
                'first_name' => 'Aurore',
                'last_name' => 'Avarguès-Weber',
            ],
        ]);
        $this->assertResponseSuccess();

        // check email notification
        $this->assertEmailWithRecipientIsInNotQueue('aurore@passbolt.com');
    }

    public function testUserRegisterNotificationSuccess()
    {
        $this->setSelfRegistrationSettingsData();
        $this->setEmailNotificationSetting('send.user.create', true);

        $this->postJson('/users/register.json', [
            'username' => 'aurore@passbolt.com',
            'profile' => [
                'first_name' => 'Aurore',
                'last_name' => 'Avarguès-Weber',
            ],
        ]);
        $this->assertResponseSuccess();

        // check email notification
        $this->assertEmailInBatchContains('You just opened an account', 'aurore@passbolt.com');
    }
}
