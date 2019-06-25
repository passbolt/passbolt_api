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

use Cake\Core\Configure;
use LdapTools\Object\LdapObjectType;
use Passbolt\DirectorySync\Actions\GroupSyncAction;
use Passbolt\DirectorySync\Actions\UserSyncAction;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults;

class DirectoryResultsIgnoreInvalidTest extends DirectorySyncIntegrationTestCase
{

    public function setUp()
    {
        parent::setUp();
        Configure::write('passbolt.plugins.directorySync.test', 'Nested');
        $this->userSyncAction = new UserSyncAction();
        $this->groupSyncAction = new GroupSyncAction();
    }

    /**
     * Test that the invalid users that are returned are correct.
     * Scenario: no filter, 2 invalid users should be returned.
     */
    public function testIgnoredInvalidUsers()
    {
        $groups = $this->userSyncAction->getDirectory()->getGroups(false);
        $users = $this->userSyncAction->getDirectory()->getUsers(false);
        $DirectoryResults = new DirectoryResults([]);
        $DirectoryResults->initializeWithEntries($users, $groups);
        $invalidUsers = $DirectoryResults->getInvalidUsers();

        $this->assertEquals(count($invalidUsers), 2);

        $expectedUsers = [
            'CN=User5,OU=PassboltUsers,DC=passbolt,DC=local',
            'CN=User6,OU=PassboltUsers,DC=passbolt,DC=local',
        ];

        foreach ($invalidUsers as $key => $invalidUser) {
            $this->assertEquals($expectedUsers[$key], $invalidUser->dn);
        }
    }

    /**
     * Test that the invalid users that are returned are correct.
     * Scenario: filter on parentGroup 'Administration', only 1 invalid users should be returned.
     */
    public function testIgnoredInvalidUsersWithGroupFilter()
    {
        $groups = $this->userSyncAction->getDirectory()->getGroups(false);
        $users = $this->userSyncAction->getDirectory()->getUsers(false);
        $DirectoryResults = new DirectoryResults([]);
        $DirectoryResults->initializeWithEntries($users, $groups);
        $resultSet = $DirectoryResults
            ->getRecursivelyFromParentGroup(LdapObjectType::USER, 'Administration');

        $invalidUsers = $resultSet->getInvalidUsers();

        $this->assertEquals(count($invalidUsers), 1);

        $expectedUsers = [
            'CN=User6,OU=PassboltUsers,DC=passbolt,DC=local',
        ];

        foreach ($invalidUsers as $key => $invalidUser) {
            $this->assertEquals($expectedUsers[$key], $invalidUser->dn);
        }
    }

    /**
     * Test that the invalid users that are returned are correct.
     * Scenario: no filter, 2 invalid users should be returned.
     */
    public function testIgnoredInvalidGroups()
    {
        $groups = $this->userSyncAction->getDirectory()->getGroups(false);
        $users = $this->userSyncAction->getDirectory()->getUsers(false);
        $DirectoryResults = new DirectoryResults([]);
        $DirectoryResults->initializeWithEntries($users, $groups);
        $invalidGroups = $DirectoryResults->getInvalidGroups();

        $this->assertEquals(count($invalidGroups), 1);

        $expectedGroups = [
            'CN=InvalidGroup1,OU=PassboltUsers,DC=passbolt,DC=local',
        ];

        foreach ($invalidGroups as $key => $invalidGroup) {
            $this->assertEquals($expectedGroups[$key], $invalidGroup->dn);
        }
    }
}
