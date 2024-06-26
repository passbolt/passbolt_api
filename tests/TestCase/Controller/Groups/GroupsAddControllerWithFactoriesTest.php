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
 * @since         4.9.0
 */

namespace App\Test\TestCase\Controller\Groups;

use App\Model\Table\GroupsTable;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\GroupsUserFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GroupsAddControllerWithFactoriesTest extends AppIntegrationTestCase
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
        $defaultData = [
            'name' => 'New group name',
            'groups_users' => [
                ['user_id' => UuidFactory::uuid(), 'is_admin' => 1],
                ['user_id' => UuidFactory::uuid()],
            ],
        ];
        $data = array_merge($defaultData, $data);

        return $data;
    }

    public function testGroupsAddController_Success(): void
    {
        $success = [
            'chinese' => [
                'name' => '私人團體',
                'groups_users' => [
                    ['user_id' => UserFactory::make()->user()->persist()->id, 'is_admin' => 1],
                    ['user_id' => UserFactory::make()->user()->persist()->id],
                ],
            ],
            'slavic' => [
                'name' => 'Частная группа',
                'groups_users' => [
                    ['user_id' => UserFactory::make()->user()->persist()->id, 'is_admin' => 1],
                    ['user_id' => UserFactory::make()->user()->persist()->id, 'is_admin' => 1],
                    ['user_id' => UserFactory::make()->user()->persist()->id],
                ],
            ],
            'french' => [
                'name' => 'Groupe privé',
                'groups_users' => [
                    ['user_id' => UserFactory::make()->user()->persist()->id, 'is_admin' => 1],
                    ['user_id' => UserFactory::make()->user()->persist()->id],
                ],
            ],
            'funny' => [
                'name' => '😃',
                'groups_users' => [
                    ['user_id' => UserFactory::make()->user()->persist()->id, 'is_admin' => 1],
                    ['user_id' => UserFactory::make()->user()->persist()->id],
                ],
            ],
        ];

        foreach ($success as $data) {
            $this->logInAsAdmin();
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

    public function testGroupAddController_Success_Legacy()
    {
        $data = [
            'Group' => ['name' => 'legacy'],
            'GroupUsers' => [
                ['GroupUser' => ['user_id' => UserFactory::make()->user()->persist()->id, 'is_admin' => 1]],
                ['GroupUser' => ['user_id' => UserFactory::make()->user()->persist()->id]],
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

    public function testGroupsAddController_Error_InvalidGroupData(): void
    {
        $responseCode = 400;
        $responseMessage = 'Could not validate group data';
        $group = GroupFactory::make()->with(
            'GroupsUsers',
            GroupsUserFactory::make()
                ->with('Users', UserFactory::make())
                ->admin()
        )
            ->persist();
        $errors = [
            'group name is missing' => [
                'errorField' => 'name._empty',
                'errorMessage' => 'The name should not be empty.',
                'data' => $this->_getDummyPostData(['name' => '']),
            ],
            'group name already exist' => [
                'errorField' => 'name.group_unique',
                'errorMessage' => 'The name is already used by another group.',
                'data' => $this->_getDummyPostData(['name' => GroupFactory::make(['name' => 'Freelancer'])->persist()->name]),
            ],
            'group name invalid' => [
                'errorField' => 'name.utf8Extended',
                'errorMessage' => 'The name should be a valid UTF8 string.',
                'data' => $this->_getDummyPostData(['name' => ['test']]),
            ],
            'at least one group manager' => [
                'errorField' => 'groups_users.at_least_one_group_manager',
                'errorMessage' => 'A group manager should be provided.',
                'data' => $this->_getDummyPostData([
                    'name' => $group->name,
                    'groups_users' => [
                        $group->groups_users[0]->user_id,
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
                    ['user_id' => UserFactory::make()->user()->deleted()->persist()->id, 'is_admin' => true]]]),
            ],
            'group user inactive' => [
                'errorField' => 'groups_users.0.user_id.user_is_active',
                'errorMessage' => 'The user does not exist.',
                'data' => $this->_getDummyPostData(['groups_users' => [
                    ['user_id' => UserFactory::make()->inactive()->user()->persist()->id, 'is_admin' => true],
                ]]),
            ],
        ];

        foreach ($errors as $case) {
            $this->logInAsAdmin();
            $this->postJson('/groups.json', $case['data']);
            $this->assertError($responseCode, $responseMessage);
            $arr = $this->getResponseBodyAsArray();
            $this->assertEquals($case['errorMessage'], Hash::get($arr, $case['errorField']));
        }
    }

    public function testGroupsAddController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $postData = [];
        $this->postJson('/groups.json', $postData);
        $this->assertForbiddenError();
    }

    public function testGroupAddController_Error_NotAuthenticated(): void
    {
        $postData = [];
        $this->postJson('/groups.json', $postData);
        $this->assertAuthenticationError();
    }

    public function testGroupAddController_Error_CsrfToken(): void
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
        $data = [
            'name' => 'Groupe privé',
            'groups_users' => [
                ['user_id' => UserFactory::make()->user()->persist()->id, 'is_admin' => 1],
                ['user_id' => UserFactory::make()->user()->persist()->id],
            ],
        ];

        $this->logInAsAdmin();
        $this->post('/groups', $data);
        $this->assertResponseCode(404);
    }
}
