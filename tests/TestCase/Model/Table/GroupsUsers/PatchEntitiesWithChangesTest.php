<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace App\Test\TestCase\Model\Table\GroupsUsers;

use App\Error\Exception\CustomValidationException;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class PatchEntitiesWithChangesTest extends AppTestCase
{
    public $Groups;
    public $GroupsUsers;

    public $fixtures = ['app.Base/Groups', 'app.Base/Users', 'app.Base/GroupsUsers'];

    public function setUp()
    {
        parent::setUp();
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
        $this->GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
    }

    public function tearDown()
    {
        unset($this->Groups);
        unset($this->Users);

        parent::tearDown();
    }

    public function testValidationSuccessOnUpdateGroupUser()
    {
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $data = [
            ['id' => UuidFactory::uuid("group_user.id.freelancer-jean"), 'is_admin' => false],
            ['id' => UuidFactory::uuid("group_user.id.freelancer-kathleen"), 'is_admin' => true]
        ];
        $patchOptions = ['allowedOperations' => [
            'update' => true
        ]];

        // Retrieve the groups and its groups_users to update.
        $group = $this->Groups->get($groupId, ['contain' => ['GroupsUsers']]);

        // Patch the group groups_users.
        try {
            $group->groups_users = $this->GroupsUsers->patchEntitiesWithChanges($group->groups_users, $data, $group->id, $patchOptions);
        } catch (CustomValidationException $e) {
            $errors = $e->getErrors();
            $this->assertEmpty($errors, 'Expect no error ' . json_encode($errors));
        }

        // Assert there is no error, and the changes are well applied.
        $errors = $group->getErrors();
        $this->assertEmpty($errors);

        // The group_user of jean is well updated.
        $extract = Hash::extract($group->groups_users, "{n}[id={$data[0]['id']}]");
        $this->assertNotEmpty($extract);
        $this->assertEquals($data[0]['is_admin'], $extract[0]['is_admin']);

        // The group_user of kathleen is well updated.
        $extract = Hash::extract($group->groups_users, "{n}[id={$data[1]['id']}]");
        $this->assertNotEmpty($extract);
        $this->assertEquals($data[1]['is_admin'], $extract[0]['is_admin']);
    }

    public function testValidationSuccessOnDeleteGroupUser()
    {
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $data = [
            ['id' => UuidFactory::uuid("group_user.id.freelancer-kathleen"), 'delete' => true],
            ['id' => UuidFactory::uuid("group_user.id.freelancer-lynne"), 'delete' => true]
        ];
        $patchOptions = ['allowedOperations' => [
            'delete' => true
        ]];

        // Retrieve the groups and its groups_users to update.
        $group = $this->Groups->get($groupId, ['contain' => ['GroupsUsers']]);

        // Patch the group groups_users.
        try {
            $group->groups_users = $this->GroupsUsers->patchEntitiesWithChanges($group->groups_users, $data, $group->id, $patchOptions);
        } catch (CustomValidationException $e) {
            $errors = $e->getErrors();
            $this->assertEmpty($errors, 'Expect no error ' . json_encode($errors));
        }

        // Assert there is no error, and the changes are well applied.
        $errors = $group->getErrors();
        $this->assertEmpty($errors);

        // The membership of jean is well deleted.
        $extract = Hash::extract($group->groups_users, "{n}[id={$data[0]['id']}]");
        $this->assertEmpty($extract);

        // The membership of lynne is well deleted.
        $extract = Hash::extract($group->groups_users, "{n}[id={$data[1]['id']}]");
        $this->assertEmpty($extract);
    }

    public function testValidationSuccessOnAddGroupUser()
    {
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $data = [
            ['user_id' => UuidFactory::uuid("user.id.ada"), 'isAdmin' => true],
            ['user_id' => UuidFactory::uuid("user.id.betty"), 'isAdmin' => false],
        ];
        $patchOptions = ['allowedOperations' => [
            'add' => true
        ]];

        // Retrieve the groups and its groups_users to update.
        $group = $this->Groups->get($groupId, ['contain' => ['GroupsUsers']]);

        // Patch the group groups_users.
        try {
            $group->groups_users = $this->GroupsUsers->patchEntitiesWithChanges($group->groups_users, $data, $group->id, $patchOptions);
        } catch (CustomValidationException $e) {
            $errors = $e->getErrors();
            $this->assertEmpty($errors, 'Expect no error ' . json_encode($errors));
        }

        // Assert there is no error, and the changes are well applied.
        $errors = $group->getErrors();
        $this->assertEmpty($errors);

        // Ada has well been added to the group
        $extract = Hash::extract($group->groups_users, "{n}[user_id={$data[0]['user_id']}]");
        $this->assertNotEmpty($extract);
        $this->assertEquals($group->id, $extract[0]['group_id']);

        // Betty has well been added to the group
        $extract = Hash::extract($group->groups_users, "{n}[user_id={$data[1]['user_id']}]");
        $this->assertNotEmpty($extract);
        $this->assertEquals($group->id, $extract[0]['group_id']);
    }

    public function testValidationErrorOnUpdateGroupUser()
    {
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $testCases = [
            'membership does not exist' => [
                'errorField' => '0.id.group_user_exists',
                'data' => [['id' => UuidFactory::uuid()]]
            ],
            'membership relative to another group' => [
                'errorField' => '0.id.group_user_exists',
                'data' => [['id' => UuidFactory::uuid("group_user.id.it_support-jean")]]
            ],
            'is_admin cannot be empty' => [
                'errorField' => '0.is_admin._empty',
                'data' => [[
                    'id' => UuidFactory::uuid("group_user.id.freelancer-jean"),
                    'is_admin' => null]]
            ],
            'is_admin is invalid' => [
                'errorField' => '0.is_admin.boolean',
                'data' => [[
                    'id' => UuidFactory::uuid("group_user.id.freelancer-jean"),
                    'type' => 42]]
            ],
        ];

        $this->_executeErrorCases($testCases, $groupId);
    }

    public function testValidationErrorOnDeleteGroupUser()
    {
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $testCases = [
            'membership does not exist' => [
                'errorField' => '0.id.group_user_exists',
                'data' => [['id' => UuidFactory::uuid(), 'delete' => true]]
            ],
            'permission relative to another group' => [
                'errorField' => '0.id.group_user_exists',
                'data' => [['id' => UuidFactory::uuid("group_user.id.it_support-jean"), 'delete' => true]]
            ],
        ];

        $this->_executeErrorCases($testCases, $groupId);
    }

    public function testValidationErrorOnAddGroupUser()
    {
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $userAId = UuidFactory::uuid('user.id.ada');
        $testCases = [
            'user_id is required' => [
                'errorField' => '0.user_id._required',
                'data' => [['is_admin' => true]]
            ],
            'user_id cannot be empty' => [
                'errorField' => '0.user_id._empty',
                'data' => [['user_id' => null]]
            ],
            'is_admin type cannot be empty' => [
                'errorField' => '0.is_admin._empty',
                'data' => [['user_id' => $userAId, 'is_admin' => null]]
            ],
            'is_admin type is invalid' => [
                'errorField' => '0.is_admin.boolean',
                'data' => [['user_id' => $userAId, 'is_admin' => 42]]
            ],
        ];

        $this->_executeErrorCases($testCases, $groupId);
    }

    protected function _executeErrorCases($testCases, $groupId)
    {
        foreach ($testCases as $caseLabel => $case) {
            $group = $this->Groups->get($groupId, ['contain' => ['GroupsUsers']]);
            try {
                $group->groups_users = $this->GroupsUsers->patchEntitiesWithChanges($group->groups_users, $case['data'], $group->id);
                $this->assertFalse(false, 'Expect an exception');
            } catch (CustomValidationException $e) {
                $this->assertEntityError($e, $case['errorField']);
            }
        }
    }
}
