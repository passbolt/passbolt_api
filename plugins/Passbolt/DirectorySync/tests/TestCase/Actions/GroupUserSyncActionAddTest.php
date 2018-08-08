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
        'app.Base/users', 'app.Base/groups', 'app.Base/secrets',  'app.Base/roles',
        'app.Base/groups_users', 'app.Base/permissions', 'app.Base/avatars',
        'app.Base/favorites', 'app.Base/email_queue',
        'plugin.passbolt/directorySync.base/directoryEntries',
        'plugin.passbolt/directorySync.base/directoryIgnore',
        'plugin.passbolt/directorySync.base/directoryRelations',
    ];

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
//        $groupData = $this->mockDirectoryGroupData('accounting', [
//            'group_users' => [
//                $userData['directory_name']
//            ]
//        ]);
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
        // TODO
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
        // TODO
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
}