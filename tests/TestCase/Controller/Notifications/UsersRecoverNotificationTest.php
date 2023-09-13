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

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

class UsersRecoverNotificationTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;
    use SelfRegistrationTestTrait;

    public function testUsersRecoverNotificationSuccess_SetupRestart(): void
    {
        $this->setSelfRegistrationSettingsData();
        $this->setEmailNotificationSetting('send.user.recover', true);

        $username = 'setup@passbolt.com';
        UserFactory::make()->user()
            ->patchData(['username' => $username])
            ->inactive()
            ->persist();

        $this->postJson('/users/recover.json', compact('username'));
        $this->assertSuccess();
        $this->assertEmailInBatchContains('You just opened an account', $username);
    }

    public function testUsersRecoverNotificationSuccess_Recover(): void
    {
        $this->setSelfRegistrationSettingsData();
        $this->setEmailNotificationSetting('send.user.recover', true);

        $username = 'recover@passbolt.com';
        UserFactory::make()->user()
            ->patchData(['username' => $username])
            ->active()
            ->persist();

        $this->postJson('/users/recover.json', compact('username'));
        $this->assertSuccess();
        $this->assertEmailInBatchContains('You have initiated an account recovery!', $username);
    }

    public function testUsersRecoverNotificationDisabled_SetupRestart(): void
    {
        $this->setEmailNotificationSetting('send.user.create', false);

        $username = 'setup@passbolt.com';
        UserFactory::make()->user()
            ->patchData(['username' => $username])
            ->inactive()
            ->persist();

        $this->postJson('/users/recover.json', compact('username'));
        $this->assertSuccess();
        $this->assertEmailWithRecipientIsInNotQueue('ruth@passbolt.com');
    }

    public function testUsersRecoverNotificationDisabled_Recover(): void
    {
        $this->setEmailNotificationSetting('send.user.recover', false);

        $username = 'recover@passbolt.com';
        UserFactory::make()->user()
            ->patchData(['username' => $username])
            ->active()
            ->persist();

        $this->postJson('/users/recover.json', compact('username'));
        $this->assertSuccess();
        $this->assertEmailWithRecipientIsInNotQueue('ada@passbolt.com');
    }
}
