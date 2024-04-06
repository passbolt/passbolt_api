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
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class ResourcesAddNotificationTest extends AppIntegrationTestCase
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

    public function testResourcesAddNotification_NotificationEnabled(): void
    {
        $this->setEmailNotificationSetting('send.password.create', true);

        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);

        ResourceTypeFactory::make()->default()->persist();
        $data = $this->getDummyResourcesPostData([
            'name' => '新的專用資源名稱',
            'username' => 'username@domain.com',
            'uri' => 'https://www.域.com',
            'description' => '新的資源描述',
        ]);

        $this->postJson('/resources.json', $data);
        $this->assertSuccess();

        // check email notification
        $this->assertEmailInBatchContains('You have saved a new password', $user->username);
    }

    public function testResourcesAddNotification_NotificationDisabled(): void
    {
        $this->setEmailNotificationSetting('send.password.create', false);

        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);

        ResourceTypeFactory::make()->default()->persist();
        $data = $this->getDummyResourcesPostData([
            'name' => '新的專用資源名稱',
            'username' => 'username@domain.com',
            'uri' => 'https://www.域.com',
            'description' => '新的資源描述',
        ]);

        $this->postJson('/resources.json', $data);
        $this->assertSuccess();

        // check email notification
        $this->assertEmailQueueIsEmpty();
    }
}
