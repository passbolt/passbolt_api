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
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncTestCase;
use Passbolt\DirectorySync\Actions\UserSyncAction;

class UserSyncActionTest extends DirectorySyncTestCase
{

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
    public function testUserSync_Case00_03()
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
    public function testUserSync_Case04()
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
    public function testUserSync_Case05_07()
    {
        $this->action = new UserSyncAction();
        $this->action->getDirectory()->setUsers([]);
        $this->mockDirectoryEntryUser('not', 'present', DirectoryEntry::STATUS_SUCCESS);
        $this->mockDirectoryEntryUser('also', 'absent', DirectoryEntry::STATUS_ERROR);
        $this->mockDirectoryEntryUser('mia', 'mia', DirectoryEntry::STATUS_ERROR);
        $this->mockDirectoryIgnore(UuidFactory::uuid('ldap.user.id.mia'), 'DirectoryEntry');
        $this->action->execute();
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
    public function testUserSync_Case11_User_Not_deletable()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com');
        $this->mockDirectoryEntryUser('ada', 'lovelace', DirectoryEntry::STATUS_SUCCESS);
        $this->action->execute();
        $syncEntry = $this->action->DirectoryEntries->find()->all()->toArray();
        $this->assertEquals($syncEntry[0]['status'], DirectoryEntry::STATUS_ERROR);

        // TODO check email notification
    }

    /**
     * Scenario: the user is active in passbolt and not present in the directory and not deletable
     * Expected result: user is deleted
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testUserSync_Case12_User_deletable()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('frances', 'allen', 'frances@passbolt.com');
        $this->mockDirectoryEntryUser('frances', 'allen', DirectoryEntry::STATUS_SUCCESS);
        $this->action->execute();
        $this->assertUserExist(UuidFactory::uuid('user.id.frances'), ['deleted' => true]);
        $this->assertDirectoryEntryEmpty();

        // TODO check email notification
    }
}