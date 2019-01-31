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
use Cake\ORM\TableRegistry;
use Passbolt\DirectorySync\Actions\UserSyncAction;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertUsersTrait;
use Passbolt\DirectorySync\Utility\Alias;

class UserSyncActionAddTest extends DirectorySyncIntegrationTestCase
{
    use AssertUsersTrait;

    public function setUp()
    {
        parent::setUp();
        $this->action = new UserSyncAction();
        $this->action->getDirectory()->setUsers([]);
    }

    /**
     *  A test to make sure that orphan directoryEntry (foreign_key => null) do not get deleted.
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

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
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $reports = $this->action->execute();
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_SUCCESS, 'type' => Alias::MODEL_USERS];
        $this->assertReport($reports[0], $expectedReport);
        $data = $reports[0]->getData();
        $this->assertUserExist($data->id, ['username' => 'neil@passbolt.com', 'active' => false, 'deleted' => false]);
        $this->assertDirectoryEntryExistsForUser(['username' => 'neil@passbolt.com', 'deleted' => false]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case18a_Valid_Null_Active_CreatedBefore()
    {
        // Get existing user.
        $Users = TableRegistry::get('Users');
        $ada = $Users->find()->where(['username' => 'ada@passbolt.com'])->first();
        $creationDate = $ada->created;
        $dateAfterCreation = $creationDate->addDays(1);

        $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com', $dateAfterCreation, $dateAfterCreation);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_ERROR, 'type' => 'SyncError'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertOrphanDirectoryEntryExists(UuidFactory::uuid('ldap.user.id.ada'));
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case18b_Valid_Null_Active_CreatedAfter()
    {
        // Get existing user.
        $Users = TableRegistry::get('Users');
        $ada = $Users->find()->where(['username' => 'ada@passbolt.com'])->first();
        $creationDate = $ada->created;
        $dateBeforeCreation = $creationDate->subDays(1);

        $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com', $dateBeforeCreation, $dateBeforeCreation);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_SYNC, 'type' => Alias::MODEL_USERS];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.ada'), 'foreign_key' => $ada->id]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $this->assertDirectoryIgnoreEmpty();
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
        $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), Alias::MODEL_USERS);
        $reports = $this->action->execute();
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertOrphanDirectoryEntryExists(UuidFactory::uuid('ldap.user.id.ada'));
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
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
        $Users = TableRegistry::get('Users');
        $ada = $Users->find()->where(['username' => 'sofia@passbolt.com'])->first();
        $deletionDate = $ada->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', $dateAfterDeletion, $dateAfterDeletion);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), Alias::MODEL_USERS);
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertOrphanDirectoryEntryExists(UuidFactory::uuid('ldap.user.id.sofia'));
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
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
        $Users = TableRegistry::get('Users');
        $ada = $Users->find()->where(['username' => 'sofia@passbolt.com'])->first();
        $deletionDate = $ada->modified;
        $dateBeforeDeletion = $deletionDate->subDays(1);

        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', $dateBeforeDeletion, $dateBeforeDeletion);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), Alias::MODEL_USERS);
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertOrphanDirectoryEntryExists(UuidFactory::uuid('ldap.user.id.sofia'));
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
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
        $Users = TableRegistry::get('Users');
        $ada = $Users->find()->where(['username' => 'sofia@passbolt.com'])->first();
        $deletionDate = $ada->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', $dateAfterDeletion, $dateAfterDeletion);
        $reports = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $data = $reports[0]->getData();
        $this->assertUserExist($data->id, ['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_SUCCESS, 'type' => Alias::MODEL_USERS];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertDirectoryEntryExistsForUser(['username' => 'sofia@passbolt.com', 'deleted' => false]);
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
        $Users = TableRegistry::get('Users');
        $ada = $Users->find()->where(['username' => 'sofia@passbolt.com'])->first();
        $deletionDate = $ada->modified;
        $dateBeforeDeletion = $deletionDate->subDays(1);

        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', $dateBeforeDeletion, $dateBeforeDeletion);
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertOrphanDirectoryEntryExists(UuidFactory::uuid('ldap.user.id.sofia'));
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_ERROR, 'type' => 'SyncError'];
        $this->assertReport($reports[0], $expectedReport);
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
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $this->mockOrphanDirectoryEntryUser(['fname' => 'neil']);
        $reports = $this->action->execute();
        $data = $reports[0]->getData();
        $this->assertUserExist($data->id, ['username' => 'neil@passbolt.com', 'deleted' => false]);
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.neil'), 'foreign_key' => $data->id]);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_SUCCESS, 'type' => Alias::MODEL_USERS];
        $this->assertReport($reports[0], $expectedReport);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case22a_Valid_Error_Active_CreatedBefore()
    {
        // Get existing user.
        $Users = TableRegistry::get('Users');
        $ada = $Users->find()->where(['username' => 'ada@passbolt.com'])->first();
        $creationDate = $ada->created;
        $dateAfterCreation = $creationDate->addDays(1);

        $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com', $dateAfterCreation, $dateAfterCreation);
        $this->mockOrphanDirectoryEntryUser(['fname' => 'ada']);
        $reports = $this->action->execute();
        $this->assertOrphanDirectoryEntryExists(UuidFactory::uuid('ldap.user.id.ada'));
        $this->assertDirectoryIgnoreEmpty();
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_ERROR, 'type' => 'SyncError'];
        $this->assertReport($reports[0], $expectedReport);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case22b_Valid_Error_Active_CreatedAfter()
    {
        $Users = TableRegistry::get('Users');
        $ada = $Users->find()->where(['username' => 'ada@passbolt.com'])->first();
        $creationDate = $ada->created;
        $dateBeforeCreation = $creationDate->subDays(1);

        $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com', $dateBeforeCreation, $dateBeforeCreation);
        $this->mockOrphanDirectoryEntryUser(['fname' => 'ada']);
        $reports = $this->action->execute();
        $this->assertDirectoryEntryExistsForUser(['id' => $ada->id]);
        $this->assertDirectoryIgnoreEmpty();
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_SYNC, 'type' => Alias::MODEL_USERS];
        $this->assertReport($reports[0], $expectedReport);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case23a_Valid_Error_Ignore_OK()
    {
        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $this->mockOrphanDirectoryEntryUser(['fname' => 'ada']);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), Alias::MODEL_USERS);
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $this->assertOrphanDirectoryEntryExists(UuidFactory::uuid('ldap.user.id.ada'));
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case23b_Valid_Error_Null_DeletedBefore_Ignore()
    {
        $Users = TableRegistry::get('Users');
        $ada = $Users->find()->where(['username' => 'sofia@passbolt.com'])->first();
        $deletionDate = $ada->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com', $dateAfterDeletion, $dateAfterDeletion);
        $this->mockOrphanDirectoryEntryUser(['fname' => 'ada']);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), Alias::MODEL_USERS);
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $this->assertOrphanDirectoryEntryExists(UuidFactory::uuid('ldap.user.id.ada'));
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case23c_Valid_Error_Null_DeletedBefore_Ignore()
    {
        $Users = TableRegistry::get('Users');
        $ada = $Users->find()->where(['username' => 'sofia@passbolt.com'])->first();
        $deletionDate = $ada->modified;
        $dateBeforeDeletion = $deletionDate->subDays(1);

        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com', $dateBeforeDeletion, $dateBeforeDeletion);
        $this->mockOrphanDirectoryEntryUser(['fname' => 'ada']);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), Alias::MODEL_USERS);
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $this->assertOrphanDirectoryEntryExists(UuidFactory::uuid('ldap.user.id.ada'));
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case24a_Ok_NoAssoc_Null_DeletedBefore_Null()
    {
        $Users = TableRegistry::get('Users');
        $sofia = $Users->find()->where(['username' => 'sofia@passbolt.com'])->first();
        $deletionDate = $sofia->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', $dateAfterDeletion, $dateAfterDeletion);
        $this->mockOrphanDirectoryEntryUser(['fname' => 'sofia']);
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $data = $reports[0]->getData();
        $this->assertUserExist($data->id, ['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.sofia'), 'foreign_key' => $data->id]);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_SUCCESS];
        $this->assertReport($reports[0], $expectedReport);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case24b_Ok_NoAssoc_Null_DeletedAfter_Null()
    {
        $Users = TableRegistry::get('Users');
        $sofia = $Users->find()->where(['username' => 'sofia@passbolt.com'])->first();
        $deletionDate = $sofia->modified;
        $dateBeforeDeletion = $deletionDate->subDays(1);

        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', $dateBeforeDeletion, $dateBeforeDeletion);
        $this->mockOrphanDirectoryEntryUser(['fname' => 'sofia']);
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'active' => true, 'deleted' => false]);
        $reports[0]->getData();
        $this->assertOrphanDirectoryEntryExists(UuidFactory::uuid('ldap.user.id.sofia'));
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_ERROR, 'type' => 'SyncError'];
        $this->assertReport($reports[0], $expectedReport);
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
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'neil']);
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreEmpty();
        $data = $reports[0]->getData();
        $this->assertUserExist($data->id, ['username' => 'neil@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_SUCCESS, 'type' => Alias::MODEL_USERS];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryEntryExistsForUser(['username' => 'neil@passbolt.com']);
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
        $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'ada', 'foreign_key' => UuidFactory::uuid('user.id.ada')]);
        $reports = $this->action->execute();
        $this->assertReportEmpty($reports);
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.ada')]);
        $this->assertOneDirectoryEntry();
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
        $this->mockDirectoryUserData('ruth', 'teitelbaum', 'ruth@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'ruth', 'foreign_key' => UuidFactory::uuid('user.id.ruth')]);
        $reports = $this->action->execute();
        $this->assertReportEmpty($reports);
        $this->assertUserExist(UuidFactory::uuid('user.id.ruth'), ['active' => false, 'deleted' => false]);
        $this->assertDirectoryEntryExistsForUser(['username' => 'ruth@passbolt.com']);
        $this->assertOneDirectoryEntry();
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
        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'ada']);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), Alias::MODEL_USERS);
        $report = $this->action->execute();
        $this->assertReportEmpty($report);
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $this->assertDirectoryEntryExistsForUser(['username' => 'ada@passbolt.com']);
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
        $Users = TableRegistry::get('Users');
        $sofia = $Users->find()->where(['username' => 'sofia@passbolt.com'])->first();
        $deletionDate = $sofia->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', $dateAfterDeletion, $dateAfterDeletion);
        $this->mockDirectoryEntryUser(['fname' => 'sofia', 'foreign_key' => UuidFactory::uuid('user.id.sofia')]);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), Alias::MODEL_USERS);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertDirectoryEntryExistsForUser(['username' => 'sofia@passbolt.com']);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case27c_Valid_Success_Ignore_Deleted_before()
    {
        $Users = TableRegistry::get('Users');
        $sofia = $Users->find()->where(['username' => 'sofia@passbolt.com'])->first();
        $deletionDate = $sofia->modified;
        $dateBeforeDeletion = $deletionDate->addDays(1);

        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', $dateBeforeDeletion, $dateBeforeDeletion);
        $this->mockDirectoryEntryUser(['fname' => 'sofia', 'foreign_key' => UuidFactory::uuid('user.id.sofia')]);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), Alias::MODEL_USERS);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertDirectoryEntryExistsForUser(['username' => 'sofia@passbolt.com']);
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
        $Users = TableRegistry::get('Users');
        $sofia = $Users->find()->where(['username' => 'sofia@passbolt.com'])->first();
        $deletionDate = $sofia->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', $dateAfterDeletion, $dateAfterDeletion);
        $this->mockDirectoryEntryUser(['fname' => 'sofia', 'foreign_key' => UuidFactory::uuid('user.id.sofia')]);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_SUCCESS, 'type' => Alias::MODEL_USERS];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserExist($reports[0]->getData()->id, ['active' => false, 'deleted' => false]);
        $this->assertDirectoryEntryExistsForUser(['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $this->assertOneDirectoryEntry();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case28b_Valid_Success_Deleted_after()
    {
        $Users = TableRegistry::get('Users');
        $sofia = $Users->find()->where(['username' => 'sofia@passbolt.com'])->first();
        $deletionDate = $sofia->modified;
        $dateBeforeDeletion = $deletionDate->subDays(1);

        $data = $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com', $dateBeforeDeletion, $dateBeforeDeletion);
        $this->mockDirectoryEntryUser(['fname' => 'sofia', 'foreign_key' => UuidFactory::uuid('user.id.sofia')]);
        $reports = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'active' => false, 'deleted' => false]);
        $this->assertOrphanDirectoryEntryExists($data['id']);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_ERROR, 'type' => 'SyncError'];
        $this->assertReport($reports[0], $expectedReport);
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
        $data = $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.neil'), Alias::MODEL_DIRECTORY_ENTRIES);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertUserNotExist(['username' => 'neil@passbolt.com']);
        $this->assertOrphanDirectoryEntryExists($data['id']);
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
        $data = $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $this->mockOrphanDirectoryEntryUser(['fname' => 'neil']);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.neil'), Alias::MODEL_DIRECTORY_ENTRIES);
        $reports = $this->action->execute();
        $this->assertUserNotExist(['username' => 'neil@passbolt.com']);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertOrphanDirectoryEntryExists($data['id']);
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
        $data = $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'neil']);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.neil'), Alias::MODEL_DIRECTORY_ENTRIES);
        $reports = $this->action->execute();
        $this->assertUserNotExist(['username' => 'neil@passbolt.com']);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.neil')]);
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
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.neil'), Alias::MODEL_USERS);
        $this->mockDirectoryEntryUser(['fname' => 'neil']);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.neil'), Alias::MODEL_DIRECTORY_ENTRIES);
        $reports = $this->action->execute();
        $this->assertUserNotExist(['username' => 'neil@passbolt.com']);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.neil')]);
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
        $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com');
        $this->mockOrphanDirectoryEntryUser(['fname' => 'ada']);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), Alias::MODEL_USERS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.ada'), Alias::MODEL_DIRECTORY_ENTRIES);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('ldap.user.id.ada')]);
        $this->assertOrphanDirectoryEntryExists(UuidFactory::uuid('ldap.user.id.ada'));
        $this->assertOneDirectoryEntry();
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
        $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.ada'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryEntryUser(['fname' => 'ada', 'foreign_key' => UuidFactory::uuid('user.id.ada')]);
        $reports = $this->action->execute();
        $this->assertReportEmpty($reports);
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('ldap.user.id.ada')]);
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.ada'), 'foreign_key' => UuidFactory::uuid('user.id.ada')]);
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
        $this->mockDirectoryUserData('ruth', 'teitelbaum', 'ruth@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.ruth'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryEntryUser(['fname' => 'ruth', 'foreign_key' => UuidFactory::uuid('user.id.ruth')]);
        $reports = $this->action->execute();
        $this->assertReportEmpty($reports);
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('ldap.user.id.ruth')]);
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.ruth'), 'foreign_key' => UuidFactory::uuid('user.id.ruth')]);
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
        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.sofia'), Alias::MODEL_DIRECTORY_ENTRIES);
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreExist();
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertOrphanDirectoryEntryExists(UuidFactory::uuid('ldap.user.id.sofia'));
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
        $this->mockDirectoryUserData('sofia', 'kovalevskaya', 'sofia@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.sofia'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryEntryUser(['fname' => 'sofia']);
        $reports = $this->action->execute();

        $this->assertDirectoryIgnoreExist();
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
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
        $data = $this->mockDirectoryUserData('neil', null, 'neil@passbolt.com');
        $reports = $this->action->execute();
        $this->assertOrphanDirectoryEntryExists($data['id']);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertUserNotExist(['username' => 'neil@passbolt.com']);
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_ERROR, 'type' => 'SyncError'];
        $this->assertReport($reports[0], $expectedReport);
    }

    /**
     * Scenario: The data is invalid but there is already an active user matching in passbolt that has been created before the directory user
     * Expected result: report error
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case34a_Invalid_Null_Active_CreatedBefore()
    {
        $Users = TableRegistry::get('Users');
        $ada = $Users->find()->where(['username' => 'ada@passbolt.com'])->first();
        $creationDate = $ada->created;
        $dateAfterCreation = $creationDate->addDays(1);

        $data = $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com', $dateAfterCreation, $dateAfterCreation);
        $reports = $this->action->execute();
        $this->assertOrphanDirectoryEntryExists($data['id']);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_ERROR, 'type' => 'SyncError'];
        $this->assertReport($reports[0], $expectedReport);
    }

    /**
     * Scenario: The data is invalid but there is already an active user matching in passbolt that has been created before the directory user
     * Expected result: report error
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case34b_Invalid_Null_Active_CreatedAfter()
    {
        $Users = TableRegistry::get('Users');
        $ada = $Users->find()->where(['username' => 'ada@passbolt.com'])->first();
        $creationDate = $ada->created;
        $dateBeforeCreation = $creationDate->subDays(1);

        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com', $dateBeforeCreation, $dateBeforeCreation);
        $reports = $this->action->execute();
        $this->assertDirectoryEntryExistsForUser(['username' => 'ada@passbolt.com']);
        $this->assertDirectoryIgnoreEmpty();
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_SYNC, 'type' => Alias::MODEL_USERS];
        $this->assertReport($reports[0], $expectedReport);
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
        $data = $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), Alias::MODEL_USERS);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertOrphanDirectoryEntryExists($data['id']);
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
    public function testDirectorySyncUserAdd_Case36a_Invalid_Null_Deleted_Before()
    {
        $Users = TableRegistry::get('Users');
        $sofia = $Users->find()->where(['username' => 'sofia@passbolt.com'])->first();
        $deletionDate = $sofia->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $data = $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', $dateAfterDeletion, $dateAfterDeletion);
        $reports = $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_ERROR, 'type' => 'SyncError'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertOrphanDirectoryEntryExists($data['id']);
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
    public function testDirectorySyncUserAdd_Case36b_Invalid_Null_Deleted_After()
    {
        $Users = TableRegistry::get('Users');
        $sofia = $Users->find()->where(['username' => 'sofia@passbolt.com'])->first();
        $deletionDate = $sofia->modified;
        $dateBeforeDeletion = $deletionDate->subDays(1);

        $data = $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', $dateBeforeDeletion, $dateBeforeDeletion);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_ERROR, 'type' => 'SyncError'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertOrphanDirectoryEntryExists($data['id']);
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
        $data = $this->mockDirectoryUserData('neil', null, 'neil@passbolt.com');
        $this->mockOrphanDirectoryEntryUser(['fname' => 'neil']);
        $reports = $this->action->execute();
        $this->assertUserNotExist(['username' => 'neil@passbolt.com']);
        $this->assertDirectoryIgnoreEmpty();
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_ERROR, 'type' => 'SyncError'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertOrphanDirectoryEntryExists($data['id']);
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
        $data = $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com');
        $this->mockOrphanDirectoryEntryUser(['fname' => 'ada']);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.ada')]);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_SYNC, 'type' => Alias::MODEL_USERS];
        $this->assertReport($reports[0], $expectedReport);
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
        $data = $this->mockDirectoryUserData('neil', null, 'neil@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'neil', 'lname' => 'armstrong', 'foreign_key' => UuidFactory::uuid('user.id.neil')]);
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreEmpty();
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_ERROR, 'type' => 'SyncError'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertOrphanDirectoryEntryExists($data['id']);
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
        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'ada']);
        $reports = $this->action->execute();
        $this->assertReportEmpty($reports);
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.ada')]);
        $this->assertOneDirectoryEntry();
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
        $this->mockDirectoryUserData('ruth', null, 'ruth@passbolt.com');
        $this->mockDirectoryEntryUser(['fname' => 'ruth']);
        $reports = $this->action->execute();
        $this->assertReportEmpty($reports);
        $this->assertDirectoryEntryExists(['id' => UuidFactory::uuid('ldap.user.id.ruth')]);
        $this->assertOneDirectoryEntry();
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
        $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $this->mockDirectoryEntryUser(['fname' => 'ada']);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), Alias::MODEL_USERS);
        $reports = $this->action->execute();
        $this->assertReportEmpty($reports);
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.ada'), ['active' => true, 'deleted' => false]);
        $this->assertDirectoryEntryExistsForUser(['username' => 'ada@passbolt.com']);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case44a_Invalid_Success_IgnoreDeleted_Before()
    {
        $Users = TableRegistry::get('Users');
        $sofia = $Users->find()->where(['username' => 'sofia@passbolt.com'])->first();
        $deletionDate = $sofia->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', $dateAfterDeletion, $dateAfterDeletion);
        $this->mockDirectoryEntryUser(['fname' => 'sofia']);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), Alias::MODEL_USERS);
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryEntryExistsForUser(['username' => 'sofia@passbolt.com']);
        $this->assertOneDirectoryEntry();
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case44b_Invalid_Success_IgnoreDeleted_After()
    {
        $Users = TableRegistry::get('Users');
        $sofia = $Users->find()->where(['username' => 'sofia@passbolt.com'])->first();
        $deletionDate = $sofia->modified;
        $dateBeforeDeletion = $deletionDate->subDays(1);

        $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', $dateBeforeDeletion, $dateBeforeDeletion);
        $this->mockDirectoryEntryUser(['fname' => 'sofia']);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), Alias::MODEL_USERS);
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryEntryExistsForUser(['username' => 'sofia@passbolt.com']);
        $this->assertOneDirectoryEntry();
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
        $data = $this->mockDirectoryUserData('neil', null, 'neil@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.neil'), Alias::MODEL_DIRECTORY_ENTRIES);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $this->assertUserNotExist(['username' => 'neil@passbolt.com']);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertOrphanDirectoryEntryExists($data['id']);
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
        $data = $this->mockDirectoryUserData('ruth', null, 'ruth@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.ruth'), Alias::MODEL_DIRECTORY_ENTRIES);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryIgnoreExist();
        $this->assertOrphanDirectoryEntryExists($data['id']);
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
        $data = $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.ada'), Alias::MODEL_DIRECTORY_ENTRIES);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('ldap.user.id.ada')]);
        $this->assertOrphanDirectoryEntryExists($data['id']);
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
        $data = $this->mockDirectoryUserData('ada', null, 'ada@passbolt.com');
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.ada'), Alias::MODEL_DIRECTORY_ENTRIES);
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.ada'), Alias::MODEL_USERS);
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('ldap.user.id.ada')]);
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertReportNotEmpty($reports);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertOrphanDirectoryEntryExists($data['id']);
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
        $data = $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', new FrozenTime('now'), new FrozenTime('now'));
        $this->mockDirectoryIgnore(UuidFactory::uuid('user.id.sofia'), Alias::MODEL_USERS);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.sofia'), Alias::MODEL_DIRECTORY_ENTRIES);
        $reports = $this->action->execute();
        $this->assertReportNotEmpty($reports);
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('user.id.sofia')]);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertOrphanDirectoryEntryExists($data['id']);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case48a_Invalid_Null_Ignore_DeletedBefore_Null()
    {
        $Users = TableRegistry::get('Users');
        $sofia = $Users->find()->where(['username' => 'sofia@passbolt.com'])->first();
        $deletionDate = $sofia->modified;
        $dateAfterDeletion = $deletionDate->addDays(1);

        $data = $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', $dateAfterDeletion, $dateAfterDeletion);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.sofia'), Alias::MODEL_DIRECTORY_ENTRIES);
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('ldap.user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertOrphanDirectoryEntryExists($data['id']);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case48b_Invalid_Success_IgnoreDeleted_After()
    {
        $Users = TableRegistry::get('Users');
        $sofia = $Users->find()->where(['username' => 'sofia@passbolt.com'])->first();
        $deletionDate = $sofia->modified;
        $dateBeforeDeletion = $deletionDate->subDays(1);

        $data = $this->mockDirectoryUserData('sofia', null, 'sofia@passbolt.com', $dateBeforeDeletion, $dateBeforeDeletion);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.sofia'), Alias::MODEL_DIRECTORY_ENTRIES);
        $reports = $this->action->execute();
        $this->assertDirectoryIgnoreExist(['id' => UuidFactory::uuid('ldap.user.id.sofia')]);
        $this->assertUserExist(UuidFactory::uuid('user.id.sofia'), ['active' => true, 'deleted' => true]);
        $this->assertUserNotExist(['username' => 'sofia@passbolt.com', 'deleted' => false]);
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_IGNORE, 'type' => 'DirectoryIgnore'];
        $this->assertReport($reports[0], $expectedReport);
        $this->assertOrphanDirectoryEntryExists($data['id']);
    }

    /**
     * Scenario:
     * Expected result:
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case17_DryRun()
    {
        $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $this->action->setDryRun(true);
        $reports = $this->action->execute();
        $expectedReport = ['action' => Alias::ACTION_CREATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_SUCCESS, 'type' => Alias::MODEL_USERS];
        $this->assertReport($reports[0], $expectedReport);
        $reports[0]->getData();
        $this->assertUserNotExist(['username' => 'neil@passbolt.com', 'active' => false, 'deleted' => false]);
        $this->assertDirectoryEntryEmpty();
        $this->assertDirectoryIgnoreEmpty();
    }
}
