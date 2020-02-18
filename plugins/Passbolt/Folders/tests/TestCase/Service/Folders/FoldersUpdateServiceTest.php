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

namespace Passbolt\Folders\Test\TestCase\Service;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Model\Table\PermissionsTable;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\UsersFixture;
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
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Service\Folders\FoldersUpdateService;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\FoldersUpdateService Test Case
 *
 * @covers \Passbolt\Folders\Service\FoldersUpdateService
 */
class FoldersUpdateServiceTest extends FoldersTestCase
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
        GroupsFixture::class,
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

    /* ************************************************************** */
    /* COMMON & VALIDATION */
    /* ************************************************************** */

    public function testUpdateFolder_CommonError1_NotFound()
    {
        $notExistFolderId = UuidFactory::uuid();
        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);

        $this->expectException(NotFoundException::class);
        $this->service->update($uac, $notExistFolderId, ['name' => 'new name']);
    }

    public function testUpdateFolder_CommonError2_NoAccess()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);
        $folder = $this->addFolderFor(['name' => 'A'], [$userBId => Permission::READ]);

        $this->expectException(ForbiddenException::class);
        $this->service->update($uac, $folder->id, ['name' => 'new name']);
    }

    public function testUpdateFolder_CommonError3_ValidationError()
    {
        $folder = $this->insertCommonError3Fixture();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folderData = ['name' => ''];

        try {;
            $this->service->update($uac, $folder->id, $folderData);
        } catch (ValidationException $e) {
            $this->assertEquals("Could not validate the folder data.", $e->getMessage());
            $errors = ['name' => ['_empty' => 'The name cannot be empty.']];
            $this->assertEquals($errors, $e->getErrors());

            return;
        }
        $this->fail('Expect ValidationException');
    }

    private function insertCommonError3Fixture()
    {
        // Ada has access to folder A as a OWNER
        // ---
        // A (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folder = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);

        return $folder;
    }

    public function testUpdateFolder_CommonSuccess1_EmailSentAfterUpdate()
    {
        $this->markTestIncomplete();
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

    /* ************************************************************** */
    /* PERSONAL FOLDER */
    /* ************************************************************** */

    public function testUpdateFolder_PersoSuccess1_UpdateName()
    {
        $folder = $this->insertPersoSuccess1Fixture();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folder = $this->service->update($uac, $folder->id, ['name' => 'A updated']);
        $this->assertTrue($folder instanceof Folder);
        $this->assertEquals('A updated', $folder->name);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userId, null);
    }

    private function insertPersoSuccess1Fixture()
    {
        // Ada has access to folder A as a OWNER
        // ---
        // A (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folder = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);

        return $folder;
    }

    public function testUpdateFolder_PersoSuccess2_MoveFolderFromRootIntoPersonalFolder()
    {
        list($folderA, $folderB) = $this->insertPersoSuccess2Fixture();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folder = $this->service->update($uac, $folderB->id, ['folder_parent_id' => $folderA->id]);
        $this->assertTrue($folder instanceof Folder);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userId, $folderA->id);
        $this->assertObjectHasFolderParentIdAttribute($folder, $folderA->id);
    }

    private function insertPersoSuccess2Fixture()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // ---
        // A (Ada:O)
        // B (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userId => Permission::OWNER]);

        return [$folderA, $folderB];
    }

    public function UpdateFolder_PersoSuccess3_MoveFolderToRoot()
    {
        list($folderA, $folderB) = $this->insertPersoSuccess3Fixture();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folder = $this->service->update($uac, $folderB->id, ['folder_parent_id' => null]);
        $this->assertTrue($folder instanceof Folder);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userId, null);
    }

    private function insertPersoSuccess3Fixture()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // A (Ada:O)
        // |- B (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);

        return [$folderA, $folderB];
    }

    public function testUpdateFolder_PersoError1_CanNotMoveAParentIntoAChild()
    {
        list($folderA, $folderB) = $this->insertPersoError1Fixture();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        try {
            $this->service->update($uac, $folderA->id, ['folder_parent_id' => $folderB->id]);
        } catch (ValidationException $e) {
            $this->assertEquals("Could not validate the folder data.", $e->getMessage());
            $errors = ['folder_parent_id' => ['folder_cycle' => 'The destination folder cannot be a child.']];
            $this->assertEquals($errors, $e->getErrors());

            return;
        }
        $this->fail('Expect ValidationException');
    }

    private function insertPersoError1Fixture()
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

    /* ************************************************************** */
    /* SHARED FOLDER */
    /* ************************************************************** */

    public function testUpdateFolder_SharedError1_InsufficientPermissionDestinationFolder()
    {
        list($folder, $destinationFolder) = $this->insertSharedError1Fixture();
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

    public function insertSharedError1Fixture()
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

    public function testUpdateFolder_SharedError2_InsufficientPermission()
    {
        $folder = $this->insertSharedError2Fixture();
        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);

        $this->expectException(ForbiddenException::class);
        $this->service->update($uac, $folder->id, ['name' => 'new folder name']);
    }

    public function insertSharedError2Fixture()
    {
        // Ada has access to folder A as a READ
        // Betty has access to folder A as a OWNER
        // A (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folder = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);

        return $folder;
    }

    public function testUpdateFolder_SharedError3_InsufficientPermissionOnOriginalParentFolder()
    {
        list($folderA, $folderB) = $this->insertSharedError3Fixture();
        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);

        try {
            $this->service->update($uac, $folderB->id, ['folder_parent_id' => null]);
        } catch (ValidationException $e) {
            $this->assertEquals("Could not validate the folder data.", $e->getMessage());
            $errors = ['folder_parent_id' => ['has_folder_access' => 'You are not allowed to move content out of the parent folder.']];
            $this->assertEquals($errors, $e->getErrors());

            return;
        }
        $this->fail('Expect ValidationException');
    }

    public function insertSharedError3Fixture()
    {
        // Ada has access to folder A as a READ
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Folder B is in folder A
        // ---
        // A (Ada:R, Betty:O)
        // |- B (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$folderA, $folderB];
    }

    public function testUpdateFolder_SharedSuccess1_MoveSharedFolderFromRootIntoSharedFolder()
    {
        list($folderA, $folderB) = $this->insertSharedSuccess1Fixture();

        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);
        $folder = $this->service->update($uac, $folderB->id, ['folder_parent_id' => $folderA->id]);
        $this->assertTrue($folder instanceof Folder);
        $this->assertObjectHasFolderParentIdAttribute($folder, $folderA->id);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
    }

    private function insertSharedSuccess1Fixture()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // ---
        // A (Ada:O, Betty:Owner)
        // B (Ada:O, Betty:Owner)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$folderA, $folderB];
    }

    public function testUpdateFolder_SharedSuccess2_MoveSharedFolderFromSharedFolderToSharedFolder_MoveToRootForSomeUsers()
    {
        list($folderA, $folderB, $folderC) = $this->insertSharedSuccess2Fixture();

        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);
        $folder = $this->service->update($uac, $folderB->id, ['folder_parent_id' => $folderC->id]);
        $this->assertTrue($folder instanceof Folder);
        $this->assertObjectHasFolderParentIdAttribute($folder, $folderC->id);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderC->id);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
    }

    private function insertSharedSuccess2Fixture()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Ada is OWNER of folder C
        // ---
        // C (Ada:O)
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'B'], [$userAId => Permission::OWNER]);

        return [$folderA, $folderB, $folderC];
    }

    public function testUpdateFolder_SharedSuccess3_MoveSharedFolderFromSharedFolderToSharedFolder_KeepInOriginalParentFolderForSomeUsers()
    {
        list($folderA, $folderB, $folderC, $folderD) = $this->insertSharedSuccess3Fixture();

        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);
        $folder = $this->service->update($uac, $folderB->id, ['folder_parent_id' => $folderD->id]);
        $this->assertTrue($folder instanceof Folder);
        $this->assertObjectHasFolderParentIdAttribute($folder, $folderD->id);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderD->id);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderC->id);
    }

    private function insertSharedSuccess3Fixture()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Betty is OWNER of folder C
        // Ada is OWNER of folder D
        // Ada sees folder B in folder A
        // Betty sees folder B in folder C
        // ---
        // A (Ada:O)
        // |- B (Ada:O, Betty:O)
        //
        // C (Betty:O)
        // |- B (Ada:O, Betty:O)
        //
        // D (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C'], [$userBId => Permission::OWNER]);
        $folderB = $this->addFolder(['name' => 'B']);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => $folderC->id]);
        $folderD = $this->addFolderFor(['name' => 'D'], [$userAId => Permission::OWNER]);

        return [$folderA, $folderB, $folderC, $folderD];
    }

    public function testUpdateFolder_SharedSuccess4_MoveSharedFolderFromRootToSharedFolder_MoveToDestinationFolderForUsersHavingItOrganizedInAnotherFolder()
    {
        list($folderA, $folderB, $folderC) = $this->insertSharedSuccess4Fixture();

        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);
        $folder = $this->service->update($uac, $folderB->id, ['folder_parent_id' => $folderC->id]);
        $this->assertTrue($folder instanceof Folder);
        $this->assertObjectHasFolderParentIdAttribute($folder, $folderC->id);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderC->id);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderC->id);
    }

    private function insertSharedSuccess4Fixture()
    {
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Folder B is in Folder A
        // Ada is OWNER of folder C
        // Betty is OWNER of folder C
        // ---
        // A (Betty:O)
        // |- B (Ada:O, Betty:O)
        //
        // C (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userBId => Permission::OWNER]);
        $folderB = $this->addFolder(['name' => 'B']);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => null]);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => $folderA->id]);
        $folderC = $this->addFolderFor(['name' => 'C'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$folderA, $folderB, $folderC];
    }


    public function testUpdateFolder_SharedSuccess5_MoveSharedFolderFromSharedFolderToRoot_KeepFolderOrganizedForSomeUsers()
    {
        list($folderA, $folderB, $folderC) = $this->insertSharedSuccess5Fixture();

        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);
        $folder = $this->service->update($uac, $folderC->id, ['folder_parent_id' => null]);
        $this->assertTrue($folder instanceof Folder);
        $this->assertObjectHasFolderParentIdAttribute($folder, null);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderB->id);
    }

    private function insertSharedSuccess5Fixture()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder B
        // Ada is OWNER of folder C
        // Betty is OWNER of folder C
        // Ada sees C at in A
        // Betty sees C in B
        // ---
        // A (Ada:O)
        // |- C (Ada:O, Betty:O)
        //
        // B (Betty:O)
        // |- C (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userBId => Permission::OWNER]);
        $folderC = $this->addFolder(['name' => 'C']);
        $this->addPermission('Folder', $folderC->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => $folderB->id]);

        return [$folderA, $folderB, $folderC];
    }
}
