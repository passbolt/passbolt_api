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

use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class GroupsAddNotificationTest extends AppIntegrationTestCase
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

    public function testGroupsUsersAddNotificationSuccess(): void
    {
        $this->setEmailNotificationSetting('send.group.user.add', true);
        RoleFactory::make()->user()->persist();
        RoleFactory::make()->admin()->persist();

        $admin = UserFactory::make()->admin()->active()->persist();
        [$ga, $user] = UserFactory::make(2)->user()->active()->persist();
        $disabled = UserFactory::make()->user()->active()->disabled()->persist();

        $this->logInAs($admin);
        $this->postJson('/groups.json', [
            'Group' => ['name' => 'Temp Group'],
            'GroupUsers' => [
                ['GroupUser' => ['user_id' => $ga->id, 'is_admin' => true]],
                ['GroupUser' => ['user_id' => $user->id]],
                ['GroupUser' => ['user_id' => $disabled->id]],
            ],
        ]);
        $this->assertResponseCode(200);

        // check email notification
        $this->assertEmailQueueCount(2);
        $this->assertEmailInBatchContains('added you to the group Temp Group', $ga->username);
        $this->assertEmailInBatchContains('And as group manager you', $ga->username);
        $this->assertEmailInBatchContains('added you to the group Temp Group', $user->username);
        $this->assertEmailInBatchNotContains('And as group manager you', $user->username);
    }

    public function testGroupsUsersAddNotificationDisabled(): void
    {
        $this->setEmailNotificationSetting('send.group.user.add', false);
        RoleFactory::make()->user()->persist();
        RoleFactory::make()->admin()->persist();

        $admin = UserFactory::make()->admin()->active()->persist();
        [$ga, $user] = UserFactory::make(2)->user()->active()->persist();
        $disabled = UserFactory::make()->user()->active()->disabled()->persist();

        $this->logInAs($admin);
        $this->postJson('/groups.json', [
            'Group' => ['name' => 'Temp Group'],
            'GroupUsers' => [
                ['GroupUser' => ['user_id' => $ga->id, 'is_admin' => true]],
                ['GroupUser' => ['user_id' => $user->id]],
                ['GroupUser' => ['user_id' => $disabled->id]],
            ],
        ]);
        $this->assertResponseCode(200);

        // check email notification
        $this->assertEmailQueueIsEmpty();
    }
}
