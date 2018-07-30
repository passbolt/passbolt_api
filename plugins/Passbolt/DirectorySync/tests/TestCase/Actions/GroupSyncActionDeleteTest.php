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
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Actions\GroupSyncAction;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncTestCase;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertGroupsTrait;
use Passbolt\DirectorySync\Utility\SyncAction;

class GroupSyncActionDeleteTest extends DirectorySyncTestCase
{
    use AssertGroupsTrait;

    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/secrets',  'app.Base/roles', 'app.Base/resources',
        'app.Alt0/groups_users', 'app.Alt0/permissions', 'app.Base/avatars',
        'app.Base/favorites', 'app.Base/email_queue',
        'plugin.passbolt/directorySync.base/directoryEntries',
        'plugin.passbolt/directorySync.base/directoryIgnore',
    ];

    /**
     * Scenario: Group doesn't exist in passbolt nor in the directory and there is no entry
     * Scenario: Group exists in passbolt and not present in the directory and there is no entry
     * Scenario: Group is deleted in passbolt and not present in the directory and there is no entry
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case01_02_03_Null_Null_Any_NotIgnore()
    {
        // Case2: Ok group is the group "marketing"
        // Case3: Deleted group is the group "deleted"
        $this->action = new GroupSyncAction();
        $report = $this->action->execute();
        $this->assertEmpty($report);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
    }

    /**
     * Scenario: Group doesn't exist in passbolt nor in the directory, there is no entry, and there is one orphan for ignore
     * Expected result: orphan entry should be removed.
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case04a_Null_Null_Null_Ignore()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.noref'), SyncAction::GROUPS);
        $report = $this->action->execute();
        $this->assertEmpty($report);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
    }

    /**
     * Scenario: Group is not in ldap and does not exist, but has an entry that is marked as ignored
     * Expected result: Delete entry, delete ignore
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case05_Null_Any_Null_SyncIgnore()
    {
        $this->action = new GroupSyncAction();

        $this->mockDirectoryEntryGroup('groupdoesnotexist', DirectoryEntry::STATUS_SUCCESS);
        $this->mockDirectoryEntryGroup('groupisreallynotthere', SyncAction::ERROR);
        $this->mockDirectoryEntryGroup('groupisignored', SyncAction::ERROR);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.noref'), 'DirectoryEntry');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.groupisignored'), 'DirectoryEntry');
        $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: Group is not in ldap and does not exist, but has an entry
     * Expected result: Delete entry
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case13_09_05_Null_Any_Null()
    {
        $this->action = new GroupSyncAction();

        $this->mockDirectoryEntryGroup('groupdoesnotexist', DirectoryEntry::STATUS_SUCCESS);
        $this->mockDirectoryEntryGroup('groupisreallynotthere', SyncAction::ERROR);
        $this->mockDirectoryEntryGroup('groupisignored', SyncAction::ERROR);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.groupisignored'), 'DirectoryEntry');
        $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: The group was deleted in LDAP and could not be previously deleted in passbolt
     * Expected result: group should be deleted
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case10_Group_deletable_Retry_Success()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryEntryGroup('marketing', SyncAction::ERROR);
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => true]);
        $this->assertDirectoryEntryEmpty();
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::SUCCESS);
    }

    /**
     * Scenario: The group was deleted in LDAP and could not be previously deleted in passbolt
     * Expected result: raise an error and delete directory entry but not the group
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case10_Group_Not_deletable_Retry_Error()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryEntryGroup('accounting', SyncAction::ERROR);
        $reports = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertGroupExist(UuidFactory::uuid('group.id.accounting'), ['deleted' => false]);
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::ERROR);
    }


    /**
     * Scenario: the group exists in passbolt, not present in the directory and can be deleted
     *            and the plugin configuration is set to ignore deleted groups
     * Expected result: nothing
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case14_Group_deletable_config_ignore()
    {
        Configure::write('passbolt.plugins.directorySync.jobs.groups.delete', false);
        $this->action = new GroupSyncAction();
        $this->mockDirectoryEntryGroup('marketing', SyncAction::SUCCESS);
        $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertOneDirectoryEntry(DirectoryEntry::STATUS_SUCCESS);
    }

    /**
     * Scenario: the group exists in passbolt, not present in the directory and can be deleted
     *            and the plugin configuration is set to ignore deleted groups
     * Expected result: nothing
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case14_Group_deletable_config_ok()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryEntryGroup('marketing', SyncAction::SUCCESS);
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => true]);
        $this->assertDirectoryEntryEmpty();
        $this->assertEquals(count($reports), 1);
        $this->assertReportAction($reports[0], SyncAction::DELETE);
        $this->assertReportStatus($reports[0], SyncAction::SUCCESS);
    }

    /**
     * Scenario: the group exists in passbolt, not present in the directory and can be deleted
     *            and the plugin configuration is set to ignore deleted groups
     * Expected result: nothing
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case14_Group_not_deletable()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryEntryGroup('accounting', SyncAction::SUCCESS);
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.accounting'), ['deleted' => false]);
        $this->assertOneDirectoryEntry(SyncAction::ERROR);
        $this->assertEquals(count($reports), 1);
        $this->assertReportAction($reports[0], SyncAction::DELETE);
        $this->assertReportStatus($reports[0], SyncAction::ERROR);
    }


    /**
     * Scenario: The group is deleted in directory and passbolt and entry was marked as to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case8_Null_Ignore_Deleted()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.deleted'), 'DirectoryEntry');
        $this->mockDirectoryEntryGroup('deleted', SyncAction::SUCCESS);
        $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: The group is deleted in directory and manually deleted in passbolt between sync after an error
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case12_Null_Error_Deleted()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryEntryGroup('deleted', SyncAction::ERROR);
        $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: The group is deleted in directory and manually deleted in passbolt between sync after a success
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case16_Null_Success_Deleted()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryEntryGroup('deleted', SyncAction::SUCCESS);
        $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: The group is deleted in directory and marked as to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case15_Null_Success_Ignore()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.marketing'), 'DirectoryEntry');
        $this->mockDirectoryEntryGroup('marketing', SyncAction::SUCCESS);
        $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: The group is deleted in directory and marked as to be ignored after an error
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case11_Null_Error_Ignore()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.marketing'), 'DirectoryEntry');
        $this->mockDirectoryEntryGroup('marketing', SyncAction::ERROR);
        $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: Group is deleted in directory and both directory entry and group are marked to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case7_Null_Ignore_Ignore()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.marketing'), 'DirectoryEntry');
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.marketing'), SyncAction::GROUPS);
        $this->mockDirectoryEntryGroup('marketing', SyncAction::SUCCESS);
        $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: Group is deleted in directory and directory entry is marked to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case6_Null_Ignore_Ok()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.marketing'), SyncAction::GROUPS);
        $this->mockDirectoryEntryGroup('marketing', SyncAction::SUCCESS);
        $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertDirectoryEntryEmpty();
    }
}