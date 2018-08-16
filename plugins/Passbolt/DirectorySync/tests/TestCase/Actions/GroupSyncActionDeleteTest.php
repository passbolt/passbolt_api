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
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Actions\GroupSyncAction;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncTestCase;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertGroupsTrait;
use Passbolt\DirectorySync\Utility\Alias;

class GroupSyncActionDeleteTest extends DirectorySyncTestCase
{
    use AssertGroupsTrait;

    public function setUp()
    {
        parent::setUp();
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
        $this->action = new GroupSyncAction();
        $this->mockDirectoryEntryGroup('groupdoesnotexist');
        $this->mockDirectoryEntryGroup('groupreallydoesnotexist');
        $report = $this->action->execute();


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
        $report = $this->action->execute();


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
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.noref'), Alias::MODEL_GROUPS);
        $report = $this->action->execute();


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
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.marketing'), Alias::MODEL_GROUPS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.deleted'), Alias::MODEL_GROUPS);
        $report = $this->action->execute();


        $this->assertDirectoryIgnoreContains(Alias::MODEL_GROUPS, UuidFactory::uuid('group.id.marketing'));
        $this->assertDirectoryIgnoreContains(Alias::MODEL_GROUPS, UuidFactory::uuid('group.id.deleted'));
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

        $this->mockDirectoryEntryGroup('groupdoesnotexist');
        $this->mockDirectoryEntryGroup('groupisreallynotthere');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.noref'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('groupdoesnotexist'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('groupisreallynotthere'), Alias::MODEL_DIRECTORY_ENTRIES);
        $report = $this->action->execute();
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

        $this->mockDirectoryEntryGroup('marketing');
        $this->mockDirectoryEntryGroup('sales');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.marketing'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.sales'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('groupisreallynotthere'), Alias::MODEL_DIRECTORY_ENTRIES);
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertGroupExist(UuidFactory::uuid('group.id.sales'), ['deleted' => false]);
        $this->assertEquals(count($reports), 2);
        $expectedReport = [
            'action' => Alias::ACTION_DELETE,
            'model'  => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_IGNORE
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
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.noref'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.deleted'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryEntryGroup('deleted');
        $report = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
        $this->assertDirectoryIgnoreEmpty();

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
        $this->mockDirectoryEntryGroup('marketing');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => true]);
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_DELETE,
            'model'  => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SUCCESS
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
        $this->mockDirectoryEntryGroup('accounting');
        $reports = $this->action->execute();
        $this->assertOneDirectoryEntry();
        $this->assertGroupExist(UuidFactory::uuid('group.id.accounting'), ['deleted' => false]);
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_DELETE,
            'model'  => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_ERROR
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
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.marketing'), Alias::MODEL_GROUPS);
        $this->mockDirectoryEntryGroup('marketing');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_DELETE,
            'model'  => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_IGNORE
        ];
        $this->assertReport($reports[0], $expectedReport);
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
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.deleted'), Alias::MODEL_GROUPS);
        $this->mockDirectoryEntryGroup('deleted');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);

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
        $this->mockDirectoryEntryGroup('deleted');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_DELETE,
            'model'  => Alias::MODEL_GROUPS,
            'status' => Alias::ACTION_SYNC
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
        $this->mockDirectoryEntryGroup('donotexist');
        $reports = $this->action->execute();

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
        $this->mockDirectoryEntryGroup('marketing');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => true]);
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_DELETE,
            'model'  => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SUCCESS
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
        $this->mockDirectoryEntryGroup('accounting');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.accounting'), ['deleted' => false]);
        $this->assertOneDirectoryEntry();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_DELETE,
            'model'  => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_ERROR
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
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.marketing'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryEntryGroup('marketing');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_DELETE,
            'model'  => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_IGNORE
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
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.deleted'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryEntryGroup('deleted');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);
        $this->assertGroupNotExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => false]);

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
        $this->mockDirectoryEntryGroup('deleted');
        $reports = $this->action->execute();
        $this->assertGroupExist(UuidFactory::uuid('group.id.deleted'), ['deleted' => true]);


    }
}