<?php

namespace Passbolt\Folders\Test\TestCase\Service;

use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Model\Table\PermissionsTable;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Service\FoldersUpdateService;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;
use Ramsey\Uuid\Uuid;

/**
 * Passbolt\Folders\Service\FoldersUpdateService Test Case
 *
 * @uses \Passbolt\Folders\Service\FoldersUpdateService
 */
class FoldersUpdateServiceTest extends AppIntegrationTestCase
{
    use IntegrationTestTrait;
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use PermissionsModelTrait;

    public $fixtures = [
        FoldersFixture::class,
        FoldersRelationsFixture::class,
        GpgkeysFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        UsersFixture::class,
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
        $this->service = new FoldersUpdateService();
    }

    public function testSuccessCase1_AddFolderParentId()
    {
        $parentFolder = null;
        $folder = null;
        $this->insertFixtureCase1($parentFolder, $folder);

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folder = $this->service->update($uac, $folder->id, ['folder_parent_id' => $parentFolder->id]);
        $this->assertTrue($folder instanceof Folder);
        $this->assertFolderRelation($folder->id, $userId, $parentFolder->id);
    }

    private function insertFixtureCase1(&$folderA, &$folderB)
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // A (Ada:O)   B (Ada:O)
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
}
