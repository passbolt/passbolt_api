<?php
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
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
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
        FoldersFixture::class,
        FoldersRelationsFixture::class,
        GpgkeysFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        UsersFixture::class,
        SecretsFixture::class,
        ResourcesFixture::class,
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
            $this->assertObjectHasFolderParentIdAttribute($childFolder, $folderA->id);
        }
        $childrenFoldersIds = Hash::extract($result->children_folders, '{n}.id');
        $this->assertContains($folderB->id, $childrenFoldersIds);
        $this->assertContains($folderC->id, $childrenFoldersIds);
    }

    public function testSuccess_ContainChildrenResources()
    {
        $userId = UuidFactory::uuid('user.id.ada');

        // Insert fixtures.
        // Ada has access to folder A, B and C as a OWNER
        // Ada see folder folders B and C in A
        // A (Ada:O)
        // |- B (Ada:O)
        // |- C (Ada:O)
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $resource1 = $this->addResourceForUsers(['name' => 'R1', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);
        $resource2 = $this->addResourceForUsers(['name' => 'R2', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);

        $this->authenticateAs('ada');
        $this->getJson("/folders/{$folderA->id}.json?contain[children_resources]=1&api-version=2");
        $this->assertSuccess();

        $result = $this->_responseJsonBody;
        $this->assertFolderAttributes($result);
        $this->assertCount(2, $result->children_resources);
        foreach($result->children_resources as $childResource) {
            $this->assertFolderAttributes($childResource);
        }
        $childrenResourceIds = Hash::extract($result->children_resources, '{n}.id');
        $this->assertContains($resource1->id, $childrenResourceIds);
        $this->assertContains($resource2->id, $childrenResourceIds);
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
