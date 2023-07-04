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
use App\Model\Table\GroupsTable;
use App\Model\Table\GroupsUsersTable;
use App\Model\Table\PermissionsTable;
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
use App\Test\Lib\Model\ProfilesModelTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Controller\Folders\FoldersViewController Test Case
 *
 * @uses \Passbolt\Folders\Controller\Folders\FoldersViewController
 */
class FoldersViewControllerTest extends FoldersIntegrationTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use GroupsModelTrait;
    use GroupsUsersModelTrait;
    use ProfilesModelTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        GpgkeysFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        RolesFixture::class,
        UsersFixture::class,
        SecretsFixture::class,
        ResourcesFixture::class,
        ResourceTypesFixture::class,
        GroupsFixture::class,
    ];

    public $Groups;
    public $GroupsUsers;
    public $Permissions;
    public $FoldersRelations;
    public $Folders;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        Configure::write('passbolt.plugins.folders', ['enabled' => true]);
        $config = TableRegistry::getTableLocator()->exists('Folders') ? [] : ['className' => FoldersTable::class];
        $this->Folders = TableRegistry::getTableLocator()->get('Folders', $config);
        $config = TableRegistry::getTableLocator()->exists('FoldersRelations') ? [] : ['className' => FoldersRelationsTable::class];
        $this->FoldersRelations = TableRegistry::getTableLocator()->get('FoldersRelations', $config);
        $config = TableRegistry::getTableLocator()->exists('Permissions') ? [] : ['className' => PermissionsTable::class];
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions', $config);
        $config = TableRegistry::getTableLocator()->exists('GroupsUsers') ? [] : ['className' => GroupsUsersTable::class];
        $this->GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers', $config);
        $config = TableRegistry::getTableLocator()->exists('Groups') ? [] : ['className' => GroupsTable::class];
        $this->Groups = TableRegistry::getTableLocator()->get('Groups', $config);
    }

    public function testFoldersViewSuccess_ContainChildrenFolders()
    {
        $userId = UuidFactory::uuid('user.id.ada');

        // Insert fixtures.
        // Ada has access to folder A, B and C as a OWNER
        // Ada see folder folders B and C in A
        // A (Ada:O)
        // |- B (Ada:O)
        // |- C (Ada:O)
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);

        $this->authenticateAs('ada');
        $this->getJson("/folders/{$folderA->id}.json?contain[children_folders]=1&api-version=2");
        $this->assertSuccess();

        $result = $this->_responseJsonBody;
        $this->assertFolderAttributes($result);
        $this->assertCount(2, $result->children_folders);
        foreach ($result->children_folders as $childFolder) {
            $this->assertFolderAttributes($childFolder);
            $this->assertObjectHasFolderParentIdAttribute($childFolder, $folderA->id);
        }
        $childrenFoldersIds = Hash::extract($result->children_folders, '{n}.id');
        $this->assertContains($folderB->id, $childrenFoldersIds);
        $this->assertContains($folderC->id, $childrenFoldersIds);
    }

    public function testFoldersViewSuccess_ContainChildrenResources()
    {
        $userId = UuidFactory::uuid('user.id.ada');

        // Insert fixtures.
        // Ada has access to folder A, B and C as a OWNER
        // Ada see folder folders B and C in A
        // A (Ada:O)
        // |- B (Ada:O)
        // |- C (Ada:O)
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $resource1 = $this->addResourceFor(['name' => 'R1', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);
        $resource2 = $this->addResourceFor(['name' => 'R2', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);

        $this->authenticateAs('ada');
        $this->getJson("/folders/{$folderA->id}.json?contain[children_resources]=1&api-version=2");
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
        $this->authenticateAs('ada');
        $resourceId = 'invalid-id';
        $this->getJson("/folders/$resourceId.json?api-version=2");
        $this->assertError(400, 'The folder id is not valid.');
    }

    public function testFoldersViewError_NotAuthenticated()
    {
        $folderId = UuidFactory::uuid('folder.id.folder');
        $this->getJson("/folders/{$folderId}.json?api-version=2");
        $this->assertAuthenticationError();
    }

    public function testFoldersViewSuccess_ContainPermissionsGroup()
    {
        $folder = $this->insertContainPermissionsGroupFixture();

        $this->authenticateAs('ada');
        $this->getJson("/folders/{$folder->id}.json?contain[permissions]=1&contain[permissions.group]=1&api-version=2");

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
        $folder = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $this->addPermission('Folder', $folder->id, 'Group', $group->id, Permission::OWNER);

        return $folder;
    }

    public function testFoldersViewSuccess_ContainPermissionsUserProfile()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $folder = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $this->authenticateAs('ada');
        $this->getJson("/folders/{$folder->id}.json?contain[permissions.user.profile]=1&api-version=2");

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
