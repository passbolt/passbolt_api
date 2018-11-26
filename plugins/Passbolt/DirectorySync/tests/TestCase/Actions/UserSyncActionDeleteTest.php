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
use Passbolt\DirectorySync\Actions\UserSyncAction;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertUsersTrait;
use Passbolt\DirectorySync\Utility\Alias;

class UserSyncActionDeleteTest extends DirectorySyncIntegrationTestCase
{
    use AssertUsersTrait;

    public function setUp()
    {
        parent::setUp();
        $this->initAction();
    }

    /**
     * Init the action
     */
    protected function initAction()
    {
        $this->action = new UserSyncAction();
        $this->action->getDirectory()->setGroups([]);
    }

    /**
     * Scenario: No data anywhere
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case01_Null_Null_Null()
    {
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
        $this->assertTrue(true, true); // there is no spoon.
    }

    /**
     * Scenario: User is OK (active or inactive) in passbolt and not present in the directory
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case02_Null_Null_OK()
    {
        $this->action->execute();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['deleted' => false, 'active' => true]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ruth'), ['deleted' => false, 'active' => false]);
    }

    /**
     * Scenario: User is deleted in passbolt and not present in the directory
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case03_Null_Null_Deleted()
    {
        $reports = $this->action->execute();
        $this->assertReportEmpty($reports);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['deleted' => true, 'active' => true]);
    }

    /**
     * Scenario: User was hard deleted manually and some associated data are still present
     * Expected result: Cleanup
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case04a_Null_Null_Null_with_orphan_ignore()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.mia'), Alias::MODEL_USERS);
        $reports = $this->action->execute();
        $this->assertReportEmpty($reports);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: User is active|inactive in passbolt and marked to be ignored and not present in the directory
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case04b_Null_Null_Ignore()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), Alias::MODEL_USERS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ruth'), Alias::MODEL_USERS);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreContains(Alias::MODEL_USERS, UuidFactory::uuid('user.id.ruth'));
        $this->assertDirectoryIgnoreContains(Alias::MODEL_USERS, UuidFactory::uuid('user.id.ada'));
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['deleted' => false, 'active' => true]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ruth'), ['deleted' => false, 'active' => false]);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: User is not active in passbolt and marked to be ignored and not present in the directory
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case04c_Null_Null_Ignore()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), Alias::MODEL_USERS);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['deleted' => true, 'active' => true]);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreContains(Alias::MODEL_USERS, UuidFactory::uuid('user.id.sofia'));
    }

    /**
     * Scenario:
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case05a_Null_Ignore_Null_Orphan()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.mia'), Alias::MODEL_DIRECTORY_ENTRIES);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario:
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case05b_Null_NoAssoc_Ignore_Null_Null()
    {
        $this->mockOrphanDirectoryEntryUser(['fname' => 'mia']);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.mia'), Alias::MODEL_DIRECTORY_ENTRIES);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario:
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case05c_Null_Ignore_Null()
    {
        $this->mockDirectoryEntryUser(['fname' => 'mia']);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.mia'), Alias::MODEL_DIRECTORY_ENTRIES);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: the user is deleted in the directory and user is inactive and marked as to be ignored in the directory
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case06a_Null_Ignore_OK()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.ruth'), Alias::MODEL_DIRECTORY_ENTRIES);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.ruth'), ['deleted' => false, 'active' => false]);
    }

    /**
     * Scenario:
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case06b_Null_Ignore_OK()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.ruth'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockOrphanDirectoryEntryUser(['fname' => 'ruth', 'lname' => 'teitelbaum']);
        $report = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.ruth'), ['deleted' => false, 'active' => false]);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertReportEmpty($report);
    }

    /**
     * Scenario:
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case06c_Null_Ignore_OK()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.ruth'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryEntryUser(['fname' => 'ruth', 'lname' => 'teitelbaum']);
        $report = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.ruth'), ['deleted' => false, 'active' => false]);
        $this->assertReportNotEmpty($report);
        $this->assertReport($report[0], ['status' => Alias::STATUS_IGNORE, 'type' => Alias::MODEL_DIRECTORY_ENTRIES]);
    }

    /**
     * Scenario: User is deleted in directory and both directory entry and user are marked to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case07a_Null_Ignore_Ignore()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.nope'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.nope'), Alias::MODEL_USERS);
        $report = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: User is deleted in directory and both directory entry and user aare marked to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case07b_Null_Ignore_Ignore()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.nope'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.nope'), Alias::MODEL_USERS);
        $this->mockOrphanDirectoryEntryUser(['fname' => 'nope', 'lname' => 'nope']);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: User is deleted in directory and both directory entry and user aare marked to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case07c_Null_Ignore_Ignore()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.nope'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.nope'), Alias::MODEL_USERS);
        $this->mockDirectoryEntryUser(['fname' => 'nope', 'lname' => 'nope']);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: The user is deleted in directory and passbolt and user was marked as to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case08a_Null_Ignore_Deleted()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.sofia'), Alias::MODEL_DIRECTORY_ENTRIES);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['deleted' => true, 'active' => true]);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: The user is deleted in directory and passbolt and user was marked as to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case08b_Null_Ignore_Deleted()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.sofia'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockOrphanDirectoryEntryUser(['fname' => 'sofia', 'lname' => 'kovalevskaya']);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['deleted' => true, 'active' => true]);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: The user is deleted in directory and passbolt and user was marked as to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case08c_Null_Ignore_Deleted()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.sofia'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryEntryUser(['fname' => 'sofia', 'lname' => 'kovalevskaya']);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['deleted' => true, 'active' => true]);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario:
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case09_Null_Error_Null()
    {
        $this->mockOrphanDirectoryEntryUser(['fname' => 'absent']);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: The user was deleted in LDAP and could not be previously deleted in passbolt
     * Expected result: user is deleted
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case10a_Null_Error_OK()
    {
        $this->mockOrphanDirectoryEntryUser(['fname' => 'frances', 'lname' => 'allen']);
        $reports = $this->action->execute();
        $this->assertReportEmpty($reports);
        $this->assertUserExist(UuidFactory::uuid('user.id.frances'), ['deleted' => false]);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: The user was deleted in LDAP and could not be previously deleted in passbolt
     * Expected result: raise an error and delete directory entry but not the user
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case10b_Null_Error_OK_not_deletable()
    {
        $this->mockOrphanDirectoryEntryUser(['fname' => 'ada', 'lname' => 'lovelace']);
        $reports = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['deleted' => false]);
        $this->assertReportEmpty($reports);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: the user is deleted in the directory and user is inactive and there was a previous error in sync
     * Expected result: delete user and directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case10a_Null_Error_OK_Inactive()
    {
        $this->mockOrphanDirectoryEntryUser(['fname' => 'ruth', 'lname' => 'teitelbaum']);
        $reports = $this->action->execute();
        $this->assertReportEmpty($reports);
        $this->assertUserExist(UuidFactory::uuid('user.id.ruth'), ['deleted' => false]);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: User is deleted in directory, previous sync was an error and user is marked to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case11a_Null_Error_Ignore()
    {
        $this->mockOrphanDirectoryEntryUser(['fname' => 'frances', 'lname' => 'allen']);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.frances'), Alias::MODEL_USERS);
        $reports = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.frances'), ['deleted' => false, 'active' => true]);
        $this->assertReportEmpty($reports);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreNotEmpty();
    }

    /**
     * Scenario: User is deleted in directory, previous sync was an error and user is marked to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case11b_Null_Error_Ignore()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), Alias::MODEL_USERS);
        $this->mockOrphanDirectoryEntryUser(['fname' => 'sofia', 'lname' => 'kovalevskaya']);
        $reports = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['deleted' => true, 'active' => true]);
        $this->assertReportEmpty($reports);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreNotEmpty();
    }

    /**
     * Scenario: The user is deleted in directory and manually deleted in passbolt between sync after an error
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case12_Null_Error_Deleted()
    {
        $this->mockOrphanDirectoryEntryUser(['fname' => 'sofia', 'lname' => 'kovalevskaya']);
        $reports = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['deleted' => true, 'active' => true]);
        $this->assertReportEmpty($reports);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: User is marked as to be ignored and is not present in ldap
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case13_Null_Success_Null()
    {
        $this->mockDirectoryEntryUser(['fname' => 'not present']);
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryEmpty();
        $this->assertReportEmpty($reports);
    }

    /**
     * Scenario: the user is active in passbolt and not present in the directory and can be deleted
     * Expected result: user is deleted
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case14a_Null_Success_OK_deletable()
    {
        $this->mockDirectoryEntryUser(['fname' => 'thelma', 'lname' => 'estrin']);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_DELETE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_SUCCESS, 'type' => 'User'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.thelma'), ['deleted' => true]);
    }

    /**
     * Scenario: the user is deleted in the directory and user is inactive
     * Expected result: delete user and directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case14a_Null_Success_OK_Inactive()
    {
        $this->mockDirectoryEntryUser(['fname' => 'ruth', 'lname' => 'teitelbaum']);
        $reports = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.ruth'), ['deleted' => true, 'active' => false]);
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_DELETE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_SUCCESS, 'type' => 'User'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: the user is active in passbolt and not present in the directory and not deletable
     * Expected result: raise an error and user is not deleted
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case14b_Null_Success_OK_not_deletable()
    {
        $this->mockDirectoryEntryUser(['fname' => 'ada', 'lname' => 'lovelace']);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_DELETE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_ERROR, 'type' => 'SyncError'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertOneDirectoryEntry();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: the user is active in passbolt and not present in the directory and can be deleted
     *            and the plugin configuration is set to ignore deleted users
     * Expected result: nothing
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case14_Null_Success_OK_deletable_no_delete_job()
    {
        $this->disableSyncOperation('users', 'delete');
        $this->initAction();

        $this->mockDirectoryEntryUser(['fname' => 'frances', 'lname' => 'allen']);
        $report = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.frances'), ['deleted' => false]);
        $this->assertOneDirectoryEntry();
        $this->assertReportEmpty($report);
    }

    /**
     * Scenario: The user is deleted in directory and user is marked as to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case15a_Null_Success_Ignore()
    {
        $this->mockDirectoryEntryUser(['fname' => 'ruth', 'lname' => 'teitelbaum']);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ruth'), Alias::MODEL_USERS);
        $reports = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.ruth'), ['deleted' => false, 'active' => false]);
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_DELETE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreNotEmpty();
    }

    /**
     * Scenario: The user is deleted in directory and user is marked as to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case15b_Null_Success_Ignore()
    {
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), Alias::MODEL_USERS);
        $this->mockDirectoryEntryUser(['fname' => 'sofia', 'lname' => 'kovalevskaya']);
        $reports = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['deleted' => true, 'active' => true]);
        $this->assertReportEmpty($reports);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreNotEmpty();
    }

    /**
     * Scenario: The user is deleted in directory and already deleted in passbolt between sync
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case16_Null_Success_Deleted()
    {
        $this->mockDirectoryEntryUser(['fname' => 'sofia', 'lname' => 'kovalevskaya']);
        $reports = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['deleted' => true, 'active' => true]);
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_DELETE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_SYNC, 'type' => 'User'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: a user should be deleted but dry-run is set to true.
     * Expected result: the user should not be deleted, and the database should not be modified.
     */
    public function testDryRunDoNotDeleteEntity()
    {
        $this->mockDirectoryEntryUser(['fname' => 'thelma', 'lname' => 'estrin']);
        $this->action->setDryRun(true);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_DELETE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_SUCCESS, 'type' => 'User'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertOneDirectoryEntry();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.thelma'), ['deleted' => false]);
    }
}
