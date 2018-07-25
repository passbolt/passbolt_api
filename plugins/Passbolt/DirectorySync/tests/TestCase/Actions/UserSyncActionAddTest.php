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
use Passbolt\DirectorySync\Actions\UserSyncAction;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncTestCase;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertUsersTrait;
use Passbolt\DirectorySync\Utility\SyncAction;

class UserSyncActionAddTest extends DirectorySyncTestCase
{
    use AssertUsersTrait;

    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/secrets', 'app.Base/roles',
        'app.Alt0/groups_users', 'app.Alt0/permissions', 'app.Base/avatars',
        'app.Base/favorites', 'app.Base/email_queue',
        'plugin.passbolt/directorySync.base/directoryEntries',
        'plugin.passbolt/directorySync.base/directoryIgnore'
    ];

    /**
     * Scenario: The user is invalid and does not exist in passbolt
     * Expected result: trigger an error
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case23_Invalid_Null_Null()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', null, 'neil@passbolt.com');
        $summary = $this->action->execute();
        $this->assertOneDirectoryEntry(SyncAction::ERROR);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertUserNotExist(['username' => 'neil@passbolt.com']);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::ERROR];
        $this->assertReport($summary[0], $expectedReport);
    }

    /**
     * Scenario: The data is invalid but there is already an active user matching in passbolt
     * Expected result: report record as synced
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case24_Invalid_Null_Active()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com');
        $summary = $this->action->execute();
        $this->assertOneDirectoryEntry(SyncAction::SUCCESS);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::SYNC];
        $this->assertReport($summary[0], $expectedReport);
    }

    /**
     * Scenario: The data is invalid but there is already an inactive user that can match in passbolt
     * Expected result: report record as synced
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case25_Invalid_Null_Inactive()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ruth', null, 'ruth@passbolt.com');
        $summary = $this->action->execute();
        $this->assertOneDirectoryEntry(SyncAction::SUCCESS);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.ruth'), ['active' => false, 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::SYNC];
        $this->assertReport($summary[0], $expectedReport);
    }

    /**
     * Scenario: The data is invalid and there is already a deleted user that can match in passbolt
     *              the user was deleted in passbolt prior to the creation date in the directory
     * Expected result: error report
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case26_Invalid_Null_Deleted_Now()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $summary = $this->action->execute();
        $this->assertOneDirectoryEntry(SyncAction::ERROR);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::ERROR];
        $this->assertReport($summary[0], $expectedReport);
    }

    /**
     * Scenario: The data is invalid and there is already a deleted user that can match in passbolt
     *              the user was deleted in passbolt after the creation date in the directory
     * Expected result: error report
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case26_Invalid_Null_Deleted_Past()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', new FrozenTime('2015-06-15 08:23:45'), new FrozenTime('2015-06-15 08:23:45'));
        $summary = $this->action->execute();
        $this->assertOneDirectoryEntry(SyncAction::ERROR);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::ERROR];
        $this->assertReport($summary[0], $expectedReport);
    }

    /**
     * Scenario: The data is invalid and there is already a deleted user that can match in passbolt
     * Expected result: ignore directory entry,
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case27_Invalid_Ignore_Null()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', null, 'neil@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'neil'], SyncAction::SUCCESS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.neil'), 'DirectoryEntry');
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertUserNotExist(['username' => 'neil@passbolt.com']);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE];
        $this->assertReport($summary[0], $expectedReport);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case28_Invalid_Error_Null()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', null, 'neil@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'neil'], SyncAction::ERROR);
        $summary = $this->action->execute();
        $this->assertUserNotExist(['username' => 'neil@passbolt.com']);
        $this->assertDirectoryIgnoreEmpty();
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::ERROR];
        $this->assertReport($summary[0], $expectedReport);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case29_Invalid_Success_Null()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', null, 'neil@passbolt.com');
        $mock = ['fname' => 'neil', 'lname' => 'armstrong', 'foreign_key' => 'null'];
        $this->mockDirectoryEntryUser($mock, SyncAction::SUCCESS);
        $summary = $this->action->execute();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryExists([
            'id' => UuidFactory::uuid('ldap.user.id.neil'),
            'foreign_key IS' => null,
            'status' => SyncAction::ERROR
        ]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::ERROR];
        $this->assertReport($summary[0], $expectedReport);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case30_Invalid_Ignore_Active()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.ada'), 'DirectoryEntry');
        $mock = ['fname' => 'ada', 'foreign_key' => UuidFactory::uuid('user.id.ada')];
        $this->mockDirectoryEntryUser($mock, SyncAction::SUCCESS);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('ldap.user.id.ada')]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE];
        $this->assertReport($summary[0], $expectedReport);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case31_Invalid_Error_Active()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'ada'], SyncAction::ERROR);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.ada'), 'status' => SyncAction::SUCCESS]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::SYNC];
        $this->assertReport($summary[0], $expectedReport);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case32_Invalid_Success_Active()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'ada'], SyncAction::SUCCESS);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.ada'), 'status' => SyncAction::SUCCESS]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::SYNC];
        $this->assertReport($summary[0], $expectedReport);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case33_Invalid_Ignore_Inactive()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ruth', null, 'ruth@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'ruth'], SyncAction::SUCCESS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.ruth'), 'DirectoryEntry');
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist();
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE];
        $this->assertReport($summary[0], $expectedReport);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case34_Invalid_Error_Inactive()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ruth', null, 'ruth@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'ruth'], SyncAction::ERROR);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.ruth'), 'status' => SyncAction::SUCCESS]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::SYNC];
        $this->assertReport($summary[0], $expectedReport);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case35_Invalid_Success_Inactive()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ruth', null, 'ruth@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'ruth'], SyncAction::SUCCESS);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.ruth'), 'status' => SyncAction::SUCCESS]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::SYNC];
        $this->assertReport($summary[0], $expectedReport);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case36_Invalid_Ignore_Deleted()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'sofia'], SyncAction::SUCCESS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.sofia'), 'DirectoryEntry');
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist();
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE];
        $this->assertReport($summary[0], $expectedReport);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case37_Invalid_Error_Deleted()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', null, 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case38_Invalid_Success_Deleted()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', null, 'neil@passbolt.com');
        $summary = $this->action->execute();
    }
    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case39_Invalid_Error_Ignore()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', null, 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case40_Invalid_Null_Ignore()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', null, 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case41_Invalid_Success_Ignore()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', null, 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case42_Invalid_Ignore_Ignore()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', null, 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case43_Invalid_Error_Ignore()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', null, 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case44_Valid_Null_Null()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case45_Valid_Null_Active()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case46_Valid_Null_Inactive()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case47_Valid_Null_Deleted()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case48_Valid_Ignore_Null()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case49_Valid_Error_Null()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }


    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case50_Valid_Success_Null()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case51_Valid_Ignore_Active()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case52_Valid_Error_Active()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case53_Valid_Success_Active()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case54_Valid_Ignore_Inactive()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case55_Valid_Error_Inactive()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case56_Valid_Success_Inactive()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case57_Valid_Ignore_Deleted()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case58_Valid_Error_Deleted()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case59_Valid_Success_Deleted()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case60_()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case61_()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case62_()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
    }
}