<?php

namespace Passbolt\Folders\Test\TestCase\Service;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
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
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Service\FoldersCreateService;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\FoldersCreateService Test Case
 *
 * @uses \Passbolt\Folders\Service\FoldersCreateService
 */
class FoldersCreateServiceTest extends AppIntegrationTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use IntegrationTestTrait;
    use PermissionsModelTrait;
    use EventDispatcherTrait;

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
     * @var FoldersCreateService
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
        /** @var FoldersCreateService service */
        $this->service = new FoldersCreateService();
    }

    public function testSuccessCase1_WithoutFolderParent()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folderData = ['name' => 'A'];
        $folder = $this->service->create($uac, $folderData);

        $this->assertTrue($folder instanceof Folder);
        $this->assertEquals('A', $folder->name);
        $this->assertEquals(null, $folder->folder_parent_id);
        $this->assertEquals($userId, $folder->created_by);
        $this->assertEquals($userId, $folder->modified_by);
        $this->assertPermission($folder->id, $userId, Permission::OWNER);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userId);
    }

    public function testSuccessCase2_WithParentFolder()
    {
        $parentFolder = null;
        $this->insertFixtureCase2($parentFolder);

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folderData = ['name' => 'B', 'folder_parent_id' => $parentFolder->id];
        $folder = $this->service->create($uac, $folderData);

        $this->assertTrue($folder instanceof Folder);
        $this->assertEquals('B', $folder->name);
        $this->assertEquals($parentFolder->id, $folder->folder_parent_id);
        $this->assertEquals($userId, $folder->created_by);
        $this->assertEquals($userId, $folder->modified_by);
        $this->assertPermission($folder->id, $userId, Permission::OWNER);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userId, $parentFolder->id);
    }

    private function insertFixtureCase2(&$parentFolder)
    {
        // Ada has access to folder A as a OWNER
        // A (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $parentFolderData = ['id' => UuidFactory::uuid(), 'name' => 'A', 'created_by' => $userId, 'modified_by' => $userId];
        $parentFolder = $this->addFolder($parentFolderData);
        $this->addPermission('Folder', $parentFolder->id, 'User', $userId, Permission::OWNER);
        $folderRelationData = ['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $parentFolder->id, 'user_id' => $userId];
        $this->addFolderRelation($folderRelationData);
    }

    public function testErrorCase3_ValidationError()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(__('Could not validate the folder data'));

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folderData = ['name' => ''];
        $this->service->create($uac, $folderData);
    }

    public function testErrorCase4_ParentFolderDoesNotExist()
    {
        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage(__('Could not validate the folder data'));

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folderData = [
            'name' => 'B',
            'folder_parent_id' => UuidFactory::uuid('folder.id.not-exist'),
        ];
        $this->service->create($uac, $folderData);
    }

    public function testErrorCase5_ParentFolderInsufficientPermission()
    {
        $parentFolder = null;
        $this->insertFixtureCase5($parentFolder);

        $this->expectException(ForbiddenException::class);
        $this->expectExceptionMessage(__('You are not allowed to create content into the parent folder.'));

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folderData = ['name' => 'B', 'folder_parent_id' => $parentFolder->id];
        $this->service->create($uac, $folderData);
    }

    private function insertFixtureCase5(&$parentFolder)
    {
        // Ada has access to folder A as a READ
        // A (Ada:R)
        $userId = UuidFactory::uuid('user.id.ada');
        $parentFolderData = ['id' => UuidFactory::uuid(), 'name' => 'A', 'created_by' => $userId, 'modified_by' => $userId];
        $parentFolder = $this->addFolder($parentFolderData);
        $this->addPermission('Folder', $parentFolder->id, 'User', $userId, Permission::READ);
        $folderRelationData = ['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $parentFolder->id, 'user_id' => $userId];
        $this->addFolderRelation($folderRelationData);
    }

    /**
     * @return void
     */
    public function testThatFoldersCreateDispatchEventToSendEmailAfterFolderIsCreated()
    {
        $eventNameToTest = FoldersCreateService::FOLDERS_CREATE_FOLDER_EVENT;
        $eventWasDispatched = false;

        $callable = function (Event $event) use (&$eventWasDispatched) {
            $this->assertArrayHasKey('folder', $event->getData(), "Event should provide the `folder` entity as event data.");
            $this->assertArrayHasKey('uac', $event->getData(), "Event should provide the `uac` as event data.");
            $eventWasDispatched = true;
        };

        // We use the same instance of event manager that the service is using to test that dispatch is done.
        $this->service->getEventManager()->on($eventNameToTest, $callable);

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folderData = ['name' => 'A'];

        $this->service->create($uac, $folderData);

        $this->assertTrue($eventWasDispatched, "Event `$eventNameToTest` was not dispatched after folder was created with success.");
    }
}
