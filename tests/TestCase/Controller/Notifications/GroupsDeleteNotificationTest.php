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
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class GroupsDeleteNotificationTest extends AppIntegrationTestCase
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
        parent::tearDown();
        $this->unloadNotificationSettings();
    }

    public function testGroupsDeleteNotificationDisabled(): void
    {
        $this->setEmailNotificationSetting('send.group.delete', false);

        RoleFactory::make()->user()->persist();
        RoleFactory::make()->admin()->persist();

        $admin = UserFactory::make()->admin()->active()->persist();
        [$ga, $user] = UserFactory::make(2)->user()->active()->persist();
        $disabled = UserFactory::make()->user()->active()->disabled()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$admin, $ga])->withGroupsUsersFor([$user, $disabled])->persist();

        $this->logInAs($admin);
        $this->deleteJson('/groups/' . $group->id . '.json');
        $this->assertResponseSuccess();

        // check email notification
        $this->assertEmailQueueIsEmpty();
    }

    public function testGroupsDeleteNotificationSuccess(): void
    {
        $this->setEmailNotificationSetting('send.group.delete', true);

        RoleFactory::make()->user()->persist();
        RoleFactory::make()->admin()->persist();

        $admin = UserFactory::make()->admin()->active()->persist();
        [$ga, $user] = UserFactory::make(2)->user()->active()->persist();
        $disabled = UserFactory::make()->user()->active()->disabled()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$admin, $ga])->withGroupsUsersFor([$user, $disabled])->persist();

        $this->logInAs($admin);
        $this->deleteJson('/groups/' . $group->id . '.json');
        $this->assertResponseSuccess();

        // email sent to group admin
        $this->assertEmailInBatchContains('deleted the group ', $ga->username);

        // email sent to regular members
        $this->assertEmailInBatchContains('deleted the group ', $user->username);

        // emails are not send to user that deleted the group
        $this->assertEmailWithRecipientIsInNotQueue($admin->username);

        // emails are not send to disabled user
        $this->assertEmailWithRecipientIsInNotQueue($disabled->username);
    }

    public function testGroupsDeleteNotificationSuccess_NoEmails(): void
    {
        $this->setEmailNotificationSetting('send.group.delete', true);

        RoleFactory::make()->user()->persist();
        RoleFactory::make()->admin()->persist();

        $admin = UserFactory::make()->admin()->active()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$admin])->persist();

        $this->logInAs($admin);
        $this->deleteJson('/groups/' . $group->id . '.json');
        $this->assertResponseSuccess();

        $this->assertEmailQueueIsEmpty();
    }
}
