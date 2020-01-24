<?php

namespace Passbolt\Folders\Test\TestCase\Service;

use App\Error\Exception\CustomValidationException;
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
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Service\FoldersUpdateService;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\FoldersUpdateService Test Case
 *
 * @covers \Passbolt\Folders\Service\FoldersUpdateService
 */
class FoldersUpdateServiceTest extends AppIntegrationTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use IntegrationTestTrait;
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
     * @var FoldersUpdateService
     */
    private $service;

    /**
     * @var PermissionsTable
     */
    private $Permissions;

    /**
     * @var FoldersRelationsTable
     */
    private $FoldersRelations;

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

    public function testSuccessCase1_UpdateName()
    {
        $folder = null;
        $this->insertFixtureCase1($folder);

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folder = $this->service->update($uac, $folder->id, ['name' => 'A updated']);
        $this->assertTrue($folder instanceof Folder);
        $this->assertEquals('A updated', $folder->name);
        $this->assertFolderRelation($folder->id, $userId, null);
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

    public function testSuccessCase4_UserCanOrganizeTheirItems()
    {
        $parentFolder = null;
        $folder = null;
        $this->insertFixtureCase4($parentFolder, $folder);

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folder = $this->service->update($uac, $folder->id, ['folder_parent_id' => $parentFolder->id]);
        $this->assertTrue($folder instanceof Folder);
        $this->assertFolderRelation($folder->id, $userId, $parentFolder->id);
    }

    private function insertFixtureCase4(&$folderA, &$folderB)
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
        $folderRelationData = ['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userId];
        $this->addFolderRelation($folderRelationData);
    }

    public function testErrorCase7_CanNotMoveAParentIntoAChild()
    {
        $this->expectException(CustomValidationException::class);
//        $this->expectExceptionMessage(__('You are not allowed to create content into the parent folder.'));

        $parentFolder = null;
        $folder = null;
        $this->insertFixtureCase7($parentFolder, $folder);

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folder = $this->service->update($uac, $parentFolder->id, ['folder_parent_id' => $folder->id]);
    }

    private function insertFixtureCase7(&$folderA, &$folderB)
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

    /**
     * @return void
     */
    public function testThatFoldersUpdateDispatchEventToSendEmailAfterFolderIsCreated()
    {
        $eventNameToTest = FoldersUpdateService::FOLDERS_UPDATE_FOLDER_EVENT;
        $eventWasDispatched = false;

        $callable = function (Event $event) use (&$eventWasDispatched) {
            $this->assertArrayHasKey('folder', $event->getData(), "Event should provide the `folder` entity as event data.");
            $this->assertArrayHasKey('uac', $event->getData(), "Event should provide the `uac` as event data.");
            $eventWasDispatched = true;
        };

        // We use the same instance of event manager that the service is using to test that dispatch is done.
        $this->service->getEventManager()->on($eventNameToTest, $callable);

        $parentFolder = null;
        $folder = null;
        $this->insertFixtureCase4($parentFolder, $folder);

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folder = $this->service->update($uac, $folder->id, ['folderparent_id' => $parentFolder->id]);

        $this->assertTrue($eventWasDispatched, "Event `$eventNameToTest` was not dispatched after folder was updated with success.");
    }
}
