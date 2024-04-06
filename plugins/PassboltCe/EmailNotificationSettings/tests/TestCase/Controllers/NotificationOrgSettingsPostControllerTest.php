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
 * @since         2.10.0
 */
namespace Passbolt\EmailNotificationSettings\Test\TestCase\Controllers;

use App\Notification\Email\EmailSubscriptionDispatcher;
use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class NotificationOrgSettingsPostControllerTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadNotificationSettings();
        (new EmailSubscriptionDispatcher())->collectSubscribedEmailRedactors();
    }

    public function tearDown(): void
    {
        $this->unloadNotificationSettings();
        parent::tearDown();
    }

    /**
     * @group notificationSettings
     * @group notificationOrgSettings
     * @group notificationOrgSettingsPost
     */
    public function testNotificationOrgSettingsPostControllerNotLoggedIn()
    {
        $this->postJson('/settings/emails/notifications.json?api-version=v2', ['send.comment.add' => false]);
        $this->assertResponseError('You need to login to access this location.');
    }

    /**
     * @group notificationSettings
     * @group notificationOrgSettings
     * @group notificationOrgSettingsPost
     */
    public function testNotificationOrgSettingsPostControllerNotJson()
    {
        $this->logInAsAdmin();
        $this->post('/settings/emails/notifications', ['send.comment.add' => false]);
        $this->assertResponseError('This is not a valid Ajax/Json request.');
    }

    /**
     * @group notificationSettings
     * @group notificationOrgSettings
     * @group notificationOrgSettingsPost
     */
    public function testNotificationOrgSettingsPostControllerNotAllowed()
    {
        $this->logInAsUser();
        $this->postJson('/settings/emails/notifications.json?api-version=v2', ['send.comment.add' => false]);
        $this->assertResponseError('You are not allowed to access this location.');
    }

    /**
     * @group notificationSettings
     * @group notificationOrgSettings
     * @group notificationOrgSettingsPost
     */
    public function testNotificationOrgSettingsPostControllerInvalidValue()
    {
        $config = 'send_comment_add';
        $this->logInAsAdmin();
        $this->postJson('/settings/emails/notifications.json?api-version=v2', [$config => 'non_boolean_value']);
        $this->assertBadRequestError('The supplied email notification settings are not valid');
        $responseBody = $this->_responseJsonBody;
        $this->assertObjectHasAttribute($config, $responseBody);
        $this->assertEquals('The send on comment added setting should be a boolean.', $responseBody->{$config}->boolean);
    }

    /**
     * @group notificationSettings
     * @group notificationOrgSettings
     * @group notificationOrgSettingsPost
     */
    public function testNotificationOrgSettingsPostControllerFailsWithoutCsrfToken()
    {
        $cases = [
            'show_comment' => false,
        ];
        $this->disableCsrfToken();
        $this->logInAsAdmin();
        $this->post('/settings/emails/notifications.json?api-version=v2', $cases);
        $this->assertResponseCode(403);
    }

    /**
     * @group notificationSettings
     * @group notificationOrgSettings
     * @group notificationOrgSettingsPost
     */
    public function testNotificationOrgSettingsPostControllerSuccess()
    {
        $cases = [
            'show_comment' => false,
            'show_description' => false,
            'show_secret' => false,
            'show_uri' => false,
            'show_username' => false,
            'send_comment_add' => false,
            'send_password_create' => false,
            'send_password_share' => false,
            'send_password_update' => false,
            'send_password_delete' => false,
            'send_user_create' => false,
            'send_user_recover' => false,
            'send_group_delete' => false,
            'send_group_user_add' => false,
            'send_group_user_delete' => false,
            'send_group_user_update' => false,
            'send_group_manager_update' => false,
        ];

        $this->logInAsAdmin();
        $this->postJson('/settings/emails/notifications.json?api-version=v2', $cases);
        $this->assertResponseSuccess();

        foreach ($cases as $case => $expected) {
            // The returned response is dot delimited
            $this->assertEquals($expected, $this->_responseJsonBody->{$case});
        }
    }
}
