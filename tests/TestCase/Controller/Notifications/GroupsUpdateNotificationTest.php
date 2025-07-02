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

use App\Test\Factory\GroupFactory;
use App\Test\Factory\GroupsUserFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class GroupsUpdateNotificationTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadNotificationSettings();
    }

    public function tearDown(): void
    {
        $this->unloadNotificationSettings();
        parent::tearDown();
    }

    public function testGroupsUpdateNotification_NotificationEnabled(): void
    {
        $uac = UserFactory::make()->admin()->persistedUAC();
        $this->setEmailNotificationSettings([
            'send.group.user.add' => true,
            'send.group.user.update' => true,
            'send.group.user.delete' => true,
            'send.group.manager.update' => true,
        ], $uac);
        RoleFactory::make()->user()->persist();

        $admin = UserFactory::make()->admin()->active()->persist();
        [$add, $add2, $remove, $promote, $demote, $ga] = UserFactory::make(6)->user()->active()->persist();
        [$gaDisabled, $disabled] = UserFactory::make(2)->user()->active()->disabled()->persist();

        $group = GroupFactory::make()
            ->withGroupsManagersFor([$admin, $demote, $ga, $gaDisabled])
            ->withGroupsUsersFor([$remove, $promote])
            ->persist();

        $gar = GroupsUserFactory::find()->where(['user_id' => $remove->id, 'group_id' => $group->id ])->first();
        $gad = GroupsUserFactory::find()->where(['user_id' => $demote->id, 'group_id' => $group->id ])->first();
        $gap = GroupsUserFactory::find()->where(['user_id' => $promote->id, 'group_id' => $group->id ])->first();

        // Update the group users.
        $changes[] = ['user_id' => $add->id]; // Add regular user as regular member
        $changes[] = ['user_id' => $add2->id, 'is_admin' => true]; // Add user as group admin
        $changes[] = ['user_id' => $disabled->id]; // Add disabled user as regular member
        $changes[] = ['id' => $gar->id, 'delete' => true]; // Remove from group
        $changes[] = ['id' => $gad->id, 'is_admin' => false]; // Demote group admin to regular member
        $changes[] = ['id' => $gap->id, 'is_admin' => true]; // Promote in group

        $this->logInAs($admin);
        $this->putJson('/groups/' . $group->id . '.json', ['groups_users' => $changes]);
        $this->assertSuccess();

        // Regular user added as member
        $this->assertEmailInBatchContains('added you to the group', $add->username);
        $this->assertEmailInBatchContains('As member of the group', $add->username);
        $this->assertEmailInBatchNotContains('And as group manager', $add->username);

        // Regular user added as admin
        $this->assertEmailInBatchContains('added you to the group', $add2->username);
        $this->assertEmailInBatchContains('As member of the group', $add2->username);
        $this->assertEmailInBatchContains('And as group manager', $add2->username);

        // Regular user removed from group
        $this->assertEmailInBatchContains('you from the group', $remove->username);
        $this->assertEmailInBatchContains('You are no longer a member', $remove->username);

        // No email for disabled users
        $this->assertEmailWithRecipientIsInNotQueue($disabled->username);
        $this->assertEmailWithRecipientIsInNotQueue($gaDisabled->username);

        // Demoted from group
        $this->assertEmailInBatchContains(
            'You are no longer a group manager of this group.',
            $demote->username
        );

        // Promoted as group manager
        $this->assertEmailInBatchContains(
            'You are now a group manager of this group.',
            $promote->username
        );

        // Admin summary
        $this->assertEmailInBatchContains('Added members', $ga->username);
        $this->assertEmailInBatchContains('Removed members', $ga->username);
        $this->assertEmailInBatchContains('Updated roles', $ga->username);
    }

    public function testGroupsUpdateNotification_NotificationDisabled(): void
    {
        $uac = UserFactory::make()->admin()->persistedUAC();
        $this->setEmailNotificationSettings([
            'send.group.user.add' => false,
            'send.group.user.update' => false,
            'send.group.user.delete' => false,
            'send.group.manager.update' => false,
        ], $uac);
        RoleFactory::make()->user()->persist();

        $admin = UserFactory::make()->admin()->active()->persist();
        [$add, $add2, $remove, $promote, $demote, $ga] = UserFactory::make(6)->user()->active()->persist();
        [$gaDisabled, $disabled] = UserFactory::make(2)->user()->active()->disabled()->persist();

        $group = GroupFactory::make()
            ->withGroupsManagersFor([$admin, $demote, $ga, $gaDisabled])
            ->withGroupsUsersFor([$remove, $promote])
            ->persist();

        $gar = GroupsUserFactory::find()->where(['user_id' => $remove->id, 'group_id' => $group->id ])->first();
        $gad = GroupsUserFactory::find()->where(['user_id' => $demote->id, 'group_id' => $group->id ])->first();
        $gap = GroupsUserFactory::find()->where(['user_id' => $promote->id, 'group_id' => $group->id ])->first();

        // Update the group users.
        $changes[] = ['user_id' => $add->id]; // Add regular user as regular member
        $changes[] = ['user_id' => $add2->id, 'is_admin' => true]; // Add user as group admin
        $changes[] = ['user_id' => $disabled->id]; // Add disabled user as regular member
        $changes[] = ['id' => $gar->id, 'delete' => true]; // Remove from group
        $changes[] = ['id' => $gad->id, 'is_admin' => false]; // Demote group admin to regular member
        $changes[] = ['id' => $gap->id, 'is_admin' => true]; // Promote in group

        $this->logInAs($admin);
        $this->putJson('/groups/' . $group->id . '.json', ['groups_users' => $changes]);
        $this->assertSuccess();

        $this->assertEmailQueueIsEmpty();
    }
}
