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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Actions;

use App\Utility\UuidFactory;
use Passbolt\DirectorySync\Actions\GroupSyncAction;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertGroupsTrait;
use Passbolt\DirectorySync\Utility\Alias;

class GroupSyncActionDeleteTest extends DirectorySyncIntegrationTestCase
{
    use AssertGroupsTrait;

    public function setUp()
    {
        parent::setUp();
        $this->initAction();
    }

    private function initAction()
    {
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
    }

    /**
     * Test that orphans ignored are cleaned up
     */
    public function testCleanupOrphanIgnore()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.noref'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.noref'), Alias::MODEL_GROUPS);
        $report = $this->action->execute();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Test that orphans ignored are cleaned up
     */
    public function testCleanupOrphanDirectoryEntry()
    {
        $this->mockDirectoryEntryGroup('groupdoesnotexist');
        $this->mockDirectoryEntryGroup('groupreallydoesnotexist');
        $report = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertReportEmpty($report);
    }

    /**
     * Scenario: No data anywhere
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncGroupDelete_Case01_Null_Null_Null()
    {
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
        $this->assertTrue(true, true); // there is no spoon.
    }

    /**
     * Scenario: Group exists in passbolt and not present in the directory and there is no entry
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case02_Null_Null_Any_NotIgnore()
    {
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
    }

    /**
     * Scenario: Group is deleted in passbolt and not present in the directory and there is no entry
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case03_Null_Null_Any_NotIgnore()
    {
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
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
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.noref'), Alias::MODEL_GROUPS);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
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
    public function testDirectorySyncGroupDelete_Case04b_Null_Null_Any_Ignore()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.marketing'), Alias::MODEL_GROUPS);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreContains(Alias::MODEL_GROUPS, UuidFactory::uuid('group.id.marketing'));
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
    }

    /**
     * Scenario:
     * Expected result: do nothing at all
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case04c_Null_Null_Any_Ignore()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.deleted'), Alias::MODEL_GROUPS);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreContains(Alias::MODEL_GROUPS, UuidFactory::uuid('group.id.deleted'));
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
    public function testDirectorySyncGroupDelete_Case05a_Null_Any_Ignore_Null_Null()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.noref'), Alias::MODEL_DIRECTORY_ENTRIES);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario:
     * Expected result: Delete entry, delete ignore
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case05b_Null_Any_Ignore_Null_Null()
    {
        $this->mockOrphanDirectoryEntryGroup('nogroup');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.nogroup'), Alias::MODEL_DIRECTORY_ENTRIES);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario:
     * Expected result: Delete entry, delete ignore
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case05c_Null_Any_Ignore_Null_Null()
    {
        $this->mockDirectoryEntryGroup('nogroup');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.nogroup'), Alias::MODEL_DIRECTORY_ENTRIES);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: the group is deleted in the directory and marked as to be ignored in the directory
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncGroupDelete_Case06a_Null_Null_Ignore_Ok_Null()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.marketing'), Alias::MODEL_DIRECTORY_ENTRIES);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
    }

    /**
     * Scenario: the group is deleted in the directory and marked as to be ignored in the directory
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncGroupDelete_Case06b_Null_NoAssoc_Ignore_Ok_Null()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.marketing'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockOrphanDirectoryEntryGroup('testgroup');
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
    }

    /**
     * Scenario: the group is deleted in the directory and marked as to be ignored in the directory
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncGroupDelete_Case06c_Null_Ok_Ignore_Ok_Null()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.marketing'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryEntryGroup('marketing');
        $report = $this->action->execute();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertReportNotEmpty($report);
        $this->assertReport($report[0], ['status' => Alias::STATUS_IGNORE, 'type' => Alias::MODEL_DIRECTORY_ENTRIES]);
    }

    /**
     * Scenario: Group is deleted in directory and both directory entry and group are marked to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncGroupDelete_Case07a_Null_Null_Ignore_Null_Ignore()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.nope'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.nope'), Alias::MODEL_GROUPS);
        $report = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: Group is deleted in directory and both directory entry and group are marked to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncGroupDelete_Case07b_Null_NoAssoc_Ignore_Null_Ignore()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.nope'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.nope'), Alias::MODEL_GROUPS);
        $this->mockOrphanDirectoryEntryGroup('nope');
        $report = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: Group is deleted in directory and both directory entry and group are marked to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncGroupDelete_Case07c_Null_Ok_Ignore_Null_Ignore()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.nope'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.nope'), Alias::MODEL_GROUPS);
        $this->mockDirectoryEntryGroup('nope');
        $report = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: The group is deleted in directory and passbolt and entry was marked as to be ignored
     * Expected result: delete directory entry, no report
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case08a_Null_Null_Ignore_Deleted_Null()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.deleted'), Alias::MODEL_DIRECTORY_ENTRIES);
        $report = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: The group is deleted in directory and passbolt and entry was marked as to be ignored
     * Expected result: delete directory entry, no report
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case08b_Null_NoAssoc_Ignore_Deleted_Null()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.deleted'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockOrphanDirectoryEntryGroup('deleted');
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: The group is deleted in directory and passbolt and entry was marked as to be ignored
     * Expected result: delete directory entry, no report
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case08c_Null_Ok_Ignore_Deleted_Null()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.deleted'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockOrphanDirectoryEntryGroup('deleted');
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case09_Null_Error_Null_Null_Null()
    {
        $this->mockOrphanDirectoryEntryGroup('notthere');
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
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
        $this->mockOrphanDirectoryEntryGroup('marketing');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertReportEmpty($reports);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
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
        $this->mockOrphanDirectoryEntryGroup('accounting');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.accounting'), ['deleted' => false]);
        $this->assertReportEmpty($reports);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
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
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.marketing'), Alias::MODEL_GROUPS);
        $this->mockOrphanDirectoryEntryGroup('marketing');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertReportEmpty($reports);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreNotEmpty();
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
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.deleted'), Alias::MODEL_GROUPS);
        $this->mockOrphanDirectoryEntryGroup('deleted');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
        $this->assertReportEmpty($reports);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreNotEmpty();
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
        $this->mockOrphanDirectoryEntryGroup('deleted');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
        $this->assertReportEmpty($reports);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
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
        $this->mockDirectoryEntryGroup('donotexist');
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
        $this->assertReportEmpty($reports);
    }

    /**
     * Scenario: the group exists in passbolt, not present in the directory and can be deleted
     * Expected result: group should be deleted
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case14a_Null_Success_Null_Ok_Null()
    {
        $this->mockDirectoryEntryGroup('marketing');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => true]);
        $this->assertReportNotEmpty($reports);
        $expectedReport = [
            'action' => Alias::ACTION_DELETE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SUCCESS,
            'type' => 'Group',
        ];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
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
    public function testDirectorySyncGroupDelete_Case14a_Null_Success_Null_Ok_Null_WithIgnoreConfig()
    {
        $this->disableSyncOperation('groups', 'delete');
        $this->initAction();

        $this->mockDirectoryEntryGroup('marketing');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertReportEmpty($reports);
        $this->assertOneDirectoryEntry();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario:
     * Expected result: nothing
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupDelete
     */
    public function testDirectorySyncGroupDelete_Case14b_Null_Success_Null_NoDeletable_Null()
    {
        $this->mockDirectoryEntryGroup('accounting');
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = [
            'action' => Alias::ACTION_DELETE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_ERROR,
            'type' => 'SyncError',
        ];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertGroupExist(UuidFactory::uuid('group.id.accounting'), ['deleted' => false]);
        $this->assertOneDirectoryEntry();
        $this->assertDirectoryIgnoreEmpty();
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
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.marketing'), Alias::MODEL_GROUPS);
        $this->mockDirectoryEntryGroup('marketing');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertReportNotEmpty($reports);
        $expectedReport = [
            'action' => Alias::ACTION_DELETE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_IGNORE,
            'type' => 'DirectoryIgnore',
        ];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreNotEmpty();
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
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.deleted'), Alias::MODEL_GROUPS);
        $this->mockDirectoryEntryGroup('deleted');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
        $this->assertGroupNotExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => false]);
        $this->assertReportEmpty($reports);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreNotEmpty();
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
        $this->mockDirectoryEntryGroup('deleted');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_DELETE, 'model' => Alias::MODEL_GROUPS, 'status' => Alias::STATUS_SYNC, 'type' => 'Group'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: a group should be deleted but dry-run is set to true.
     * Expected result: the group should not be deleted, and the database should not be modified.
     */
    public function testDryRunDoNotDeleteEntity()
    {
        $this->mockDirectoryEntryGroup('marketing');
        $this->action->setDryRun(true);
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertReportNotEmpty($reports);
        $expectedReport = [
            'action' => Alias::ACTION_DELETE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SUCCESS,
            'type' => 'Group',
        ];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertOneDirectoryEntry();
        $this->assertDirectoryIgnoreEmpty();
    }
}
