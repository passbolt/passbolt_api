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

namespace App\Test\TestCase\Model\Table\Groups;

use App\Model\Table\GroupsTable;
use App\Test\Lib\AppTestCase;
use App\Utility\Common;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class SaveTest extends AppTestCase
{
    public $Groups;

    public $fixtures = ['app.groups', 'app.users', 'app.groups_users'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Groups') ? [] : ['className' => GroupsTable::class];
        $this->Groups = TableRegistry::get('Groups', $config);
    }

    public function tearDown()
    {
        unset($this->Groups);

        parent::tearDown();
    }

    public function testSuccess()
    {
        $groupName = 'UnitTest Group';
        $userAId = Common::uuid('user.id.ada');
        $userBId = Common::uuid('user.id.betty');
        $group = $this->Groups->newEntity(
            [
                'name' => $groupName,
                'groups_users' => [
                    ['user_id' => $userAId, 'is_admin' => true],
                    ['user_id' => $userBId]
                ],
                'created_by' => Common::uuid('user.id.admin'),
                'modified_by' => Common::uuid('user.id.admin'),
            ],
            [
                'validate' => 'default',
                'accessibleFields' => [
                    'name' => true,
                    'created_by' => true,
                    'modified_by' => true,
                    'groups_users' => true
                ]
            ]
        );
        $save = $this->Groups->save($group);
        $this->assertNotFalse($save, 'The group save operation failed.');

        // Check that the groups and its sub-models are saved as expected.
        $group = $this->Groups->find()
            ->contain('GroupsUsers')
            ->contain('GroupsUsers.Users')
            ->where(['id' => $save->id])
            ->first();
        $this->assertEquals($groupName, $group->name);
        $this->assertCount(2, $group->groups_users);
        $groupUserA = Hash::extract($group->groups_users, "{n}[user_id=$userAId][is_admin=true]");
        $this->assertNotEmpty($groupUserA);
        $groupUserB = Hash::extract($group->groups_users, "{n}[user_id=$userBId]");
        $this->assertNotEmpty($groupUserB);
    }

    public function testErrorRuleGroupUnique()
    {
        $groupName = 'Freelancer';
        $userAId = Common::uuid('user.id.ada');
        $userBId = Common::uuid('user.id.betty');
        $group = $this->Groups->newEntity(
            [
                'name' => $groupName,
                'groups_users' => [
                    ['user_id' => $userAId, 'is_admin' => true],
                    ['user_id' => $userBId]
                ],
                'created_by' => Common::uuid('user.id.admin'),
                'modified_by' => Common::uuid('user.id.admin'),
            ],
            [
                'validate' => 'default',
                'accessibleFields' => [
                    'name' => true,
                    'created_by' => true,
                    'modified_by' => true,
                    'groups_users' => true
                ]
            ]
        );
        $save = $this->Groups->save($group);
        $this->assertFalse($save);
        $errors = $group->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['name']['group_unique']);
    }

    public function testErrorRuleAtLeastOneAdmin()
    {
        $groupName = 'UnitTest Group';
        $userAId = Common::uuid('user.id.ada');
        $userBId = Common::uuid('user.id.betty');
        $group = $this->Groups->newEntity(
            [
                'name' => $groupName,
                'groups_users' => [
                    ['user_id' => $userAId],
                    ['user_id' => $userBId]
                ],
                'created_by' => Common::uuid('user.id.admin'),
                'modified_by' => Common::uuid('user.id.admin'),
            ],
            [
                'validate' => 'default',
                'accessibleFields' => [
                    'name' => true,
                    'created_by' => true,
                    'modified_by' => true,
                    'groups_users' => true
                ]
            ]
        );
        $save = $this->Groups->save($group);
        $this->assertFalse($save);
        $errors = $group->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['groups_users']['at_least_one_admin']);
    }

    public function testErrorHasManyGroupsUsersRuleUserExists()
    {
        $groupName = 'UnitTest Group';
        $userAId = Common::uuid('user.id.ada');
        $userBId = Common::uuid();
        $group = $this->Groups->newEntity(
            [
                'name' => $groupName,
                'groups_users' => [
                    ['user_id' => $userAId, 'is_admin' => true],
                    ['user_id' => $userBId]
                ],
                'created_by' => Common::uuid('user.id.admin'),
                'modified_by' => Common::uuid('user.id.admin'),
            ],
            [
                'validate' => 'default',
                'accessibleFields' => [
                    'name' => true,
                    'created_by' => true,
                    'modified_by' => true,
                    'groups_users' => true
                ]
            ]
        );
        $save = $this->Groups->save($group);
        $this->assertFalse($save);
        $errors = $group->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['groups_users'][1]['user_id']['user_exists']);
    }


    public function testErrorHasManyGroupsUsersRuleUserIsNotSoftDeleted()
    {
        $groupName = 'UnitTest Group';
        $userAId = Common::uuid('user.id.ada');
        $userBId = Common::uuid('user.id.sofia');
        $group = $this->Groups->newEntity(
            [
                'name' => $groupName,
                'groups_users' => [
                    ['user_id' => $userAId, 'is_admin' => true],
                    ['user_id' => $userBId]
                ],
                'created_by' => Common::uuid('user.id.admin'),
                'modified_by' => Common::uuid('user.id.admin'),
            ],
            [
                'validate' => 'default',
                'accessibleFields' => [
                    'name' => true,
                    'created_by' => true,
                    'modified_by' => true,
                    'groups_users' => true
                ]
            ]
        );
        $save = $this->Groups->save($group);
        $this->assertFalse($save);
        $errors = $group->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['groups_users'][1]['user_id']['user_is_not_soft_deleted']);
    }
}
