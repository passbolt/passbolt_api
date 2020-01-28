<?php
namespace Passbolt\Folders\Test\TestCase\Controller;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Controller\Folders\FoldersViewController Test Case
 *
 * @uses \Passbolt\Folders\Controller\Folders\FoldersViewController
 */
class FoldersViewControllerTest extends AppIntegrationTestCase
{
    use IntegrationTestTrait;
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Base/GroupsUsers',
        'app.Base/Resources',
        'app.Base/Users',
        'app.Base/Permissions',
        'plugin.Passbolt/Folders.Folders',
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
        $config = TableRegistry::getTableLocator()->exists('Folders') ? [] : ['className' => FoldersTable::class];
        $this->Folders = TableRegistry::getTableLocator()->get('Folders', $config);
        $config = TableRegistry::getTableLocator()->exists('FoldersRelations') ? [] : ['className' => FoldersRelationsTable::class];
        $this->FoldersRelations = TableRegistry::getTableLocator()->get('FoldersRelations', $config);
        $config = TableRegistry::getTableLocator()->exists('Permissions') ? [] : ['className' => PermissionsTable::class];
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions', $config);
    }

    public function testSuccess_Contain()
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
        foreach($result->children_folders as $childFolder) {
            $this->assertFolderAttributes($childFolder);
        }
        $childrenFoldersIds = Hash::extract($result->children_folders, '{n}.id');
        $this->assertContains($folderB->id, $childrenFoldersIds);
        $this->assertContains($folderC->id, $childrenFoldersIds);
    }

    public function testError_NotValidIdParameter()
    {
        $this->authenticateAs('ada');
        $resourceId = 'invalid-id';
        $this->getJson("/folders/$resourceId.json?version=2");
        $this->assertError(400, 'The folder id is not valid.');
    }

    public function testError_NotAuthenticated()
    {
        $folderId = UuidFactory::uuid('folder.id.folder');
        $this->getJson("/folders/{$folderId}.json?api-version=2");
        $this->assertAuthenticationError();
    }
}
