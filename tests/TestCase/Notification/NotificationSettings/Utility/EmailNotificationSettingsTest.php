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

namespace App\Test\TestCase\Notification\NotificationSettings\Utility;

use App\Model\Entity\Role;
use App\Notification\NotificationSettings\CoreNotificationSettingsDefinition;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Event\EventManager;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use EmailQueue\Model\Table\EmailQueueTable;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

class EmailNotificationSettingsTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;

    /**
     * @var \EmailQueue\Model\Table\EmailQueueTable emailQueue
     */
    protected $emailQueue;

    /**
     * @var \App\Model\Table\UsersTable Users
     */
    protected $Users;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Comments',
        'app.Base/Permissions', 'app.Base/Avatars', 'app.Base/Roles', 'app.Base/Profiles',
         'app.Base/Gpgkeys',
    ];

    public function setUp()
    {
        parent::setUp();

        $this->emailQueue = TableRegistry::getTableLocator()
            ->get('EmailQueue', ['className' => EmailQueueTable::class]);

        $this->Users = TableRegistry::getTableLocator()->get('Users');

        EventManager::instance()
            ->on(new CoreNotificationSettingsDefinition());
    }

    public function tearDown()
    {
        parent::tearDown();
        EmailNotificationSettings::flushCache();
    }

    /**
     * @group notification
     * @group notificationSettings
     * @group notificationOrgSettings
     */
    public function testNotificationSettingReturnsConfigSetting()
    {
        $cases = [
            'show.comment' => true,
            'show.description' => true,
            'show.secret' => true,
            'show.uri' => true,
            'show.username' => true,
            'send.comment.add' => true,
            'send.password.create' => true,
            'send.password.share' => true,
            'send.password.update' => true,
            'send.password.delete' => true,
            'send.user.create' => true,
            'send.user.recover' => true,
            'send.group.delete' => true,
            'send.group.user.add' => true,
            'send.group.user.delete' => true,
            'send.group.user.update' => true,
            'send.group.manager.update' => true,
        ];

        foreach ($cases as $config => $expected) {
            $originalConfigSetting = EmailNotificationSettings::get($config);
            $accessControl = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));

            // Changing the settings to !default
            EmailNotificationSettings::save([
                $config => !$originalConfigSetting,
            ], $accessControl);

            $updatedTriggerSetting = EmailNotificationSettings::get($config);
            $expectedConfigSetting = !$originalConfigSetting;
            $this->assertEquals($expectedConfigSetting, $updatedTriggerSetting, $config);
        }
    }

    /**
     * @group notification
     * @group notificationSettings
     * @group notificationOrgSettings
     */
    public function testNotificationSettingReturnsDBSetting()
    {
        $cases = [
            'send.comment.add' => false,
            'send.password.create' => true,
            'send.password.share' => false,
        ];

        $this->setEmailNotificationSettings($cases);
        foreach ($cases as $config => $expected) {
            $triggerSettingFromDb = EmailNotificationSettings::get($config);

            $this->assertEquals($expected, $triggerSettingFromDb, $config);
        }
    }

    /**
     * @group notification
     * @group notificationSettings
     * @group notificationOrgSettings
     */
    public function testNotificationSettingTriggerCommentAdd()
    {
        $testResourceId = UuidFactory::uuid('resource.id.bower');
        $shouldSend = EmailNotificationSettings::get('send.comment.add');
        $oldEmailQueueLength = $expectedEmailQueueLength = $this->emailQueue->find()->count();
        $this->_addTestComment($testResourceId);
        $resourceSubscribers = $this->_getResourceSubscribers($testResourceId)->count();

        // The user making the comment doesn't get an email
        $emailRecipientCount = $resourceSubscribers - 1;
        if ($shouldSend) {
            $expectedEmailQueueLength = $oldEmailQueueLength + $emailRecipientCount;
        }

        $this->assertEquals($expectedEmailQueueLength, $this->emailQueue->find()->count());
    }

    /**
     * @group notification
     * @group notificationSettings
     * @group notificationOrgSettings
     */
    public function testNotificationSettingTriggerGroupAdd()
    {
        $oldEmailQueueLength = $expectedEmailQueueLength = $this->emailQueue->find()->count();
        $this->authenticateAs('admin');
        $testGroupData = $this->_addTestGroup();
        $shouldSend = EmailNotificationSettings::get('send.group.user.add');
        $emailRecipientCount = count($testGroupData['GroupUsers']);
        if ($shouldSend) {
            $expectedEmailQueueLength = $oldEmailQueueLength + $emailRecipientCount;
        }

        $this->assertEquals($expectedEmailQueueLength, $this->emailQueue->find()->count());
    }

    /**
     * @group notification
     * @group notificationSettings
     * @group notificationOrgSettings
     */
    public function testNotificationSettingTriggerGroupAddShouldNotSend()
    {
        $this->setEmailNotificationSetting('send.group.user.add', false);
        $oldEmailQueueLength = $this->emailQueue->find()->count();
        $this->_addTestGroup();
        $this->assertEquals($oldEmailQueueLength, $this->emailQueue->find()->count());
    }

    /**
     * @group notification
     * @group notificationSettings
     * @group notificationOrgSettings
     */
    public function testNotificationSettingInvalidDbSettingsThrowsException()
    {
        $invalidJsonString = '{foo: ';
        Configure::write('passbolt.email.send.comment.add', true);

        // Using low level table insert as the utility checks for invalid json
        /** @var OrganizationSettingsTable $organizationSettings */
        $organizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $accessControl = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $organizationSettings->createOrUpdateSetting(EmailNotificationSettings::NAMESPACE, $invalidJsonString, $accessControl);
        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('The Email Notification Settings configs are invalid');
        EmailNotificationSettings::get('send.comment.add');
    }

    /**
     * Add a test comment
     *
     * @param string $resourceId ResourceId where comment will be added
     * @return void
     */
    protected function _addTestComment($resourceId)
    {
        $this->authenticateAs('ada');
        $commentContent = 'this is a test';

        $postData = [
            'content' => $commentContent,
        ];

        $this->postJson("/comments/resource/$resourceId.json?api-version=v2", $postData);
    }

    /**
     * Add a test group
     *
     * @return array Group data
     */
    protected function _addTestGroup()
    {
        $testGroupData = [
            'Group' => ['name' => 'New group name'],
            'GroupUsers' => [
                ['GroupUser' => ['user_id' => UuidFactory::uuid('user.id.ada'), 'is_admin' => 1]],
                ['GroupUser' => ['user_id' => UuidFactory::uuid('user.id.betty')]],
            ],
        ];

        $this->postJson('/groups.json?api-version=v2', $testGroupData);

        return $testGroupData;
    }

    /**
     * Get subscribers for a resource
     *
     * @param string $resourceId Resource Id
     * @return array List of users
     */
    protected function _getResourceSubscribers($resourceId)
    {
        $options = ['contain' => ['Roles'], 'filter' => ['has-access' => [$resourceId]]];

        return $this->Users->findIndex(Role::USER, $options)->all();
    }
}
