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
use Passbolt\DirectorySync\Actions\GroupSyncAction;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertGroupsTrait;
use Passbolt\DirectorySync\Utility\Alias;

class GroupSyncActionAddTest extends DirectorySyncIntegrationTestCase
{
    use AssertGroupsTrait;

    public function setUp()
    {
        parent::setUp();
        $this->action = new GroupSyncAction();
        $this->action->getDirectory()->setGroups([]);
    }

    /**
     * Scenario: Group was added in ldap
     * Expected result: Group should be added and a directory entry should be created
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case17_Ok_Null_Null_Null_Null()
    {
        $this->mockDirectoryGroupData('newFromLdap');
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SUCCESS,
            'type' => Alias::MODEL_GROUPS
        ];
        $this->assertReport($reports[0], $expectedReport);
        $data = $reports[0]->getData();
        $this->assertGroupExist($data->id, ['name' => 'newFromLdap', 'deleted' => false]);
        $this->assertOneDirectoryEntry();
        $this->assertDirectoryEntryExistsForGroup(['name' => 'newFromLdap', 'deleted' => false]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: The same group exists both side but the group on passbolt side has been created before.
     * Expected result: There should be an error
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case18a_Ok_Null_Null_OkCreatedBefore_Null()
    {
        $group = $this->Groups->find()->where(['name' => 'marketing'])->first();
        $creationDate = $group->created;
        $dateAfterCreation = $creationDate->addDays(1);

        $groupData = $this->mockDirectoryGroupData('marketing', ['created' => $dateAfterCreation, 'modified' => $dateAfterCreation]);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $this->assertOrphanDirectoryEntryExists($groupData['id']);
        $this->assertOneDirectoryEntry();
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_ERROR,
            'type' => 'SyncError',
        ];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: The same group exists both side, but has never been synced
     * Expected result: The group should be synced
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case18b_Ok_Null_Null_OkCreatedBefore_Null()
    {
        $group = $this->Groups->find()->where(['name' => 'marketing'])->first();
        $creationDate = $group->created;
        $dateBeforeCreation = $creationDate->subDays(1);

        $groupData = $this->mockDirectoryGroupData('marketing', ['created' => $dateBeforeCreation, 'modified' => $dateBeforeCreation]);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $this->assertDirectoryEntryExistsForGroup(['name' => 'marketing']);
        $this->assertOneDirectoryEntry();
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SYNC,
            'type' => Alias::MODEL_GROUPS,
        ];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: A group has been marked as to be ignored
     * Expected result: It should be ignored and a report should be done
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case19a_Ok_Null_Null_Ok_Ignore()
    {
        $group = $this->Groups->find()->where(['name' => 'marketing'])->first();
        $groupData = $this->mockDirectoryGroupData('marketing');
        $this->mockDirectoryIgnore($group->id, Alias::MODEL_GROUPS);
        $reports = $this->action->execute();
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_GROUPS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertOrphanDirectoryEntryExists(UuidFactory::uuid('ldap.group.id.marketing'));
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('group.id.marketing')]);
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
    }

    /**
     * Scenario: Group was added in ldap and a similar deleted group already exist and was deleted before
     * Expected result: Group should be added and a directory entry should be created
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case19b_Ok_Null_Null_DeletedBefore_Ignore()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $this->mockDirectoryIgnore($group->id, Alias::MODEL_GROUPS);
        $this->mockDirectoryGroupData('deleted', ['created' => $dateAfterDeletion, 'modified' => $dateAfterDeletion]);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_IGNORE
        ];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertGroupNotExist(null, ['name' => 'deleted', 'deleted' => false]);
    }

    /**
     * Scenario: Group was added in ldap and a similar deleted group already exist and was deleted after and is ignored
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case19c_Ok_Null_Null_DeletedAfter_Ignore()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateBeforeDeletion = $deletionDate->subDays(1);
        $groupData = $this->mockDirectoryGroupData('deleted', ['created' => $dateBeforeDeletion, 'modified' => $dateBeforeDeletion]);
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.deleted'), Alias::MODEL_GROUPS);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_IGNORE,
            'type' => 'DirectoryIgnore'
        ];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertGroupNotExist(null, ['name' => 'deleted', 'deleted' => false]);
        $this->assertDirectoryIgnoreNotEmpty();
    }

    /**
     * Scenario: Group was added in ldap and a similar deleted group already exist and was deleted before
     * Expected result: Group should be added and a directory entry should be created
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case20a_Ok_Null_Null_DeletedBefore_Null()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $groupData = $this->mockDirectoryGroupData('deleted', ['created' => $dateAfterDeletion, 'modified' => $dateAfterDeletion]);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SUCCESS
        ];
        $this->assertReport($reports[0], $expectedReport);

        $group = $this->assertGroupExist(null, ['name' => 'deleted', 'deleted' => false]);
        $this->assertOneDirectoryEntry();
        $this->assertDirectoryEntryExists([
            'id' => $groupData['id'],
            'foreign_model' => Alias::MODEL_GROUPS,
            'foreign_key' => $group['id']
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
    public function testDirectorySyncGroup_Case20b_Ok_Null_Null_DeletedAfter_Null()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateBeforeDeletion = $deletionDate->subDays(1);

        $groupData = $this->mockDirectoryGroupData('deleted', ['created' => $dateBeforeDeletion, 'modified' => $dateBeforeDeletion]);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_ERROR,
            'type' => 'SyncError',
        ];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertGroupNotExist(null, ['name' => 'deleted', 'deleted' => false]);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertOrphanDirectoryEntryExists(UuidFactory::uuid('ldap.group.id.deleted'));
    }

    /**
     * Scenario: Previous group creation failed
     * Expected result: Try to create, then success
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case21_Ok_Error_Null_Null_Null()
    {
        $groupData = $this->mockDirectoryGroupData('testgroup');

        // Add directoryEntry for error
        $this->mockDirectoryEntryGroup('testgroup', null, null, null, null, false);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SUCCESS
        ];
        $this->assertReport($reports[0], $expectedReport);

        $group = $this->assertGroupExist(null, ['name' => 'testgroup', 'deleted' => false]);
        $this->assertOneDirectoryEntry();
        $this->assertDirectoryEntryExists([
            'id' => $groupData['id'],
            'foreign_model' => Alias::MODEL_GROUPS,
            'foreign_key' => $group['id']
        ]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: Previous group creation failed and group has been created manually
     * Expected result: Update status and send report
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case22a_Ok_Error_Null_Ok_Null()
    {
        $group = $this->Groups->find()->where(['name' => 'marketing'])->first();
        $creationDate = $group->created;
        $dateAfterCreation = $creationDate->addDays(1);

        $groupData = $this->mockDirectoryGroupData('marketing', ['created' => $dateAfterCreation, 'modified' => $dateAfterCreation]);
        $this->mockOrphanDirectoryEntryGroup('marketing');
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_ERROR,
            'type' => 'SyncError'
        ];
        $this->assertReport($reports[0], $expectedReport);

        $this->assertGroupExist(null, ['name' => 'marketing', 'deleted' => false]);
        $this->assertOneDirectoryEntry();
        $this->assertOrphanDirectoryEntryExists(UuidFactory::uuid('ldap.group.id.marketing'));
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: Previous group creation failed and group has been created manually
     * Expected result: Update status and send report
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case22b_Ok_Error_Null_Ok_Null()
    {
        $group = $this->Groups->find()->where(['name' => 'marketing'])->first();
        $creationDate = $group->created;
        $dateBeforeCreation = $creationDate->subDays(1);

        $groupData = $this->mockDirectoryGroupData('marketing', ['created' => $dateBeforeCreation, 'modified' => $dateBeforeCreation]);
        $this->mockOrphanDirectoryEntryGroup('marketing');
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SYNC,
            'type' => Alias::MODEL_GROUPS
        ];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertGroupExist(null, ['name' => 'marketing', 'deleted' => false]);
        $this->assertOneDirectoryEntry();
        $this->assertDirectoryEntryExistsForGroup(['name' => 'marketing', 'deleted' => false]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: A group could not be synced and then was marked as ignore
     * Expected result: The corresponding entry should be removed and the group ignored
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case23a_Ok_Error_Null_Ok_Ignore()
    {
        $group = $this->Groups->find()->where(['name' => 'marketing'])->first();

        $this->mockDirectoryGroupData('marketing');
        $this->mockOrphanDirectoryEntryGroup('marketing');
        $this->mockDirectoryIgnore($group->id, Alias::MODEL_GROUPS);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_IGNORE
        ];
        $this->assertReport($reports[0], $expectedReport);
    }

    /**
     * Scenario: Group was added in ldap and a similar deleted group already exist and was deleted before, and the group is marked as ignored
     * Expected result: Delete directory entry, return ignore report
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case23b_Ok_Error_Null_DeletedBefore_Ignore()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $this->mockDirectoryEntryGroup('deleted');
        $this->mockDirectoryIgnore($group->id, Alias::MODEL_GROUPS);
        $this->mockDirectoryGroupData('deleted', ['created' => $dateAfterDeletion, 'modified' => $dateAfterDeletion]);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_IGNORE
        ];
        $this->assertReport($reports[0], $expectedReport);

        $this->assertGroupNotExist(null, ['name' => 'deleted', 'deleted' => false]);

        $this->assertDirectoryIgnoreNotEmpty();
    }

    /**
     * Scenario: Group was added in ldap and a similar deleted group already exist and was deleted after and is ignored
     * Expected result: Delete directory entry, return ignore report
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case23c_Ok_Error_Null_DeletedAfter_Ignore()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateBeforeDeletion = $deletionDate->subDays(1);

        $this->mockOrphanDirectoryEntryGroup('deleted');
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.deleted'), Alias::MODEL_GROUPS);
        $groupData = $this->mockDirectoryGroupData('deleted', ['created' => $dateBeforeDeletion, 'modified' => $dateBeforeDeletion]);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_IGNORE
        ];
        $this->assertReport($reports[0], $expectedReport);

        $this->assertGroupNotExist(null, ['name' => 'deleted', 'deleted' => false]);

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
    public function testDirectorySyncGroup_Case24a_Ok_Error_Null_DeletedBefore_Null()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $groupData = $this->mockDirectoryGroupData('deleted', ['created' => $dateAfterDeletion, 'modified' => $dateAfterDeletion]);

        // Add directoryEntry for success.
        $this->mockDirectoryEntryGroup('deleted');

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SUCCESS
        ];
        $this->assertReport($reports[0], $expectedReport);

        $group = $this->assertGroupExist(null, ['name' => 'deleted', 'deleted' => false]);
        // The directory entry should have been updated.
        $this->assertOneDirectoryEntry();
        $this->assertDirectoryEntryExists([
            'id' => $groupData['id'],
            'foreign_model' => Alias::MODEL_GROUPS,
            'foreign_key' => $group['id']
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
    public function testDirectorySyncGroup_Case24b_Ok_Error_Null_DeletedAfter_Null()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateBeforeDeletion = $deletionDate->subDays(1);

        $groupData = $this->mockDirectoryGroupData('deleted', ['created' => $dateBeforeDeletion, 'modified' => $dateBeforeDeletion]);
        $this->mockDirectoryEntryGroup('deleted');

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_ERROR,
            'type' => 'SyncError'
        ];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertGroupNotExist(null, ['name' => 'deleted', 'deleted' => false]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: Previous group creation worked but group was hard deleted in passbolt
     * Expected result: Try to add the group again, then success
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case25_Ok_Success_Null_Null_Null()
    {
        $groupData = $this->mockDirectoryGroupData('newgroup');
        $this->mockDirectoryEntryGroup('newgroup', null, null, null, null, UuidFactory::uuid('group.id.newgroup_old'));
        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SUCCESS
        ];
        $this->assertReport($reports[0], $expectedReport);

        $group = $this->assertGroupExist(null, ['name' => 'newgroup', 'deleted' => false]);

        $this->assertDirectoryEntryExists([
            'id' => $groupData['id'],
            'foreign_model' => Alias::MODEL_GROUPS,
            'foreign_key' => $group->id
        ]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: The group exists and has been synced
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case26_Ok_Success_Null_Ok_Null()
    {
        $this->mockDirectoryGroupData('marketing');
        $this->mockDirectoryEntryGroup('marketing');

        $reports = $this->action->execute();

        $this->assertOneDirectoryEntry();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: A group was synced and then marked as ignore
     * Expected result: The corresponding entry should be removed and the group ignored
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case27a_Ok_Success_Null_Ok_Ignore()
    {
        $this->mockDirectoryGroupData('marketing');
        $this->mockDirectoryEntryGroup('marketing');
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.marketing'), Alias::MODEL_GROUPS);
        $reports = $this->action->execute();
        $this->assertReportEmpty($reports);
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('group.id.marketing')]);
        $this->assertGroupExist(UuidFactory::uuid('group.id.marketing'), ['deleted' => false]);
        $this->assertDirectoryEntryExistsForGroup(['name' => 'marketing']);
    }

    /**
     * Scenario: The same group was already synced successfully, then deleted in passbolt, but for some reason the deletion date is marked before the ldap creation + group is ignored.
     * Expected result: Should be ignored, entry group should be deleted and an ignore report should be generated.
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case27b_Ok_Success_Null_DeletedBefore_Ignore()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $this->mockDirectoryIgnore($group->id, Alias::MODEL_GROUPS);
        $this->mockDirectoryGroupData('deleted', ['created' => $dateAfterDeletion, 'modified' => $dateAfterDeletion]);
        $this->mockDirectoryEntryGroup('deleted');

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_IGNORE
        ];
        $this->assertReport($reports[0], $expectedReport);

        $this->assertGroupNotExist(null, ['name' => 'deleted', 'deleted' => false]);

        $this->assertDirectoryIgnoreNotEmpty();
    }

    /**
     * Scenario: The same group was already synced successfully, then deleted in passbolt.
     * Expected result: Should be ignored, entry group should be deleted and an ignore report should be generated.
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case27c_Ok_Success_Null_DeletedAfter_Ignore()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateBeforeDeletion = $deletionDate->subDays(1);

        $this->mockDirectoryIgnore($group->id, Alias::MODEL_GROUPS);
        $this->mockDirectoryGroupData('deleted', ['created' => $dateBeforeDeletion, 'modified' => $dateBeforeDeletion]);

        // Add directoryEntry for success.
        $this->mockDirectoryEntryGroup('deleted');

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_IGNORE
        ];
        $this->assertReport($reports[0], $expectedReport);

        $this->assertGroupNotExist(null, ['name' => 'deleted', 'deleted' => false]);

        $this->assertDirectoryIgnoreNotEmpty();
    }

    /**
     * Scenario: The same group was already synced successfully, then deleted in passbolt, but for some reason the deletion date is marked before the ldap creation
     * Expected result: group should be created, entry should be marked as success and success report should be generated.
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case28a_Ok_Success_Null_DeletedBefore_Null()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $groupData = $this->mockDirectoryGroupData('deleted', ['created' => $dateAfterDeletion, 'modified' => $dateAfterDeletion]);
        $this->mockDirectoryEntryGroup('deleted');

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SUCCESS
        ];
        $this->assertReport($reports[0], $expectedReport);

        $group = $this->assertGroupExist(null, ['name' => 'deleted', 'deleted' => false]);
        $this->assertDirectoryEntryExists([
            'id' => $groupData['id'],
            'foreign_model' => Alias::MODEL_GROUPS,
            'foreign_key' => $group->id
        ]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: The same group was already synced successfully, then deleted in passbolt.
     * Expected result: Should be ignored, entry should be marked as error and an ignore report should be generated.
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case28b_Ok_Success_Null_DeletedAfter_Null()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateBeforeDeletion = $deletionDate->subDays(1);

        $groupData = $this->mockDirectoryGroupData('deleted', ['created' => $dateBeforeDeletion, 'modified' => $dateBeforeDeletion]);
        $this->mockDirectoryEntryGroup('deleted');

        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_ERROR,
            'type' => 'SyncError'
        ];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertGroupNotExist(null, ['name' => 'deleted', 'deleted' => false]);
        $this->assertOrphanDirectoryEntryExists($groupData['id']);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: Entry is marked as to be ignored
     * Expected result: It should be ignored
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case29a_Ok_Null_Ignore_Null_Null()
    {
        $groupData = $this->mockDirectoryGroupData('newgroup');
        $this->mockDirectoryIgnore($groupData['id'], Alias::MODEL_DIRECTORY_ENTRIES);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_IGNORE
        ];
        $this->assertReport($reports[0], $expectedReport);

        $this->assertDirectoryIgnoreNotEmpty();
    }

    /**
     * Scenario: DirectoryEntry is set to error and is marked as to be ignored
     * Expected result: It should be ignored, directoryEntry should be deleted
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case29b_Ok_Error_Ignore_Null_Null()
    {
        $groupData = $this->mockDirectoryGroupData('newgroup');
        $this->mockDirectoryIgnore($groupData['id'], Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryEntryGroup('newgroup');

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_IGNORE
        ];
        $this->assertReport($reports[0], $expectedReport);

        $this->assertDirectoryIgnoreExist(['id' => $groupData['id']]);
    }

    /**
     * Scenario: DirectoryEntry is set to error and is marked as to be ignored
     * Expected result: It should be ignored, directoryEntry should be deleted
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case29c_Ok_Success_Ignore_Null_Null()
    {
        $groupData = $this->mockDirectoryGroupData('newgroup');
        $this->mockDirectoryIgnore($groupData['id'], Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryEntryGroup('newgroup');

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_IGNORE
        ];
        $this->assertReport($reports[0], $expectedReport);

        $this->assertDirectoryIgnoreExist(['id' => $groupData['id']]);
    }

    /**
     * Scenario: A group was synced and then marked as ignored, and the corresponding entry is also ignored.
     * Expected result: The corresponding entry should be removed and the group ignored
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case30a_Ok_Success_Ignore_Null_Ignore()
    {
        $groupData = $this->mockDirectoryGroupData('newgroup');
        $this->mockDirectoryEntryGroup('newgroup');
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.newgroup'), Alias::MODEL_GROUPS);
        $this->mockDirectoryIgnore($groupData['id'], Alias::MODEL_DIRECTORY_ENTRIES);

        $this->assertDirectoryIgnoreExist(['id' => $groupData['id']]);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_IGNORE
        ];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryIgnoreExist(['id' => $groupData['id']]);
        $this->assertDirectoryIgnoreDoesNotExist(['id' => UuidFactory::uuid('group.id.newgroup')]);
    }

    /**
     * Scenario: A group was synced and then marked as ignored, and the corresponding entry is also ignored.
     * Expected result: The corresponding entry should be removed and the group ignored
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case30b_Ok_Error_Ignore_Ok_Ignore()
    {
        $group = $this->Groups->find()->where(['name' => 'marketing'])->first();
        $groupData = $this->mockDirectoryGroupData('marketing');
        $this->mockDirectoryEntryGroup('marketing');
        $this->mockDirectoryIgnore($group->id, Alias::MODEL_GROUPS);
        $this->mockDirectoryIgnore($groupData['id'], Alias::MODEL_DIRECTORY_ENTRIES);

        $reports = $this->action->execute();

        $this->assertDirectoryIgnoreNotEmpty();
    }

    /**
     * Scenario: The group exists and has been synced, but the group has been ignored
     * Expected result: Delete entry, no report
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case31_Ok_Any_Ignore_Ok_Null()
    {
        $group = $this->Groups->find()->where(['name' => 'marketing'])->first();
        $groupData = $this->mockDirectoryGroupData('marketing');
        $this->mockDirectoryIgnore($groupData['id'], Alias::MODEL_DIRECTORY_ENTRIES);

        $reports = $this->action->execute();

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
    public function testDirectorySyncGroup_Case32_Any_Ok_Ignore_Deleted_Null()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $groupData = $this->mockDirectoryGroupData('deleted');
        $this->mockDirectoryIgnore($groupData['id'], Alias::MODEL_DIRECTORY_ENTRIES);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], Alias::STATUS_IGNORE);

        $this->assertDirectoryIgnoreContains(Alias::MODEL_DIRECTORY_ENTRIES, $groupData['id']);
    }

    /**
     * Scenario: Group was added in ldap, but has invalid name
     * Expected result: Entry should be marked as error
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case33_Invalid_Null_Null_Null_Null()
    {
        $groupName = str_repeat("group", 256);
        $groupData = $this->mockDirectoryGroupData($groupName);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_ERROR
        ];
        $this->assertReport($reports[0], $expectedReport);

        $this->assertGroupNotExist(null, ['name' => $groupName]);
        $this->assertOneDirectoryEntry();
        $entry = $this->assertDirectoryEntryExists([
            'id' => $groupData['id'],
            'foreign_model' => Alias::MODEL_GROUPS,
            'foreign_key IS' => null
        ]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: Group was added in ldap and synced, and now group doesn't validate for some reason
     * Expected result: Entry should be marked as success, and sync report should be sent.
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case34_Invalid_Null_Null_Ok_Null()
    {
        // can't test
        $this->markTestSkipped();
    }

    /**
     * Scenario:
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case35_Invalid_Null_Null_Ok_Ignore()
    {
        $group = $this->Groups->find()->where(['name' => 'marketing'])->first();
        $groupData = $this->mockDirectoryGroupData('marketing'); // is invalid coz already exists.
        $this->mockDirectoryIgnore($group->id, Alias::MODEL_GROUPS);

        $reports = $this->action->execute();

        $this->assertDirectoryIgnoreNotEmpty();
    }

    /**
     * Scenario:
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case36a_Invalid_Null_Null_DeletedBefore_Null()
    {
        // Can't test.
        $this->markTestSkipped();
    }

    /**
     * Scenario:
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case36b_Invalid_Null_Null_DeletedAfter_Null()
    {
        // Can't test.
        $this->markTestSkipped();
    }

    /**
     * Scenario:
     * Expected result: Keep error status (loop on error)
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case37_Invalid_Error_Null_Null_Null()
    {
        $groupName = str_repeat('group', 256);
        $groupData = $this->mockDirectoryGroupData($groupName); // is invalid coz name is too long
        $this->mockDirectoryEntryGroup($groupName);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_GROUPS, 'status' => Alias::STATUS_ERROR];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryIgnoreEmpty();
        $entry = $this->assertDirectoryEntryExists([
            'id' => $groupData['id'],
            'foreign_model' => Alias::MODEL_GROUPS,
            'foreign_key IS' => null
        ]);
    }

    /**
     * Scenario: Group creation failed initially and was fixed manually.
     * Expected result: Mark as success
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case38_Invalid_Error_Null_Ok_Null()
    {
        $group = $this->Groups->find()->where(['name' => 'marketing'])->first();

        $groupName = str_repeat('group', 256);
        $groupData = $this->mockDirectoryGroupData('marketing', ['cn' => $groupName]);
        $this->saveMockDirectoryGroupData($groupData);
        $this->mockOrphanDirectoryEntryGroup('marketing');
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_GROUPS, 'status' => Alias::STATUS_ERROR, 'type' => 'SyncError'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertOrphanDirectoryEntryExists($groupData['id']);
    }

    /**
     * Scenario:
     * Expected result: Set DirEntry to error, send error report
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case41_Invalid_Success_Null_Null_Null()
    {
        $groupName = str_repeat('group', 256);
        $this->mockDirectoryGroupData('newgroup', ['cn' => $groupName]);
        $this->mockDirectoryEntryGroup('newgroup', null, null, null, null, UuidFactory::uuid('group.id.neverexisted'));

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_GROUPS, 'status' => Alias::STATUS_ERROR];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertOneDirectoryEntry();
    }

    /**
     * Scenario:
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case42_Invalid_Success_Null_Ok_Null()
    {
        $invalidGroupName = str_repeat('group', 256);
        $this->mockDirectoryGroupData('marketing', ['cn' => $invalidGroupName]);
        $this->mockDirectoryEntryGroup('marketing', null, null, null, null, UuidFactory::uuid('group.id.marketing'));

        $reports = $this->action->execute();

        $this->assertDirectoryIgnoreEmpty();
        $this->assertOneDirectoryEntry();
    }

    /**
     * Scenario:
     * Expected result: delete DirEntry, no report
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case43_Invalid_Success_Null_Ok_Ignore()
    {
        $invalidGroupName = str_repeat('group', 256);
        $this->mockDirectoryGroupData('marketing', ['cn' => $invalidGroupName]);
        $this->mockDirectoryEntryGroup('marketing', null, null, null, null, UuidFactory::uuid('group.id.marketing'));
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.marketing'), Alias::MODEL_GROUPS);

        $reports = $this->action->execute();

        $this->assertDirectoryIgnoreNotEmpty();
    }

    /**
     * Scenario:
     * Expected result: delete DirEntry, ignore report
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case44a_Invalid_Success_Null_DeletedBefore_Ignore()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $groupName = str_repeat('group', 256);

        $data = $this->mockDirectoryGroupData('deleted', [
            'cn' => $groupName,
            'created' => $dateAfterDeletion,
            'modified' => $dateAfterDeletion
        ]);
        $this->mockDirectoryEntryGroup('deleted');
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.deleted'), Alias::MODEL_GROUPS);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_GROUPS, 'status' => Alias::STATUS_IGNORE];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryIgnoreNotEmpty();
    }

    /**
     * Scenario:
     * Expected result: delete DirEntry, ignore report
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case44b_Invalid_Success_Null_DeletedAfter_Ignore()
    {
        $group = $this->Groups->find()->where(['name' => 'deleted', 'deleted' => true])->first();
        $deletionDate = $group->modified;
        $dateBeforeDeletion = $deletionDate->subDays(1);

        $groupName = str_repeat('group', 256);
        $this->mockDirectoryGroupData('deleted', [
            'cn' => $groupName,
            'created' => $dateBeforeDeletion,
            'modified' => $dateBeforeDeletion
        ]);
        $this->mockDirectoryEntryGroup('deleted', null, null, null, null, UuidFactory::uuid('group.id.deleted'));
        $this->mockDirectoryIgnore(UuidFactory::uuid('group.id.deleted'), Alias::MODEL_GROUPS);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_GROUPS, 'status' => Alias::STATUS_IGNORE];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryIgnoreNotEmpty();
    }

    /**
     * Scenario:
     * Expected result: do nothing, ignore report
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case45_Invalid_Null_Ignore_Null_Null()
    {
        $invalidGroupName = str_repeat('group', 256);
        $this->mockDirectoryGroupData('newgroup', ['cn' => $invalidGroupName]);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.group.id.newgroup'), Alias::MODEL_DIRECTORY_ENTRIES);

        $reports = $this->action->execute();
        $this->assertEquals(count($reports), 1);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_GROUPS, 'status' => Alias::STATUS_IGNORE];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryIgnoreNotEmpty();
    }

    /**
     * Scenario: Group was added in ldap but the test is executed in dry-run
     * Expected result: DB should remain unmodified, but the report should say that a group has been added
     *
     * @group DirectorySync
     * @group DirectorySyncGroup
     * @group DirectorySyncGroupAdd
     */
    public function testDirectorySyncGroup_Case17_DryRun()
    {
        $this->mockDirectoryGroupData('newFromLdap');
        $this->action->setDryRun(true);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = [
            'action' => Alias::ACTION_CREATE,
            'model' => Alias::MODEL_GROUPS,
            'status' => Alias::STATUS_SUCCESS,
            'type' => Alias::MODEL_GROUPS
        ];
        $this->assertReport($reports[0], $expectedReport);
        $reports[0]->getData();
        $this->assertGroupNotExist(null, ['name' => 'newFromLdap', 'deleted' => false]);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }
}
