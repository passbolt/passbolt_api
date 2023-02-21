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

use App\Test\Factory\CommentFactory;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class CommentsAddNotificationTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;

    public $Comments;

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

    public function testCommentsAddNotificationGroupSuccess()
    {
        RoleFactory::make()->guest()->persist();
        [$u0, $u1, $u2, $u4] = UserFactory::make(4)->user()->active()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u0])->withGroupsUsersFor([$u1, $u2])->persist();
        $resourceId = ResourceFactory::make()->withPermissionsFor([$g1])->persist()->id;

        $this->setEmailNotificationSetting('send.comment.add', true);
        $this->setEmailNotificationSetting('show.comment', true);

        $this->loginAs($u0);
        $postData = ['content' => 'this is a test'];
        $this->postJson('/comments/resource/' . $resourceId . '.json', $postData);
        $this->assertSuccess();

        $this->assertEquals(CommentFactory::count(), 1);
        $this->assertEquals(EmailQueueFactory::count(), 2);

        // Every member of the group should get notification
        $this->assertEmailInBatchContains('commented on', $u1->username);
        $this->assertEmailInBatchContains('this is a test', $u1->username);
        $this->assertEmailWithRecipientIsInQueue($u1->username);
        $this->assertEmailWithRecipientIsInQueue($u2->username);

        // except sender and of course user without permission
        $this->assertEmailWithRecipientIsInNotQueue($u0->username);
        $this->assertEmailWithRecipientIsInNotQueue($u4->username);
    }

    public function testCommentsAddNotificationUserSuccess()
    {
        RoleFactory::make()->guest()->persist();
        [$u0, $u1, $u2] = UserFactory::make(3)->user()->active()->persist();
        $resourceId = ResourceFactory::make()->withPermissionsFor([$u0, $u1])->persist()->id;

        $this->setEmailNotificationSetting('send.comment.add', true);
        $this->setEmailNotificationSetting('show.comment', true);

        $this->loginAs($u0);
        $postData = ['content' => 'this is a test'];
        $this->postJson('/comments/resource/' . $resourceId . '.json', $postData);
        $this->assertSuccess();

        $this->assertEquals(CommentFactory::count(), 1);
        $this->assertEquals(EmailQueueFactory::count(), 1);

        // Every user should get notification
        $this->assertEmailInBatchContains('commented on', $u1->username);
        $this->assertEmailInBatchContains('this is a test', $u1->username);
        $this->assertEmailWithRecipientIsInQueue($u1->username);

        // except sender and user without permissions
        $this->assertEmailWithRecipientIsInNotQueue($u0->username);
        $this->assertEmailWithRecipientIsInNotQueue($u2->username);
    }

    public function testCommentsAddNotificationDoNotShowContent()
    {
        RoleFactory::make()->guest()->persist();
        [$u0, $u1] = UserFactory::make(2)->user()->active()->persist();
        $resourceId = ResourceFactory::make()->withPermissionsFor([$u0, $u1])->persist()->id;

        $this->setEmailNotificationSetting('send.comment.add', true);
        $this->setEmailNotificationSetting('show.comment', false);

        $this->loginAs($u0);
        $postData = ['content' => 'this is a test'];
        $this->postJson('/comments/resource/' . $resourceId . '.json', $postData);
        $this->assertSuccess();

        $this->assertEquals(CommentFactory::count(), 1);
        $this->assertEquals(EmailQueueFactory::count(), 1);
        $this->assertEmailInBatchNotContains('this is a test', $u1->username);
    }

    public function testCommentsAddNotificationDisabled()
    {
        RoleFactory::make()->guest()->persist();
        [$u0, $u1] = UserFactory::make(2)->user()->active()->persist();
        $resourceId = ResourceFactory::make()->withPermissionsFor([$u0, $u1])->persist()->id;

        $this->setEmailNotificationSetting('send.comment.add', false);

        $this->loginAs($u0);
        $postData = ['content' => 'this is a test'];
        $this->postJson('/comments/resource/' . $resourceId . '.json', $postData);
        $this->assertSuccess();

        // Nobody should get notifications
        $this->assertEmailQueueIsEmpty();
    }
}
