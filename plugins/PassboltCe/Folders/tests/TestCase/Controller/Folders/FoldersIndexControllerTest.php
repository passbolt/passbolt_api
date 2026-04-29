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
use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\GroupsModelTrait;
use Cake\Utility\Hash;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;

/**
 * @see \Passbolt\Folders\Controller\Folders\FoldersIndexController
 * @uses \Passbolt\Folders\Controller\Folders\FoldersIndexController
 */
class FoldersIndexControllerTest extends FoldersIntegrationTestCase
{
    use FoldersModelTrait;
    use GroupsModelTrait;

    /**
     * @return void
     */
    public function testFoldersIndexFilterByIdSuccess()
    {
        // Ada has access to folderA, folderB and folderC as a OWNER
        // A (Ada:O) ; B (Ada:O); C (Ada:O)
        $userA = UserFactory::make()->user()->persist();
        [$folderA, $folderB, $folderC] = FolderFactory::make(3)->withPermissionsFor([$userA])->persist();

        $this->logInAs($userA);

        $this->getJson('/folders.json?api-version=2&filter[has-id][]=' . $folderA->id . '&filter[has-id][]=' . $folderB->id);
        $this->assertSuccess();

        $this->assertCount(2, $this->_responseJsonBody);
        $folderIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $this->assertContains($folderA->id, $folderIds);
        $this->assertContains($folderB->id, $folderIds);
        $this->assertNotContains($folderC->id, $this->_responseJsonBody);
    }

    /**
     * @return void
     */
    public function testFoldersIndexFilterByIdSuccessOnOneId()
    {
        // Ada has access to folderA, folderB and folderC as a OWNER
        // A (Ada:O) ; B (Ada:O); C (Ada:O)
        $userA = UserFactory::make()->user()->persist();
        [$folderA, $folderB, $folderC] = FolderFactory::make(3)->withPermissionsFor([$userA])->persist();

        $this->logInAs($userA);

        $this->getJson('/folders.json?api-version=2&filter[has-id]=' . $folderA->id);
        $this->assertSuccess();

        $this->assertCount(1, $this->_responseJsonBody);
        $folderIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $this->assertContains($folderA->id, $folderIds);
        $this->assertNotContains($folderB->id, $this->_responseJsonBody);
        $this->assertNotContains($folderC->id, $this->_responseJsonBody);
    }

