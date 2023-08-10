<?php
declare(strict_types=1);

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

use Cake\I18n\FrozenTime;
use Passbolt\DirectorySync\Actions\UserSyncAction;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertUsersTrait;
use Passbolt\DirectorySync\Utility\Alias;

class UserSyncActionUpdateTest extends DirectorySyncIntegrationTestCase
{
    use AssertUsersTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->initAction();
    }

    /**
     * Init action
     *
     * @return void
     */
    private function initAction(): void
    {
        $this->action = new UserSyncAction();
        $this->action->getDirectory()->setUsers([]);
    }

    /**
     * Scenario: User exists in passbolt, then updated first/last name on ldap
     * Expected result: User first name and last name updated
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case64a__OkRenamed_Success_Ok()
    {
        $user = $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $this->action->execute();
        $updatedUser = $this->mockDirectoryUserData('buzz', 'aldrin', 'neil@passbolt.com', null, new FrozenTime());
        $updatedUser['id'] = $user['id'];
        $this->action->getDirectory()->setUsers([$updatedUser]);
        $reports = $this->action->execute();
        $expectedReport = ['action' => Alias::ACTION_UPDATE, 'model' => Alias::MODEL_USERS, 'status' => Alias::STATUS_SUCCESS, 'type' => Alias::MODEL_USERS];
        $this->assertReport($reports[1], $expectedReport);
        $data = $reports[1]->getData();
        $this->assertUserExist($data->id, ['username' => 'neil@passbolt.com', 'active' => false, 'deleted' => false]);
        $this->assertUserFullName($data->id, 'Buzz', 'Aldrin');
        $this->assertDirectoryEntryExistsForUser(['username' => 'neil@passbolt.com', 'deleted' => false]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: User exists in passbolt, then updated first/last name on ldap but sync.users.update is disabled
     * Expected result: Don't update User first name and last name because sync.groups.update is disabled
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case64b__OkRenamed_Success_keepPrevious()
    {
        $this->disableSyncOperation('users', Alias::ACTION_UPDATE);
        $this->initAction();
        $user = $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $this->action->execute();
        $updatedUser = $this->mockDirectoryUserData('buzz', 'aldrin', 'neil@passbolt.com', null, new FrozenTime());
        $updatedUser['id'] = $user['id'];
        $this->action->getDirectory()->setUsers([$updatedUser]);
        $reports = $this->action->execute();
        $this->assertEmpty($reports[1]);
        $data = $reports[0]->getData();
        $this->assertUserExist($data->id, ['username' => 'neil@passbolt.com', 'active' => false, 'deleted' => false]);
        $this->assertUserFullName($data->id, 'Neil', 'Armstrong');
        $this->assertDirectoryEntryExistsForUser(['username' => 'neil@passbolt.com', 'deleted' => false]);
        $this->assertDirectoryIgnoreEmpty();
    }

    /**
     * Scenario: User exists in passbolt, then updated first/last name on ldap but first or last are invalid
     * Expected result: Don't update User first name and last name because data is invalid
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserAdd
     */
    public function testDirectorySyncUserAdd_Case64c_Invalid_Error_keepPrevious()
    {
        $invalidUserFirstName = str_repeat('user', 256);
        $user = $this->mockDirectoryUserData('neil', 'armstrong', 'neil@passbolt.com');
        $this->action->execute();
        $updatedUser = $this->mockDirectoryUserData($invalidUserFirstName, 'aldrin', 'neil@passbolt.com', null, new FrozenTime());
        $updatedUser['id'] = $user['id'];
        $this->action->getDirectory()->setUsers([$updatedUser]);
        $reports = $this->action->execute();
        $this->assertNotEmpty($reports[1]);
        $this->assertSame(Alias::STATUS_ERROR, $reports[1]->getStatus());
        $this->assertSame(__('The user neil@passbolt.com full name could not be updated to {0} {1}.', ucfirst($invalidUserFirstName), 'Aldrin'), $reports[1]->getMessage());
        $data = $reports[0]->getData();
        $this->assertUserExist($data->id, ['username' => 'neil@passbolt.com', 'active' => false, 'deleted' => false]);
        $this->assertUserFullName($data->id, 'Neil', 'Armstrong');
        $this->assertDirectoryEntryExistsForUser(['username' => 'neil@passbolt.com', 'deleted' => false]);
        $this->assertDirectoryIgnoreEmpty();
    }
}
