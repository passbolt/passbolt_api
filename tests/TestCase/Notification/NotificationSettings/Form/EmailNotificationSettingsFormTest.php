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

namespace App\Test\TestCase\Notification\NotificationSettings\Form;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use Cake\Event\EventDispatcherTrait;
use Passbolt\EmailNotificationSettings\Form\EmailNotificationSettingsForm;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

class EmailNotificationSettingsFormTest extends AppTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EventDispatcherTrait;
    use FormatValidationTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadPlugins(['Passbolt/EmailNotificationSettings' => []]);
        $this->loadNotificationSettings();
    }

    public function testNotificationSettingsFormFieldShowComment()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'show_comment',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormFieldShowDescription()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'show_description',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormFieldShowSecret()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'show_secret',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormFieldShowUri()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'show_uri',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormFieldShowUsername()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'show_username',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormFieldSendCommentAdd()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'send_comment_add',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormFieldSendPasswordCreate()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'send_password_create',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormFieldSendPasswordShare()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'send_password_share',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormFieldSendPasswordUpdate()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'send_password_update',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormFieldSendPasswordDelete()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'send_password_delete',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormFieldSendUserCreate()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'send_user_create',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormFieldSendUserRecover()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'send_user_recover',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormFieldSendGroupDelete()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'send_group_delete',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormFieldSendGroupUserAdd()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'send_group_user_add',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormFieldSendGroupUserDelete()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'send_group_user_delete',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormFieldSendGroupUserUpdate()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'send_group_user_update',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormFieldSendGroupManagerUpdate()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'send_group_manager_update',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormFieldSendAdminUserSetupCompleted()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'send_admin_user_setup_completed',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormFieldSendAdminUserRecoverAbort()
    {
        $testCases = [
            'boolean' => $this->getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            EmailNotificationSettingsForm::class,
            'send_admin_user_recover_abort',
            self::getDummyData(),
            $testCases
        );
    }

    public function testNotificationSettingsFormIgnoresInvalidKeys()
    {
        $validKeys = static::getDummyData();
        $invalidKeys = [
            'invalid_key_1' => false,
            'invalid_key_2' => false,
            'invalid_key_3' => false,

        ];
        $testCase = array_merge($validKeys, $invalidKeys);
        EmailNotificationSettings::get();
        $actual = EmailNotificationSettingsForm::stripInvalidKeys($testCase);
        $this->assertEquals($validKeys, $actual);
    }

    public function testNotificationSettingsFormFormatKeys()
    {
        $testCase = static::getDummyData();
        $expected = [];
        foreach ($testCase as $prop => $propVal) {
            $key = str_replace('_', '.', $prop);
            $expected[$key] = $propVal;
        }

        $actual = EmailNotificationSettingsForm::formatFormDataToOrgSettings($testCase);
        $this->assertEquals($actual, $expected);
    }

    /**
     * Get dummy data.
     *
     * @param array|null $data Custom data that will be merged with the default content.
     * @return array Merged data
     */
    public static function getDummyData(?array $data = []): array
    {
        $default = [
            'show_comment' => true,
            'show_description' => true,
            'show_secret' => true,
            'show_uri' => true,
            'show_username' => true,
            'send_comment_add' => true,
            'send_password_create' => true,
            'send_password_share' => true,
            'send_password_update' => true,
            'send_password_delete' => true,
            'send_user_create' => true,
            'send_user_recover' => true,
            'send_group_delete' => true,
            'send_group_user_add' => true,
            'send_group_user_delete' => true,
            'send_group_user_update' => true,
            'send_group_manager_update' => true,
            'send_admin_user_setup_completed' => true,
            'send_admin_user_recover_abort' => true,
        ];

        return array_merge($default, $data);
    }
}
