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
 * @since         4.10.0
 */

namespace App\Test\TestCase\Controller\Groups;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\GroupsUserFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;

/**
 * @covers \App\Controller\Groups\GroupsAddController
 */
class GroupsAddControllerWithFactoriesTest extends AppIntegrationTestCase
{
    /**
     * @dataProvider groupAddSuccessRequestDataProvider
     */
    public function testGroupsAddController_Success($name, $groupsUsers): void
    {
        $this->logInAsAdmin();

        $data = ['name' => $name, 'groups_users' => $groupsUsers()];
        $this->postJson('/groups.json', $data);

        $this->assertResponseSuccess();
        // Check that the groups and its sub-models are saved as expected.
        $group = GroupFactory::find()
            ->contain('GroupsUsers')
            ->contain('GroupsUsers.Users')
            ->where(['id' => $this->_responseJsonBody->id])
            ->firstOrFail();
        $this->assertEquals($data['name'], $group->name);
        $this->assertFalse($group->deleted);
        $this->assertCount(count($data['groups_users']), $group->groups_users);
        foreach ($data['groups_users'] as $dataGroupUser) {
            $groupUser = Hash::extract($group->groups_users, "{n}[user_id={$dataGroupUser['user_id']}]");
            $this->assertNotEmpty($groupUser);
            $isAdmin = Hash::get((array)$dataGroupUser, 'is_admin', false);
            $this->assertEquals($isAdmin, $groupUser[0]->is_admin);
        }
    }

