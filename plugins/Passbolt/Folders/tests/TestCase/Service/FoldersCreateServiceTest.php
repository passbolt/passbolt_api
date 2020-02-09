<?php

namespace Passbolt\Folders\Test\TestCase\Service;

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
        $parentFolder = $this->insertFixtureCase2();

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

    private function insertFixtureCase2()
    {
        // Ada has access to folder A as a OWNER
        // A (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);

        return $folderA;
    }

    public function testErrorCase3_ValidationError()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folderData = ['name' => ''];

        try {
            $this->service->create($uac, $folderData);
        } catch (ValidationException $e) {
            $this->assertEquals("Could not validate the folder data.", $e->getMessage());
            $errors = ['name' => ['_empty' => 'The name cannot be empty.']];
            $this->assertEquals($errors, $e->getErrors());

            return;
        }
        $this->fail('Expect ValidationException');
    }

    public function testErrorCase4_ParentFolderDoesNotExist()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folderData = [
            'name' => 'B',
            'folder_parent_id' => UuidFactory::uuid('folder.id.not-exist'),
        ];

        try {
            $this->service->create($uac, $folderData);
        } catch (ValidationException $e) {
            $this->assertEquals("Could not validate the folder data.", $e->getMessage());
            $errors = ['folder_parent_id' => ['folder_exists' => 'The folder parent must exist.']];
            $this->assertEquals($errors, $e->getErrors());

            return;
        }
        $this->fail('Expect ValidationException');
    }

    public function testErrorCase5_ParentFolderInsufficientPermission()
    {
        list ($parentFolder) = $this->insertFixtureCase5();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folderData = ['name' => 'B', 'folder_parent_id' => $parentFolder->id];

        try {
            $this->service->create($uac, $folderData);
        } catch (ValidationException $e) {
            $this->assertEquals("Could not validate the folder data.", $e->getMessage());
            $errors = ['folder_parent_id' => ['has_folder_access' => 'You are not allowed to create content into the parent folder.']];
            $this->assertEquals($errors, $e->getErrors());

            return;
        }
        $this->fail('Expect ValidationException');
    }

    private function insertFixtureCase5()
    {
        // Ada has access to folder A as a READ
        // Betty is OWNER of folder A
        // A (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);

        return [$folderA];
    }

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
