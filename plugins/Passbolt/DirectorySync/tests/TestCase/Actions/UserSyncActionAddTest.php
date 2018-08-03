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
use Cake\I18n\FrozenTime;
use Passbolt\DirectorySync\Actions\UserSyncAction;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncTestCase;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertUsersTrait;
use Passbolt\DirectorySync\Utility\SyncAction;

class UserSyncActionAddTest extends DirectorySyncTestCase
{
    use AssertUsersTrait;

    public $fixtures = [
        'app.Base/users', 'app.Base/profiles', 'app.Base/groups', 'app.Base/secrets', 'app.Base/roles',
        'app.Alt0/groups_users', 'app.Alt0/permissions', 'app.Base/avatars',
        'app.Base/favorites', 'app.Base/email_queue', 'app.Base/authentication_tokens',
        'plugin.passbolt/directorySync.base/directoryEntries',
        'plugin.passbolt/directorySync.base/directoryIgnore'
    ];

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case17_Valid_Null_Null()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $summary = $this->action->execute();
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::SUCCESS, 'type' => SyncAction::USERS];
        $this->assertReport($summary[0], $expectedReport);
        $data = $summary[0]->getData();
        $this->assertUserExist($data->id, ['username' => 'neil@passbolt.com', 'active' => false, 'deleted' => false]);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case18_Valid_Null_Active()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com');
        $summary = $this->action->execute();
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.ada'), 'status' => SyncAction::SUCCESS]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::SYNC, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case18_Valid_Null_Inactive()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ruth', 'teitelbaum', 'ruth@passbolt.com');
        $summary = $this->action->execute();
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.ruth'), 'status' => SyncAction::SUCCESS]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ruth'), ['active' => false, 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::SYNC, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case19a_Valid_Null_OK_Ignore()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), SyncAction::USERS);
        $reports = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $this->assertReportEmpty($reports);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case19b_Valid_Null_Ignore_Deleted_Before()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', new FrozenTime('2015-06-15 08:23:45'), new FrozenTime('2015-06-15 08:23:45'));
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), SyncAction::USERS);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case19c_Valid_Null_Ignore_Deleted_After()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), SyncAction::USERS);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case20a_Valid_Null_Deleted_Before()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $summary = $this->action->execute();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $data = $summary[0]->getData();
        $this->assertUserExist($data->id, ['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::SUCCESS, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case20b_Valid_Null_Deleted_After()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', new FrozenTime('2015-06-15 08:23:45'), new FrozenTime('2015-06-15 08:23:45'));
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case21_Valid_Error_Null()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'neil'], SyncAction::ERROR);
        $summary = $this->action->execute();
        $this->assertDirectoryIgnoreEmpty();
        $data = $summary[0]->getData();
        $this->assertUserExist($data->id, ['username' => 'neil@passbolt.com', 'deleted' => false]);
        $this->assertDirectoryEntryExists(['foreign_key' => $data->id, 'status' => SyncAction::SUCCESS]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::SUCCESS, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case22_Valid_Error_Active()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com');
        $mock = ['fname' => 'ada', 'foreign_key' => 'null'];
        $this->mockDirectoryEntryUser($mock, SyncAction::ERROR);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryExists(['foreign_key' => UuidFactory::uuid('user.id.ada')]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::SYNC, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case22_Valid_Error_Inactive()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ruth', 'teitelbaum', 'ruth@passbolt.com');
        $mock = ['fname' => 'ruth', 'foreign_key' => 'null'];
        $this->mockDirectoryEntryUser($mock, SyncAction::ERROR);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryExists(['foreign_key' => UuidFactory::uuid('user.id.ruth')]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::SYNC, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case23_Valid_Error_Ignore_OK()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $this->mockDirectoryEntryUser(['fname' => 'ada'], SyncAction::ERROR);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), SyncAction::USERS);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case23_Valid_Error_Ignore_Deleted_Before()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $this->mockDirectoryEntryUser(['fname' => 'sofia'], SyncAction::ERROR);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), SyncAction::USERS);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case24b_Valid_Error_Deleted_Before()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $mock = ['fname' => 'sofia', 'foreign_key' => 'null'];
        $this->mockDirectoryEntryUser($mock, SyncAction::ERROR);
        $summary = $this->action->execute();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $data = $summary[0]->getData();
        $this->assertUserExist($data->id, ['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $this->assertDirectoryEntryExists(['foreign_key' => $data->id]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::SUCCESS];
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
    public function testDirectorySyncUserAdd_Case24b_Valid_Error_Deleted_After()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', new FrozenTime('2015-06-15 08:23:45'), new FrozenTime('2015-06-15 08:23:45'));
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case25_Valid_Success_Null()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'neil'], SyncAction::SUCCESS);
        $summary = $this->action->execute();
        $this->assertDirectoryIgnoreEmpty();
        $data = $summary[0]->getData();
        $this->assertUserExist($data->id, ['username' => 'neil@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::SUCCESS, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case26_Valid_Success_Active()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com');
        $mock = ['fname' => 'ada', 'foreign_key' => UuidFactory::uuid('user.id.ada')];
        $this->mockDirectoryEntryUser($mock, SyncAction::SUCCESS);
        $summary = $this->action->execute();
        $this->assertReportEmpty($summary);
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.ada'), 'status' => SyncAction::SUCCESS]);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case26_Valid_Success_Inactive()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ruth', 'teitelbaum', 'ruth@passbolt.com');
        $mock = ['fname' => 'ruth', 'foreign_key' => UuidFactory::uuid('user.id.ruth')];
        $this->mockDirectoryEntryUser($mock, SyncAction::SUCCESS);
        $summary = $this->action->execute();
        $this->assertReportEmpty($summary);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case27a_Valid_Success_Ignore_OK()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $this->mockDirectoryEntryUser(['fname' => 'ada'], SyncAction::SUCCESS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), SyncAction::USERS);
        $report = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $this->assertReportEmpty($report);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case27b_Valid_Success_Ignore_Deleted_after()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $mock = ['fname' => 'sofia', 'foreign_key' => UuidFactory::uuid('user.id.sofia')];
        $this->mockDirectoryEntryUser($mock, SyncAction::SUCCESS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), SyncAction::USERS);
        $summary = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case27b_Valid_Success_Ignore_Deleted_before()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', new FrozenTime('2015-06-15 08:23:45'), new FrozenTime('2015-06-15 08:23:45'));
        $mock = ['fname' => 'sofia', 'foreign_key' => UuidFactory::uuid('user.id.sofia')];
        $this->mockDirectoryEntryUser($mock, SyncAction::SUCCESS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), SyncAction::USERS);
        $summary = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $data = $summary[0]->getData();
        $this->assertEquals($data->id, UuidFactory::uuid('user.id.sofia'));
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case28a_Valid_Success_Deleted_after()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $mock = ['fname' => 'sofia', 'foreign_key' => UuidFactory::uuid('user.id.sofia')];
        $this->mockDirectoryEntryUser($mock, SyncAction::SUCCESS);
        $summary = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $data = $summary[0]->getData();
        $this->assertUserExist($data->id, ['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $this->assertDirectoryEntryExists(['foreign_key' => $data->id]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::SUCCESS];
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
    public function testDirectorySyncUserAdd_Case28a_Valid_Success_Deleted_before()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', new FrozenTime('2015-06-15 08:23:45'), new FrozenTime('2015-06-15 08:23:45'));
        $mock = ['fname' => 'sofia', 'foreign_key' => UuidFactory::uuid('user.id.sofia')];
        $this->mockDirectoryEntryUser($mock, SyncAction::SUCCESS);
        $summary = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $data = $summary[0]->getData();
        $this->assertEquals($data->id, UuidFactory::uuid('user.id.sofia'));
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
    public function testDirectorySyncUserAdd_Case29a_Valid_Ignore_Null()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.neil'), SyncAction::DIRECTORY_ENTRIES);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertUserNotExist(['username' => 'neil@passbolt.com']);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => 'array'];
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
    public function testDirectorySyncUserAdd_Case29b_Valid_Ignore_Null()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'neil'], SyncAction::ERROR);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.neil'), SyncAction::DIRECTORY_ENTRIES);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertUserNotExist(['username' => 'neil@passbolt.com']);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::DIRECTORY_ENTRIES];
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
    public function testDirectorySyncUserAdd_Case29c_Valid_Ignore_Null()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'neil'], SyncAction::SUCCESS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.neil'), SyncAction::DIRECTORY_ENTRIES);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertUserNotExist(['username' => 'neil@passbolt.com']);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::DIRECTORY_ENTRIES];
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
    public function testDirectorySyncUserAdd_Case30a_Valid_Ignore_Ignore()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.neil'), SyncAction::USERS);
        $this->mockDirectoryEntryUser(['fname' => 'neil'], SyncAction::SUCCESS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.neil'), SyncAction::DIRECTORY_ENTRIES);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreDoesNotExist(['id' => UuidFactory::uuid('user.id.neil')]);
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('ldap.user.id.neil')]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::DIRECTORY_ENTRIES];
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
    public function testDirectorySyncUserAdd_Case30a2_Valid_NullIgnore_Ignore()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.neil'), SyncAction::USERS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.neil'), SyncAction::DIRECTORY_ENTRIES);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreDoesNotExist(['id' => UuidFactory::uuid('user.id.neil')]);
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('ldap.user.id.neil')]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => 'array'];
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
    public function testDirectorySyncUserAdd_Case30b_Valid_Ignore_Ignore()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $this->mockDirectoryEntryUser(['fname' => 'ada'], SyncAction::ERROR);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), SyncAction::USERS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.ada'), SyncAction::DIRECTORY_ENTRIES);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('ldap.user.id.ada')]);
        $this->assertReportEmpty($summary);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case31_Valid_Ignore_Active()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.ada'), SyncAction::DIRECTORY_ENTRIES);
        $mock = ['fname' => 'ada', 'foreign_key' => UuidFactory::uuid('user.id.ada')];
        $this->mockDirectoryEntryUser($mock, SyncAction::SUCCESS);
        $summary = $this->action->execute();
        $this->assertReportEmpty($summary);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('ldap.user.id.ada')]);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case31b_Valid_Ignore_Inactive()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ruth', 'teitelbaum', 'ada@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.ruth'), SyncAction::DIRECTORY_ENTRIES);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist();
        $this->assertReportEmpty($summary);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case32_Valid_Ignore_Deleted()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.sofia'), SyncAction::DIRECTORY_ENTRIES);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist();
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => 'array'];
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
    public function testDirectorySyncUserAdd_Case32b_Valid_Ignore_Deleted()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.sofia'), SyncAction::DIRECTORY_ENTRIES);
        $this->mockDirectoryEntryUser(['fname' => 'sofia'], SyncAction::ERROR);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist();
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::DIRECTORY_ENTRIES];
        $this->assertReport($summary[0], $expectedReport);
    }

    /**
     * Scenario: The user is invalid and does not exist in passbolt
     * Expected result: trigger an error
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case33_Invalid_Null_Null()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', null, 'neil@passbolt.com');
        $summary = $this->action->execute();
        $this->assertOneDirectoryEntry(SyncAction::ERROR);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertUserNotExist(['username' => 'neil@passbolt.com']);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::ERROR, 'type' => SyncAction::DIRECTORY_ENTRIES];
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
    public function testDirectorySyncUserAdd_Case34_Invalid_Null_Active()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com');
        $summary = $this->action->execute();
        $this->assertOneDirectoryEntry(SyncAction::SUCCESS);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::SYNC, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case34_Invalid_Null_Inactive()
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
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case35_Invalid_Null_Ignore()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), SyncAction::USERS);
        $summary = $this->action->execute();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $this->assertReportEmpty($summary);
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
    public function testDirectorySyncUserAdd_Case36a_Invalid_Null_Deleted_Now()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $summary = $this->action->execute();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::ERROR, 'type' => 'array'];
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
    public function testDirectorySyncUserAdd_Case36b_Invalid_Null_Deleted_Past()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', new FrozenTime('2015-06-15 08:23:45'), new FrozenTime('2015-06-15 08:23:45'));
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case37_Invalid_Error_Null()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', null, 'neil@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'neil'], SyncAction::ERROR);
        $summary = $this->action->execute();
        $this->assertUserNotExist(['username' => 'neil@passbolt.com']);
        $this->assertDirectoryIgnoreEmpty();
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::ERROR, 'type' => SyncAction::DIRECTORY_ENTRIES];
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
    public function testDirectorySyncUserAdd_Case38_Invalid_Error_Active()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'ada'], SyncAction::ERROR);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.ada'), 'status' => SyncAction::SUCCESS]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::SYNC, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case38_Invalid_Error_Inactive()
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
    public function testDirectorySyncUserAdd_Case39a_Invalid_Error_Deleted_before()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', null, 'sofia@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $this->mockDirectoryEntryUser(['fname' => 'ada'], SyncAction::ERROR);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), SyncAction::USERS);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case39b_Invalid_Error_Deleted_before()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $this->mockDirectoryEntryUser(['fname' => 'sofia'], SyncAction::ERROR);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), SyncAction::USERS);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case39b_Invalid_Error_Deleted_after()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', new FrozenTime('2015-06-15 08:23:45'), new FrozenTime('2015-06-15 08:23:45'));
        $this->mockDirectoryEntryUser(['fname' => 'sofia'], SyncAction::ERROR);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), SyncAction::USERS);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case40a_Invalid_Error_Deleted_before()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $this->mockDirectoryEntryUser(['fname' => 'sofia'], SyncAction::ERROR);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::ERROR, 'type' => SyncAction::DIRECTORY_ENTRIES];
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
    public function testDirectorySyncUserAdd_Case40b_Invalid_Error_Deleted_after()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', new FrozenTime('2015-06-15 08:23:45'), new FrozenTime('2015-06-15 08:23:45'));
        $this->mockDirectoryEntryUser(['fname' => 'sofia'], SyncAction::ERROR);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case41_Invalid_Success_Null()
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
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::ERROR, 'type' => SyncAction::DIRECTORY_ENTRIES];
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
    public function testDirectorySyncUserAdd_Case42_Invalid_Success_Active()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'ada'], SyncAction::SUCCESS);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.ada'), 'status' => SyncAction::SUCCESS]);
        $this->assertReportEmpty($summary);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case42_Invalid_Success_Inactive()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ruth', null, 'ruth@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'ruth'], SyncAction::SUCCESS);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.ruth'), 'status' => SyncAction::SUCCESS]);
        $this->assertReportEmpty($summary);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case43_Invalid_Success_Ignore()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $this->mockDirectoryEntryUser(['fname' => 'ada'], SyncAction::SUCCESS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), SyncAction::USERS);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $this->assertReportEmpty($summary);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case44_Invalid_Success_IgnoreDeleted_After()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $this->mockDirectoryEntryUser(['fname' => 'sofia'], SyncAction::SUCCESS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), SyncAction::USERS);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case44_Invalid_Success_IgnoreDeleted_Before()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', new FrozenTime('2015-06-15 08:23:45'), new FrozenTime('2015-06-15 08:23:45'));
        $this->mockDirectoryEntryUser(['fname' => 'sofia'], SyncAction::SUCCESS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), SyncAction::USERS);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case45_Invalid_NullIgnore_Null()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('neil', null, 'neil@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.neil'), SyncAction::DIRECTORY_ENTRIES);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertUserNotExist(['username' => 'neil@passbolt.com']);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => 'array'];
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
    public function testDirectorySyncUserAdd_Case46_Invalid_Ignore_Inactive()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ruth', null, 'ruth@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'ruth'], SyncAction::SUCCESS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.ruth'), SyncAction::DIRECTORY_ENTRIES);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist();
        $this->assertReportEmpty($summary);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case46_Invalid_Ignore_Active()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.ada'), SyncAction::DIRECTORY_ENTRIES);
        $mock = ['fname' => 'ada', 'foreign_key' => UuidFactory::uuid('user.id.ada')];
        $this->mockDirectoryEntryUser($mock, SyncAction::SUCCESS);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('ldap.user.id.ada')]);
        $this->assertReportEmpty($summary);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case47a_Invalid_Ignore_Ignore()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), SyncAction::USERS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.ada'), SyncAction::DIRECTORY_ENTRIES);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertReportEmpty($summary);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case47b_Invalid_Ignore_DeletedIgnore()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), SyncAction::USERS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.sofia'), SyncAction::DIRECTORY_ENTRIES);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::USERS];
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
    public function testDirectorySyncUserAdd_Case48a_Invalid_Ignore_Deleted()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'sofia'], SyncAction::SUCCESS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.sofia'), SyncAction::DIRECTORY_ENTRIES);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist();
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::DIRECTORY_ENTRIES];
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
    public function testDirectorySyncUserAdd_Case48b_Invalid_Error_Deleted_Past()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', new FrozenTime('2015-06-15 08:23:45'), new FrozenTime('2015-06-15 08:23:45'));
        $this->mockDirectoryEntryUser(['fname' => 'sofia'], SyncAction::ERROR);
        $summary = $this->action->execute();
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => SyncAction::CREATE, 'model' => SyncAction::USERS, 'status' => SyncAction::IGNORE, 'type' => SyncAction::USERS];
        $this->assertReport($summary[0], $expectedReport);
    }

}