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
use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GroupsAddControllerTest extends AppIntegrationTestCase
{
    public $Groups;

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Groups') ? [] : ['className' => GroupsTable::class];
        $this->Groups = TableRegistry::getTableLocator()->get('Groups', $config);
    }

    protected function _getDummyPostData($data = []): array
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        $defaultData = [
            'name' => 'New group name',
            'groups_users' => [
                ['user_id' => $userA->id, 'is_admin' => 1],
                ['user_id' => $userB->id],
            ],
        ];
        $data = array_merge($defaultData, $data);

        return $data;
    }

    public function testGroupsAddSuccess(): void
    {
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        $success = [
            'chinese' => [
                'name' => '私人團體',
                'groups_users' => [
                    ['user_id' => $userA->id, 'is_admin' => 1],
                    ['user_id' => $userB->id],
                ],
            ],
            'slavic' => [
                'name' => 'Частная группа',
                'groups_users' => [
                    ['user_id' => $userA->id, 'is_admin' => 1],
                    ['user_id' => $userB->id, 'is_admin' => 1],
                    ['user_id' => $userC->id],
                ],
            ],
            'french' => [
                'name' => 'Groupe privé',
                'groups_users' => [
                    ['user_id' => $userA->id, 'is_admin' => 1],
                    ['user_id' => $userB->id],
                ],
            ],
            'funny' => [
                'name' => '😃',
                'groups_users' => [
                    ['user_id' => $userA->id, 'is_admin' => 1],
                    ['user_id' => $userB->id],
                ],
            ],
        ];

        foreach ($success as $data) {
            $this->logInAsAdmin();
            $this->postJson('/groups.json', $data);
            $this->assertResponseSuccess();
            $response = $this->getResponseBodyAsArray();
            $this->assertSame(count($data['groups_users']), count($response['groups_users']));
            foreach ($data['groups_users'] as $gu) {
                $responseGroupUser = Hash::extract($response['groups_users'], "{n}[user_id={$gu['user_id']}]");
                $this->assertNotEmpty($responseGroupUser, "Group user {$gu['user_id']} not found in response");
                $this->assertSame((bool)($gu['is_admin'] ?? null), $responseGroupUser[0]['is_admin']);
            }

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

    public function testGroupsAddSuccessLegacy(): void
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        $data = [
            'Group' => ['name' => 'legacy'],
            'GroupUsers' => [
                ['GroupUser' => ['user_id' => $userA->id, 'is_admin' => 1]],
                ['GroupUser' => ['user_id' => $userB->id]],
            ],
        ];
        $this->logInAsAdmin();
        $this->postJson('/groups.json', $data);
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

    public function testGroupsAddValidationErrors(): void
    {
        $responseCode = 400;
        $responseMessage = 'Could not validate group data';
        $errors = [
            'group name is missing' => [
                'errorField' => 'name._empty',
                'errorMessage' => 'The name should not be empty.',
                'data' => $this->_getDummyPostData(['name' => '']),
            ],
            'group name already exist' => [
                'errorField' => 'name.group_unique',
                'errorMessage' => 'The name is already used by another group.',
                'data' => $this->_getDummyPostData(['name' => 'Freelancer']),
            ],
            'group name invalid' => [
                'errorField' => 'name.utf8Extended',
                'errorMessage' => 'The name should be a valid UTF8 string.',
                'data' => $this->_getDummyPostData(['name' => ['test']]),
            ],
            'at least one group manager' => [
                'errorField' => 'groups_users.at_least_one_group_manager',
                'errorMessage' => 'A group manager should be provided.',
                'data' => $this->_getDummyPostData(['groups_users' => [
                    ['user_id' => UserFactory::make()->persist()->id],
                ]]),
            ],
            'nos users provided' => [
                'errorField' => 'groups_users.at_least_one_group_manager',
                'errorMessage' => 'A group manager should be provided.',
                'data' => ['name' => 'New group name'],
            ],
            'group user id not valid' => [
                'errorField' => 'groups_users.0.user_id.uuid',
                'errorMessage' => 'The user identifier should be a valid UUID.',
                'data' => $this->_getDummyPostData(['groups_users' => [
                    ['user_id' => 'invalid-id'],
                ]]),
            ],
            'group user soft deleted' => [
                'errorField' => 'groups_users.0.user_id.user_is_not_soft_deleted',
                'errorMessage' => 'The user does not exist.',
                'data' => $this->_getDummyPostData(['groups_users' => [
                    ['user_id' => UserFactory::make()->deleted()->persist()->id, 'is_admin' => true],
                ]]),
            ],
            'group user inactive' => [
                'errorField' => 'groups_users.0.user_id.user_is_active',
                'errorMessage' => 'The user does not exist.',
                'data' => $this->_getDummyPostData(['groups_users' => [
                    ['user_id' => UserFactory::make()->inactive()->persist()->id, 'is_admin' => true]],
                ]),
            ],
        ];

        GroupFactory::make(['name' => 'Freelancer'])->persist();
        foreach ($errors as $case) {
            $this->logInAsAdmin();
            $this->postJson('/groups.json', $case['data']);
            $this->assertError($responseCode, $responseMessage);
            $arr = $this->getResponseBodyAsArray();
            $this->assertEquals($case['errorMessage'], Hash::get($arr, $case['errorField']));
        }
    }

    public function testGroupsAddSuccess_ContainMyGroupUser_WhenUserIsNotMember(): void
    {
        $this->logInAsAdmin();
        $data = $this->_getDummyPostData();
        $this->postJson('/groups.json?contain[my_group_user]=1', $data);
        $this->assertResponseSuccess();
        $response = $this->_responseJsonBody;
        $this->assertObjectHasAttribute('my_group_user', $response);
        $this->assertNull($response->my_group_user);
    }

    public function testGroupsAddSuccess_ContainMyGroupUser_WhenUserIsMember(): void
    {
        $admin = $this->logInAsAdmin();
        $user = UserFactory::make()->persist();
        $data = [
            'name' => 'Test group with my_group_user',
            'groups_users' => [
                ['user_id' => $admin->id, 'is_admin' => 1],
                ['user_id' => $user->id],
            ],
        ];
        $this->postJson('/groups.json?contain[my_group_user]=1', $data);
        $this->assertResponseSuccess();
        $response = $this->_responseJsonBody;
        $group = GroupFactory::firstOrFail();
        $this->assertObjectHasAttribute('my_group_user', $response);
        $this->assertNotNull($response->my_group_user);
        $this->assertEquals($admin->id, $response->my_group_user->user_id);
        $this->assertEquals($group->id, $response->my_group_user->group_id);
        $this->assertTrue($response->my_group_user->is_admin);
    }

    public function testGroupsAddSuccess_DoNotContainMyGroupUserByDefault(): void
    {
        $this->logInAsAdmin();
        $data = $this->_getDummyPostData();
        $this->postJson('/groups.json', $data);
        $this->assertResponseSuccess();
        $response = $this->_responseJsonBody;
        $this->assertObjectNotHasAttribute('my_group_user', $response);
    }

    public function testGroupsAddErrorNotAdmin(): void
    {
        $this->logInAsUser();
        $postData = [];
        $this->postJson('/groups.json', $postData);
        $this->assertForbiddenError('You are not authorized to access that location.');
    }

    public function testGroupsAddErrorNotAuthenticated(): void
    {
        $postData = [];
        $this->postJson('/groups.json', $postData);
        $this->assertAuthenticationError();
    }

    public function testErrorCsrfToken(): void
    {
        $this->disableCsrfToken();
        $this->logInAsAdmin();
        $this->post('/groups.json');
        $this->assertResponseCode(403);
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testGroupsAddController_Error_NotJson(): void
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        $data = [
            'name' => 'Groupe privé',
            'groups_users' => [
                ['user_id' => $userA->id, 'is_admin' => 1],
                ['user_id' => $userB->id],
            ],
        ];

        $this->logInAsAdmin();
        $this->post('/groups', $data);
        $this->assertResponseCode(404);
    }
}
