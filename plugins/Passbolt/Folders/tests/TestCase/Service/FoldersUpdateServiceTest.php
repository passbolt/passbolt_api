<?php

namespace Passbolt\Folders\Test\TestCase\Service;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Model\Entity\User;
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
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Closure;
use Exception;
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

    public function testSuccessCase8_UserCanMoveAFolderToRoot()
    {
        $parentFolder = null;
        $folder = null;
        $this->insertFixtureCaseX($parentFolder, $folder);

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folder = $this->service->update($uac, $folder->id, ['folder_parent_id' => null]);
        $this->assertTrue($folder instanceof Folder);
        $this->assertFolderRelation($folder->id, $userId, null);
    }

    private function insertFixtureCase8(&$folderA, &$folderB)
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

    /**
     * @param Closure $fixture
     * @param int $targetPermission
     * @param int $destinationPermission
     * @param UserAccessControl $uac
     * @throws Exception
     * @dataProvider provideErrorCase5_UserNotAllowedToOrganize
     */
    public function testErrorCase5_UserIsNotAllowedToOrganize(Closure $fixture, int $targetPermission, int $destinationPermission, UserAccessControl $uac)
    {
        // Retrieve in variables what is returned from the fixture.
        list($folder, $newParentFolder) = $this->executeFixture($fixture, $targetPermission, $destinationPermission);

        $this->expectException(ForbiddenException::class);

        $this->service->update($uac, $folder->id, ['folder_parent_id' => $newParentFolder->id]);
    }

    public function provideErrorCase5_UserNotAllowedToOrganize()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);

        /**
         * This fixture create a folder with a parent folder.
         *
         * (T)arget is the folder being moved.
         * (P)arent is the parent folder of (T)arget folder.
         * (D)estination is the new parent folder.
         *
         * User has the same permission for P and T.
         *
         * @param int $targetPermission
         * @param int $destinationPermission
         * @return array
         */
        $folderWithParentFolderFixture = function (int $targetPermission, int $destinationPermission) use ($userId) {
            $parentFolder = $this->addFolderFor(['id' => UuidFactory::uuid(), 'name' => 'P'], [$userId => $targetPermission]);
            $newParentFolder = $this->addFolderFor(['id' => UuidFactory::uuid(), 'name' => 'D'], [$userId => $destinationPermission]);
            $folder = $this->addFolderFor(['name' => 'T', 'folder_parent_id' => $parentFolder->id], [$userId => $targetPermission]);

            return [$folder, $newParentFolder];
        };

        /**
         * This fixture create a folder with no parent folder (folder at the root).
         *
         * (T)arget is the folder being moved.
         * (D)estination is the new parent folder.
         *
         * User has the same permission for P and T.
         *
         * @param int $targetPermission
         * @param int $destinationPermission
         * @return array
         */
        $folderWithNoParentFolderFixture = function (int $targetPermission, int $destinationPermission) use ($userId) {
            $newParentFolder = $this->addFolderFor(['id' => UuidFactory::uuid(), 'name' => 'D'], [$userId => $destinationPermission]);
            $folder = $this->addFolderFor(['name' => 'T'], [$userId => $targetPermission]);

            return [$folder, $newParentFolder];
        };

        return [
            'User has READ permission on Target; READ permission on DESTINATION; TARGET is at the root;' => [
                $folderWithParentFolderFixture,
                Permission::READ, // Permission for target item
                Permission::READ, // Permission for destination item
                $uac
            ],
            'User has READ permission on Target; READ permission on DESTINATION; TARGET is not at the root;' => [
                $folderWithNoParentFolderFixture,
                Permission::READ, // Permission for target item
                Permission::READ, // Permission for destination item
                $uac
            ],
        ];
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
        // Retrieve in variables what is returned from the fixture.
        list($folder, $newParentFolder) = $this->executeFixture($fixture, $targetPermission, $destinationPermission);

        $folder = $this->service->update($uac, $folder->id, ['folder_parent_id' => $newParentFolder->id]);

        $this->assertFolderRelation($folder->id, $uac->userId(), $newParentFolder->id);
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
                $uac
            ],
            'User has UPDATE permission on TARGET; UPDATE permission on DESTINATION; TARGET is at the root;' => [
                $folderWithNoParentFolderFixture,
                Permission::UPDATE, // target item
                Permission::UPDATE, // destination item
                $uac
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

//
//    public function provideSharedCases()
//    {
//        $userId = UuidFactory::uuid('user.id.ada');
//        $uac = new UserAccessControl(Role::USER, $userId);
//
//        return [
//            'User has READ permission on TARGET; UPDATE permission on DESTINATION; TARGET is not at the root' => [
//                $folderWithNoParentFolderFixture,
//                Permission::READ, // Permission for target item
//                Permission::UPDATE, // Permission for destination item
//                $uac
//            ],
//            'User has no permission on TARGET; UPDATE permission on DESTINATION; TARGET is not at the root' => [
//                $folderWithNoParentFolderFixture,
//                0, // Permission for target item
//                Permission::UPDATE, // Permission for destination item
//                $uac
//            ],
//            'User has READ permission on TARGET; UPDATE permission on DESTINATION; TARGET is at the root;' => [
//                $folderWithParentFolderFixture,
//                Permission::READ, // Permission for target item
//                Permission::UPDATE, // Permission for destination item
//                $uac
//            ],
//            'User has no permission on TARGET; UPDATE permission on DESTINATION; TARGET is at the root;' => [
//                $folderWithParentFolderFixture,
//                0, // Permission for target item
//                Permission::UPDATE, // Permission for destination item
//                $uac
//            ],
//        ];
//    }

}
