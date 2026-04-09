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

use App\Notification\Email\Redactor\User\AdminDeleteEmailRedactor;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\Core\Configure;

class UsersDeleteNotificationTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public function testUsersDeleteNotificationSuccess(): void
    {
        RoleFactory::make()->guest()->persist();
        $ursula = UserFactory::make()->withProfileName('Ursula', 'Martin')->persist();
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        $groupA = GroupFactory::make()
            ->withGroupsManagersFor([$userA, $userB])
            ->withGroupsUsersFor([$ursula])
            ->persist();
        $groupB = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$ursula])
            ->persist();
        Configure::write(AdminDeleteEmailRedactor::CONFIG_KEY_EMAIL_ENABLED, false);

        $this->logInAsAdmin();
        $this->deleteJson('/users/' . $ursula->get('id') . '.json');

        $this->assertSuccess();
        $this->assertEmailInBatchContains('deleted the user Ursula', $userA->username);
        $this->assertEmailInBatchContains($groupA->name, $userA->username);
        $this->assertEmailInBatchContains($groupB->name, $userA->username);

        $this->assertEmailInBatchContains('deleted the user Ursula', $userB->username);
        $this->assertEmailInBatchContains($groupA->name, $userB->username);
        $this->assertEmailInBatchNotContains($groupB->name, $userB->username);

        $this->assertEmailWithRecipientIsInNotQueue($userC->username);

        // Two mails should be in the queue
        $this->assertEmailQueueCount(2);
    }
}
