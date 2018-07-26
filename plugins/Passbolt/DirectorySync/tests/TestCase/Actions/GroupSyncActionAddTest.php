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
use Passbolt\DirectorySync\Utility\SyncAction;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncTestCase;
use Passbolt\DirectorySync\Actions\GroupSyncAction;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertGroupsTrait;
use Cake\ORM\TableRegistry;

class GroupSyncActionTest extends DirectorySyncTestCase
{
    use AssertGroupsTrait;

    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/secrets',  'app.Base/roles',
        'app.Alt0/groups_users', 'app.Alt0/permissions', 'app.Base/avatars',
        'app.Base/favorites', 'app.Base/email_queue',
        'plugin.passbolt/directorySync.base/directoryEntries',
        'plugin.passbolt/directorySync.base/directoryIgnore',
    ];

    /**
     * Scenario: Group does not exist in ldap nor in passbolt
     * Scenario: Group exist in passbolt and not in ldap
     * Scenario: Group was deleted in passbolt and is not present in ldap
     * Scenario: Group is marked as to be ignored and is not present in ldap
     *
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case01_04_Null_Null_Any()
    {
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);

        // Nothing should happen
        $reports = $this->action->execute();
        $this->assertEmpty($reports);

        $syncEntry = $this->action->DirectoryEntries->find()->all()->toArray();
        $this->assertEquals(count($syncEntry), 0);
    }

    /**
     * Scenario: Group was added in ldap
     * Expected result: Group should be added and a directory entry should be created
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case17_Ok_Null_Null()
    {
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('newFromLdap');

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::SUCCESS);

        $group = $this->assertGroupExist(null, ['name' => 'newFromLdap', 'deleted' => false]);
        $this->assertOneDirectoryEntry(SyncAction::SUCCESS);
        $this->assertDirectoryEntryExists([
            'id' => $groupData['id'],
            'foreign_model' => 'Groups',
            'foreign_key' => $group['id'],
            'status' => SyncAction::SUCCESS
        ]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: Previous group creation failed
     * Expected result: Try to create, then success
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case21_Ok_Error_Null()
    {
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('testgroup');

        // Add directoryEntry for error
        $this->mockDirectoryEntryGroup('testgroup', SyncAction::ERROR, null, null, null, null, false);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::SUCCESS);

        $group = $this->assertGroupExist(null, ['name' => 'testgroup', 'deleted' => false]);
        $this->assertOneDirectoryEntry(SyncAction::SUCCESS);
        $this->assertDirectoryEntryExists([
            'id' => $groupData['id'],
            'foreign_model' => 'Groups',
            'foreign_key' => $group['id'],
            'status' => SyncAction::SUCCESS
        ]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: Previous group creation failed and it was fixed manually
     * Expected result: Set sync to success
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case23_Ok_Error_Ok()
    {
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('marketing');

        // Add directoryEntry for error
        $this->mockDirectoryEntryGroup('marketing', SyncAction::ERROR, null, null, null, null, false);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $this->assertReportAction($reports[0], SyncAction::CREATE);
        $this->assertReportStatus($reports[0], SyncAction::SYNC);

        $group = $this->assertGroupExist(null, ['name' => 'marketing', 'deleted' => false]);
        $this->assertOneDirectoryEntry(SyncAction::SUCCESS);
        $this->assertDirectoryEntryExists([
            'id' => $groupData['id'],
            'foreign_model' => 'Groups',
            'foreign_key' => $group['id'],
            'status' => SyncAction::SUCCESS
        ]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: Group was added in ldap and a similar deleted group already exist and was deleted before
     * Expected result: Group should be added and a directory entry should be created
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case20_Ok_Null_Deleted_Before()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('deleted', [], $dateAfterDeletion, $dateAfterDeletion);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::SUCCESS);

        $group = $this->assertGroupExist(null, ['name' => 'deleted', 'deleted' => false]);
        $this->assertOneDirectoryEntry(SyncAction::SUCCESS);
        $this->assertDirectoryEntryExists([
            'id' => $groupData['id'],
            'foreign_model' => 'Groups',
            'foreign_key' => $group['id'],
            'status' => SyncAction::SUCCESS
        ]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: Group was added in ldap and a similar deleted group already exist and was deleted after
     * Expected result: Group should be marked as ignored
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case20_Ok_Null_Deleted_After()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateBeforeDeletion = $deletionDate->subDays(1);

        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('deleted', [], $dateBeforeDeletion, $dateBeforeDeletion);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::IGNORE);

        $this->assertGroupNotExist(null, ['name' => 'deleted', 'deleted' => false]);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreNotEmpty();
    }

    /**
     * Scenario: The same group was already synced successfully, then deleted in passbolt, but for some reason the deletion date is marked before the ldap creation.
     * Expected result: Group should be re-created and the directory entry should have been updated.
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case28_Ok_Success_Deleted_Before()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('deleted', [], $dateAfterDeletion, $dateAfterDeletion);

        // Add directoryEntry for success.
        $this->mockDirectoryEntryGroup('deleted', SyncAction::SUCCESS);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::SUCCESS);

        $group = $this->assertGroupExist(null, ['name' => 'deleted', 'deleted' => false]);
        // The directory entry should have been updated.
        $this->assertOneDirectoryEntry(SyncAction::SUCCESS);
        $this->assertDirectoryEntryExists([
            'id' => $groupData['id'],
            'foreign_model' => 'Groups',
            'foreign_key' => $group['id'],
            'status' => SyncAction::SUCCESS
        ]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: The same group was already synced successfully, then deleted in passbolt.
     * Expected result: Group should be marked as ignored and directory entry should be deleted.
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case28_Ok_Success_Deleted_After()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateBeforeDeletion = $deletionDate->subDays(1);

        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('deleted', [], $dateBeforeDeletion, $dateBeforeDeletion);

        // Add directoryEntry for success.
        $this->mockDirectoryEntryGroup('deleted', SyncAction::SUCCESS);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::IGNORE);

        $this->assertGroupNotExist(null, ['name' => 'deleted', 'deleted' => false]);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreNotEmpty();
    }

    /**
     * Scenario: The same group was already synced successfully, then could not be synced, then deleted in passbolt.
     * Expected result: Group should be re-created and the directory entry should have been updated.
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case24_Ok_Error_Deleted_Before()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('deleted', [], $dateAfterDeletion, $dateAfterDeletion);

        // Add directoryEntry for success.
        $this->mockDirectoryEntryGroup('deleted', SyncAction::ERROR);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::SUCCESS);

        $group = $this->assertGroupExist(null, ['name' => 'deleted', 'deleted' => false]);
        // The directory entry should have been updated.
        $this->assertOneDirectoryEntry(SyncAction::SUCCESS);
        $this->assertDirectoryEntryExists([
            'id' => $groupData['id'],
            'foreign_model' => 'Groups',
            'foreign_key' => $group['id'],
            'status' => SyncAction::SUCCESS
        ]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: The same group was already synced successfully, then deleted in passbolt.
     * Expected result: Group should be marked as ignored and directory entry should be deleted.
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case24_Ok_Error_Deleted_After()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateBeforeDeletion = $deletionDate->subDays(1);

        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('deleted', [], $dateBeforeDeletion, $dateBeforeDeletion);

        // Add directoryEntry for success.
        $this->mockDirectoryEntryGroup('deleted', SyncAction::ERROR);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::IGNORE);

        $this->assertGroupNotExist(null, ['name' => 'deleted', 'deleted' => false]);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreNotEmpty();
    }

    /**
     * Scenario: Entry is marked as to be ignored
     * Expected result: It should be ignored
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case29_Ok_Ignore_Null()
    {
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('newgroup');
        $this->mockDirectoryIgnore($groupData['id'], 'DirectoryEntry');


        $reports = $this->action->execute();
        // TODO: no report can be returned because at this time the entity doesn't exist anymore.
//        $this->assertEquals(count($reports), 1);
//        $this->assertReportStatus($reports[0], SyncAction::IGNORE);

        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreNotEmpty();
    }


    /**
     * Scenario: The same group was already synced successfully, then deleted in passbolt and marked as ignore.
     * Expected result: Group should be marked as ignored and directory entry should be deleted.
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case32_Ok_Ignore_Deleted()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();

        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('deleted');
        $this->mockDirectoryIgnore($groupData['id'], 'DirectoryEntry');

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::IGNORE);

        $this->assertDirectoryIgnoreContains(SyncAction::GROUPS, $group->id);
        $this->assertDirectoryIgnoreContains(SyncAction::DIRECTORY_ENTRY, $groupData['id']);
    }

    /**
     * Scenario: The same group exists both side, but has never been synced
     * Expected result: The group should be synced
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case19_Ok_Null_Ok()
    {
        $group = $this->Groups->find()->where(['name' => 'marketing'])->first();

        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('marketing');

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $this->assertReportAction($reports[0], SyncAction::CREATE);
        $this->assertReportStatus($reports[0], SyncAction::SYNC);
        $this->assertDirectoryEntryExists(['id' => $groupData['id'], 'status' => SyncAction::SUCCESS]);
        $this->assertOneDirectoryEntry(SyncAction::SUCCESS);
    }

    /**
     * Scenario: The group exists and has been synced
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case27_Ok_Success_Ok()
    {
        $group = $this->Groups->find()->where(['name' => 'marketing'])->first();

        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('marketing');
        $this->mockDirectoryEntryGroup('marketing', SyncAction::SUCCESS);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 0);
        $this->assertOneDirectoryEntry(SyncAction::SUCCESS);
    }

    /**
     * Scenario: The group exists and has been synced
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case31_Ok_Ignore_Ok()
    {
        $group = $this->Groups->find()->where(['name' => 'marketing'])->first();

        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('marketing');
        $this->mockDirectoryIgnore($groupData['id'], SyncAction::DIRECTORY_ENTRY);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::IGNORE);
    }

    /**
     * Scenario: A group has been marked as to be ignored
     * Expected result: It should be ignored and a report should be done
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case18_Ok_Null_Ignore()
    {
        $group = $this->Groups->find()->where(['name' => 'marketing'])->first();

        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('marketing');
        $this->mockDirectoryIgnore($group->id, SyncAction::GROUPS);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::IGNORE);
    }

    /**
     * Scenario: A group could not be synced and then was marked as ignore
     * Expected result: The corresponding entry should be removed and the group ignored
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case22_Ok_Error_Ignore()
    {
        $group = $this->Groups->find()->where(['name' => 'marketing'])->first();

        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('marketing');
        $this->mockDirectoryEntryGroup('marketing', SyncAction::ERROR);
        $this->mockDirectoryIgnore($group->id, SyncAction::GROUPS);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::IGNORE);

        // The directory entry should have been removed
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: A group was synced and then marked as ignore
     * Expected result: The corresponding entry should be removed and the group ignored
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case26_Ok_Success_Ignore()
    {
        $group = $this->Groups->find()->where(['name' => 'marketing'])->first();

        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('marketing');
        $this->mockDirectoryEntryGroup('marketing', SyncAction::SUCCESS);
        $this->mockDirectoryIgnore($group->id, SyncAction::GROUPS);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::IGNORE);

        // The directory entry should have been removed
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: A group was synced and then marked as ignore
     * Expected result: The corresponding entry should be removed and the group ignored
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case30_Ok_Ignore_Ignore()
    {
        $group = $this->Groups->find()->where(['name' => 'marketing'])->first();

        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
        $groupData = $this->mockDirectoryGroupData('marketing');
        $this->mockDirectoryIgnore($group->id, SyncAction::GROUPS);
        $this->mockDirectoryIgnore($groupData['id'], SyncAction::DIRECTORY_ENTRY);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::IGNORE);

        // The directory entry should have been removed
        $this->assertDirectoryEntryEmpty();
    }
}