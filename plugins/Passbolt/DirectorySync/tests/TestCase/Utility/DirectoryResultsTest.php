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

use LdapTools\Object\LdapObjectType;
use Passbolt\DirectorySync\Actions\AllSyncAction;
use Passbolt\DirectorySync\Actions\GroupSyncAction;
use Passbolt\DirectorySync\Actions\UserSyncAction;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Cake\Core\Configure;
use Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults;

class DirectoryResultsTest extends DirectorySyncIntegrationTestCase
{

    public function setUp()
    {
        parent::setUp();
        Configure::write('passbolt.plugins.directorySync.test', 'Nested');
        $this->userSyncAction = new UserSyncAction();
        $this->groupSyncAction = new GroupSyncAction();
        $users = $this->userSyncAction->getDirectory()->getUsers();
    }

    /**
     * Test that the groups users are properly retrieved and populated in the results.
     */
    public function testGroupUsersArePopulated() {
        $groups = $this->userSyncAction->getDirectory()->getGroups();
        $users = $this->userSyncAction->getDirectory()->getUsers();
        $DirectoryResults = new DirectoryResults([]);
        $DirectoryResults->initializeWithEntries($users, $groups);
        $retrievedGroups = $DirectoryResults->getGroups();
        $this->assertEquals(count($retrievedGroups), 4);
        $expectedGroupsUsers = [
            'CN=Administration,OU=PassboltUsers,DC=passbolt,DC=local' => [
                'groups' => [
                    'CN=Managers,OU=PassboltUsers,DC=passbolt,DC=local'
                ],
                'users' => [],
            ],
            'CN=Managers,OU=PassboltUsers,DC=passbolt,DC=local' => [
                'groups' => [
                    'CN=CLevel,OU=PassboltUsers,DC=passbolt,DC=local',
                ],
                'users' => [
                    'CN=User1,OU=PassboltUsers,DC=passbolt,DC=local',
                    'CN=User2,OU=PassboltUsers,DC=passbolt,DC=local',
                ],
            ],
            'CN=CLevel,OU=PassboltUsers,DC=passbolt,DC=local' => [
                'groups' => [],
                'users' => [
                    'CN=User1,OU=PassboltUsers,DC=passbolt,DC=local',
                ]
            ],
            'CN=Developers,OU=PassboltUsers,DC=passbolt,DC=local' => [
                'groups' => [],
                'users' => [
                    'CN=User3,OU=PassboltUsers,DC=passbolt,DC=local'
                ]
            ]
        ];

        foreach($expectedGroupsUsers as $groupDn => $groupsUsers) {
            $this->assertEquals($retrievedGroups[$groupDn]['group']['groups'], $groupsUsers['groups']);
            $this->assertEquals($retrievedGroups[$groupDn]['group']['users'], $groupsUsers['users']);
        }
    }

    /**
     * test that the groups that are retrieved by function getRecursivelyFromParentGroup are correct.
     */
    public function testGroupsFromParentGroup() {
        $groups = $this->userSyncAction->getDirectory()->getGroups();
        $users = $this->userSyncAction->getDirectory()->getUsers();
        $DirectoryResults = new DirectoryResults([]);
        $DirectoryResults->initializeWithEntries($users, $groups);
        $retrievedGroups = $DirectoryResults
            ->getRecursivelyFromParentGroup(LdapObjectType::GROUP, 'Administration')
            ->getGroups();

        $this->assertEquals(count($retrievedGroups), 2);

        $expectedGroupsUsers = [
            'CN=Managers,OU=PassboltUsers,DC=passbolt,DC=local' => [
                'groups' => [
                    'CN=CLevel,OU=PassboltUsers,DC=passbolt,DC=local',
                ],
                'users' => [
                    'CN=User1,OU=PassboltUsers,DC=passbolt,DC=local',
                    'CN=User2,OU=PassboltUsers,DC=passbolt,DC=local',
                ],
            ],
            'CN=CLevel,OU=PassboltUsers,DC=passbolt,DC=local' => [
                'groups' => [],
                'users' => [
                    'CN=User1,OU=PassboltUsers,DC=passbolt,DC=local',
                ]
            ],
        ];

        foreach($expectedGroupsUsers as $groupDn => $groupsUsers) {
            $this->assertEquals($retrievedGroups[$groupDn]['group']['groups'], $groupsUsers['groups']);
            $this->assertEquals($retrievedGroups[$groupDn]['group']['users'], $groupsUsers['users']);
        }
    }

    public function testUsersFromParentGroup() {
        $groups = $this->userSyncAction->getDirectory()->getGroups();
        $users = $this->userSyncAction->getDirectory()->getUsers();
        $DirectoryResults = new DirectoryResults([]);
        $DirectoryResults->initializeWithEntries($users, $groups);
        $retrievedUsers = $DirectoryResults
            ->getRecursivelyFromParentGroup(LdapObjectType::USER, 'Administration')
            ->getUsers();

        $this->assertEquals(count($retrievedUsers), 2);

        $expectedUsers = [
            'CN=User1,OU=PassboltUsers,DC=passbolt,DC=local',
            'CN=User2,OU=PassboltUsers,DC=passbolt,DC=local',
        ];

        $this->assertEquals($expectedUsers, array_keys($retrievedUsers));
    }

    public function testGetTree() {
        $groups = $this->userSyncAction->getDirectory()->getGroups();
        $users = $this->userSyncAction->getDirectory()->getUsers();
        $DirectoryResults = new DirectoryResults([]);
        $DirectoryResults->initializeWithEntries($users, $groups);
        $tree = $DirectoryResults->getTree();
        $this->assertTrue(isset($tree['CN=Administration,OU=PassboltUsers,DC=passbolt,DC=local']));
        $group1 = $tree['CN=Administration,OU=PassboltUsers,DC=passbolt,DC=local'];
        $this->assertTrue(isset($group1['group']['groups']['CN=Managers,OU=PassboltUsers,DC=passbolt,DC=local']));
        $group2 = $group1['group']['groups']['CN=Managers,OU=PassboltUsers,DC=passbolt,DC=local'];
        $this->assertTrue(isset($group2['group']['groups']['CN=CLevel,OU=PassboltUsers,DC=passbolt,DC=local']));
    }

    public function testGetFlattenedTree() {
        $groups = $this->userSyncAction->getDirectory()->getGroups();
        $users = $this->userSyncAction->getDirectory()->getUsers();
        $DirectoryResults = new DirectoryResults([]);
        $DirectoryResults->initializeWithEntries($users, $groups);
        $flatTree = $DirectoryResults->getFlattenedTree();
        $expectedEntities = [
            'CN=User4,OU=PassboltUsers,DC=passbolt,DC=local',
            'CN=Administration,OU=PassboltUsers,DC=passbolt,DC=local',
            'CN=Managers,OU=PassboltUsers,DC=passbolt,DC=local',
            'CN=CLevel,OU=PassboltUsers,DC=passbolt,DC=local',
            'CN=Developers,OU=PassboltUsers,DC=passbolt,DC=local',
        ];
        foreach($flatTree as $key => $entity) {
            $this->assertEquals($entity->dn, $expectedEntities[$key]);
        }
    }
}