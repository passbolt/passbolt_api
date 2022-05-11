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

class CommentsAddNotificationTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;

    public $Comments;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Resources', 'app.Base/Comments', 'app.Base/Profiles',
        'app.Alt0/Permissions', 'app.Alt0/GroupsUsers', 'app.Base/Roles',
          'app.Base/Gpgkeys',
    ];

    public function testCommentsAddNotificationGroupSuccess()
    {
        $this->setEmailNotificationSetting('send.comment.add', true);

        $this->authenticateAs('dame');
        $postData = ['content' => 'this is a test'];
        $resourceId = UuidFactory::uuid('resource.id.docker');
        $this->postJson('/comments/resource/' . $resourceId . '.json?api-version=v2', $postData);
        $this->assertSuccess();

        // Every member of the group should get notification
        $this->assertEmailInBatchContains('commented on Docker', 'edith@passbolt.com');
        $this->assertEmailInBatchContains('this is a test', 'edith@passbolt.com');
        $this->assertEmailWithRecipientIsInQueue('frances@passbolt.com');
        $this->assertEmailWithRecipientIsInQueue('grace@passbolt.com');

        // except Dame
        $this->assertEmailWithRecipientIsInNotQueue('dame@passbolt.com');
    }

    public function testCommentsAddNotificationUserSuccess()
    {
        $this->setEmailNotificationSetting('send.comment.add', true);

        $this->authenticateAs('betty');
        $postData = ['content' => 'this is a test'];
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->postJson('/comments/resource/' . $resourceId . '.json?api-version=v2', $postData);
        $this->assertSuccess();

        // Every users with direct permissions should get notified
        $this->assertEmailWithRecipientIsInQueue('dame@passbolt.com');
        $this->assertEmailWithRecipientIsInQueue('ada@passbolt.com');
        $this->assertEmailWithRecipientIsInQueue('frances@passbolt.com');

        // except Dame
        $this->assertEmailWithRecipientIsInNotQueue('betty@passbolt.com');
    }

    public function testCommentsAddNotificationDoNotShowContent()
    {
        $this->setEmailNotificationSetting('show.comment', false);

        $this->authenticateAs('betty');
        $postData = ['content' => 'this is a test'];
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->postJson('/comments/resource/' . $resourceId . '.json?api-version=v2', $postData);
        $this->assertSuccess();

        // Every users with direct permissions should get notified
        $this->assertEmailWithRecipientIsInQueue('dame@passbolt.com');
        $this->assertEmailInBatchNotContains('this is a test', 'dame@passbolt.com');
    }

    public function testCommentsAddNotificationDisabled()
    {
        $this->setEmailNotificationSetting('send.comment.add', false);

        $this->authenticateAs('betty');
        $postData = ['content' => 'this is a test'];
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->postJson('/comments/resource/' . $resourceId . '.json?api-version=v2', $postData);
        $this->assertSuccess();

        // Nobody should get notifications
        $this->assertEmailQueueIsEmpty();
    }
}
