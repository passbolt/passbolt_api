<?php
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

use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertDirectoryRelationsTrait;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertGroupUsersTrait;
use Passbolt\DirectorySync\Utility\SyncAction;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncTestCase;
use Passbolt\DirectorySync\Actions\GroupSyncAction;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertGroupsTrait;
use Cake\ORM\TableRegistry;

class GroupUserSyncActionAddTest extends DirectorySyncTestCase
{
    use AssertGroupsTrait;
    use AssertGroupUsersTrait;
    use AssertDirectoryRelationsTrait;

    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/secrets',  'app.Base/roles', 'app.Base/resources',
        'app.Base/groups_users', 'app.Base/permissions', 'app.Base/avatars', 'app.Base/secrets',
        'app.Base/favorites', 'app.Base/email_queue',
        'plugin.passbolt/directorySync.base/directoryEntries',
        'plugin.passbolt/directorySync.base/directoryIgnore',
        'plugin.passbolt/directorySync.base/directoryRelations',
    ];

    // TODO: should break if default group admin doesn't exist, is deleted or !active.

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
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $this->mockDirectoryEntryGroup('marketing', SyncAction::SUCCESS, null, null, null, null, UuidFactory::uuid('group.id.marketing'));

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $this->assertNoReportsForModel($reports, SyncAction::GROUPS_USERS);

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
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $relation = $this->mockDirectoryRelationGroupUser('accounting', 'ada');

        $reports = $this->action->execute();

        $this->assertEmpty($reports);
        $this->assertGroupExist(UuidFactory::uuid('group.id.accounting'), ['deleted' => false]);
        $this->assertGroupUserNotExist(null, ['group_id' => UuidFactory::uuid('group.id.accounting'), 'user_id' => UuidFactory::uuid('user.id.ada')]);
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
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $this->mockDirectoryEntryGroup('freelancer', SyncAction::SUCCESS, null, null, null, null, UuidFactory::uuid('group.id.freelancer'));
        $relation = $this->mockDirectoryRelationGroupUser('freelancer', 'lynne');

        $reports = $this->action->execute();

        // Only one report.
        // No report should be sent about groups_users
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => SyncAction::DELETE,
            'model'  => SyncAction::GROUPS,
            'status' => SyncAction::SUCCESS
        ];
        $this->assertReport($reports[0], $expectedReport);

        $this->assertGroupNotExist(UuidFactory::uuid('group.id.freelancer'), ['deleted' => false]);
        $this->assertGroupUserNotExist(null, ['group_id' => UuidFactory::uuid('group.id.freelancer'), 'user_id' => UuidFactory::uuid('user.id.lynne')]);
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
        // Not deletable = accounting

        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $this->mockDirectoryEntryGroup('accounting', SyncAction::SUCCESS, null, null, null, null, UuidFactory::uuid('group.id.accounting'));
        $userEntry = $this->mockDirectoryEntryUser(['fname' => 'frances', 'lname' => 'frances', 'foreign_key' => UuidFactory::uuid('user.id.frances')], SyncAction::SUCCESS);
        $relation = $this->mockDirectoryRelationGroupUser('accounting', 'frances');

        $userData = $this->mockDirectoryUserData('frances', 'frances', 'frances@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $groupData = $this->mockDirectoryGroupData('accounting');

        $reports = $this->action->execute();

        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => SyncAction::DELETE,
            'model'  => SyncAction::GROUPS_USERS,
            'status' => SyncAction::ERROR
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
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('newgroup');

        $reports = $this->action->execute();

        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => SyncAction::CREATE,
            'model'  => SyncAction::GROUPS,
            'status' => SyncAction::SUCCESS
        ];
        $this->assertReport($reports[0], $expectedReport);
        $groupCreated = $this->assertGroupExist(null, ['name' => 'newgroup', 'deleted' => false]);

        $defaultGroupAdmin = 'ada@passbolt.com';
        // Get default group admin.
        Configure::write('passbolt.plugins.directorySync.defaultGroupAdminUser', $defaultGroupAdmin);
        // Get user.
        $defaultGroupAdmin = $this->Users->find()->where(['username' => $defaultGroupAdmin])->first();

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
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('newgroup');
        $this->mockDirectoryEntryUser(['fname' => 'ada', 'lname' => 'ada', 'foreign_key' => UuidFactory::uuid('user.id.ada')], SyncAction::SUCCESS);

        $reports = $this->action->execute();

        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => SyncAction::CREATE,
            'model'  => SyncAction::GROUPS,
            'status' => SyncAction::SUCCESS
        ];
        $this->assertReport($reports[0], $expectedReport);
        $groupCreated = $this->assertGroupExist(null, ['name' => 'newgroup', 'deleted' => false]);

        $defaultGroupAdmin = 'ada@passbolt.com';
        // Get default group admin.
        Configure::write('passbolt.plugins.directorySync.defaultGroupAdminUser', $defaultGroupAdmin);
        // Get user.
        $defaultGroupAdmin = $this->Users->find()->where(['username' => $defaultGroupAdmin])->first();

        $groupUser = $this->assertGroupUserExist(null, ['group_id' => $groupCreated->id, 'user_id' => $defaultGroupAdmin->id]);
        $this->assertDirectoryRelationExist($groupUser->id);
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
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $this->mockDirectoryEntryGroup('accounting', SyncAction::SUCCESS, null, null, null, null, UuidFactory::uuid('group.id.accounting'));
        $relation = $this->mockDirectoryRelationGroupUser('accounting', 'ada');
        $groupData = $this->mockDirectoryGroupData('accounting');

        $reports = $this->action->execute();

        $this->assertEmpty($reports);
        $this->assertGroupExist(UuidFactory::uuid('group.id.accounting'), ['deleted' => false]);
        $this->assertGroupUserNotExist(null, ['group_id' => UuidFactory::uuid('group.id.accounting'), 'user_id' => UuidFactory::uuid('user.id.ada')]);
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
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $this->mockDirectoryEntryGroup('accounting', SyncAction::SUCCESS, null, null, null, null, UuidFactory::uuid('group.id.accounting'));
        $userEntry = $this->mockDirectoryEntryUser(['fname' => 'grace', 'lname' => 'grace', 'foreign_key' => UuidFactory::uuid('user.id.grace')], SyncAction::SUCCESS);
        $relation = $this->mockDirectoryRelationGroupUser('accounting', 'grace');
        $userData = $this->mockDirectoryUserData('grace', 'grace', 'grace@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $groupData = $this->mockDirectoryGroupData('accounting');

        $reports = $this->action->execute();

        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => SyncAction::DELETE,
            'model'  => SyncAction::GROUPS_USERS,
            'status' => SyncAction::SUCCESS
        ];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertGroupExist(UuidFactory::uuid('group.id.accounting'), ['deleted' => false]);

        // TODO: understand why the relationId is still in the database with group_id => null. DO WE HAVE A DEEPER INTEGRITY ISSUE??
        $this->assertGroupUserNotExist(null, ['group_id' => UuidFactory::uuid('group.id.accounting'), 'user_id' => UuidFactory::uuid('user.id.grace')]);
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
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $userEntry = $this->mockDirectoryEntryUser(['fname' => 'ruth', 'lname' => 'ruth', 'foreign_key' => UuidFactory::uuid('user.id.ruth')], SyncAction::SUCCESS);
        $groupData = $this->mockDirectoryGroupData('newgroup', [
            'group_users' => [
                $userEntry->directory_name
            ]
        ]);

        $reports = $this->action->execute();

        $this->assertEquals(count($reports), 2);
        $expectedGroupReport = [
            'action' => SyncAction::CREATE,
            'model'  => SyncAction::GROUPS,
            'status' => SyncAction::SUCCESS
        ];
        $this->assertReport($reports[0], $expectedGroupReport);

        $expectedUserGroupReport = [
            'action' => SyncAction::CREATE,
            'model'  => SyncAction::GROUPS_USERS,
            'status' => SyncAction::IGNORE
        ];
        $this->assertReport($reports[1], $expectedUserGroupReport);

        $groupCreated = $this->assertGroupExist(null, ['name' => 'newgroup', 'deleted' => false]);

        $defaultGroupAdmin = 'ada@passbolt.com';
        Configure::write('passbolt.plugins.directorySync.defaultGroupAdminUser', $defaultGroupAdmin);
        // Get user.
        $defaultGroupAdmin = $this->Users->find()->where(['username' => $defaultGroupAdmin])->first();

        $groupUserAda = $this->assertGroupUserExist(null, ['group_id' => $groupCreated->id, 'user_id' => $defaultGroupAdmin->id]);
        $this->assertGroupUserNotExist(null, ['group_id' => $groupCreated->id, 'user_id' => UuidFactory::uuid('user.id.ruth')]);
        $this->assertDirectoryRelationEmpty();
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
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $userEntry = $this->mockDirectoryEntryUser(['fname' => 'frances', 'lname' => 'frances', 'foreign_key' => UuidFactory::uuid('user.id.frances')], SyncAction::SUCCESS);
        $groupData = $this->mockDirectoryGroupData('newgroup', [
            'group_users' => [
                $userEntry->directory_name
            ]
        ]);

        $reports = $this->action->execute();

        $this->assertEquals(count($reports), 2);
        $expectedGroupReport = [
            'action' => SyncAction::CREATE,
            'model'  => SyncAction::GROUPS,
            'status' => SyncAction::SUCCESS
        ];
        $this->assertReport($reports[0], $expectedGroupReport);

        $expectedUserGroupReport = [
            'action' => SyncAction::CREATE,
            'model'  => SyncAction::GROUPS_USERS,
            'status' => SyncAction::SUCCESS
        ];
        $this->assertReport($reports[1], $expectedUserGroupReport);
        $groupCreated = $this->assertGroupExist(null, ['name' => 'newgroup', 'deleted' => false]);

        $defaultGroupAdmin = 'ada@passbolt.com';
        Configure::write('passbolt.plugins.directorySync.defaultGroupAdminUser', $defaultGroupAdmin);
        // Get user.
        $defaultGroupAdmin = $this->Users->find()->where(['username' => $defaultGroupAdmin])->first();

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
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $userEntry = $this->mockDirectoryEntryUser(['fname' => 'frances', 'lname' => 'frances', 'foreign_key' => UuidFactory::uuid('user.id.frances')], SyncAction::SUCCESS);
        $groupEntry = $this->mockDirectoryEntryGroup('marketing', SyncAction::SUCCESS);
        $groupData = $this->mockDirectoryGroupData('marketing', [
            'group_users' => [
                $userEntry->directory_name
            ]
        ]);

        $reports = $this->action->execute();

        $this->assertEquals(count($reports), 1);
        $expectedUserGroupReport = [
            'action' => SyncAction::CREATE,
            'model'  => SyncAction::GROUPS_USERS,
            'status' => SyncAction::SUCCESS
        ];
        $this->assertReport($reports[0], $expectedUserGroupReport);

        $defaultGroupAdmin = 'ada@passbolt.com';
        Configure::write('passbolt.plugins.directorySync.defaultGroupAdminUser', $defaultGroupAdmin);
        // Get user.
        $defaultGroupAdmin = $this->Users->find()->where(['username' => $defaultGroupAdmin])->first();

        // Group user for default admin should not exist.
        $this->assertGroupUserNotExist(null, ['group_id' => UuidFactory::uuid('group.id.marketing'), 'user_id' => $defaultGroupAdmin->id]);

        // Frances should be in group users and directoryRelations.
        $groupUserFrances = $this->assertGroupUserExist(null, ['group_id' => UuidFactory::uuid('group.id.marketing'), 'user_id' => UuidFactory::uuid('user.id.frances')]);
        $this->assertDirectoryRelationExist($groupUserFrances->id);
    }

    // Case where group is edited and a user is added.

    // case where group is edited with new users:
    //  => case where a user is added to group with passwords.
}