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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Actions;

use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Actions\GroupSyncAction;
use Passbolt\DirectorySync\Model\Entity\DirectoryIgnore;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncTestCase;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertGroupsTrait;
use Passbolt\DirectorySync\Utility\SyncAction;

class GroupSyncActionDeleteTest extends DirectorySyncTestCase
{
    use AssertGroupsTrait;

    public $fixtures = [
        'app.Base/users',
        'app.Base/groups',
        'app.Base/secrets',
        'app.Base/roles',
        'app.Base/resources',
        'app.Alt0/groups_users',
        'app.Alt0/permissions',
        'app.Base/avatars',
        'app.Base/favorites',
        'app.Base/email_queue',
        'plugin.passbolt/directorySync.base/directoryEntries',
        'plugin.passbolt/directorySync.base/directoryIgnore',
    ];

    /**
     * Test that orphans ignored are cleaned up
     */
    public function testCleanupOrphanIgnore()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.noref'), SyncAction::DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.noref'), SyncAction::GROUPS);
        $report = $this->action->execute();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertEmpty($report);
    }

    /**
     * Test that orphans ignored are cleaned up
     */
    public function testCleanupOrphanDirectoryEntry()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryEntryGroup('groupdoesnotexist', DirectoryEntry::STATUS_SUCCESS);
        $this->mockDirectoryEntryGroup('groupreallydoesnotexist', DirectoryEntry::STATUS_ERROR);
        $report = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertEmpty($report);
    }

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
        $report       = $this->action->execute();
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
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.noref'), SyncAction::GROUPS);
        $report = $this->action->execute();
        $this->assertEmpty($report);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
    }

    /**
     * Scenario:
     * Expected result: do nothing at all
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case04b_04c_Null_Null_Any_Ignore()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.marketing'), SyncAction::GROUPS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.deleted'), SyncAction::GROUPS);
        $report = $this->action->execute();
        $this->assertEmpty($report);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreContains(SyncAction::GROUPS, UuidFactory::uuid('group.id.marketing'));
        $this->assertDirectoryIgnoreContains(SyncAction::GROUPS, UuidFactory::uuid('group.id.deleted'));
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
    }

    /**
     * Scenario:
     * Expected result: Delete entry, delete ignore
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case05a_05b_05c_Null_Any_Ignore_Null_Null()
    {
        $this->action = new GroupSyncAction();

        $this->mockDirectoryEntryGroup('groupdoesnotexist', DirectoryEntry::STATUS_SUCCESS);
        $this->mockDirectoryEntryGroup('groupisreallynotthere', SyncAction::ERROR);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.noref'), SyncAction::DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('groupdoesnotexist'), SyncAction::DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('groupisreallynotthere'), SyncAction::DIRECTORY_ENTRIES);
        $report = $this->action->execute();
        $this->assertEmpty($report);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario:
     * Expected result: Delete entry, delete ignore
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case06a_06b_06c_Null_Any_Ignore_Ok_Null()
    {
        $this->action = new GroupSyncAction();

        $this->mockDirectoryEntryGroup('marketing', DirectoryEntry::STATUS_SUCCESS);
        $this->mockDirectoryEntryGroup('sales', DirectoryEntry::STATUS_ERROR);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.marketing'), SyncAction::DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.sales'), SyncAction::DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('groupisreallynotthere'), SyncAction::DIRECTORY_ENTRIES);
        $reports = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertGroupExist(UuidFactory::uuid('group.id.sales'), ['deleted' => false]);

        $this->assertEquals(count($reports), 2);
        $expectedReport = [
            'action' => SyncAction::DELETE,
            'model'  => SyncAction::GROUPS,
            'status' => SyncAction::IGNORE
        ];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertReport($reports[1], $expectedReport);
    }

    public function testDirectorySyncGroupDelete_Case07a_07b_07c_Null_Any_Ignore_Deleted_Null() {
        // Is already tested by testCleanupOrphanIgnore()
        $this->assertTrue(true);
    }

    /**
     * Scenario: The group is deleted in directory and passbolt and entry was marked as to be ignored
     * Expected result: delete directory entry, no report
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case08a_08b_08c_Null_Any_Ignore_Deleted_Null()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.noref'), SyncAction::DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.deleted'), SyncAction::DIRECTORY_ENTRIES);
        $this->mockDirectoryEntryGroup('deleted', SyncAction::SUCCESS);
        $report = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertEmpty($report);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case09_Null_Error_Null_Null_Null() {
        // Already tested by testCleanupOrphanDirectoryEntry.
        $this->assertTrue(true);
    }

    /**
     * Scenario: The group was deleted in LDAP and could not be previously deleted in passbolt
     * Expected result: group should be deleted with a report
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case10a_Null_Error_Null_Ok_Null()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryEntryGroup('marketing', SyncAction::ERROR);
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => true]);
        $this->assertDirectoryEntryEmpty();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => SyncAction::DELETE,
            'model'  => SyncAction::GROUPS,
            'status' => SyncAction::SUCCESS
        ];
        $this->assertReport($reports[0], $expectedReport);
    }

    /**
     * Scenario: The group was deleted in LDAP and could not be previously deleted in passbolt
     * Expected result: raise an error and delete directory entry but not the group
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case10b_Null_Error_Null_NotDeletable_Null()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryEntryGroup('accounting', SyncAction::ERROR);
        $reports = $this->action->execute();
        $this->assertOneDirectoryEntry(SyncAction::ERROR);
        $this->assertGroupExist(UuidFactory::uuid('group.id.accounting'), ['deleted' => false]);
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => SyncAction::DELETE,
            'model'  => SyncAction::GROUPS,
            'status' => SyncAction::ERROR
        ];
        $this->assertReport($reports[0], $expectedReport);
    }

    /**
     * Scenario: The group exists in directory and marked as to be ignored after an error
     * Expected result: delete directory entry, one ignored report.
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case11a_Null_Error_Null_Ok_Ignore()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.marketing'), SyncAction::GROUPS);
        $this->mockDirectoryEntryGroup('marketing', SyncAction::ERROR);
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertDirectoryEntryEmpty();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => SyncAction::DELETE,
            'model'  => SyncAction::GROUPS,
            'status' => SyncAction::IGNORE
        ];
        $this->assertReport($reports[0], $expectedReport);
        // TODO: discuss with remy (left a comment in doc)
        $this->markAsRisky();
    }

    /**
     * Scenario: The group is deleted in directory and marked as to be ignored after an error
     * Expected result: delete directory entry, no reports.
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case11b_Null_Error_Null_Deleted_Ignore()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.deleted'), SyncAction::GROUPS);
        $this->mockDirectoryEntryGroup('deleted', SyncAction::ERROR);
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
        $this->assertDirectoryEntryEmpty();
        $this->assertEmpty($reports);
    }

    /**
     * Scenario: The group is deleted in directory and manually deleted in passbolt between sync after an error
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case12_Null_Error_Null_Deleted_Null()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryEntryGroup('deleted', SyncAction::ERROR);
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
        $this->assertDirectoryEntryEmpty();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => SyncAction::DELETE,
            'model'  => SyncAction::GROUPS,
            'status' => SyncAction::SYNC
        ];
        $this->assertReport($reports[0], $expectedReport);
    }

    /**
     * Scenario: The group is deleted in directory and manually deleted in passbolt between sync after an error
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case13_Null_Success_Null_Null_Null()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryEntryGroup('donotexist', SyncAction::SUCCESS);
        $reports = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertEmpty($reports);
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
    public function testDirectorySyncGroupDelete_Case14a_Null_Success_Null_Ok_Null()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryEntryGroup('marketing', SyncAction::SUCCESS);
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => true]);
        $this->assertDirectoryEntryEmpty();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => SyncAction::DELETE,
            'model'  => SyncAction::GROUPS,
            'status' => SyncAction::SUCCESS
        ];
        $this->assertReport($reports[0], $expectedReport);
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
    public function testDirectorySyncGroupDelete_Case14b_Null_Success_Null_NoDeletable_Null()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryEntryGroup('accounting', SyncAction::SUCCESS);
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.accounting'), ['deleted' => false]);
        $this->assertOneDirectoryEntry(SyncAction::ERROR);
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => SyncAction::DELETE,
            'model'  => SyncAction::GROUPS,
            'status' => SyncAction::ERROR
        ];
        $this->assertReport($reports[0], $expectedReport);
    }

    /**
     * Scenario: The group is deleted in directory and correspond to a group marked as ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case15a_Null_Success_Null_Ok_Ignore()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.marketing'), SyncAction::DIRECTORY_ENTRIES);
        $this->mockDirectoryEntryGroup('marketing', SyncAction::SUCCESS);
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertDirectoryEntryEmpty();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => SyncAction::DELETE,
            'model'  => SyncAction::GROUPS,
            'status' => SyncAction::IGNORE
        ];
        $this->assertReport($reports[0], $expectedReport);
    }

    /**
     * Scenario: The group is deleted in directory and correspond to a deleted group marked as ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case15b_Null_Success_Null_Deleted_Ignore()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.deleted'), SyncAction::DIRECTORY_ENTRIES);
        $this->mockDirectoryEntryGroup('deleted', SyncAction::SUCCESS);
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
        $this->assertGroupNotExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => false]);
        $this->assertDirectoryEntryEmpty();
        $this->assertEmpty($reports);
    }

    /**
     * Scenario: The group is deleted in directory and manually deleted in passbolt between sync after a success
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case16_Null_Success_Null_Deleted_Null()
    {
        $this->action = new GroupSyncAction();
        $this->mockDirectoryEntryGroup('deleted', SyncAction::SUCCESS);
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
        $this->assertDirectoryEntryEmpty();
        $this->assertEmpty($reports);
    }
}