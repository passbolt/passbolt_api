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
 * @since         2.14.0
 */

namespace Passbolt\Folders\Test\TestCase\Controller;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Test\Fixture\Alt0\SecretsFixture;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * @see \Passbolt\Folders\Controller\Folders\FoldersIndexController
 */
class FoldersIndexControllerTest extends AppIntegrationTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use PermissionsModelTrait;

    /** @var FoldersRelationsTable */
    private $FoldersRelations;

    /**
     * @var PermissionsTable
     */
    private $Permissions;

    public $fixtures = [
        FoldersFixture::class,
        FoldersRelationsFixture::class,
        GpgkeysFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        UsersFixture::class,
        ResourcesFixture::class,
        SecretsFixture::class,
    ];

    public function setUp()
    {
        parent::setUp();
        Configure::write('passbolt.plugins.folders', ['enabled' => true]);
        $config = TableRegistry::getTableLocator()->exists('FoldersRelations') ? [] : ['className' => FoldersRelationsTable::class];
        $this->FoldersRelations = TableRegistry::getTableLocator()->get('FoldersRelations', $config);
        $config = TableRegistry::getTableLocator()->exists('Permissions') ? [] : ['className' => PermissionsTable::class];
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions', $config);
    }

    private function insertFixtureCase1(&$folder)
    {
        // Ada has access to folder Lovelace and Something as a OWNER
        // Lovelace (Ada:O) ; Something (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderData = ['id' => UuidFactory::uuid(), 'name' => 'Lovelace', 'created_by' => $userId, 'modified_by' => $userId];
        $folder = $this->addFolder($folderData);
        $this->addPermission('Folder', $folder->id, 'User', $userId, Permission::OWNER);

        $folderData = ['id' => UuidFactory::uuid(), 'name' => 'Something', 'created_by' => $userId, 'modified_by' => $userId];
        $folder = $this->addFolder($folderData);
        $this->addPermission('Folder', $folder->id, 'User', $userId, Permission::OWNER);
    }

    /**
     * @return void
     */
    public function testFoldersIndexFilterBySearchSuccess()
    {
        $this->insertFixtureCase1($folder);

        $this->authenticateAs('ada');

        $this->getJson('/folders.json?api-version=2&filter[search]=Love');
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 1);
        $this->assertEquals($this->_responseJsonBody[0]->name, 'Lovelace');
        $this->assertNotContains('Something', $this->_responseJsonBody);

        $this->getJson('/folders.json?api-version=2&filter[search]=ovela');
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 1);
        $this->assertEquals($this->_responseJsonBody[0]->name, 'Lovelace');
        $this->assertNotContains('Something', $this->_responseJsonBody);

        $this->getJson('/folders.json?api-version=2&filter[search]=ace');
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 1);
        $this->assertEquals($this->_responseJsonBody[0]->name, 'Lovelace');
        $this->assertNotContains('Something', $this->_responseJsonBody);

        $this->getJson('/folders.json?api-version=2&filter[search]=Lovelace');
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 1);
        $this->assertEquals($this->_responseJsonBody[0]->name, 'Lovelace');
        $this->assertNotContains('Something', $this->_responseJsonBody);

        $this->assertSuccess();
    }

    private function insertFixtureCase2(&$folder)
    {
        // Ada has access to folder Lovelace and Something as a OWNER
        // Lovelace (Ada:O) ; Something (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');

        $folderId = UuidFactory::uuid('folder.id.specific');
        $folderData = ['id' => $folderId, 'name' => 'Specific', 'created_by' => $userId, 'modified_by' => $userId];
        $folder = $this->addFolder($folderData);
        $this->addPermission('Folder', $folder->id, 'User', $userId, Permission::OWNER);

        $folderId = UuidFactory::uuid('folder.id.specific2');
        $folderData = ['id' => $folderId, 'name' => 'Specific 2', 'created_by' => $userId, 'modified_by' => $userId];
        $folder = $this->addFolder($folderData);
        $this->addPermission('Folder', $folder->id, 'User', $userId, Permission::OWNER);

        $folderId = UuidFactory::uuid('folder.id.other');
        $folderData = ['id' => $folderId, 'name' => 'Other', 'created_by' => $userId, 'modified_by' => $userId];
        $folder = $this->addFolder($folderData);
        $this->addPermission('Folder', $folder->id, 'User', $userId, Permission::OWNER);
    }

    /**
     * @return void
     */
    public function testFoldersIndexFilterByIdSuccess()
    {
        $this->insertFixtureCase2($folder);

        $this->authenticateAs('ada');

        $folderId1 = UuidFactory::uuid('folder.id.specific');
        $folderId2 = UuidFactory::uuid('folder.id.specific2');
        $this->getJson('/folders.json?api-version=2&filter[has-id][]=' . $folderId1 . '&filter[has-id][]=' . $folderId2);
        $this->assertSuccess();

        $this->assertCount(2, $this->_responseJsonBody);
        $folderIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $this->assertContains($folderId1, $folderIds);
        $this->assertContains($folderId2, $folderIds);
        $this->assertNotContains(UuidFactory::uuid('folder.id.other'), $this->_responseJsonBody);

        $this->assertSuccess();
    }

    private function insertFixtureCase3()
    {
        // Relations are expressed as follow: folder_parent_id => [child_folder_id]
        $folderRelations = [
            UuidFactory::uuid('folder.id.a') => [
                UuidFactory::uuid('folder.id.b')
            ],
            UuidFactory::uuid('folder.id.c') => [
                UuidFactory::uuid('folder.id.e'),
            ],
        ];

        $userId = UuidFactory::uuid('user.id.ada');
        foreach ($folderRelations as $folderParentId => $childrenFolders) {
            $this->addFolderFor(['id' => $folderParentId,], [$userId => Permission::OWNER]);
            foreach ($childrenFolders as $childrenFolderId) {
                $this->addFolderFor(['id' => $childrenFolderId, 'folder_parent_id' => $folderParentId,], [$userId => Permission::OWNER]);
            }
        }
    }

    /**
     * @return void
     */
    public function testFoldersIndexFilterHasParentSuccess()
    {
        $this->insertFixtureCase3();
        $this->authenticateAs('ada');

        $expectedRelations = [
            UuidFactory::uuid('folder.id.a') => [
                UuidFactory::uuid('folder.id.b')
            ],
            UuidFactory::uuid('folder.id.c') => [
                UuidFactory::uuid('folder.id.e'),
            ],
        ];

        foreach ($expectedRelations as $folderParentId => $expectedFolderChildrenIds) {
            $this->getJson('/folders.json?api-version=2&filter[has-parent][]=' . $folderParentId);
            $this->assertSuccess();

            $resultFolderIds = Hash::extract($this->_responseJsonBody, '{n}.id');

            foreach ($this->_responseJsonBody as $folder) {
                $this->assertObjectHasFolderParentIdAttribute($folder, $folderParentId);
            }

            foreach ($expectedFolderChildrenIds as $expectedFolderChildrenId) {
                $this->assertContains($expectedFolderChildrenId, $resultFolderIds);
            }
        }
    }

    public function testSuccess_ContainChildrenResources()
    {
        $userId = UuidFactory::uuid('user.id.ada');

        // Insert fixtures.
        // Ada has access to folder A, R1 and R2 as a OWNER
        // Ada see resources R1 and R2 in folder A
        // A (Ada:O)
        // |- R1 (Ada:O)
        // |- R2 (Ada:O)
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $resource1 = $this->addResourceForUsers(['name' => 'R1', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);
        $resource2 = $this->addResourceForUsers(['name' => 'R2', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);

        $this->authenticateAs('ada');
        $this->getJson("/folders.json?contain[children_resources]=1&api-version=2");
        $this->assertSuccess();

        $result = $this->_responseJsonBody;
        $folder = $result[0];

        $this->assertFolderAttributes($folder);
        $this->assertAttributeNotEmpty('children_resources', $folder);
        $this->assertCount(2, $folder->children_resources);
        foreach($folder->children_resources as $childResource) {
            $this->assertResourceAttributes($childResource);
        }
        $childrenResourceIds = Hash::extract($folder->children_resources, '{n}.id');
        $this->assertContains($resource1->id, $childrenResourceIds);
        $this->assertContains($resource2->id, $childrenResourceIds);
    }

    public function testSuccess_ContainChildrenFolders()
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
        $this->getJson("/folders.json?contain[children_folders]=1&api-version=2");
        $this->assertSuccess();

        $result = $this->_responseJsonBody;
        $folder = $result[0];

        $this->assertFolderAttributes($folder);
        $this->assertAttributeNotEmpty('children_folders', $folder);
        $this->assertCount(2, $folder->children_folders);
        foreach($folder->children_folders as $childFolder) {
            $this->assertFolderAttributes($childFolder);
        }
        $childrenFolderIds = Hash::extract($folder->children_folders, '{n}.id');
        $this->assertContains($resource1->id, $childrenFolderIds);
        $this->assertContains($resource2->id, $childrenFolderIds);
    }
}
