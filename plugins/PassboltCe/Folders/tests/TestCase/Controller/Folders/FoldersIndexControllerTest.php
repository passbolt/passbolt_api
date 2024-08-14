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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Test\TestCase\Controller\Folders;

use App\Model\Entity\Permission;
use App\Test\Fixture\Alt0\SecretsFixture;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\ResourceTypesFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\GroupsUsersModelTrait;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * @see \Passbolt\Folders\Controller\Folders\FoldersIndexController
 * @uses \Passbolt\Folders\Controller\Folders\FoldersIndexController
 */
class FoldersIndexControllerTest extends FoldersIntegrationTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use GroupsModelTrait;
    use GroupsUsersModelTrait;
    use PermissionsModelTrait;

    public $fixtures = [
        GpgkeysFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        RolesFixture::class,
        UsersFixture::class,
        ResourcesFixture::class,
        ResourceTypesFixture::class,
        SecretsFixture::class,
        GroupsFixture::class,
    ];

    private function insertFixtureCase2()
    {
        // Ada has access to folder Lovelace and Something as a OWNER
        // Lovelace (Ada:O) ; Something (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C'], [$userId => Permission::OWNER]);

        return [$folderA, $folderB, $folderC];
    }

    /**
     * @return void
     */
    public function testFoldersIndexFilterByIdSuccess()
    {
        [$folderA, $folderB, $folderC] = $this->insertFixtureCase2();

        $this->authenticateAs('ada');

        $this->getJson('/folders.json?api-version=2&filter[has-id][]=' . $folderA->id . '&filter[has-id][]=' . $folderB->id);
        $this->assertSuccess();

        $this->assertCount(2, $this->_responseJsonBody);
        $folderIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $this->assertContains($folderA->id, $folderIds);
        $this->assertContains($folderB->id, $folderIds);
        $this->assertNotContains(UuidFactory::uuid('folder.id.other'), $this->_responseJsonBody);

        $this->assertSuccess();
    }

    /**
     * @return void
     */
    public function testFoldersIndexFilterByIdSuccessOnOneId()
    {
        [$folderA, $folderB, $folderC] = $this->insertFixtureCase2();

        $this->authenticateAs('ada');

        $this->getJson('/folders.json?api-version=2&filter[has-id]=' . $folderA->id);
        $this->assertSuccess();

        $this->assertCount(1, $this->_responseJsonBody);
        $folderIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $this->assertContains($folderA->id, $folderIds);
        $this->assertNotContains(UuidFactory::uuid('folder.id.other'), $this->_responseJsonBody);

        $this->assertSuccess();
    }

    private function insertFixtureCase3()
    {
        // Relations are expressed as follow: folder_parent_id => [child_folder_id]
        $folderRelations = [
            UuidFactory::uuid('folder.id.a') => [],
            UuidFactory::uuid('folder.id.c') => [
                UuidFactory::uuid('folder.id.e'),
            ],
            UuidFactory::uuid('folder.id.d') => [
                UuidFactory::uuid('folder.id.f'),
                UuidFactory::uuid('folder.id.g'),
            ],
        ];

        $userId = UuidFactory::uuid('user.id.ada');
        foreach ($folderRelations as $folderParentId => $childrenFolders) {
            $this->addFolderFor(['id' => $folderParentId, ], [$userId => Permission::OWNER]);
            foreach ($childrenFolders as $childrenFolderId) {
                $this->addFolderFor(['id' => $childrenFolderId, 'folder_parent_id' => $folderParentId, ], [$userId => Permission::OWNER]);
            }
        }
    }

    public function provideFoldersIndexFilterHasParentSuccessRelations()
    {
        return [
            'When has parent is false' => [
                [false],
                [
                    UuidFactory::uuid('folder.id.a'),
                    UuidFactory::uuid('folder.id.c'),
                    UuidFactory::uuid('folder.id.d'),
                ],
            ],
            'When has-parent is single and return only 1 item' => [
                [
                    UuidFactory::uuid('folder.id.c'),
                ],
                [
                    UuidFactory::uuid('folder.id.e'),
                ],
            ],
            'When has-parent is single and return more than 1 item' => [
                [
                    UuidFactory::uuid('folder.id.d'),
                ],
                [
                    UuidFactory::uuid('folder.id.f'),
                    UuidFactory::uuid('folder.id.g'),
                ],
            ],
            'When has-parent is multiple and return 1 item' => [
                [
                    UuidFactory::uuid('folder.id.a'), // has no children
                    UuidFactory::uuid('folder.id.c'), // has 1 child
                ],
                [
                    UuidFactory::uuid('folder.id.e'),
                ],
            ],
            'When has-parent is multiple and return more than 1 item' => [
                [
                    UuidFactory::uuid('folder.id.c'), // has 1 child
                    UuidFactory::uuid('folder.id.d'), // has 2 children
                ],
                [
                    UuidFactory::uuid('folder.id.e'),
                    UuidFactory::uuid('folder.id.f'),
                    UuidFactory::uuid('folder.id.g'),
                ],
            ],
            'When has-parent is mixed with root and ids' => [
                [
                    false, // has no children
                    UuidFactory::uuid('folder.id.c'),
                ],
                [
                    UuidFactory::uuid('folder.id.e'),
                    UuidFactory::uuid('folder.id.a'),
                    UuidFactory::uuid('folder.id.c'),
                    UuidFactory::uuid('folder.id.d'),
                ],
            ],
        ];
    }

    /**
     * @dataProvider provideFoldersIndexFilterHasParentSuccessRelations
     * @param mixed $hasParentFilterId
     * @param array $expectedFolderChildrenIds
     * @return void
     */
    public function testFoldersIndexFilterHasParentSuccess($hasParentFilterId, array $expectedFolderChildrenIds)
    {
        $this->insertFixtureCase3();
        $this->authenticateAs('ada');

        $queryParameters = http_build_query([
            'api-version' => 2,
            'filter' => [
                'has-parent' => $hasParentFilterId,
            ],
        ]);

        $this->getJson('/folders.json?' . $queryParameters);
        $this->assertSuccess();

        $resultFolderIds = Hash::extract($this->_responseJsonBody, '{n}.id');

        foreach ($expectedFolderChildrenIds as $expectedFolderChildrenId) {
            $this->assertContains($expectedFolderChildrenId, $resultFolderIds);
        }
    }

    /**
     * @return void
     */
    public function testFoldersIndexFilterHasParentNoArrayBracketNotation()
    {
        $this->insertFixtureCase3();
        $this->authenticateAs('ada');

        $hasParentFilterId = UuidFactory::uuid('folder.id.c');
        $expectedFolderChildrenIds = [UuidFactory::uuid('folder.id.e'),];

        $this->getJson('/folders.json?api-version=2&filter[has-parent]=' . $hasParentFilterId);
        $this->assertSuccess();

        $resultFolderIds = Hash::extract($this->_responseJsonBody, '{n}.id');

        foreach ($expectedFolderChildrenIds as $expectedFolderChildrenId) {
            $this->assertContains($expectedFolderChildrenId, $resultFolderIds);
        }
    }

    /**
     * @dataProvider provideFoldersIndexFilterHasParentSuccessRelations
     * @param mixed $hasParentFilterId
     * @param array $expectedFolderChildrenIds
     * @return void
     */
    public function testFoldersIndexFilterHasParentAndFilterSuccess($hasParentFilterId, array $expectedFolderChildrenIds)
    {
        $this->insertFixtureCase3();
        $this->authenticateAs('ada');

        $queryParameters = http_build_query([
            'api-version' => 2,
            'filter' => [
                'has-parent' => $hasParentFilterId,
                'search' => UuidFactory::uuid('folder.id.name'),
            ],
        ]);

        $this->getJson('/folders.json?' . $queryParameters);
        $this->assertSuccess();

        $resultFolderIds = Hash::extract($this->_responseJsonBody, '{n}.id');

        foreach ($expectedFolderChildrenIds as $expectedFolderChildrenId) {
            $this->assertContains($expectedFolderChildrenId, $resultFolderIds);
        }
    }

    /**
     * @dataProvider provideFoldersIndexFilterHasParentSuccessRelations
     * @param mixed $hasParentFilterId
     * @param array $expectedFolderChildrenIds
     * @return void
     */
    public function testFoldersIndexFilterHasParentAndFilterSuccess_NoResult($hasParentFilterId, array $expectedFolderChildrenIds)
    {
        $this->insertFixtureCase3();
        $this->authenticateAs('ada');

        $queryParameters = http_build_query([
            'api-version' => 2,
            'filter' => [
                'has-parent' => $hasParentFilterId,
                'search' => 'nope',
            ],
        ]);

        $this->getJson('/folders.json?' . $queryParameters);
        $this->assertSuccess();

        $this->assertEmpty(Hash::extract($this->_responseJsonBody, '{n}.id'));
    }

    public function testFoldersIndexSuccess_ContainChildrenResources()
    {
        $userId = UuidFactory::uuid('user.id.ada');

        // Insert fixtures.
        // Ada has access to folder A, R1 and R2 as a OWNER
        // Ada see resources R1 and R2 in folder A
        // A (Ada:O)
        // |- R1 (Ada:O)
        // |- R2 (Ada:O)
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $resource1 = $this->addResourceFor(['name' => 'R1', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);
        $resource2 = $this->addResourceFor(['name' => 'R2', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);

        $this->authenticateAs('ada');
        $this->getJson('/folders.json?contain[children_resources]=1&api-version=2');
        $this->assertSuccess();

        $result = $this->_responseJsonBody;
        $folder = $result[0];

        $this->assertFolderAttributes($folder);
        $this->assertNotEmpty($folder->children_resources);
        $this->assertCount(2, $folder->children_resources);
        foreach ($folder->children_resources as $childResource) {
            $this->assertResourceAttributes($childResource);
            $this->assertObjectHasFolderParentIdAttribute($childResource, $folderA->id);
            $this->assertObjectNotHasAttribute('_joinData', $childResource);
        }
        $childrenResourceIds = Hash::extract($folder->children_resources, '{n}.id');
        $this->assertContains($resource1->get('id'), $childrenResourceIds);
        $this->assertContains($resource2->get('id'), $childrenResourceIds);
    }

    public function testFoldersIndexSuccess_ContainChildrenFolders()
    {
        $userId = UuidFactory::uuid('user.id.ada');

        // Insert fixtures.
        // Ada has access to folder A, B and C as a OWNER
        // Ada see folder folders B and C in A
        // A (Ada:O)
        // |- B (Ada:O)
        // |- C (Ada:O)
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $resource1 = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);
        $resource2 = $this->addFolderFor(['name' => 'C', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);

        $this->authenticateAs('ada');
        $this->getJson('/folders.json?contain[children_folders]=1&api-version=2');
        $this->assertSuccess();

        $result = $this->_responseJsonBody;
        $folder = $result[0];

        $this->assertFolderAttributes($folder);
        $this->assertNotEmpty($folder->children_folders);
        $this->assertCount(2, $folder->children_folders);
        foreach ($folder->children_folders as $childFolder) {
            $this->assertFolderAttributes($childFolder);
            $this->assertObjectNotHasAttribute('_joinData', $childFolder);
        }
        $childrenFolderIds = Hash::extract($folder->children_folders, '{n}.id');
        $this->assertContains($resource1->id, $childrenFolderIds);
        $this->assertContains($resource2->id, $childrenFolderIds);
    }

    public function testFoldersIndexSuccess_ContainPermission()
    {
        $userId = UuidFactory::uuid('user.id.ada');

        // Insert fixtures.
        // Ada has access to folder A, B and C as a OWNER
        // Ada see folder folders B and C in A
        // A (Ada:O)
        // |- B (Ada:O)
        // |- C (Ada:O)
        $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $this->addFolderFor(['name' => 'B'], [$userId => Permission::UPDATE]);
        $this->addFolderFor(['name' => 'C'], [$userId => Permission::READ]);

        $this->authenticateAs('ada');
        $this->getJson('/folders.json?contain[permission]=1&api-version=2');
        $this->assertSuccess();

        /** @var \Passbolt\Folders\Model\Entity\Folder[] $result */
        $result = $this->_responseJsonBody;

        $this->assertCount(3, $result);
        foreach ($result as $folder) {
            $this->assertFolderAttributes($folder);
            $this->assertPermissionAttributes($folder->permission);
        }
    }

    public function testFoldersIndexSuccess_ContainPermissions()
    {
        $userId = UuidFactory::uuid('user.id.ada');

        // Insert fixtures.
        // Ada has access to folder A, B and C as a OWNER
        // Ada see folder folders B and C in A
        // A (Ada:O)
        // |- B (Ada:O)
        // |- C (Ada:O)
        $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $this->addFolderFor(['name' => 'B'], [$userId => Permission::UPDATE]);
        $this->addFolderFor(['name' => 'C'], [$userId => Permission::READ]);

        $this->authenticateAs('ada');
        $this->getJson('/folders.json?contain[permissions]=1&api-version=2');
        $this->assertSuccess();

        /** @var \Passbolt\Folders\Model\Entity\Folder[] $result */
        $result = $this->_responseJsonBody;
        $this->assertCount(3, $result);
        foreach ($result as $folder) {
            $this->assertFolderAttributes($folder);
            $this->assertObjectHasAttribute('permissions', $folder);
            foreach ($folder->permissions as $permission) {
                $this->assertPermissionAttributes($permission);
            }
        }
    }

    public function testFoldersIndexSuccess_ContainPermissionsGroup()
    {
        $this->insertContainPermissionsGroupFixture();

        $this->authenticateAs('ada');
        $this->getJson('/folders.json?contain[permissions]=1&contain[permissions.group]=1&api-version=2');
        $this->assertSuccess();

        /** @var \Passbolt\Folders\Model\Entity\Folder[] $result */
        $result = $this->_responseJsonBody;
        $this->assertCount(1, $result);
        foreach ($result as $folder) {
            $this->assertFolderAttributes($folder);
            $this->assertObjectHasAttribute('permissions', $folder);

            /** @var \App\Model\Entity\Permission $permission */
            foreach ($folder->permissions as $permission) {
                if ($permission->aro === 'Group') {
                    break; // we are only interested in the group permission
                }
            }
            $this->assertObjectHasAttribute('group', $permission);
            $this->assertGroupAttributes($permission->group);
        }
    }

    public function insertContainPermissionsGroupFixture()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $groupData = [
            'groups_users' => [
                ['user_id' => $userAId, 'is_admin' => true],
                ['user_id' => $userBId],
            ],
        ];
        $group = $this->addGroup($groupData);
        $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER], [$group->id => Permission::OWNER]);
    }

    public function testFoldersIndexSuccess_ContainPermissionsUserProfile()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $this->authenticateAs('ada');
        $this->getJson('/folders.json?contain[permissions.user.profile]=1&api-version=2');

        $this->assertSuccess();
        /** @var \Passbolt\Folders\Model\Entity\Folder[] $result */
        $result = $this->_responseJsonBody;
        $folder = $result[0];
        $this->assertFolderAttributes($folder);
        $this->assertObjectHasAttribute('permissions', $folder);

        $permission = $folder->permissions[0];
        $user = $permission->user;
        $this->assertObjectHasAttribute('profile', $user);
        $this->assertProfileAttributes($user->profile);
    }
}
