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
use App\Utility\UuidFactory;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class GroupsAddNotificationTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;

    public $Groups;

    public $fixtures = [
        'app.Base/Groups', 'app.Base/Users', 'app.Base/GroupsUsers', 'app.Base/Profiles', 'app.Base/Roles',
          'app.Base/Gpgkeys',
    ];

    public function testGroupsUsersAddNotificationDisabled(): void
    {
        $this->setEmailNotificationSetting('send.group.user.add', false);

        $this->authenticateAs('admin');
        $this->postJson('/groups.json?api-version=v2', [
            'Group' => ['name' => 'Temp Group'],
            'GroupUsers' => [
                ['GroupUser' => ['user_id' => UuidFactory::uuid('user.id.ada'), 'is_admin' => 1]],
                ['GroupUser' => ['user_id' => UuidFactory::uuid('user.id.betty')]],
            ],
        ]);
        $this->assertResponseSuccess();

        // check email notification
        $this->assertEmailQueueIsEmpty();
    }

    public function testGroupsUsersAddNotificationSuccess(): void
    {
        $this->setEmailNotificationSetting('send.group.user.add', true);

        $this->authenticateAs('admin');
        $this->postJson('/groups.json?api-version=v2', [
            'Group' => ['name' => 'Temp Group'],
            'GroupUsers' => [
                ['GroupUser' => ['user_id' => UuidFactory::uuid('user.id.ada'), 'is_admin' => true]],
                ['GroupUser' => ['user_id' => UuidFactory::uuid('user.id.betty')]],
                ['GroupUser' => ['user_id' => UuidFactory::uuid('user.id.admin')]],
            ],
        ]);
        $this->assertResponseSuccess();

        // check email notification
        $this->assertEmailQueueCount(2);
        $this->assertEmailInBatchContains('Admin added you to the group Temp Group', 'ada@passbolt.com');
        $this->assertEmailInBatchContains('And as group manager you', 'ada@passbolt.com');
        $this->assertEmailInBatchContains('Admin added you to the group Temp Group', 'betty@passbolt.com');
        $this->assertEmailInBatchNotContains('And as group manager you', 'betty@passbolt.com');
    }
}
