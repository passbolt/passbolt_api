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
use App\Test\Lib\Utility\FixtureProviderTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Closure;
use Exception;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
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
    use FixtureProviderTrait;
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
        $folder = $this->insertFixtureCase1();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folder = $this->service->update($uac, $folder->id, ['name' => 'A updated']);
        $this->assertTrue($folder instanceof Folder);
        $this->assertEquals('A updated', $folder->name);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userId, null);
    }

    private function insertFixtureCase1()
    {
        // Ada has access to folder A as a OWNER
        // A (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folder = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);

        return $folder;
    }

    public function testSuccessCase4_UserCanOrganizeTheirItems()
    {
        list($folderA, $folderB) = $this->insertFixtureCase4();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folder = $this->service->update($uac, $folderB->id, ['folder_parent_id' => $folderA->id]);
        $this->assertTrue($folder instanceof Folder);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userId, $folderA->id);
        $this->assertObjectHasFolderParentIdAttribute($folder, $folderA->id);
    }

    private function insertFixtureCase4()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // A (Ada:O)   B (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userId => Permission::OWNER]);

        return [$folderA, $folderB];
    }

    public function testSuccessCase8_UserCanMoveAFolderToRoot()
    {
        list($folderA, $folderB) = $this->insertFixtureCase8();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folder = $this->service->update($uac, $folderB->id, ['folder_parent_id' => null]);
        $this->assertTrue($folder instanceof Folder);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userId, null);
    }

    private function insertFixtureCase8()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // A (Ada:O)
        // |
        // B (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);

        return [$folderA, $folderB];
    }

    public function testErrorCase7_CanNotMoveAParentIntoAChild()
    {
        list($folderA, $folderB) = $this->insertFixtureCase7();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        try {
            $this->service->update($uac, $folderA->id, ['folder_parent_id' => $folderB->id]);
        } catch (ValidationException $e) {
            $this->assertEquals("Could not validate the folder data.", $e->getMessage());
            $errors = ['folder_parent_id' => ['folder_exists' => 'The destination folder cannot be a child.']];
            $this->assertEquals($errors, $e->getErrors());

            return;
        }
        $this->fail('Expect ValidationException');
    }

    private function insertFixtureCase7()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // A (Ada:O)
        // |
        // B (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);

        return [$folderA, $folderB];
    }

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

        list($folderA, $folderB) = $this->insertFixtureCase4();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $this->service->update($uac, $folderB->id, ['folder_parent_id' => $folderA->id]);

        $this->assertTrue($eventWasDispatched, "Event `$eventNameToTest` was not dispatched after folder was updated with success.");
    }

    public function testErrorCase5_InsufficientPermissionDestinationFolder()
    {
        list($folder, $destinationFolder) = $this->insertFixtureCase5();
        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);

        try {
            $this->service->update($uac, $folder->id, ['folder_parent_id' => $destinationFolder->id]);
        } catch (ValidationException $e) {
            $this->assertEquals("Could not validate the folder data.", $e->getMessage());
            $errors = ['folder_parent_id' => ['has_folder_access' => 'You are not allowed to create content into the parent folder.']];
            $this->assertEquals($errors, $e->getErrors());

            return;
        }
        $this->fail('Expect ValidationException');
    }

    public function insertFixtureCase5()
    {
        // Ada has access to folder A as a READ
        // Betty has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // A (Ada:R, Betty:O)    B(Ada:Owner)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folder = $this->addFolderFor(['name' => 'B'], [$userAId => Permission::OWNER]);
        $destinationFolder = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);

        return [$folder, $destinationFolder];
    }

    /**
     * @param Closure $fixture
     * @param int $targetPermission
     * @param int $destinationPermission
     * @param UserAccessControl $uac
     * @throws Exception
     * @dataProvider provideSuccessCase6_UserCanHaveItsOwnOrganization
     */
    public function testSuccessCase6_UserCanHaveItsOwnOrganization(Closure $fixture, int $targetPermission, int $destinationPermission, UserAccessControl $uac)
    {
        list($folder, $newParentFolder) = $this->executeFixture($fixture, $targetPermission, $destinationPermission);

        $folder = $this->service->update($uac, $folder->id, ['folder_parent_id' => $newParentFolder->id]);

        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $uac->userId(), $newParentFolder->id);
        $this->assertObjectHasFolderParentIdAttribute($folder, $newParentFolder->id);
    }

    public function provideSuccessCase6_UserCanHaveItsOwnOrganization()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));

        $folderWithNoParentFolderFixture = function (int $targetPermission, int $destinationPermission) use ($userId) {
            $newParentFolder = $this->addFolderFor(
                ['id' => UuidFactory::uuid(), 'name' => 'New Parent Folder'],
                [$userId => $destinationPermission]
            );
            $folder = $this->addFolderFor(['name' => 'Folder'], [$userId => $targetPermission]);

            return [$folder, $newParentFolder];
        };

        return [
            'User has READ permission on TARGET; UPDATE permission on DESTINATION; TARGET is at the root;' => [
                $folderWithNoParentFolderFixture,
                Permission::READ, // target item
                Permission::UPDATE, // destination item
                $uac,
            ],
            'User has UPDATE permission on TARGET; UPDATE permission on DESTINATION; TARGET is at the root;' => [
                $folderWithNoParentFolderFixture,
                Permission::UPDATE, // target item
                Permission::UPDATE, // destination item
                $uac,
            ],
        ];
    }

    public function testErrorCase0_NotFoundError()
    {
        $notExistFolderId = UuidFactory::uuid();
        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);

        $this->expectException(NotFoundException::class);

        $this->service->update($uac, $notExistFolderId, ['name' => 'new name']);
    }
}
