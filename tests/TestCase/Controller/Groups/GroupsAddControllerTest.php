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

namespace App\Test\TestCase\Controller\Groups;

use App\Model\Entity\Role;
use App\Model\Table\GroupsTable;
use App\Test\TestCase\ApplicationTest;
use App\Utility\Common;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GroupsAddControllerTest extends ApplicationTest
{
    public $Groups;

    public $fixtures = ['app.groups', 'app.users', 'app.groups_users'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Groups') ? [] : ['className' => GroupsTable::class];
        $this->Groups = TableRegistry::get('Groups', $config);
    }

    public function testAddApiV1Success()
    {
        $this->authenticateAs('admin', Role::ADMIN);
        $groupName = 'UnitTest Group';
        $userAId = Common::uuid('user.id.ada');
        $userBId = Common::uuid('user.id.betty');
        $postData = [
            'Group' => [
                'name' => $groupName,
            ],
            'GroupUsers' => [
                [
                    'GroupUser' => [
                        'user_id' => $userAId,
                        'is_admin' => 1,
                    ],
                ],
                [
                    'GroupUser' => [
                        'user_id' => $userBId,
                    ],
                ],
            ],
        ];
        $this->postJson("/groups.json", $postData);
        $this->assertSuccess();

        // Check that the groups and its sub-models are saved as expected.
        $group = $this->Groups->find()
            ->contain('GroupsUsers')
            ->contain('GroupsUsers.Users')
            ->where(['id' => $this->_responseJsonBody->Group->id])
            ->first();
        $this->assertEquals($groupName, $group->name);
        $this->assertCount(2, $group->groups_users);
        $groupUserA = Hash::extract($group->groups_users, "{n}[user_id=$userAId][is_admin=true]");
        $this->assertNotEmpty($groupUserA);
        $groupUserB = Hash::extract($group->groups_users, "{n}[user_id=$userBId]");
        $this->assertNotEmpty($groupUserB);
    }

    public function testAddRuleValidationErrorGroupUnique()
    {
        $this->authenticateAs('admin', Role::ADMIN);
        $groupName = 'Freelancer';
        $postData = [
            'Group' => [
                'name' => $groupName,
            ]
        ];
        $this->postJson("/groups.json", $postData);
        $this->assertError(400, 'Could not validate group data.');
        $this->assertNotEmpty($this->_responseJsonBody->Group->name);
        $this->assertContains('The group name provided is already used by another group.', $this->_responseJsonBody->Group->name);
    }

    public function testAddRuleValidationErrorAtLeastOneAdmin()
    {
        $this->authenticateAs('admin', Role::ADMIN);
        $groupName = 'UnitTest Group';
        $postData = [
            'Group' => [
                'name' => $groupName,
            ]
        ];
        $this->postJson("/groups.json", $postData);
        $this->assertError(400, 'Could not validate group data.');
        $this->assertEmpty($this->_responseJsonBody);
        // @TODO For now the App JS checks that there is an admin provided. The API doesn't return any feedbacks on that error.
        $this->markTestIncomplete();
    }


    public function testAddRuleValidationErrorHasManyGroupsUsersRuleUserExists()
    {
        $this->authenticateAs('admin', Role::ADMIN);
        $groupName = 'UnitTest Group';
        $postData = [
            'Group' => [
                'name' => $groupName,
            ],
            'GroupUsers' => [
                [
                    'GroupUser' => [
                        'user_id' => Common::uuid(),
                        'is_admin' => 1,
                    ],
                ],
            ],
        ];
        $this->postJson("/groups.json", $postData);
        $this->assertError(400, 'Could not validate group data.');
        $this->assertEmpty($this->_responseJsonBody);
        // @TODO For now the App JS checks that there is an admin provided. The API doesn't return any feedbacks on that error.
        $this->markTestIncomplete();
    }

    public function testAddErrorNotAdmin()
    {
        $this->authenticateAs('dame');
        $postData = [];
        $this->postJson("/groups.json", $postData);
        $this->assertForbiddenError();
    }

    public function testAddErrorNotAuthenticated()
    {
        $postData = [];
        $this->postJson("/groups.json", $postData);
        $this->assertAuthenticationError();
    }
}
