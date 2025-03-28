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
 * @since         4.4.0
 */
namespace App\Test\TestCase\Controller\Notifications;

use App\Notification\Email\Redactor\User\AdminDeleteEmailRedactor;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\GroupsUserFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\Core\Configure;
use Cake\Event\EventList;
use Cake\Event\EventManager;

/**
 * @covers \App\Notification\Email\Redactor\User\AdminDeleteEmailRedactor
 */
class AdminDeleteNotificationTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();

        RoleFactory::make()->guest()->persist();
        // Enable event tracking for emails
        EventManager::instance()->setEventList(new EventList());
        // Mock user agent and IP
        $this->mockUserAgent('PHPUnit');
        $this->mockUserIp();
    }

    public function testAdminDeleteNotification_Success(): void
    {
        [$adminDeleted, $operator, $otherAdmin] = UserFactory::make(3)->admin()->persist();
        // This admin should not receive notifications
        UserFactory::make()->admin()->disabled()->persist();
        // This user should not receive notifications
        UserFactory::make()->user()->persist();

        $this->logInAs($operator);
        $this->deleteJson("/users/{$adminDeleted->id}.json");

        $this->assertSuccess();
        // Email assertions
        $this->assertEmailQueueCount(2);
        $adminFullName = $adminDeleted->profile->full_name;
        $operatorFullName = $operator->profile->full_name;
        $this->assertEmailInBatchContains(
            "You deleted administrator {$adminFullName}",
            $operator->username
        );
        $this->assertEmailInBatchContains(
            "The administrator {$adminFullName} ({$adminDeleted->username}) is now deleted from the passbolt organisation.",
            $operator->username
        );
        $this->assertEmailInBatchContains(
            "{$operatorFullName} deleted administrator {$adminFullName}",
            $otherAdmin->username
        );
        $this->assertEmailInBatchContains(
            "The administrator {$adminFullName} ({$adminDeleted->username}) is now deleted from the passbolt organisation.",
            $otherAdmin->username
        );
    }

    public function testAdminDeleteNotification_Delete_User_Should_Not_Send_Notification(): void
    {
        /** @var \App\Model\Entity\User $userDeleted */
        $userDeleted = UserFactory::make()->user()->persist();

        $this->logInAsAdmin();
        $this->deleteJson("/users/{$userDeleted->id}.json");

        $this->assertSuccess();
        // No emails should be sent if the deleted user is not an admin
        $this->assertEmailQueueCount(0);
    }

    public function testAdminDeleteNotification_NotificationOff(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->active()->persist();
        /** @var \App\Model\Entity\User $operator */
        $operator = UserFactory::make()->admin()->active()->persist();
        UserFactory::make()->admin()->active()->persist();
        // Turn off notification
        Configure::write(AdminDeleteEmailRedactor::CONFIG_KEY_EMAIL_ENABLED, false);

        $this->logInAs($operator);
        $this->deleteJson("/users/{$admin->id}.json");

        $this->assertSuccess();
        // Email assertions
        $this->assertEmailQueueCount(0);
    }

    public function testAdminDeleteNotification_DuplicateEmailNotSentToGroupManager(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->active()->persist();
        /** @var \App\Model\Entity\User $operator */
        $operator = UserFactory::make()->admin()->active()->persist();
        /** @var \App\Model\Entity\User $johnAdmin */
        $johnAdmin = UserFactory::make()->admin()->active()->persist();
        // Create group related entries
        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make(['name' => 'Marketing'])->withGroupsManagersFor([$johnAdmin])->persist();
        GroupsUserFactory::make()
            ->with('Users', $admin)
            ->with('Groups', $group)
            ->persist();

        $this->logInAs($operator);
        $this->deleteJson("/users/{$admin->id}.json");

        $this->assertSuccess();
        // Email assertions
        $this->assertEmailQueueCount(2);
        $adminFullName = $admin->profile->full_name;
        $this->assertEmailInBatchContains(
            "You deleted administrator {$adminFullName}",
            $operator->username
        );
        $this->assertEmailInBatchContains(
            "The administrator {$adminFullName} ({$admin->username}) is now deleted from the passbolt organisation.",
            $operator->username
        );
        // Group manager is notified
        $this->assertEmailInBatchContains(
            "{$operator->profile->first_name} deleted user {$admin->profile->first_name}",
            $johnAdmin->username
        );
        $this->assertEmailInBatchContains(
            'This user was a member of the following group(s) you manage',
            $johnAdmin->username
        );
        $this->assertEmailInBatchContains($group->name, $johnAdmin->username);
    }

    /**
     * If send.group.user.delete redactor is disabled, group manager who is admin will get notified as admin not GM.
     *
     * @return void
     */
    public function testAdminDeleteNotification_GroupUserDeleteRedactorDisabled(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->active()->persist();
        /** @var \App\Model\Entity\User $operator */
        $operator = UserFactory::make()->admin()->active()->persist();
        /** @var \App\Model\Entity\User $johnAdmin */
        $johnAdmin = UserFactory::make()->admin()->active()->persist();
        // Create group related entries
        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make(['name' => 'Marketing'])->withGroupsManagersFor([$johnAdmin])->persist();
        GroupsUserFactory::make()
            ->with('Users', $admin)
            ->with('Groups', $group)
            ->persist();
        // Disable sending user delete email to group managers
        Configure::write('passbolt.email.send.group.user.delete', false);

        $this->logInAs($operator);
        $this->deleteJson("/users/{$admin->id}.json");

        $this->assertSuccess();
        // Email assertions
        $this->assertEmailQueueCount(2);
        $adminFullName = $admin->profile->full_name;
        $operatorFullName = $operator->profile->full_name;
        // Admin #1
        $this->assertEmailInBatchContains(
            "You deleted administrator {$adminFullName}",
            $operator->username
        );
        $this->assertEmailInBatchContains(
            "The administrator {$adminFullName} ({$admin->username}) is now deleted from the passbolt organisation.",
            $operator->username
        );
        // Admin #2
        $this->assertEmailInBatchContains(
            "{$operatorFullName} deleted administrator {$adminFullName}",
            $johnAdmin->username
        );
        $this->assertEmailInBatchContains(
            "The administrator {$adminFullName} ({$admin->username}) is now deleted from the passbolt organisation.",
            $operator->username
        );
    }

    public function testAdminDeleteNotification_SentToAdminWhoGotDeleted(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->active()->persist();
        /** @var \App\Model\Entity\User $operator */
        $operator = UserFactory::make()->admin()->active()->persist();
        /** @var \App\Model\Entity\User $johnAdmin */
        $johnAdmin = UserFactory::make()->admin()->active()->persist();
        // Create group related entries
        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make(['name' => 'Marketing'])->withGroupsManagersFor([$johnAdmin])->persist();
        GroupsUserFactory::make()
            ->with('Users', $admin)
            ->with('Groups', $group)
            ->persist();
        // Turn on notification for user themselves who got deleted
        Configure::write(AdminDeleteEmailRedactor::CONFIG_KEY_SEND_USER_EMAIL, true);

        $this->logInAs($operator);
        $this->deleteJson("/users/{$admin->id}.json");

        $this->assertSuccess();
        // Email assertions
        $this->assertEmailQueueCount(3);
        $adminFullName = $admin->profile->full_name;
        $operatorFullName = $operator->profile->full_name;
        // Deleted admin is notified
        $this->assertEmailInBatchContains("{$operatorFullName} deleted your account", $admin->username);
        $this->assertEmailInBatchContains("{$operatorFullName} deleted you from the passbolt organisation", $admin->username);
        $this->assertEmailInBatchNotContains('Log in passbolt', $admin->username);
        // Admin is notified
        $this->assertEmailInBatchContains(
            "You deleted administrator {$adminFullName}",
            $operator->username
        );
        $this->assertEmailInBatchContains(
            "The administrator {$adminFullName} ({$admin->username}) is now deleted from the passbolt organisation.",
            $operator->username
        );
        // Group manager is notified
        $this->assertEmailInBatchContains(
            "{$operator->profile->first_name} deleted user {$admin->profile->first_name}",
            $johnAdmin->username
        );
        $this->assertEmailInBatchContains(
            'This user was a member of the following group(s) you manage',
            $johnAdmin->username
        );
        $this->assertEmailInBatchContains($group->name, $johnAdmin->username);
    }
}
