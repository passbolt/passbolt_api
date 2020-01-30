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
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Closure;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Service\FoldersDeleteService;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;
use Ramsey\Uuid\Uuid;

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
        $folder = null;
        $this->insertFixtureCase1($folder);

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $this->service->delete($uac, $folder->id);
        $this->assertFolderNotExist($folder->id);
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

    public function testSuccessCase2_DeleteFolderAndContent()
    {
        $parentFolder = null;
        $folder = null;
        $this->insertFixtureCase2($parentFolder, $folder);

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $this->service->delete($uac, $parentFolder->id, true);
        $this->assertFolderNotExist($parentFolder->id);
        $this->assertFolderNotExist($folder->id);
    }

    private function insertFixtureCase2(&$folderA, &$folderB)
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

    public function testSuccessCase3_DeleteFolderAndMoveChildrenToRoot()
    {
        $parentFolder = null;
        $folder = null;
        $this->insertFixtureCase2($parentFolder, $folder);

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $this->service->delete($uac, $parentFolder->id, false);
        $this->assertFolderNotExist($parentFolder->id);
        $this->assertFolder($folder->id);
        $this->assertFolderRelation($folder->id, $userId, null);
    }

    private function insertFixtureCase3(&$folderA, &$folderB)
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

    /**
     * @return void
     */
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

        $parentFolder = null;
        $folder = null;
        $this->insertFixtureCase3($parentFolder, $folder);

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $this->service->delete($uac, $folder->id);

        $this->assertTrue($eventWasDispatched, "Event `$eventNameToTest` was not dispatched after folder was deleted with success.");
    }

    /**
     * Test that a user got access denied if does not have at least UPDATE permission.
     * @dataProvider provideErrorCase4_UserIsNotAllowedToDeleteFolder
     */
    public function testErrorCase1_UserIsNotAllowedToDeleteFolder(Closure $fixture, UserAccessControl $notAllowedUac)
    {
        $folder = $this->executeFixture($fixture);

        $this->expectException(ForbiddenException::class);

        $this->service->delete($notAllowedUac, $folder->id);
    }

    public function provideErrorCase4_UserIsNotAllowedToDeleteFolder()
    {
        $fixture = function () {
            $folder = $this->addFolderFor(['name' => 'A'], [
                UuidFactory::uuid('user.id.ada') => Permission::OWNER,
                UuidFactory::uuid('user.id.betty') => Permission::READ,
            ]);

            $this->addFolderFor(['name' => 'B'], [
                UuidFactory::uuid('user.id.carol') => Permission::UPDATE,
            ]);

            return $folder;
        };

        return [
            'Betty has a READ permission defined on the folder' => [$fixture, $this->getUserAccessControl('betty')],
            'Carol has a UPDATE permission defined on another folder' => [$fixture, $this->getUserAccessControl('carol')],
            'Dame has no permission defined om the folder' => [$fixture, $this->getUserAccessControl('dame')],
        ];
    }

    /**
     * @param string $username
     * @return UserAccessControl
     */
    private function getUserAccessControl(string $username)
    {
        return new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.'. $username));
    }


    public function testSuccessCase4_DeleteFolderAndContent_ChildOfChild()
    {
        $parentFolder = null;
        $folder = null;
        $childFolder = null;
        $this->insertFixtureCase4($parentFolder, $folder, $childFolder);

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $this->service->delete($uac, $parentFolder->id, true);
        $this->assertFolderNotExist($parentFolder->id);
        $this->assertFolderNotExist($folder->id);
        $this->assertFolderNotExist($childFolder->id);
    }

    private function insertFixtureCase4(&$folderA, &$folderB, &$folderC)
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

        $folderCData = ['id' => UuidFactory::uuid(), 'name' => 'C', 'created_by' => $userId, 'modified_by' => $userId];
        $folderC = $this->addFolder($folderCData);
        $this->addPermission('Folder', $folderC->id, 'User', $userId, Permission::OWNER);
        $folderRelationData = ['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userId, 'folder_parent_id' => $folderB->id];
        $this->addFolderRelation($folderRelationData);
    }
}
