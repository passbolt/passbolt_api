<?php

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

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
class ResourcesIndexControllerTest extends AppIntegrationTestCase
{
    use FoldersRelationsModelTrait;
    use FoldersModelTrait;
    use PermissionsModelTrait;

    private $Permissions;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Avatars', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources',
        'app.Base/Secrets', 'app.Base/Favorites', 'app.Base/Permissions', 'app.Base/Avatars', 'plugin.Passbolt/Folders.Folders',
        'plugin.Passbolt/Folders.FoldersRelations',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        Configure::write('passbolt.plugins.folders', ['enabled' => true]);
        $config = TableRegistry::getTableLocator()->exists('FoldersRelations') ? [] : ['className' => FoldersRelationsTable::class];
        $this->FoldersRelations = TableRegistry::getTableLocator()->get('FoldersRelations', $config);
        $config = TableRegistry::getTableLocator()->exists('Permissions') ? [] : ['className' => PermissionsTable::class];
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions', $config);
    }

    public function insertFixtureCase3()
    {
        // Relations are expressed as follow: folder_parent_id => [child_folder_id]
        $folderRelations = [
            UuidFactory::uuid('folder.id.a') => [
                UuidFactory::uuid('resource.id.a')
            ],
            UuidFactory::uuid('folder.id.b') => [
                UuidFactory::uuid('resource.id.b'),
            ],
        ];

        foreach ($folderRelations as $folderParentId => $childrenFolders) {
            $this->addFolderAndItsChildren($folderParentId, $childrenFolders, UuidFactory::uuid('user.id.ada'));
        }
    }

    /**
     * @param string $folderParentId Folder parent id
     * @param array $childrenFolders Children folders
     * @param string $userId user id
     */
    private function addFolderAndItsChildren(string $folderParentId, array $childrenFolders, string $userId)
    {
        $parentFolder = $this->addFolderFor(['id' => $folderParentId], [$userId => Permission::OWNER]);
        foreach ($childrenFolders as $childrenFolderId) {
            $this->addResourceForUsers(['id' => $childrenFolderId, 'folder_parent_id' => $parentFolder->id], [$userId => Permission::OWNER]);
        }
    }

    /**
     * @return void
     */
    public function testFoldersIndexFilterHasParentSuccess()
    {
        $this->insertFixtureCase3();

        $this->authenticateAs('ada');

        // Relations are expressed as follow: folder_parent_id => [child_folder_id]
        $expectedFolderRelations = [
            UuidFactory::uuid('folder.id.a') => [
                UuidFactory::uuid('resource.id.a')
            ],
            UuidFactory::uuid('folder.id.b') => [
                UuidFactory::uuid('resource.id.b'),
            ],
        ];

        foreach ($expectedFolderRelations as $folderParentId => $expectedFolderChildrenIds) {
            $this->getJson('/resources.json?api-version=2&filter[has-parent][]=' . $folderParentId);
            $this->assertSuccess();

            $resultFolderIds = Hash::extract($this->_responseJsonBody, '{n}.id');
            foreach ($expectedFolderChildrenIds as $expectedFolderChildrenId) {
                $this->assertContains($expectedFolderChildrenId, $resultFolderIds, "Expected children is missing for the given parent folder.");
            }
        }
    }
}
