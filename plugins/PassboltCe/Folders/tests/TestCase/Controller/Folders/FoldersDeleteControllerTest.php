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
use App\Model\Table\PermissionsTable;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Controller\Folders\FoldersDeleteController Test Case
 *
 * @uses \Passbolt\Folders\Controller\Folders\FoldersDeleteController
 */
class FoldersDeleteControllerTest extends FoldersIntegrationTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use PermissionsModelTrait;

    public $fixtures = [
    GpgkeysFixture::class,
        GroupsFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        RolesFixture::class,
        UsersFixture::class,
    ];

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
    }

    public function testFoldersDeleteFolder_PersoSuccess1_DeleteFolder()
    {
        $folder = $this->insertPersoSuccess1Fixture();

        $this->authenticateAs('ada');
        $this->deleteJson("/folders/{$folder->id}.json?api-version=2");
        $this->assertSuccess();
        $this->assertFolderNotExist($folder->id);
    }

    private function insertPersoSuccess1Fixture()
    {
        // Ada has access to folder A as a OWNER
        // A (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);

        return $folderA;
    }

    public function testFoldersDeleteFolder_PersoSuccess3_CascadeDelete()
    {
        [$folderA, $folderB] = $this->insertPersoSuccess3Fixture();

        $this->authenticateAs('ada');
        $this->deleteJson("/folders/{$folderA->id}.json?cascade=1&api-version=2");
        $this->assertSuccess();
        $this->assertFolderNotExist($folderA->id);
        $this->assertFolderNotExist($folderB->id);
    }

    private function insertPersoSuccess3Fixture()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // Folder B is in folder A
        // A (Ada:O)
        // |
        // B (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);

        return [$folderA, $folderB];
    }

    public function testFoldersDeleteFolder_PersoSuccess2_NoCascadeMoveChildrenToRoo()
    {
        [$folderA, $folderB] = $this->insertPersoSuccess2Fixture();

        $this->authenticateAs('ada');
        $this->deleteJson("/folders/{$folderA->id}.json?api-version=2");
        $this->assertSuccess();
        $this->assertFolderNotExist($folderA->id);
        $this->assertFolder($folderB->id);
    }

    private function insertPersoSuccess2Fixture()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // Folder B is in folder A
        // A (Ada:O)
        // |
        // B (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);

        return [$folderA, $folderB];
    }

    public function testFoldersDeleteFolder_Error_NotValidIdParameter()
    {
        $this->authenticateAs('ada');
        $resourceId = 'invalid-id';
        $this->deleteJson("/folders/$resourceId.json?api-version=2");
        $this->assertError(400, 'The folder id is not valid.');
    }

    public function testFoldersDeleteFolder_Error_IsProtectedByCsrfToken()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('ada');
        $folderId = UuidFactory::uuid('folder.id.folder');
        $this->delete("/folders/{$folderId}.json?api-version=2");
        $this->assertResponseCode(403);
    }

    public function testFoldersDeleteFolder_Error_NotAuthenticated()
    {
        $folderId = UuidFactory::uuid('folder.id.folder');
        $this->deleteJson("/folders/{$folderId}.json?api-version=2");
        $this->assertAuthenticationError();
    }
}
