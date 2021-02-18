<?php
declare(strict_types=1);

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

namespace App\Test\TestCase\Controller\Groups;

use App\Model\Table\GroupsTable;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GroupsAddControllerTest extends AppIntegrationTestCase
{
    public $Groups;

    public $fixtures = [
        'app.Base/Groups', 'app.Base/Users', 'app.Base/GroupsUsers', 'app.Base/Profiles', 'app.Base/Roles',
         'app.Base/Avatars',
    ];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Groups') ? [] : ['className' => GroupsTable::class];
        $this->Groups = TableRegistry::getTableLocator()->get('Groups', $config);
    }

    protected function _getDummyPostData($data = [])
    {
        $defaultData = [
            'name' => 'New group name',
            'groups_users' => [
                ['user_id' => UuidFactory::uuid('user.id.ada'), 'is_admin' => 1],
                ['user_id' => UuidFactory::uuid('user.id.betty')],
            ],
        ];
        $data = array_merge($defaultData, $data);

        return $data;
    }

    public function testGroupsAddSuccess()
    {
        $success = [
            'chinese' => [
                'name' => '私人團體',
                'groups_users' => [
                    ['user_id' => UuidFactory::uuid('user.id.ada'), 'is_admin' => 1],
                    ['user_id' => UuidFactory::uuid('user.id.betty')],
                ],
            ],
            'slavic' => [
                'name' => 'Частная группа',
                'groups_users' => [
                    ['user_id' => UuidFactory::uuid('user.id.ada'), 'is_admin' => 1],
                    ['user_id' => UuidFactory::uuid('user.id.betty'), 'is_admin' => 1],
                    ['user_id' => UuidFactory::uuid('user.id.carol')],
                ],
            ],
            'french' => [
                'name' => 'Groupe privé',
                'groups_users' => [
                    ['user_id' => UuidFactory::uuid('user.id.ada'), 'is_admin' => 1],
                    ['user_id' => UuidFactory::uuid('user.id.betty')],
                ],
            ],
            'funny' => [
                'name' => '😃',
                'groups_users' => [
                    ['user_id' => UuidFactory::uuid('user.id.ada'), 'is_admin' => 1],
                    ['user_id' => UuidFactory::uuid('user.id.betty')],
                ],
            ],
        ];

        foreach ($success as $case => $data) {
            $this->authenticateAs('admin');
            $this->postJson('/groups.json', $data);
            $this->assertResponseSuccess();

            // Check that the groups and its sub-models are saved as expected.
            $group = $this->Groups->find()
                ->contain('GroupsUsers')
                ->contain('GroupsUsers.Users')
                ->where(['id' => $this->_responseJsonBody->id])
                ->first();
            $this->assertEquals($data['name'], $group->name);
            $this->assertEquals(false, $group->deleted);
            $this->assertCount(count($data['groups_users']), $group->groups_users);
            foreach ($data['groups_users'] as $dataGroupUser) {
                $groupUser = Hash::extract($group->groups_users, "{n}[user_id={$dataGroupUser['user_id']}]");
                $this->assertNotEmpty($groupUser);
                $isAdmin = Hash::get((array)$dataGroupUser, 'is_admin', false);
                $this->assertEquals($isAdmin, $groupUser[0]->is_admin);
            }
        }
    }

    public function testGroupsAddSuccessLegacy()
    {
        $data = [
            'Group' => ['name' => 'legacy'],
            'GroupUsers' => [
                ['GroupUser' => ['user_id' => UuidFactory::uuid('user.id.ada'), 'is_admin' => 1]],
                ['GroupUser' => ['user_id' => UuidFactory::uuid('user.id.betty')]],
            ],
        ];
        $this->authenticateAs('admin');
        $this->postJson('/groups.json?api-version=v2', $data);
        $this->assertResponseSuccess();
        $group = $this->Groups->find()
            ->contain('GroupsUsers')
            ->contain('GroupsUsers.Users')
            ->where(['id' => $this->_responseJsonBody->id])
            ->first();
        $this->assertEquals($data['Group']['name'], $group->name);
        $this->assertEquals(false, $group->deleted);
        $this->assertEquals(count($data['GroupUsers']), count($group->groups_users));
    }

    public function testGroupsAddValidationErrors()
    {
        $responseCode = 400;
        $responseMessage = 'Could not validate group data';
        $errors = [
            'group name is missing' => [
                'errorField' => 'name._empty',
                'errorMessage' => 'The name cannot be empty.',
                'data' => $this->_getDummyPostData(['name' => '']),
            ],
            'group name already exist' => [
                'errorField' => 'name.group_unique',
                'errorMessage' => 'The name provided is already used by another group.',
                'data' => $this->_getDummyPostData(['name' => 'Freelancer']),
            ],
            'group name invalid' => [
                'errorField' => 'name.utf8Extended',
                'errorMessage' => 'The name is not a valid utf8 string.',
                'data' => $this->_getDummyPostData(['name' => ['test']]),
            ],
            'at least one group manager' => [
                'errorField' => 'groups_users.at_least_one_admin',
                'errorMessage' => 'A group manager must be provided.',
                'data' => $this->_getDummyPostData(['groups_users' => [
                    ['user_id' => UuidFactory::uuid('user.id.ada')],
                ]]),
            ],
            'nos users provided' => [
                'errorField' => 'groups_users.at_least_one_admin',
                'errorMessage' => 'A group manager must be provided.',
                'data' => ['name' => 'New group name'],
            ],
            'group user id not valid' => [
                'errorField' => 'groups_users.0.user_id.uuid',
                'errorMessage' => 'The provided value is invalid',
                'data' => $this->_getDummyPostData(['groups_users' => [
                    ['user_id' => 'invalid-id'],
                ]]),
            ],
            'group user soft deleted' => [
                'errorField' => 'groups_users.0.user_id.user_is_not_soft_deleted',
                'errorMessage' => 'The user does not exist.',
                'data' => $this->_getDummyPostData(['groups_users' => [
                    ['user_id' => UuidFactory::uuid('user.id.sofia'), 'is_admin' => true],
                ]]),
            ],
            'group user inactive' => [
                'errorField' => 'groups_users.0.user_id.user_is_active',
                'errorMessage' => 'The user does not exist.',
                'data' => $this->_getDummyPostData(['groups_users' => [
                    ['user_id' => UuidFactory::uuid('user.id.ruth'), 'is_admin' => true]],
                ]),
            ],
        ];

        foreach ($errors as $caseLabel => $case) {
            $this->authenticateAs('admin');
            $this->postJson('/groups.json', $case['data']);
            $this->assertError($responseCode, $responseMessage);
            $arr = json_decode(json_encode($this->_responseJsonBody), true);
            $this->assertEquals($case['errorMessage'], Hash::get($arr, $case['errorField']));
        }
    }

    public function testGroupsAddCannotModifyNotAccessibleFields()
    {
        $this->markTestIncomplete();
    }

    public function testGroupsAddErrorNotAdmin()
    {
        $this->authenticateAs('dame');
        $postData = [];
        $this->postJson('/groups.json', $postData);
        $this->assertForbiddenError();
    }

    public function testGroupsAddErrorNotAuthenticated()
    {
        $postData = [];
        $this->postJson('/groups.json', $postData);
        $this->assertAuthenticationError();
    }

    public function testErrorCsrfToken()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('admin');
        $this->post('/groups.json?api-version=2');
        $this->assertResponseCode(403);
    }
}
