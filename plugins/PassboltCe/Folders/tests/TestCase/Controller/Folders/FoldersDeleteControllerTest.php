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

use App\Model\Table\PermissionsTable;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;

/**
 * Passbolt\Folders\Controller\Folders\FoldersDeleteController Test Case
 *
 * @uses \Passbolt\Folders\Controller\Folders\FoldersDeleteController
 */
class FoldersDeleteControllerTest extends FoldersIntegrationTestCase
{
    use FoldersModelTrait;

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
        // Ada has access to folder A as a OWNER
        // A (Ada:O)
        $userI = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withPermissionsFor([$userI])->persist();

        $this->logInAs($userI);
        $this->deleteJson("/folders/{$folderA->get('id')}.json?api-version=2");
        $this->assertSuccess();
        $this->assertFolderNotExist($folderA->get('id'));
    }

    public function testFoldersDeleteFolder_PersoSuccess3_CascadeDelete()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // Folder B is in folder A
        // A (Ada:O)
        // |
        // B (Ada:O)
        $userI = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withPermissionsFor([$userI])->persist();
        $folderB = FolderFactory::make()->withPermissionsFor([$userI])->withFoldersRelationsFor([$userI], $folderA)->persist();

        $this->logInAs($userI);
        $this->deleteJson("/folders/{$folderA->get('id')}.json?cascade=1&api-version=2");
        $this->assertSuccess();
        $this->assertFolderNotExist($folderA->get('id'));
        $this->assertFolderNotExist($folderB->get('id'));
    }

    public function testFoldersDeleteFolder_PersoSuccess2_NoCascadeMoveChildrenToRoo()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // Folder B is in folder A
        // A (Ada:O)
        // |
        // B (Ada:O)
        $userI = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withPermissionsFor([$userI])->persist();
        $folderB = FolderFactory::make()->withPermissionsFor([$userI])->withFoldersRelationsFor([$userI], $folderA)->persist();

        $this->logInAs($userI);
        $this->deleteJson("/folders/{$folderA->get('id')}.json?api-version=2");
        $this->assertSuccess();
        $this->assertFolderNotExist($folderA->get('id'));
        $this->assertFolder($folderB->get('id'));
    }

    public function testFoldersDeleteFolder_Error_NotValidIdParameter()
    {
        $this->logInAsUser();
        $resourceId = 'invalid-id';
        $this->deleteJson("/folders/$resourceId.json?api-version=2");
        $this->assertError(400, 'The folder id is not valid.');
    }

    public function testFoldersDeleteFolder_Error_IsProtectedByCsrfToken()
    {
        $this->disableCsrfToken();
        $this->logInAsUser();
        $folderId = FolderFactory::make()->persist()->get('id');
        $this->delete("/folders/{$folderId}.json?api-version=2");
        $this->assertResponseCode(403);
    }

    public function testFoldersDeleteFolder_Error_NotAuthenticated()
    {
        $folderId = FolderFactory::make()->persist()->get('id');
        $this->deleteJson("/folders/{$folderId}.json?api-version=2");
        $this->assertAuthenticationError();
    }

    public function testFoldersDeleteError_NotJson()
    {
        $this->logInAsUser();
        $folderId = UuidFactory::uuid();
        $this->delete("/folders/{$folderId}");
        $this->assertResponseCode(404);
    }
}
