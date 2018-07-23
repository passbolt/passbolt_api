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
use Passbolt\DirectorySync\Actions\UserSyncAction;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncTestCase;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertUsersTrait;
use Passbolt\DirectorySync\Utility\SyncAction;

class UserSyncActionDeleteTest extends DirectorySyncTestCase
{
    use AssertUsersTrait;

    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/secrets',
        'app.Alt0/groups_users', 'app.Alt0/permissions', 'app.Base/avatars',
        'app.Base/favorites', 'app.Base/email_queue',
        'plugin.passbolt/directorySync.base/directoryEntries',
        'plugin.passbolt/directorySync.base/directoryIgnore'
    ];

    /**
     * Scenario: User is active in passbolt and not present in the directory
     * Scenario: User is not active in passbolt and not present in the directory
     * Scenario: User is deleted in passbolt and not present in the directory
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case00_03_Null_Null_Any()
    {
        $this->action = new UserSyncAction();
        $this->action->getDirectory()->setUsers([]);
        $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['deleted' => false, 'active' => true]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ruth'), ['deleted' => false, 'active' => false]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['deleted' => true, 'active' => true]);
    }

    /**
     * Scenario: User is active in passbolt and marked to be ignored and not present in the directory
     * Scenario: User is not active in passbolt and marked to be ignored and not present in the directory
     * Scenario: User is deleted in passbolt and marked to be ignored and not present in the directory
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case04_Null_Null_Ignore()
    {
        $this->action = new UserSyncAction();
        $this->action->getDirectory()->setUsers([]);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), 'User');
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ruth'), 'User');
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), 'User');
        $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['deleted' => false, 'active' => true]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ruth'), ['deleted' => false, 'active' => false]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['deleted' => true, 'active' => true]);
    }

    /**
     * Scenario: User is marked as to be ignored and is not present in ldap
     * Expected result: Do nothing
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case05_07_Null_Any_Null()
    {
        $this->action = new UserSyncAction();
        $this->action->getDirectory()->setUsers([]);
        $this->mockDirectoryEntryUser('not', 'present', DirectoryEntry::STATUS_SUCCESS);
        $this->mockDirectoryEntryUser('also', 'absent', SyncAction::ERROR);
        $this->mockDirectoryEntryUser('mia', 'mia', SyncAction::ERROR);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.mia'), 'DirectoryEntry');
        $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: The user was deleted in LDAP and could not be previously deleted in passbolt
     * Expected result: user is deleted
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case09_User_deletable_Retry_Success()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('frances', 'allen', 'frances@passbolt.com');
        $this->mockDirectoryEntryUser('frances', 'allen', SyncAction::ERROR);
        $reports = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.frances'), ['deleted' => true]);
        $this->assertDirectoryEntryEmpty();
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::SUCCESS);
    }

    /**
     * Scenario: The user was deleted in LDAP and could not be previously deleted in passbolt
     * Expected result: raise an error and delete directory entry but not the user
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case10_User_Not_deletable_Retry_Error()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com');
        $this->mockDirectoryEntryUser('ada', 'lovelace', SyncAction::ERROR);
        $reports = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.frances'), ['deleted' => false]);
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::ERROR);
    }

    /**
     * Scenario: the user is active in passbolt and not present in the directory and not deletable
     * Expected result: raise an error and user is not deleted
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case11_User_Not_deletable()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com');
        $this->mockDirectoryEntryUser('ada', 'lovelace', DirectoryEntry::STATUS_SUCCESS);
        $reports = $this->action->execute();
        $this->assertOneDirectoryEntry(SyncAction::ERROR);
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::ERROR);
    }

    /**
     * Scenario: the user is active in passbolt and not present in the directory and can be deleted
     * Expected result: user is deleted
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case12_User_deletable()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('frances', 'allen', 'frances@passbolt.com');
        $this->mockDirectoryEntryUser('frances', 'allen', DirectoryEntry::STATUS_SUCCESS);
        $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.frances'), ['deleted' => true]);
        $this->assertDirectoryEntryEmpty();
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
    public function testDirectorySyncUserDelete_Case13_User_deletable_No_Delete_Job()
    {
        Configure::write('passbolt.plugins.directorySync.jobs.users.delete', false);
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('frances', 'allen', 'frances@passbolt.com');
        $this->mockDirectoryEntryUser('frances', 'allen', DirectoryEntry::STATUS_SUCCESS);
        $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.frances'), ['deleted' => false]);
        $this->assertOneDirectoryEntry(DirectoryEntry::STATUS_SUCCESS);
    }

    /**
     * Scenario: the user is deleted in the directory and user is inactive and marked as to be ignored in the directory
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case14_Null_Ignore_Inactive()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.ruth'), 'DirectoryEntry');
        $this->mockDirectoryEntryUser('ruth', 'teitelbaum', DirectoryEntry::STATUS_SUCCESS);
        $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.ruth'), ['deleted' => false, 'active' => false]);
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: the user is deleted in the directory and user is inactive and there was a previous error in sync
     * Expected result: delete user and directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case15_Null_Error_Inactive()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryEntryUser('ruth', 'teitelbaum', SyncAction::ERROR);
        $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.ruth'), ['deleted' => true, 'active' => false]);
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: the user is deleted in the directory and user is inactive
     * Expected result: delete user and directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case16_Null_Success_Inactive()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryEntryUser('ruth', 'teitelbaum', DirectoryEntry::STATUS_SUCCESS);
        $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.ruth'), ['deleted' => true, 'active' => false]);
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
    public function testDirectorySyncUserDelete_Case17_Null_Ignore_Deleted()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.sofia'), 'DirectoryEntry');
        $this->mockDirectoryEntryUser('sofia', 'kovalevskaya', DirectoryEntry::STATUS_SUCCESS);
        $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['deleted' => true, 'active' => true]);
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: The user is deleted in directory and manually deleted in passbolt between sync after an error
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case18_Null_Error_Deleted()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryEntryUser('sofia', 'kovalevskaya', SyncAction::ERROR);
        $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['deleted' => true, 'active' => true]);
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: The user is deleted in directory and already deleted in passbolt between sync
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case19_Null_Success_Deleted()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryEntryUser('sofia', 'kovalevskaya', DirectoryEntry::STATUS_SUCCESS);
        $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['deleted' => true, 'active' => true]);
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: The user is deleted in directory and user is marked as to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case20_Null_Success_Ignore()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), 'User');
        $this->mockDirectoryEntryUser('sofia', 'kovalevskaya', SyncAction::ERROR);
        $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['deleted' => true, 'active' => true]);
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: User is deleted in directory and both directory entry and user aare marked to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case21_Null_Ignore_Ignore()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.sofia'), 'DirectoryEntry');
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), 'User');
        $this->mockDirectoryEntryUser('sofia', 'kovalevskaya', DirectoryEntry::STATUS_SUCCESS);
        $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['deleted' => true, 'active' => true]);
        $this->assertDirectoryEntryEmpty();

        // Same with error
        $this->action = new UserSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.sofia'), 'DirectoryEntry');
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), 'User');
        $this->mockDirectoryEntryUser('sofia', 'kovalevskaya', SyncAction::ERROR);
        $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['deleted' => true, 'active' => true]);
        $this->assertDirectoryEntryEmpty();
    }

    /**
     * Scenario: User is deleted in directory, previous sync was an error and user is marked to be ignored
     * Expected result: delete directory entry
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testDirectorySyncUserDelete_Case22_Null_Error_Ignore()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), 'User');
        $this->mockDirectoryEntryUser('sofia', 'kovalevskaya', SyncAction::ERROR);
        $reports = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['deleted' => true, 'active' => true]);
        $this->assertDirectoryEntryEmpty();
        $this->assertEquals(count($reports), 1);
        $this->assertReportStatus($reports[0], SyncAction::IGNORE);
    }
}