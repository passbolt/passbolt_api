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

use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Utility\Hash;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;

/**
 * Passbolt\Folders\Controller\Folders\FoldersViewController Test Case
 *
 * @uses \Passbolt\Folders\Controller\Folders\FoldersViewController
 */
class FoldersViewControllerTest extends FoldersIntegrationTestCase
{
    use FoldersModelTrait;
    use GroupsModelTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        Configure::write('passbolt.plugins.folders', ['enabled' => true]);
    }

    public function testFoldersViewSuccess_ContainChildrenFolders()
    {
        $userA = UserFactory::make()->persist();

        // Ada has access to folder A, B and C as a OWNER
        // Ada see folder folders B and C in A
        // A (Ada:O)
        // |- B (Ada:O)
        // |- C (Ada:O)
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->persist();
        [$folderB, $folderC] = FolderFactory::make(2)->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA], $folderA)->persist();

        $this->logInAs($userA);
        $this->getJson("/folders/{$folderA->get('id')}.json?contain[children_folders]=1&api-version=2");
        $this->assertSuccess();

        $result = $this->_responseJsonBody;
        $this->assertFolderAttributes($result);
        $this->assertCount(2, $result->children_folders);
        foreach ($result->children_folders as $childFolder) {
            $this->assertFolderAttributes($childFolder);
            $this->assertObjectHasFolderParentIdAttribute($childFolder, $folderA->get('id'));
        }
        $childrenFoldersIds = Hash::extract($result->children_folders, '{n}.id');
        $this->assertContains($folderB->id, $childrenFoldersIds);
        $this->assertContains($folderC->id, $childrenFoldersIds);
    }

    public function testFoldersViewSuccess_ContainChildrenResources()
    {
        $userA = UserFactory::make()->persist();

        // Ada has access to folder A as a OWNER
        // Ada has access to resource 1 and 2 as a OWNER
        // Ada see folder resources 1 and 2 in A
        // A (Ada:O)
        // |- 1 (Ada:O)
        // |- 2 (Ada:O)
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->persist();
        [$resource1, $resource2] = ResourceFactory::make(2)
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();

        $this->logInAs($userA);
        $this->getJson("/folders/{$folderA->get('id')}.json?contain[children_resources]=1&api-version=2");
        $this->assertSuccess();

        $result = $this->_responseJsonBody;
        $this->assertFolderAttributes($result);
        $this->assertCount(2, $result->children_resources);
        foreach ($result->children_resources as $childResource) {
            $this->assertFolderAttributes($childResource);
        }
        $childrenResourceIds = Hash::extract($result->children_resources, '{n}.id');
        $this->assertContains($resource1->get('id'), $childrenResourceIds);
        $this->assertContains($resource2->get('id'), $childrenResourceIds);
    }

    public function testFoldersViewError_NotValidIdParameter()
    {
        $this->logInAsUser();
        $resourceId = 'invalid-id';
        $this->getJson("/folders/$resourceId.json?api-version=2");
        $this->assertError(400, 'The folder id is not valid.');
    }

    public function testFoldersViewError_NotAuthenticated()
    {
        $folderId = FolderFactory::make()->persist()->get('id');
        $this->getJson("/folders/{$folderId}.json?api-version=2");
        $this->assertAuthenticationError();
    }

    public function testFoldersViewError_DoesNotExist()
    {
        $this->logInAsUser();
        $folderId = UuidFactory::uuid();
        $this->getJson("/folders/{$folderId}.json?api-version=2");
        $this->assertResponseError('The folder does not exist.');
    }

    public function testFoldersViewSuccess_ContainPermissionsGroup()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA, $userB])->persist();
        $folder = FolderFactory::make()->withPermissionsFor([$group, $userA])->withFoldersRelationsFor([$userA])->persist();

        $this->logInAs($userA);
        $this->getJson("/folders/{$folder->get('id')}.json?contain[permissions]=1&contain[permissions.group]=1&api-version=2");

        $this->assertSuccess();
        /** @var \Passbolt\Folders\Model\Entity\Folder[] $result */
        $folder = $this->_responseJsonBody;
        $this->assertFolderAttributes($folder);
        $this->assertObjectHasAttribute('permissions', $folder);

        foreach ($folder->permissions as $permission) {
            if ($permission->group !== null) {
                break; // we are only interested in the group permission but we do not create a permission ONLY for a group because this is not how the system behave
            }
        }
        $this->assertObjectHasAttribute('group', $permission);
        $this->assertGroupAttributes($permission->group);
    }

    public function testFoldersViewSuccess_ContainPermissionsUserProfile()
    {
        $userA = UserFactory::make()->persist();
        $folder = FolderFactory::make()->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA])->persist();
        $this->logInAs($userA);
        $this->getJson("/folders/{$folder->get('id')}.json?contain[permissions.user.profile]=1&api-version=2");

        $this->assertSuccess();
        /** @var \Passbolt\Folders\Model\Entity\Folder[] $result */
        $folder = $this->_responseJsonBody;
        $this->assertFolderAttributes($folder);
        $this->assertObjectHasAttribute('permissions', $folder);

        $permission = $folder->permissions[0];
        $user = $permission->user;
        $this->assertObjectHasAttribute('profile', $user);
        $this->assertProfileAttributes($user->profile);
    }
}