    public function testGroupAddController_Success_Legacy(): void
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
        $group = GroupFactory::find()
            ->contain('GroupsUsers')
            ->contain('GroupsUsers.Users')
            ->where(['id' => $this->_responseJsonBody->id])
            ->firstOrFail();
        $this->assertEquals($data['Group']['name'], $group->name);
        $this->assertFalse($group->deleted);
        $this->assertEquals(count($data['GroupUsers']), count($group->groups_users));
    }

    /**
     * @dataProvider invalidGroupDataProvider
     */
    public function testGroupsAddController_Error_InvalidGroupData($data, $errorField, $errorMessage): void
    {
        $this->logInAsAdmin();

        $this->postJson('/groups.json', $data());

        $this->assertError(400, 'Could not validate group data');
        $resultArray = $this->getResponseBodyAsArray();
        $this->assertEquals($errorMessage, Hash::get($resultArray, $errorField));
    }

    public function testGroupsAddController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $this->postJson('/groups.json', []);
        $this->assertForbiddenError();
    }

    public function testGroupAddController_Error_NotAuthenticated(): void
    {
        $this->postJson('/groups.json', []);
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
        $this->logInAsAdmin();
        $this->post('/groups', [
            'name' => 'Groupe privé',
            'groups_users' => [
                ['user_id' => UserFactory::make()->user()->persist()->id, 'is_admin' => 1],
                ['user_id' => UserFactory::make()->user()->persist()->id],
            ],
        ]);
        $this->assertResponseCode(404);
    }

    // ---------------------------
    // Data providers
    // ---------------------------

    /**
     * Data provider for testGroupsAddController_Success
     *
     * @return array
     */
    public function groupAddSuccessRequestDataProvider(): array
    {
        return [
            'chinese' => [
                'name' => '私人團體',
                /**
                 * We are using closure here because data providers run before the phpunit's setUp method.
                 * And in setUp we truncate all the dirty tables hence we don't get the data in the test case that was generated via data provider.
                 * Using closure here makes sure we generate data in the test case.
                 */
                'groups_users' => function () {
                    return [
                        ['user_id' => UserFactory::make()->user()->persist()->id, 'is_admin' => 1],
                        ['user_id' => UserFactory::make()->user()->persist()->id],
                    ];
                },
            ],
            'slavic' => [
                'name' => 'Частная группа',
                'groups_users' => function () {
                    return [
                        ['user_id' => UserFactory::make()->user()->persist()->id, 'is_admin' => 1],
                        ['user_id' => UserFactory::make()->user()->persist()->id, 'is_admin' => 1],
                        ['user_id' => UserFactory::make()->user()->persist()->id],
                    ];
                },
            ],
            'french' => [
                'name' => 'Groupe privé',
                'groups_users' => function () {
                    return [
                        ['user_id' => UserFactory::make()->user()->persist()->id, 'is_admin' => 1],
                        ['user_id' => UserFactory::make()->user()->persist()->id],
                    ];
                },
            ],
            'funny' => [
                'name' => '😃',
                'groups_users' => function () {
                    return [
                        ['user_id' => UserFactory::make()->user()->persist()->id, 'is_admin' => 1],
                        ['user_id' => UserFactory::make()->user()->persist()->id],
                    ];
                },
            ],
        ];
    }

    public function invalidGroupDataProvider(): array
    {
        return [
            'group name is missing' => [
                'data' => function () {
                    return $this->getDummyPostData(['name' => '']);
                },
                'errorField' => 'name._empty',
                'errorMessage' => 'The name should not be empty.',
            ],
            'group name already exist' => [
                'data' => function () {
                    return $this->getDummyPostData(['name' => GroupFactory::make(['name' => 'Freelancer'])->persist()->name]);
                },
                'errorField' => 'name.group_unique',
                'errorMessage' => 'The name is already used by another group.',
            ],
            'group name invalid' => [
                'data' => function () {
                    return $this->getDummyPostData(['name' => ['test']]);
                },
                'errorField' => 'name.utf8Extended',
                'errorMessage' => 'The name should be a valid UTF8 string.',

            ],
            'at least one group manager' => [
                'data' => function () {
                    $group = GroupFactory::make()->with(
                        'GroupsUsers',
                        GroupsUserFactory::make()
                            ->with('Users', UserFactory::make())
                            ->admin()
                    )->persist();

                    return $this->getDummyPostData([
                        'name' => $group->name,
                        'groups_users' => [
                            $group->groups_users[0]->user_id,
                        ],
                    ]);
                },
                'errorField' => 'groups_users.at_least_one_group_manager',
                'errorMessage' => 'A group manager should be provided.',

            ],
            'nos users provided' => [
                'data' => function () {
                    return ['name' => 'New group name'];
                },
                'errorField' => 'groups_users.at_least_one_group_manager',
                'errorMessage' => 'A group manager should be provided.',

            ],
            'group user id not valid' => [
                'data' => function () {
                    return $this->getDummyPostData(['groups_users' => [
                        ['user_id' => 'invalid-id'],
                    ]]);
                },
                'errorField' => 'groups_users.0.user_id.uuid',
                'errorMessage' => 'The user identifier should be a valid UUID.',

            ],
            'group user soft deleted' => [
                'data' => function () {
                    return $this->getDummyPostData([
                        'groups_users' => [
                            ['user_id' => UserFactory::make()->user()->deleted()->persist()->id, 'is_admin' => true],
                        ],
                    ]);
                },
                'errorField' => 'groups_users.0.user_id.user_is_not_soft_deleted',
                'errorMessage' => 'The user does not exist.',

            ],
            'group user inactive' => [
                'data' => function () {
                    return $this->getDummyPostData(['groups_users' => [
                        ['user_id' => UserFactory::make()->inactive()->user()->persist()->id, 'is_admin' => true],
                    ]]);
                },
                'errorField' => 'groups_users.0.user_id.user_is_active',
                'errorMessage' => 'The user does not exist.',

            ],
        ];
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    private function getDummyPostData(array $data = []): array
    {
        $defaultData = [
            'name' => 'New group name',
            'groups_users' => [
                ['user_id' => UuidFactory::uuid(), 'is_admin' => 1],
                ['user_id' => UuidFactory::uuid()],
            ],
        ];

        return array_merge($defaultData, $data);
    }
}
