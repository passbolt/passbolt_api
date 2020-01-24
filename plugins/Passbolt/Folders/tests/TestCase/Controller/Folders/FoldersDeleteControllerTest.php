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
use App\Test\Fixture\Base\AvatarsFixture;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Controller\Folders\FoldersDeleteController Test Case
 *
 * @uses \Passbolt\Folders\Controller\Folders\FoldersDeleteController
 */
class FoldersDeleteControllerTest extends AppIntegrationTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use IntegrationTestTrait;
    use PermissionsModelTrait;

    public $fixtures = [
        AvatarsFixture::class,
        FoldersFixture::class,
        FoldersRelationsFixture::class,
        GpgkeysFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        RolesFixture::class,
        UsersFixture::class
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

    public function testSuccessCase1_DeleteFolder()
    {
        $folder = null;
        $this->insertFixtureCase1($folder);

        $this->authenticateAs('ada');
        $this->deleteJson("/folders/{$folder->id}.json?api-version=2");
        $this->assertSuccess();
    }

    private function insertFixtureCase1(&$folder)
    {
        // Ada has access to folder A as a OWNER
        // A (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderData = ['id' => UuidFactory::uuid(), 'name' => 'A', 'created_by' => $userId, 'modified_by' => $userId];
        $folder = $this->addFolder($folderData);
        $this->addPermission('Folder', $folder->id, 'User', $userId, Permission::OWNER);
        $folderRelationData = ['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folder->id, 'user_id' => $userId];
        $this->addFolderRelation($folderRelationData);
    }

    public function testSuccessCase2_DeleteFolderAndContent()
    {
        $parentFolder = null;
        $folder = null;
        $this->insertFixtureCase2($parentFolder, $folder);

        $this->authenticateAs('ada');
        $this->deleteJson("/folders/{$parentFolder->id}.json?cascade=1&api-version=2");
        $this->assertSuccess();
    }

    private function insertFixtureCase2(&$folderA, &$folderB)
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // Folder B is in folder A
        // A (Ada:O)
        // |
        // B (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderAData = ['id' => UuidFactory::uuid(), 'name' => 'A', 'created_by' => $userId, 'modified_by' => $userId];
        $folderA = $this->addFolder($folderAData);
        $this->addPermission('Folder', $folderA->id, 'User', $userId, Permission::OWNER);
        $folderRelationData = ['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userId];
        $this->addFolderRelation($folderRelationData);

        $folderBData = ['id' => UuidFactory::uuid(), 'name' => 'B', 'created_by' => $userId, 'modified_by' => $userId];
        $folderB = $this->addFolder($folderBData);
        $this->addPermission('Folder', $folderB->id, 'User', $userId, Permission::OWNER);
        $folderRelationData = ['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userId, 'folder_parent_id' => $folderA->id];
        $this->addFolderRelation($folderRelationData);
    }

    public function testNotValidIdParameter()
    {
        $this->authenticateAs('ada');
        $resourceId = 'invalid-id';
        $this->deleteJson("/folders/$resourceId.json?version=2");
        $this->assertError(400, 'The folder id is not valid.');
    }

    public function testActionIsProtectedByCsrfTokenAndReturnErrorIfNotProvided()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('ada');
        $folderId = UuidFactory::uuid('folder.id.folder');
        $this->delete("/folders/{$folderId}.json?api-version=2");
        $this->assertResponseCode(403);
    }

    public function testResourcesAddErrorNotAuthenticated()
    {
        $folderId = UuidFactory::uuid('folder.id.folder');
        $this->deleteJson("/folders/{$folderId}.json?api-version=2");
        $this->assertAuthenticationError();
    }
}
