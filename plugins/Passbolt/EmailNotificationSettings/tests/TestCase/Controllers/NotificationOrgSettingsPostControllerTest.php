<?php
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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\OrgSettings;

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

class NotificationOrgSettingsPostControllerTest extends AppIntegrationTestCase
{
    public function tearDown()
    {
        EmailNotificationSettings::flushCache();
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
        $this->authenticateAs('admin');
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
        $this->authenticateAs('ada');
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
        $this->authenticateAs('admin');
        $this->postJson('/settings/emails/notifications.json?api-version=v2', [$config => 'non_boolean_value']);
        $this->assertBadRequestError('The supplied email notification settings are not valid');
        $responseBody = $this->_responseJsonBody;
        $this->assertObjectHasAttribute($config, $responseBody);
        $this->assertEquals('Send comment add should be a boolean.', $responseBody->{$config}->boolean);
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
        $this->authenticateAs('admin');
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

        $this->authenticateAs('admin');
        $this->postJson('/settings/emails/notifications.json?api-version=v2', $cases);
        $this->assertResponseSuccess();

        foreach ($cases as $case => $expected) {
            // The returned response is dot delimited
            $this->assertEquals($expected, $this->_responseJsonBody->{$case});
        }
    }
}
