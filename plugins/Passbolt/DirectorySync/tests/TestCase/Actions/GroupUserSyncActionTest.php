<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Actions;

use App\Notification\Email\EmailSubscriptionDispatcher;
use App\Notification\Email\Redactor\CoreEmailRedactorPool;
use App\Shell\AppShellBootstrap;
use App\Utility\UuidFactory;
use Cake\Event\EventManager;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Passbolt\DirectorySync\Actions\GroupSyncAction;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertDirectoryRelationsTrait;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertGroupsTrait;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertGroupUsersTrait;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class GroupUserSyncActionTest extends DirectorySyncIntegrationTestCase
{
    use AssertDirectoryRelationsTrait;
    use AssertGroupsTrait;
    use AssertGroupUsersTrait;
    use EmailNotificationSettingsTestTrait;

    public function setUp()
    {
        parent::setUp();
        $this->initAction();
        $this->loadNotificationSettings();
        $this->setEmailNotificationSetting('send.group.user.add', true);
        EventManager::instance()->on(new CoreEmailRedactorPool());
        (new EmailSubscriptionDispatcher())->collectSubscribedEmailRedactors();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->unloadNotificationSettings();
    }

    /**
     * Init the action
     *
     * @throws \Exception
     * @return void
     */
    public function initAction()
    {
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
    }

    /**
     * Scenario: A group was deleted but no group users were ever created
     * Expected result: do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_Case2_Null_Null_Null_Ok_Any()
    {
        $this->mockDirectoryEntryGroup('marketing');
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $this->assertNoReportsForModel($reports, Alias::MODEL_GROUPS_USERS);
        $this->assertGroupNotExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertDirectoryRelationEmpty();
    }

    /**
     * Scenario: A group was deleted and some groupUsers were already synced, but have been removed at passbolt end
     * Expected result: remove directory relation
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_Case3_Null_Null_Ok_Null_Any()
    {
        $relation = $this->mockDirectoryRelationGroupUser('accounting', 'edith');
        $reports = $this->action->execute();
        $this->assertReportEmpty($reports);
        $this->assertGroupExist(UuidFactory::uuid('group.id.accounting'), ['deleted' => false]);
        $this->assertGroupUserNotExist(null, ['group_id' => UuidFactory::uuid('group.id.accounting'), 'user_id' => UuidFactory::uuid('user.id.edith')]);
        $this->assertDirectoryRelationNotExist($relation->id);
    }

    /**
     * Scenario: a group was deleted in ldap and its group users were synced
     * Expected result: remove group user and entry, send report
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_Case4_Null_Null_Ok_Ok_Any()
    {
        $this->mockDirectoryEntryGroup('freelancer', null, null, null, null, UuidFactory::uuid('group.id.freelancer'));
        $relation = $this->mockDirectoryRelationGroupUser('freelancer', 'frances');
        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_DELETE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SUCCESS,
            'type' => Alias::MODEL_GROUPS,
        ];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertNoReportsForModel($reports, Alias::MODEL_GROUPS_USERS);
        $this->assertGroupNotExist(UuidFactory::uuid('group.id.freelancer'), ['deleted' => false]);
        $this->assertGroupUserNotExist(null, ['group_id' => UuidFactory::uuid('group.id.freelancer'), 'user_id' => UuidFactory::uuid('user.id.frances')]);
        $this->assertDirectoryRelationNotExist($relation->id);
    }

    /**
     * Scenario: a groupUser was deleted in ldap who is the sole manager of the group
     * Expected result: do nothing, send error report
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_Case5_Ok_Null_Ok_NotDeletable_Any()
    {
        $this->mockDirectoryEntryGroup('accounting', null, null, null, null, UuidFactory::uuid('group.id.accounting'));
        $userEntry = $this->mockDirectoryEntryUser(['fname' => 'ada', 'lname' => 'lovelace', 'foreign_key' => UuidFactory::uuid('user.id.ada')]);
        $relation = $this->mockDirectoryRelationGroupUser('accounting', 'ada');
        $userData = $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $groupData = $this->mockDirectoryGroupData('accounting');

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_DELETE,
            'model' => Alias::MODEL_GROUPS_USERS,
            'status' => Alias::STATUS_ERROR,
            'type' => 'SyncError',
        ];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertGroupExist(UuidFactory::uuid('group.id.accounting'), ['deleted' => false]);
        $this->assertGroupUserExist($relation->id);
        $this->assertDirectoryRelationExist($relation->id);
    }

    /**
     * Scenario: a group has been created without group users
     * Expected result: create group and add default group user as group manager
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_Case6_Ok_Null_Null_Null_Any()
    {
        $this->mockDirectoryGroupData('newgroup');
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SUCCESS,
            'type' => Alias::MODEL_GROUPS,
        ];
        $this->assertReport($reports[0], $expectedReport);
        $groupCreated = $this->assertGroupExist(null, ['name' => 'newgroup', 'deleted' => false]);

        $defaultGroupAdmin = $this->directoryOrgSettings->getDefaultGroupAdminUser();
        $defaultGroupAdmin = $this->Users->findByUsername($defaultGroupAdmin)->first();

        $groupUser = $this->assertGroupUserExist(null, ['group_id' => $groupCreated->id, 'user_id' => $defaultGroupAdmin->id]);
        $this->assertDirectoryRelationNotExist($groupUser->id);
    }

    /**
     * Scenario: a group has been created without group users in ldap, and already contains a matching group and group users in passbolt
     * Expected result: do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_Case7_Ok_Null_Null_Ok_Any()
    {
        $groupData = $this->mockDirectoryGroupData('newgroup');
        $this->mockDirectoryEntryUser(['fname' => 'ada', 'lname' => 'ada', 'foreign_key' => UuidFactory::uuid('user.id.ada')]);

        $reports = $this->action->execute();

        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SUCCESS,
        ];
        $this->assertReport($reports[0], $expectedReport);
        $groupCreated = $this->assertGroupExist(null, ['name' => 'newgroup', 'deleted' => false]);

        $defaultGroupAdmin = $this->directoryOrgSettings->getDefaultGroupAdminUser();
        $defaultGroupAdmin = $this->Users->findByUsername($defaultGroupAdmin)->first();

        $groupUser = $this->assertGroupUserExist(null, ['group_id' => $groupCreated->id, 'user_id' => $defaultGroupAdmin->id]);
        $this->assertDirectoryRelationNotExist($groupUser->id);
    }

    /**
     * Scenario: a group has group users removed in ldap but these group users were removed in passbolt
     * Expected result: remove directory relation
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_Case8_Ok_Null_Ok_Null_Any()
    {
        $this->mockDirectoryEntryGroup('freelancer', null, null, null, null, UuidFactory::uuid('group.id.freelancer'));
        $relation = $this->mockDirectoryRelationGroupUser('freelancer', 'ada');
        $this->mockDirectoryGroupData('freelancer');
        $this->assertGroupUserNotExist(null, ['group_id' => UuidFactory::uuid('group.id.freelancer'), 'user_id' => UuidFactory::uuid('user.id.ada')]);
        $reports = $this->action->execute();
        $this->assertEmpty($reports);
        $this->assertGroupExist(UuidFactory::uuid('group.id.freelancer'), ['deleted' => false]);
        $this->assertGroupUserNotExist(null, ['group_id' => UuidFactory::uuid('group.id.freelancer'), 'user_id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertDirectoryRelationNotExist($relation->id);
    }

    /**
     * Scenario: a groupuser that was already synced has been removed in ldap
     * Expected result: remove group user in passbolt
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_Case9_Ok_Null_Ok_Ok_Ok()
    {
        $this->mockDirectoryEntryGroup('accounting', null, null, null, null, UuidFactory::uuid('group.id.accounting'));
        $userEntry = $this->mockDirectoryEntryUser(['fname' => 'betty', 'lname' => 'betty', 'foreign_key' => UuidFactory::uuid('user.id.betty')]);
        $relation = $this->mockDirectoryRelationGroupUser('accounting', 'betty');
        $userData = $this->mockDirectoryUserData('betty', 'betty', 'betty@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $groupData = $this->mockDirectoryGroupData('accounting');

        $reports = $this->action->execute();

        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_DELETE,
            'model' => Alias::MODEL_GROUPS_USERS,
            'status' => Alias::STATUS_SUCCESS,
            'type' => Alias::MODEL_GROUPS,
        ];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertGroupExist(UuidFactory::uuid('group.id.accounting'), ['deleted' => false]);

        $this->assertGroupUserNotExist(null, ['group_id' => UuidFactory::uuid('group.id.accounting'), 'user_id' => UuidFactory::uuid('user.id.betty')]);
        $this->assertDirectoryRelationNotExist($relation->id);
    }

    /**
     * Scenario: a group has a groupUser in ldap which has not been synced yet, but the corresponding user doesn't exist, has been deleted or is unactive.
     * Expected result: do nothing, send ignore report
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_Case10_Ok_Ok_Null_Null_NotOk()
    {
        // Ruth is a inactive user.
        $userEntry = $this->mockDirectoryEntryUser(['fname' => 'ruth', 'lname' => 'ruth', 'foreign_key' => UuidFactory::uuid('user.id.ruth')]);
        $this->mockDirectoryUserData('ruth', 'ruth', 'ruth@passbolt.com');
        $this->mockDirectoryGroupData('newgroup', [
            'group_users' => [
                $userEntry->directory_name,
            ],
        ]);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $this->assertEquals(count($reports), 2);
        $expectedGroupReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SUCCESS,
            'type' => Alias::MODEL_GROUPS,
        ];
        $this->assertReport($reports[0], $expectedGroupReport);

        $expectedUserGroupReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS_USERS,
            'status' => Alias::STATUS_IGNORE,
            'type' => Alias::MODEL_GROUPS,
            'message' => 'The user ruth@passbolt.com could not be added to the group newgroup because he has not yet activated his account.',
        ];
        $this->assertReport($reports[1], $expectedUserGroupReport);

        $groupCreated = $this->assertGroupExist(null, ['name' => 'newgroup', 'deleted' => false]);

        $defaultGroupAdmin = $this->directoryOrgSettings->getDefaultGroupAdminUser();
        $defaultGroupAdmin = $this->Users->findByUsername($defaultGroupAdmin)->first();

        $groupUserAda = $this->assertGroupUserExist(null, ['group_id' => $groupCreated->id, 'user_id' => $defaultGroupAdmin->id]);
        $this->assertGroupUserNotExist(null, ['group_id' => $groupCreated->id, 'user_id' => UuidFactory::uuid('user.id.ruth')]);
        $this->assertDirectoryRelationEmpty();
    }

    /**
     * Scenario: a group has two groupUsers in ldap which has not been synced yet, one of the corresponding user doesn't exist, has been deleted or is unactive.
     * Expected result: add first user, send ignore report for second one
     *
     * This is
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_Case10_Ok_Ok_Null_Null_NotOk_WithTwoRows()
    {
        $userEntryValid = $this->mockDirectoryEntryUser(['fname' => 'frances', 'lname' => 'frances', 'foreign_key' => UuidFactory::uuid('user.id.frances')]);
        // Ruth is a inactive user.
        $userEntryInactive = $this->mockDirectoryEntryUser(['fname' => 'ruth', 'lname' => 'ruth', 'foreign_key' => UuidFactory::uuid('user.id.ruth')]);
        $this->mockDirectoryUserData('ruth', 'ruth', 'ruth@passbolt.com');
        $this->mockDirectoryUserData('frances', 'frances', 'frances@passbolt.com');
        $this->mockDirectoryGroupData('newgroup', [
            'group_users' => [
                $userEntryValid->directory_name,
                $userEntryInactive->directory_name,
            ],
        ]);
        $reports = $this->action->execute();

        $this->assertReportNotEmpty($reports);
        $this->assertEquals(count($reports), 3);

        $expectedGroupReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SUCCESS,
            'type' => Alias::MODEL_GROUPS,
            'message' => 'The group newgroup was successfully added to passbolt.',
        ];
        $this->assertReport($reports[0], $expectedGroupReport);

        $expectedUserGroupReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS_USERS,
            'status' => Alias::STATUS_SUCCESS,
            'type' => Alias::MODEL_GROUPS,
            'message' => 'The user frances@passbolt.com was successfully added to the group newgroup.',
        ];
        $this->assertReport($reports[1], $expectedUserGroupReport);

        $expectedUserGroupReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS_USERS,
            'status' => Alias::STATUS_IGNORE,
            'type' => Alias::MODEL_GROUPS,
            'message' => 'The user ruth@passbolt.com could not be added to the group newgroup because he has not yet activated his account.',
        ];
        $this->assertReport($reports[2], $expectedUserGroupReport);

        $groupCreated = $this->assertGroupExist(null, ['name' => 'newgroup', 'deleted' => false]);

        $defaultGroupAdmin = $this->directoryOrgSettings->getDefaultGroupAdminUser();
        $defaultGroupAdmin = $this->Users->findByUsername($defaultGroupAdmin)->first();

        $this->assertGroupUserExist(null, ['group_id' => $groupCreated->id, 'user_id' => $defaultGroupAdmin->id]);
        $this->assertGroupUserNotExist(null, ['group_id' => $groupCreated->id, 'user_id' => UuidFactory::uuid('user.id.ruth')]);
        $this->assertDirectoryRelationNotEmpty();
    }

    /**
     * Scenario: a groupUser has been added to a group without passwords in ldap, not yet added in passbolt
     * Expected result: check if group already has access to passwords. If not, add the groupUser. If yes, send notification to groupAdmins to add user.
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_Case11_Ok_Ok_Null_Null_Ok()
    {
        $userEntry = $this->mockDirectoryEntryUser(['fname' => 'frances', 'lname' => 'frances', 'foreign_key' => UuidFactory::uuid('user.id.frances')]);
        $this->mockDirectoryUserData('frances', 'frances', 'frances@passbolt.com');
        $groupData = $this->mockDirectoryGroupData('newgroup', [
            'group_users' => [
                $userEntry->directory_name,
            ],
        ]);

        $reports = $this->action->execute();
        $this->assertNotEmpty($reports);
        $this->assertEquals(count($reports), 2);
        $expectedGroupReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SUCCESS,
            'type' => Alias::MODEL_GROUPS,
        ];
        $this->assertReport($reports[0], $expectedGroupReport);

        $expectedUserGroupReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS_USERS,
            'status' => Alias::STATUS_SUCCESS,
            'type' => Alias::MODEL_GROUPS,
        ];
        $this->assertReport($reports[1], $expectedUserGroupReport);
        $groupCreated = $this->assertGroupExist(null, ['name' => 'newgroup', 'deleted' => false]);

        $defaultGroupAdmin = $this->directoryOrgSettings->getDefaultGroupAdminUser();
        $defaultGroupAdmin = $this->Users->findByUsername($defaultGroupAdmin)->first();

        $groupUserAda = $this->assertGroupUserExist(null, ['group_id' => $groupCreated->id, 'user_id' => $defaultGroupAdmin->id]);
        $groupUserFrances = $this->assertGroupUserExist(null, ['group_id' => $groupCreated->id, 'user_id' => UuidFactory::uuid('user.id.frances')]);
        $this->assertDirectoryRelationNotExist($groupUserAda->id); // Directory relation should not be added for default user.
        $this->assertDirectoryRelationExist($groupUserFrances->id);
    }

    /**
     * Scenario: a groupUser has been added to a group without passwords in ldap, not yet added in passbolt
     * Expected result: check if group already has access to passwords. If not, add the groupUser. If yes, send notification to groupAdmins to add user.
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_Case11a_Ok_Ok_Null_Null_Ok_Edited_Group_No_Passwords()
    {
        $defaultGroupAdmin = 'edith@passbolt.com';
        $this->setDefaultGroupAdminUser($defaultGroupAdmin);
        $defaultGroupAdmin = $this->Users->findByUsername($defaultGroupAdmin)->first();
        $this->initAction();

        $userEntry = $this->mockDirectoryEntryUser(['fname' => 'frances', 'lname' => 'frances', 'foreign_key' => UuidFactory::uuid('user.id.frances')]);
        $groupEntry = $this->mockDirectoryEntryGroup('marketing');
        $this->mockDirectoryUserData('frances', 'frances', 'frances@passbolt.com');
        $groupData = $this->mockDirectoryGroupData('marketing', [
            'group_users' => [
                $userEntry->directory_name,
            ],
        ]);

        $reports = $this->action->execute();

        $this->assertEquals(count($reports), 1);
        $expectedUserGroupReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS_USERS,
            'status' => Alias::STATUS_SUCCESS,
            'type' => Alias::MODEL_GROUPS,
        ];
        $this->assertReport($reports[0], $expectedUserGroupReport);

        // Group user for default admin should not exist.
        $this->assertGroupUserNotExist(null, ['group_id' => UuidFactory::uuid('group.id.marketing'), 'user_id' => $defaultGroupAdmin->id]);

        // Frances should be in group users and directoryRelations.
        $groupUserFrances = $this->assertGroupUserExist(null, ['group_id' => UuidFactory::uuid('group.id.marketing'), 'user_id' => UuidFactory::uuid('user.id.frances')]);
        $this->assertDirectoryRelationExist($groupUserFrances->id);
    }

    /**
     * Scenario: a groupUser has been added to a group without passwords in ldap, not yet added in passbolt
     * Expected result: check if group already has access to passwords. If not, add the groupUser. If yes, send notification to groupAdmins to add user.
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_Case11a_Ok_Ok_Null_Null_Ok_Edited_Group_No_Passwords_UpdateDisabled()
    {
        $this->disableSyncOperation('groups', 'update');
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);

        $userEntry = $this->mockDirectoryEntryUser(['fname' => 'frances', 'lname' => 'frances', 'foreign_key' => UuidFactory::uuid('user.id.frances')]);
        $this->mockDirectoryEntryGroup('marketing');
        $this->mockDirectoryGroupData('marketing', [
            'group_users' => [
                $userEntry->directory_name,
            ],
        ]);

        $reports = $this->action->execute();
        $this->assertReportEmpty($reports);
        // Frances should be in group users and directoryRelations.
        $this->assertGroupUserNotExist(null, ['group_id' => UuidFactory::uuid('group.id.marketing'), 'user_id' => UuidFactory::uuid('user.id.frances')]);
    }

    /**
     * Scenario: a groupUser has been added to a group with passwords in ldap, not yet added in passbolt
     * Expected result: Send email notification to groupAdmins to add user.
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_Case11b_Ok_Ok_Null_Null_Ok_Edited_Group_With_Passwords()
    {
        // Init AppShellBootstrap to handle email notifications.
        AppShellBootstrap::init();

        $defaultGroupAdmin = 'edith@passbolt.com';
        $this->setDefaultGroupAdminUser($defaultGroupAdmin);
        $defaultGroupAdmin = $this->Users->findByUsername($defaultGroupAdmin)->first();
        $this->initAction();

        $userEntry = $this->mockDirectoryEntryUser(['fname' => 'frances', 'lname' => 'frances', 'foreign_key' => UuidFactory::uuid('user.id.frances')]);
        $this->mockDirectoryEntryGroup('accounting');
        $this->mockDirectoryUserData('frances', 'frances', 'frances@passbolt.com');
        $this->mockDirectoryGroupData('accounting', [
            'group_users' => [
                $userEntry->directory_name,
            ],
        ]);

        $reports = $this->action->execute();

        $this->assertEquals(count($reports), 1);
        $expectedUserGroupReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS_USERS,
            'status' => Alias::STATUS_SUCCESS,
            'type' => Alias::MODEL_USERS,
        ];
        $this->assertReport($reports[0], $expectedUserGroupReport);

        // Group user for default admin should not exist.
        $this->assertGroupUserNotExist(null, ['group_id' => UuidFactory::uuid('group.id.accounting'), 'user_id' => $defaultGroupAdmin->id]);

        // Frances should be in group users and directoryRelations.
        $this->assertGroupUserNotExist(null, ['group_id' => UuidFactory::uuid('group.id.marketing'), 'user_id' => UuidFactory::uuid('user.id.frances')]);
        $this->assertDirectoryRelationEmpty();

        // Assert email notification
        $this->get('/seleniumtests/showLastEmail/ada@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('requested you to add members to a group');
        $this->assertResponseContains('Frances Allen (Member)');
    }

    /**
     * PB-1431: Fix: LDAP: a notification should not be sent to a group administrator requesting him to add a non-active user.
     *
     * Scenario: a non active groupUser has been added to a ldap group, not yet added in Passbolt.
     * But the passbolt group has access to shared passwords.
     * Expected result: No email notification should be sent. An ignore report should be broadcasted.
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_PB1431()
    {
        // Init AppShellBootstrap to handle email notifications.
        AppShellBootstrap::init();

        $defaultGroupAdmin = 'edith@passbolt.com';
        $this->setDefaultGroupAdminUser($defaultGroupAdmin);
        $defaultGroupAdmin = $this->Users->findByUsername($defaultGroupAdmin)->first();
        $this->initAction();

        $userEntry = $this->mockDirectoryEntryUser(['fname' => 'ruth', 'lname' => 'ruth', 'foreign_key' => UuidFactory::uuid('user.id.ruth')]);
        $this->mockDirectoryEntryGroup('accounting');
        $this->mockDirectoryUserData('ruth', 'ruth', 'ruth@passbolt.com');
        $this->mockDirectoryGroupData('accounting', [
            'group_users' => [
                $userEntry->directory_name,
            ],
        ]);

        $reports = $this->action->execute();

        $this->assertEquals(count($reports), 1);
        $expectedUserGroupReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS_USERS,
            'status' => Alias::STATUS_IGNORE,
            'type' => Alias::MODEL_GROUPS,
            'message' => 'The user ruth@passbolt.com could not be added to the group Accounting because he has not yet activated his account.',
        ];
        $this->assertReport($reports[0], $expectedUserGroupReport);

        // Group user for default admin should not exist.
        $this->assertGroupUserNotExist(null, ['group_id' => UuidFactory::uuid('group.id.accounting'), 'user_id' => $defaultGroupAdmin->id]);

        // Frances should not be in group users and directoryRelations.
        $this->assertGroupUserNotExist(null, ['group_id' => UuidFactory::uuid('group.id.marketing'), 'user_id' => UuidFactory::uuid('user.id.ruth')]);
        $this->assertDirectoryRelationEmpty();

        // No email notification should have been sent to the group manager.
        $this->get('/seleniumtests/showLastEmail/ada@passbolt.com');
        $this->assertResponseCode(500);
    }

    /**
     * Scenario: A groupUser has been added to a group in ldap and already exist in passbolt
     * Expected result: do nothing. ignore report.
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_Case12_Ok_Ok_Null_CreatedBefore_Ok()
    {
        $userEntry = $this->mockDirectoryEntryUser(['fname' => 'grace', 'lname' => 'grace', 'foreign_key' => UuidFactory::uuid('user.id.grace')]);
        $groupEntry = $this->mockDirectoryEntryGroup('freelancer');
        $this->mockDirectoryUserData('grace', 'grace', 'grace@passbolt.com');
        $groupData = $this->mockDirectoryGroupData('freelancer', [
            'group_users' => [
                $userEntry->directory_name,
            ],
        ]);

        // Define creation date.
        $dateGroupModification = $groupData['directory_modified'];
        $dateBeforeGroupModification = $dateGroupModification->subDays(1);

        // Update corresponding GroupUser so it is created before.
        $groupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        $groupsUsers->getConnection()->execute("UPDATE {$groupsUsers->getTable()} SET created = ? WHERE group_id = ? AND user_id = ?", [
            $dateBeforeGroupModification->format('Y-m-d H:i:s'),
            UuidFactory::uuid('group.id.freelancer'),
            UuidFactory::uuid('user.id.grace'),
        ]);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $this->assertEquals(count($reports), 1);
        $expectedUserGroupReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS_USERS,
            'status' => Alias::STATUS_IGNORE,
            'type' => 'DirectoryEntry',
        ];
        $this->assertReport($reports[0], $expectedUserGroupReport);
        // groupUser should exist, but not directory relation since the sync shouldn't have happened.
        $groupUser = $this->assertGroupUserExist(null, ['group_id' => UuidFactory::uuid('group.id.freelancer'), 'user_id' => UuidFactory::uuid('user.id.grace')]);
        $this->assertDirectoryRelationNotExist($groupUser->id);
    }

    /**
     * Scenario: GroupAdmin added the user to a group in passbolt after it was requested from him.
     * Expected result: create corresponding directoryRelation
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_Case13_Ok_Ok_Null_CreatedAfter_Ok()
    {
        $userEntry = $this->mockDirectoryEntryUser(['fname' => 'nancy', 'lname' => 'nancy', 'foreign_key' => UuidFactory::uuid('user.id.nancy')], Alias::STATUS_SUCCESS);
        $groupEntry = $this->mockDirectoryEntryGroup('marketing');
        $this->mockDirectoryUserData('nancy', 'nancy', 'nancy@passbolt.com');
        $groupData = $this->mockDirectoryGroupData('marketing', [
            'group_users' => [
                $userEntry->directory_name,
            ],
        ]);

        // Define creation date.
        $dateGroupModification = $groupData['directory_modified'];
        $dateAfterGroupModification = $dateGroupModification->addDays(1);

        // Update corresponding GroupUser so it is created before.
        $groupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        $groupsUsers->getConnection()->execute("UPDATE {$groupsUsers->getTable()} SET created = ? WHERE group_id = ? AND user_id = ?", [
            $dateAfterGroupModification->format('Y-m-d H:i:s'),
            UuidFactory::uuid('group.id.marketing'),
            UuidFactory::uuid('user.id.nancy'),
        ]);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedUserGroupReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS_USERS,
            'status' => Alias::STATUS_SUCCESS,
            'type' => Alias::MODEL_GROUPS,
        ];
        $this->assertReport($reports[0], $expectedUserGroupReport);

        // groupUser should exist, but not directory relation since the sync shouldn't have happened.
        $groupUser = $this->assertGroupUserExist(null, ['group_id' => UuidFactory::uuid('group.id.marketing'), 'user_id' => UuidFactory::uuid('user.id.nancy')]);
        $this->assertDirectoryRelationExist($groupUser->id);
    }

    /**
     * Scenario: A groupUser exists in ldap and has already been synced, but has been deleted in passbolt.
     * Expected result: do nothing, send ignore report
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_Case14_Ok_Ok_Ok_Null_Any()
    {
        $userEntry = $this->mockDirectoryEntryUser(['fname' => 'ada', 'lname' => 'ada', 'foreign_key' => UuidFactory::uuid('user.id.ada')], Alias::STATUS_SUCCESS);
        $this->mockDirectoryEntryGroup('freelancer');
        $this->mockDirectoryUserData('ada', 'ada', 'ada@passbolt.com');
        $this->mockDirectoryGroupData('freelancer', [
            'group_users' => [
                $userEntry->directory_name,
            ],
        ]);
        $this->mockDirectoryRelationGroupUser('freelancer', 'ada');

        $reports = $this->action->execute();

        //var_dump($reports);
        $this->assertEquals(count($reports), 1);
        $expectedUserGroupReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS_USERS,
            'status' => Alias::STATUS_IGNORE,
            'type' => 'DirectoryEntry',
        ];
        $this->assertReport($reports[0], $expectedUserGroupReport);
        $this->assertGroupUserNotExist(null, ['group_id' => UuidFactory::uuid('group.id.freelancer'), 'user_id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertDirectoryRelationExist(null, [
            'parent_key' => UuidFactory::uuid('ldap.group.id.freelancer'),
            'child_key' => UuidFactory::uuid('ldap.user.id.ada'),
        ]);
    }

    /**
     * Scenario: A group user exists in ldap and has already been synced.
     * Expected result: do nothing. No report.
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_Case15_Ok_Ok_Ok_Ok_Any()
    {
        $userEntry = $this->mockDirectoryEntryUser(['fname' => 'frances', 'lname' => 'frances', 'foreign_key' => UuidFactory::uuid('user.id.frances')], Alias::STATUS_SUCCESS);
        $this->mockDirectoryEntryGroup('freelancer');
        $this->mockDirectoryUserData('frances', 'frances', 'frances@passbolt.com');
        $this->mockDirectoryGroupData('freelancer', [
            'group_users' => [
                $userEntry->directory_name,
            ],
        ]);
        $this->mockDirectoryRelationGroupUser('freelancer', 'frances');
        $reports = $this->action->execute();
        $this->assertEmpty($reports);
        $this->assertGroupUserExist(null, ['group_id' => UuidFactory::uuid('group.id.freelancer'), 'user_id' => UuidFactory::uuid('user.id.frances')]);
        $this->assertDirectoryRelationExist(null, [
            'parent_key' => UuidFactory::uuid('ldap.group.id.freelancer'),
            'child_key' => UuidFactory::uuid('ldap.user.id.frances'),
        ]);
    }

    /**
     * Unit test for PASSBOLT-3406
     *
     * foreignKey becomes null after a user is deleted and the sync is done once.
     * Expected: no exception should be thrown
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_foreignKeyIsNull()
    {
        $userEntry = $this->mockDirectoryEntryUser(['fname' => 'sofia', 'lname' => 'sofia', 'foreign_key' => 'null'], Alias::STATUS_SUCCESS);
        $this->mockDirectoryEntryGroup('freelancer');
        $this->mockDirectoryGroupData('freelancer', [
            'group_users' => [
                $userEntry->directory_name,
            ],
        ]);
        $this->mockDirectoryRelationGroupUser('freelancer', 'sofia');
        $reports = $this->action->execute();
        $this->assertEmpty($reports);
    }

    /**
     * Unit test for PB-767
     *
     * When multiple users are added to a group, a failed validation on one groupUser should not make the other associations fail.
     *
     * @group DirectorySync
     * @group DirectorySyncGroupUser
     * @group DirectorySyncGroupUserAdd
     */
    public function testDirectorySyncGroupUser_failedValidationShouldNotContaminateOtherGroupUsers()
    {
        $this->mockDirectoryUserData('ruth', 'ruth', 'ruth@passbolt.com');
        $this->mockDirectoryUserData('betty', 'betty', 'betty@passbolt.com');
        // Ruth is a inactive user.
        $ruthEntry = $this->mockDirectoryEntryUser(['fname' => 'ruth', 'lname' => 'ruth', 'foreign_key' => UuidFactory::uuid('user.id.ruth')], Alias::STATUS_SUCCESS);
        $bettyEntry = $this->mockDirectoryEntryUser(['fname' => 'betty', 'lname' => 'betty', 'foreign_key' => UuidFactory::uuid('user.id.betty')], Alias::STATUS_SUCCESS);
        $this->mockDirectoryEntryGroup('marketing');
        //$this->mockDirectoryEntryGroup('marketing');
        $this->mockDirectoryGroupData('marketing', [
            'group_users' => [
                $ruthEntry->directory_name,
                $bettyEntry->directory_name,
            ],
        ]);
        $reports = $this->action->execute();

        $expectedUserGroupReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS_USERS,
            'status' => Alias::STATUS_IGNORE,
            'type' => Alias::MODEL_GROUPS,
        ];
        $this->assertReport($reports[0], $expectedUserGroupReport);

        $expectedUserGroupReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS_USERS,
            'status' => Alias::STATUS_SUCCESS,
            'type' => Alias::MODEL_GROUPS,
        ];
        $this->assertReport($reports[1], $expectedUserGroupReport);
    }
}