    public function testFoldersIndexFilterHasParentSuccess()
    {
        // Ada is OWNER of folder A
        // Ada has OWNER on folder B
        // Ada is OWNER of folder C
        // Ada is OWNER of folder D
        // Ada is OWNER of folder E
        // Ada is OWNER of folder F
        // Ada sees D in B
        // Ada sees E in C
        // Ada sees F in C
        // ----
        // A (Ada:O)
        //
        // B (Ada:O)
        // |- D (Ada:O)
        //
        // C (Ada:O)
        // |- E (Ada:R)
        // |- F (Ada:O)
        $userA = UserFactory::make()->user()->persist();
        [$folderA, $folderB, $folderC] = FolderFactory::make(3)
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderD */
        $folderD = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderB)
            ->persist();
        [$folderE, $folderF] = FolderFactory::make(2)
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderC)
            ->persist();

        $cases = [
            'When has parent is false' => [
                'filter' => [false],
                'expected' => [
                    $folderA->id,
                    $folderB->id,
                    $folderC->id,
                ],
            ],
            'When has-parent is single and return only 1 item' => [
                'filter' => [
                    $folderB->id,
                ],
                'expected' => [
                    $folderD->id,
                ],
            ],
            'When has-parent is single and return more than 1 item' => [
                'filter' => [
                    $folderC->id,
                ],
                'expected' => [
                    $folderE->id,
                    $folderF->id,
                ],
            ],
            'When has-parent is multiple and return 1 item' => [
                'filter' => [
                    $folderA->id, // has no children
                    $folderB->id, // has 1 child
                ],
                'expected' => [
                    $folderD->id,
                ],
            ],
            'When has-parent is multiple and return more than 1 item' => [
                'filter' => [
                    $folderB->id, // has 1 child
                    $folderC->id, // has 2 children
                ],
                'expected' => [
                    $folderD->id,
                    $folderE->id,
                    $folderF->id,
                ],
            ],
            'When has-parent is mixed with root and ids' => [
                'filter' => [
                    false, // has no children
                    $folderB->id,
                ],
                'expected' => [
                    $folderD->id,
                    $folderA->id,
                    $folderB->id,
                    $folderC->id,
                ],
            ],
        ];

        $this->logInAs($userA);

        foreach ($cases as $case) {
            $queryParameters = http_build_query([
                'api-version' => 2,
                'filter' => [
                    'has-parent' => $case['filter'],
                ],
            ]);

            $this->getJson('/folders.json?' . $queryParameters);
            $this->assertSuccess();

            $resultFolderIds = Hash::extract($this->_responseJsonBody, '{n}.id');

            foreach ($case['expected'] as $expectedFolderChildrenId) {
                $this->assertContains($expectedFolderChildrenId, $resultFolderIds);
            }
        }
    }

    /**
     * @return void
     */
    public function testFoldersIndexFilterHasParentNoArrayBracketNotation()
    {
        // Ada has OWNER on folder B
        // Ada is OWNER of folder D
        // Ada sees D in B
        // A (Ada:O)
        // B (Ada:O)
        // |- D (Ada:O)
        $userA = UserFactory::make()->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderD */
        $folderD = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderB)
            ->persist();
        $this->logInAs($userA);

        $hasParentFilterId = $folderB->id;
        $expectedFolderChildrenIds = [$folderD->id,];

        $this->getJson('/folders.json?api-version=2&filter[has-parent]=' . $hasParentFilterId);
        $this->assertSuccess();

        $resultFolderIds = Hash::extract($this->_responseJsonBody, '{n}.id');

        foreach ($expectedFolderChildrenIds as $expectedFolderChildrenId) {
            $this->assertContains($expectedFolderChildrenId, $resultFolderIds);
        }
    }

    public function testFoldersIndexFilterHasParentAndFilterSuccess()
    {
        // Ada is OWNER of folder A
        // Ada has OWNER on folder B
        // Ada is OWNER of folder C
        // Ada is OWNER of folder D
        // Ada is OWNER of folder E
        // Ada is OWNER of folder F
        // Ada sees D in B
        // Ada sees E in C
        // Ada sees F in C
        // ----
        // A (Ada:O)
        //
        // B (Ada:O)
        // |- D (Ada:O)
        //
        // C (Ada:O)
        // |- E (Ada:R)
        // |- F (Ada:O)
        $userA = UserFactory::make()->user()->persist();
        [$folderA, $folderB, $folderC] = FolderFactory::make(3)
            ->patchData(['name' => 'test-folder'])
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderD */
        $folderD = FolderFactory::make(['name' => 'test-folder'])
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderB)
            ->persist();
        [$folderE, $folderF] = FolderFactory::make(2)
            ->patchData(['name' => 'test-folder'])
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderC)
            ->persist();

        $cases = [
            'When has parent is false' => [
                'filter' => [false],
                'expected' => [
                    $folderA->id,
                    $folderB->id,
                    $folderC->id,
                ],
            ],
            'When has-parent is single and return only 1 item' => [
                'filter' => [
                    $folderB->id,
                ],
                'expected' => [
                    $folderD->id,
                ],
            ],
            'When has-parent is single and return more than 1 item' => [
                'filter' => [
                    $folderC->id,
                ],
                'expected' => [
                    $folderE->id,
                    $folderF->id,
                ],
            ],
            'When has-parent is multiple and return 1 item' => [
                'filter' => [
                    $folderA->id, // has no children
                    $folderB->id, // has 1 child
                ],
                'expected' => [
                    $folderD->id,
                ],
            ],
            'When has-parent is multiple and return more than 1 item' => [
                'filter' => [
                    $folderB->id, // has 1 child
                    $folderC->id, // has 2 children
                ],
                'expected' => [
                    $folderD->id,
                    $folderE->id,
                    $folderF->id,
                ],
            ],
            'When has-parent is mixed with root and ids' => [
                'filter' => [
                    false, // has no children
                    $folderB->id,
                ],
                'expected' => [
                    $folderD->id,
                    $folderA->id,
                    $folderB->id,
                    $folderC->id,
                ],
            ],
        ];

        $this->logInAs($userA);

        foreach ($cases as $case) {
            $queryParameters = http_build_query([
                'api-version' => 2,
                'filter' => [
                    'has-parent' => $case['filter'],
                    'search' => $folderA->name,
                ],
            ]);

            $this->getJson('/folders.json?' . $queryParameters);
            $this->assertSuccess();

            $resultFolderIds = Hash::extract($this->_responseJsonBody, '{n}.id');

            foreach ($case['expected'] as $expectedId) {
                $this->assertContains($expectedId, $resultFolderIds);
            }
        }
    }

    public function testFoldersIndexFilterHasParentAndFilterSuccess_NoResult()
    {
        // Ada is OWNER of folder A
        // Ada has OWNER on folder B
        // Ada is OWNER of folder C
        // Ada is OWNER of folder D
        // Ada is OWNER of folder E
        // Ada is OWNER of folder F
        // Ada sees D in B
        // Ada sees E in C
        // Ada sees F in C
        // ----
        // A (Ada:O)
        //
        // B (Ada:O)
        // |- D (Ada:O)
        //
        // C (Ada:O)
        // |- E (Ada:R)
        // |- F (Ada:O)
        $userA = UserFactory::make()->user()->persist();
        [$folderA, $folderB, $folderC] = FolderFactory::make(3)
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderD */
        $folderD = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderB)
            ->persist();
        [$folderE, $folderF] = FolderFactory::make(2)
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderC)
            ->persist();

        $cases = [
            'When has parent is false' => [
                'filter' => [false],
                'expected' => [
                    $folderA->id,
                    $folderB->id,
                    $folderC->id,
                ],
            ],
            'When has-parent is single and return only 1 item' => [
                'filter' => [
                    $folderB->id,
                ],
                'expected' => [
                    $folderD->id,
                ],
            ],
            'When has-parent is single and return more than 1 item' => [
                'filter' => [
                    $folderC->id,
                ],
                'expected' => [
                    $folderE->id,
                    $folderF->id,
                ],
            ],
            'When has-parent is multiple and return 1 item' => [
                'filter' => [
                    $folderA->id, // has no children
                    $folderB->id, // has 1 child
                ],
                'expected' => [
                    $folderD->id,
                ],
            ],
            'When has-parent is multiple and return more than 1 item' => [
                'filter' => [
                    $folderB->id, // has 1 child
                    $folderC->id, // has 2 children
                ],
                'expected' => [
                    $folderD->id,
                    $folderE->id,
                    $folderF->id,
                ],
            ],
            'When has-parent is mixed with root and ids' => [
                'filter' => [
                    false, // has no children
                    $folderB->id,
                ],
                'expected' => [
                    $folderD->id,
                    $folderA->id,
                    $folderB->id,
                    $folderC->id,
                ],
            ],
        ];

        $this->logInAs($userA);

        foreach ($cases as $case) {
            $queryParameters = http_build_query([
                'api-version' => 2,
                'filter' => [
                    'has-parent' => $case['filter'],
                    'search' => 'nope',
                ],
            ]);

            $this->getJson('/folders.json?' . $queryParameters);
            $this->assertSuccess();

            $this->assertEmpty(Hash::extract($this->_responseJsonBody, '{n}.id'));
        }
    }

    public function testFoldersIndexSuccess_ContainChildrenResources()
    {
        // Ada has access to folder A, R1 and R2 as a OWNER
        // Ada see resources R1 and R2 in folder A
        // A (Ada:O)
        // |- R1 (Ada:O)
        // |- R2 (Ada:O)
        $userA = UserFactory::make()->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        [$resourceA, $resourceB] = ResourceFactory::make(2)
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();

        $this->loginAs($userA);
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
        $this->assertContains($resourceA->get('id'), $childrenResourceIds);
        $this->assertContains($resourceB->get('id'), $childrenResourceIds);
    }

    public function testFoldersIndexSuccess_ContainChildrenFolders()
    {
        // Ada has access to folder A, B and C as a OWNER
        // Ada see folder folders B and C in A
        // A (Ada:O)
        // |- B (Ada:O)
        // |- C (Ada:O)
        $userA = UserFactory::make()->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        [$folderB, $folderC] = FolderFactory::make(2)
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();

        $this->loginAs($userA);
        $this->getJson('/folders.json?contain[children_folders]=1&api-version=2');
        $this->assertSuccess();

        $result = $this->_responseJsonBody;
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = current(array_filter((array)$result, fn ($f) => $f->id === $folderA->id));

        $this->assertFolderAttributes($folder);
        $this->assertNotEmpty($folder->children_folders);
        $this->assertCount(2, $folder->children_folders);
        foreach ($folder->children_folders as $childFolder) {
            $this->assertFolderAttributes($childFolder);
            $this->assertObjectNotHasAttribute('_joinData', $childFolder);
        }
        $childrenFolderIds = Hash::extract($folder->children_folders, '{n}.id');
        $this->assertContains($folderB->id, $childrenFolderIds);
        $this->assertContains($folderC->id, $childrenFolderIds);
    }

    public function testFoldersIndexSuccess_ContainPermission()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a UPDATE
        // Ada has access to folder C as a READ
        // A (Ada:O)
        // B (Ada:U)
        // C (Ada:R)
        $userA = UserFactory::make()->user()->persist();
        // Folder A
        FolderFactory::make()->withPermissionsFor([$userA])->persist();
        // Folder B
        FolderFactory::make()->withPermissionsFor([$userA], Permission::UPDATE)->persist();
        // Folder C
        FolderFactory::make()->withPermissionsFor([$userA], Permission::READ)->persist();

        $this->loginAs($userA);
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
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a UPDATE
        // Ada has access to folder C as a READ
        // A (Ada:O)
        // B (Ada:U)
        // C (Ada:R)
        $userA = UserFactory::make()->user()->persist();
        // Folder A
        FolderFactory::make()->withPermissionsFor([$userA])->persist();
        // Folder B
        FolderFactory::make()->withPermissionsFor([$userA], Permission::UPDATE)->persist();
        // Folder C
        FolderFactory::make()->withPermissionsFor([$userA], Permission::READ)->persist();

        $this->loginAs($userA);
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
        // Ada is OWNER of folder A
        // GroupA is OWNER of folder A
        // Ada is manager of group GroupA
        // Betty is user of group GroupA
        // ---
        // A (Ada:O, G1:O)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $groupA = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        // FolderA
        FolderFactory::make()->withPermissionsFor([$userA, $groupA])->persist();

        $this->loginAs($userA);
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

    public function testFoldersIndexSuccess_ContainPermissionsUserProfile()
    {
        $userA = UserFactory::make()->user()->persist();
        FolderFactory::make()->withPermissionsFor([$userA])->persist();
        $this->logInAs($userA);
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

    public function testFoldersIndexError_NotJson()
    {
        $this->logInAsUser();
        $this->get('/folders');
        $this->assertResponseCode(404);
    }
}
