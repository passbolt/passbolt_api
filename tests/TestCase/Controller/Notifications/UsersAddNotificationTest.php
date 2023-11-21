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

use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class UsersAddNotificationTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;

    public function testUserAddNotification_NotificationDisabled(): void
    {
        $this->setEmailNotificationSetting('send.user.create', false);

        RoleFactory::make()->guest()->persist();
        $role = RoleFactory::make()->user()->persist();
        $admin = UserFactory::make()->admin()->active()->persist();

        $this->logInAs($admin);
        $this->postJson('/users.json', [
            'username' => 'new.user@passbolt.com',
            'role_id' => $role->id,
            'profile' => [
                'first_name' => 'new',
                'last_name' => 'user',
            ],
        ]);
        $this->assertResponseSuccess();

        // check email notification
        $this->assertEmailQueueIsEmpty();
    }

    public function testUserAddNotification_NotificationEnabled(): void
    {
        $this->setEmailNotificationSetting('send.user.create', true);

        RoleFactory::make()->guest()->persist();
        $role = RoleFactory::make()->user()->persist();
        $admin = UserFactory::make()
            ->admin()
            ->active()
            ->with('Profiles', [
                'first_name' => '<Steve>',
                'last_name' => 'Doe',
            ])
            ->persist();

        $firstName = 'John\'s';
        $lastName = 'O\'Connor';
        $username = 'new@passbolt.com';
        $this->logInAs($admin);
        $this->postJson('/users.json', [
            'username' => $username,
            'role_id' => $role->id,
            'profile' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
            ],
        ]);
        $this->assertResponseSuccess();
        $userId = $this->_responseJsonBody->id;

        // check email notification
        $this->assertEmailInBatchContains(
            [
                $admin->profile->first_name . ' just created an account for you',
                $admin->profile->first_name . ' just invited you to join passbolt',
                'Welcome ' . $firstName,
                '/setup/start/' . $userId . '/' . AuthenticationTokenFactory::firstOrFail()->token,
            ],
            $username
        );
    }
}
