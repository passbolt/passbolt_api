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
    /**
     * Scenario: User is active in passbolt and not present in ldap
     * Scenario: User is not active in passbolt and not present in ldap
     * Scenario: User was added in passbolt and not ldap and still needs to complete the setup
     * Expected result: Do nothing
     */
    public function testUserSync_Case00_04()
    {
        // Ada (active), Ruth (inactive) and Sofia (deleted) are in passbolt
        $this->action = new UserSyncAction();
        $userIds = [
            UuidFactory::uuid('user.id.ada'),
            UuidFactory::uuid('user.id.ruth'),
            UuidFactory::uuid('user.id.sofia')
        ];
        $users = $this->action->Users->find()->where(['id IN' => $userIds])->all()->toArray();
        $this->assertEquals(count($users), 3);

        // Directory data is empty
        $this->action->getDirectory()->setUsers([]);

        // Nothing should happen
        $this->action->execute();
        $syncEntry = $this->action->DirectoryEntries->find()->all()->toArray();
        $this->assertEquals(count($syncEntry), 0);
    }

    /**
     * Scenario: the user is active in passbolt and not present in ldap
     * Expected result: Do nothing
     */
    public function testUserSync_Case25()
    {
        $this->action = new UserSyncAction();
        $this->mockDirectoryUserData('ada', 'lovelace', 'ada@passbolt.com');
        $this->mockDirectoryEntryUser('ada', 'lovelace', DirectoryEntry::STATUS_SUCCESS);
        $this->action->execute();
        $syncEntry = $this->action->DirectoryEntries->find()->all()->toArray();
        $this->assertEquals(count($syncEntry), 1);
        $this->assertEquals($syncEntry[0]['status'], DirectoryEntry::STATUS_SUCCESS);
    }
}