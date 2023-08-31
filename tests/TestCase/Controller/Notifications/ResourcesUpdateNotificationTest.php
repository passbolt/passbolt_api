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

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class ResourcesUpdateNotificationTest extends AppIntegrationTestCase
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

    public function testResourcesUpdateNotification_NotificationEnabled(): void
    {
        $this->setEmailNotificationSetting('send.password.update', true);

        RoleFactory::make()->guest()->persist();
        [$user, $user2] = UserFactory::make(2)->user()->active()->persist();
        $disabled = UserFactory::make()->user()->disabled()->persist();
        ResourceTypeFactory::make()->default()->persist();
        $r = ResourceFactory::make()->withPermissionsFor([$user, $user2, $disabled])->persist();

        // Post updated data
        $this->logInAs($user);
        $this->putJson('/resources/' . $r->id . '.json', [
            'name' => 'R1 name updated',
            'username' => 'R1 username updated',
            'uri' => 'https://r1-updated.com',
            'description' => 'R1 description updated',
        ]);
        $this->assertSuccess();

        // check email notification
        $this->assertEmailInBatchContains('updated the password', $user2->username);
        $this->assertEmailInBatchContains('updated the password', $user->username);
        $this->assertEmailWithRecipientIsInNotQueue($disabled->username);
    }

    public function testResourcesUpdateNotification_NotificationDisabled_Metadata(): void
    {
        $this->setEmailNotificationSetting('send.password.update', false);

        RoleFactory::make()->guest()->persist();
        [$user, $user2] = UserFactory::make(2)->user()->active()->persist();
        $disabled = UserFactory::make()->user()->disabled()->persist();
        ResourceTypeFactory::make()->default()->persist();
        $r = ResourceFactory::make()->withPermissionsFor([$user, $user2, $disabled])->persist();

        // Post updated data
        $this->logInAs($user);
        $this->putJson('/resources/' . $r->id . '.json', [
            'name' => 'R1 name updated',
            'username' => 'R1 username updated',
            'uri' => 'https://r1-updated.com',
            'description' => 'R1 description updated',
        ]);
        $this->assertSuccess();

        // check email notification
        $this->assertEmailQueueIsEmpty();
    }
}
