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
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
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

    public function setUp(): void
    {
        parent::setUp();

        $this->emailQueue = TableRegistry::getTableLocator()
            ->get('EmailQueue', ['className' => EmailQueueTable::class]);

        $this->Users = TableRegistry::getTableLocator()->get('Users');

        EventManager::instance()
            ->on(new CoreNotificationSettingsDefinition());
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
            $uac = UserFactory::make()->admin()->persistedUAC();

            // Changing the settings to !default
            EmailNotificationSettings::save([
                $config => !$originalConfigSetting,
            ], $uac);

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

        $uac = UserFactory::make()->admin()->persistedUAC();
        $this->setEmailNotificationSettings($cases, $uac);
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
        RoleFactory::make()->guest()->persist();
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $testResourceId = ResourceFactory::make()->withPermissionsFor([$userA, $userB])->persist()->get('id');
        $shouldSend = EmailNotificationSettings::get('send.comment.add');
        $oldEmailQueueLength = $expectedEmailQueueLength = $this->emailQueue->find()->all()->count();
        EmailNotificationSettings::flushCache();
        $this->_addTestComment($userA, $testResourceId);
        $resourceSubscribers = $this->_getResourceSubscribers($testResourceId)->count();

        // The user making the comment doesn't get an email
        $emailRecipientCount = $resourceSubscribers - 1;
        if ($shouldSend) {
            $expectedEmailQueueLength = $oldEmailQueueLength + $emailRecipientCount;
        }

        $this->assertEquals($expectedEmailQueueLength, $this->emailQueue->find()->all()->count());
    }

    /**
     * @group notification
     * @group notificationSettings
     * @group notificationOrgSettings
     */
    public function testNotificationSettingTriggerGroupAdd()
    {
        $oldEmailQueueLength = $expectedEmailQueueLength = $this->emailQueue->find()->all()->count();
        $this->logInAsAdmin();
        $testGroupData = $this->_addTestGroup();
        $shouldSend = EmailNotificationSettings::get('send.group.user.add');
        $emailRecipientCount = count($testGroupData['GroupUsers']);
        if ($shouldSend) {
            $expectedEmailQueueLength = $oldEmailQueueLength + $emailRecipientCount;
        }

        $this->assertEquals($expectedEmailQueueLength, $this->emailQueue->find()->all()->count());
    }

    /**
     * @group notification
     * @group notificationSettings
     * @group notificationOrgSettings
     */
    public function testNotificationSettingTriggerGroupAddShouldNotSend()
    {
        $this->setEmailNotificationSetting('send.group.user.add', false);
        $oldEmailQueueLength = $this->emailQueue->find()->all()->count();
        $this->_addTestGroup();
        $this->assertEquals($oldEmailQueueLength, $this->emailQueue->find()->all()->count());
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
        /** @var \App\Model\Table\OrganizationSettingsTable $organizationSettings */
        $organizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $uac = UserFactory::make()->admin()->persistedUAC();
        $organizationSettings->createOrUpdateSetting(EmailNotificationSettings::NAMESPACE, $invalidJsonString, $uac);
        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('The Email Notification Settings configs are invalid');
        EmailNotificationSettings::get('send.comment.add');
    }

    /**
     * Add a test comment
     *
     * @param User $user entity
     * @param string $resourceId ResourceId where comment will be added
     * @return void
     */
    protected function _addTestComment($user, $resourceId)
    {
        $this->logInAs($user);
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
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $testGroupData = [
            'Group' => ['name' => 'New group name'],
            'GroupUsers' => [
                ['GroupUser' => ['user_id' => $userA->id, 'is_admin' => 1]],
                ['GroupUser' => ['user_id' => $userB->id]],
            ],
        ];

        $this->postJson('/groups.json?api-version=v2', $testGroupData);

        return $testGroupData;
    }

    /**
     * Get subscribers for a resource
     *
     * @param string $resourceId Resource Id
     * @return \Cake\Datasource\ResultSetInterface
     */
    protected function _getResourceSubscribers($resourceId)
    {
        $options = ['contain' => ['Roles'], 'filter' => ['has-access' => [$resourceId]]];

        return $this->Users->findIndex(Role::USER, $options)->all();
    }
}
