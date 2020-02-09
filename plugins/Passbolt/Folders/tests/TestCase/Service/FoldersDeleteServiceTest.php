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
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Closure;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Service\FoldersDeleteService;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\FoldersDeleteService Test Case
 *
 * @uses \Passbolt\Folders\Service\FoldersDeleteService
 */
class FoldersDeleteServiceTest extends AppIntegrationTestCase
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
     * @var FoldersDeleteService
     */
    private $service;

    /**
     * @var FoldersTable
     */
    private $Folders;

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
        $config = TableRegistry::getTableLocator()->exists('Folders') ? [] : ['className' => FoldersTable::class];
        $this->Folders = TableRegistry::getTableLocator()->get('Folders', $config);
        $config = TableRegistry::getTableLocator()->exists('FoldersRelations') ? [] : ['className' => FoldersRelationsTable::class];
        $this->FoldersRelations = TableRegistry::getTableLocator()->get('FoldersRelations', $config);
        $config = TableRegistry::getTableLocator()->exists('Permissions') ? [] : ['className' => PermissionsTable::class];
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions', $config);
        $this->service = new FoldersDeleteService();
    }

    public function testSuccessCase1_DeleteFolder()
    {
        $folder = $this->insertFixtureCase1();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $this->service->delete($uac, $folder->id);
        $this->assertFolderNotExist($folder->id);
    }

    private function insertFixtureCase1()
    {
        // Ada has access to folder A as a OWNER
        // A (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);

        return $folderA;
    }

    public function testSuccessCase2_DeleteFolderAndContent()
    {
        list ($parentFolder, $folder) = $this->insertFixtureCase2();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $this->service->delete($uac, $parentFolder->id, true);
        $this->assertFolderNotExist($parentFolder->id);
        $this->assertFolderNotExist($folder->id);
        $this->assertPermissionNotExist($folder->id, $userId);
        $this->assertPermissionNotExist($parentFolder->id, $userId);
    }

    private function insertFixtureCase2()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // Folder B is in folder A
        // A (Ada:O)
        // |
        // B (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);

        return [$folderA, $folderB];
    }

    public function testSuccessCase3_DeleteFolderAndMoveChildrenToRoot()
    {
        list($parentFolder, $folder) = $this->insertFixtureCase2();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $this->service->delete($uac, $parentFolder->id, false);
        $this->assertFolderNotExist($parentFolder->id);
        $this->assertFolder($folder->id);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userId, null);
    }

    private function insertFixtureCase3()
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
        $eventNameToTest = FoldersDeleteService::FOLDERS_DELETE_FOLDER_EVENT;
        $eventWasDispatched = false;

        $callable = function (Event $event) use (&$eventWasDispatched) {
            $this->assertArrayHasKey('folder', $event->getData(), "Event should provide the `folder` entity as event data.");
            $this->assertArrayHasKey('uac', $event->getData(), "Event should provide the `uac` as event data.");
            $eventWasDispatched = true;
        };

        // We use the same instance of event manager that the service is using to test that dispatch is done.
        $this->service->getEventManager()->on($eventNameToTest, $callable);

        list($parentFolder, $folder) = $this->insertFixtureCase3();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $this->service->delete($uac, $folder->id);

        $this->assertTrue($eventWasDispatched, "Event `$eventNameToTest` was not dispatched after folder was deleted with success.");
    }

    public function testErrorCase1_InsufficientPermission()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userAId);
        $folder = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::READ]);

        $this->expectException(ForbiddenException::class);
        $this->service->delete($uac, $folder->id);
    }

    public function testErrorCase1_NoAccess()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);
        $folder = $this->addFolderFor(['name' => 'A'], [$userBId => Permission::READ]);

        $this->expectException(ForbiddenException::class);
        $this->service->delete($uac, $folder->id);
    }

    public function testSuccessCase4_DeleteFolderAndContent_ChildOfChild()
    {
        list($parentFolder, $folder, $childFolder) = $this->insertFixtureCase4();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $this->service->delete($uac, $parentFolder->id, true);
        $this->assertFolderNotExist($parentFolder->id);
        $this->assertFolderNotExist($folder->id);
        $this->assertFolderNotExist($childFolder->id);

        $this->assertPermissionNotExist($folder->id, $userId);
        $this->assertPermissionNotExist($parentFolder->id, $userId);
        $this->assertPermissionNotExist($childFolder->id, $userId);
    }

    private function insertFixtureCase4()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // Folder B is in folder A
        // A (Ada:O)
        // |
        // B (Ada:O)
        // |
        // C (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C', 'folder_parent_id' => $folderB->id], [$userId => Permission::OWNER]);

        return [$folderA, $folderB, $folderC];
    }

    public function testSuccessCase5_DeleteFolderAndContent_OnlyWhenEnoughPermissions()
    {
        list ($folderA, $folderB, $folderC) = $this->insertFixtureCase5();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);

        $this->service->delete($uac, $folderA->id, true);

        $this->assertFolderNotExist($folderA->id);
        $this->assertFolder($folderB->id);
        $this->assertFolder($folderC->id);

        // Check that the relation with the child of parent have been deleted
        $this->assertFolderRelationNotExist($folderB->id, $userId, $folderA->id);

        // Check that the relations with parents of parents have been deleted
        $this->assertNoRelationsExistFor($folderA->id, $userId);

        $this->assertPermissionNotExist($folderA->id, $userId);
        $this->assertPermission($folderB->id, $userId, Permission::READ);
        $this->assertPermission($folderC->id, $userId, Permission::OWNER);
    }

    private function insertFixtureCase5()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B in READ
        // Ada has access to folder C as a OWNER
        // Folder B is in folder A
        // A (Ada:O)
        // |
        // B (Ada:R, Betty:0)
        // |
        // C (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userAId => Permission::READ, $userBId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C', 'folder_parent_id' => $folderB->id], [$userAId => Permission::OWNER]);

        return [$folderA, $folderB, $folderC];
    }
}
