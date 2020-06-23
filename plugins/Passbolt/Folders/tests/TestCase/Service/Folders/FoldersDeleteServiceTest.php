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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Test\TestCase\Service\Folders;

use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Model\Table\PermissionsTable;
use App\Model\Table\ResourcesTable;
use App\Notification\Email\EmailSubscriptionDispatcher;
use App\Test\Fixture\Alt0\SecretsFixture;
use App\Test\Fixture\Base\AvatarsFixture;
use App\Test\Fixture\Base\EmailQueueFixture;
use App\Test\Fixture\Base\FavoritesFixture;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Test\Lib\Utility\FixtureProviderTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Notification\Email\FoldersEmailRedactorPool;
use Passbolt\Folders\Notification\NotificationSettings\FolderNotificationSettingsDefinition;
use Passbolt\Folders\Service\Folders\FoldersDeleteService;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;
use Passbolt\Log\Test\Fixture\Base\ActionLogsFixture;
use Passbolt\Log\Test\Fixture\Base\ActionsFixture;

/**
 * Passbolt\Folders\Service\FoldersDeleteService Test Case
 *
 * @uses \Passbolt\Folders\Service\Folders\FoldersDeleteService
 */
class FoldersDeleteServiceTest extends FoldersTestCase
{
    use EmailNotificationSettingsTestTrait;
    use FixtureProviderTrait;
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use IntegrationTestTrait;
    use PermissionsModelTrait;
    use ResourcesModelTrait;

    public $fixtures = [
        ActionsFixture::class,
        ActionLogsFixture::class,
        AvatarsFixture::class,
        EmailQueueFixture::class,
        FoldersFixture::class,
        FoldersRelationsFixture::class,
        GpgkeysFixture::class,
        GroupsFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        UsersFixture::class,
        ResourcesFixture::class,
        RolesFixture::class,
        SecretsFixture::class,
        FavoritesFixture::class,
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
     * @var ResourcesTable
     */
    private $Resources;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Folders') ? [] : ['className' => FoldersTable::class];
        $this->Folders = TableRegistry::getTableLocator()->get('Folders', $config);
        $config = TableRegistry::getTableLocator()->exists('FoldersRelations') ? [] : ['className' => FoldersRelationsTable::class];
        $this->FoldersRelations = TableRegistry::getTableLocator()->get('FoldersRelations', $config);
        $config = TableRegistry::getTableLocator()->exists('Permissions') ? [] : ['className' => PermissionsTable::class];
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions', $config);
        $config = TableRegistry::getTableLocator()->exists('Resources') ? [] : ['className' => ResourcesTable::class];
        $this->Resources = TableRegistry::getTableLocator()->get('Resources', $config);

        $this->service = new FoldersDeleteService();

        $this->loadNotificationSettings();
        EventManager::instance()->on(new FolderNotificationSettingsDefinition());
        EventManager::instance()->on(new FoldersEmailRedactorPool());
        (new EmailSubscriptionDispatcher())->collectSubscribedEmailRedactors();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->unloadNotificationSettings();
    }

    /* ************************************************************** */
    /* COMMON & VALIDATION */
    /* ************************************************************** */

    public function testDeleteFolder_CommonError1_FolderNotExist()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->expectException(NotFoundException::class);
        $this->service->delete($uac, UuidFactory::uuid());
    }

    public function testDeleteFolder_CommonError2_NoAccess()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);
        $folder = $this->addFolderFor(['name' => 'A'], [$userBId => Permission::READ]);

        $this->expectException(ForbiddenException::class);
        $this->service->delete($uac, $folder->id);
    }

    public function testDeleteFolder_CommonSuccess2_NotifyUsersAfterDelete()
    {
        list($folderA, $folderB, $userAId, $userBId) = $this->insertSharedSuccess2Fixture();

        $uac = new UserAccessControl(Role::USER, $userAId);
        $this->service->delete($uac, $folderA->id);

        $this->get('/seleniumtests/showLastEmail/ada@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('deleted the folder');

        $this->get('/seleniumtests/showLastEmail/betty@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('deleted the folder');
    }

    /* ************************************************************** */
    /* PERSONAL FOLDER */
    /* ************************************************************** */

    private function insertPersoSuccess1Fixture()
    {
        // Ada has access to folder A as a OWNER
        // ---
        // A (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);

        return $folderA;
    }

    public function testDeleteFolder_PersoSuccess1_DeleteFolder()
    {
        $folder = $this->insertPersoSuccess1Fixture();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $this->service->delete($uac, $folder->id);
        $this->assertFolderNotExist($folder->id);
    }

    public function testDeleteFolder_PersoSuccess2_NoCascadeMoveChildrenToRoot()
    {
        list($parentFolder, $folder) = $this->insertPersoSuccess2Fixture();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $this->service->delete($uac, $parentFolder->id, false);
        $this->assertFolderNotExist($parentFolder->id);
        $this->assertFolder($folder->id);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userId, null);
    }

    private function insertPersoSuccess2Fixture()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of folder B
        // ---
        // A (Ada:O)
        // |
        // B (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);

        return [$folderA, $folderB];
    }

    public function testDeleteFolder_PersoSuccess3_CascadeDelete()
    {
        list ($folderA, $resource1, $folderB, $resource2) = $this->insertPersoSuccess3Fixture();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $this->service->delete($uac, $folderA->id, true);
        $this->assertFolderNotExist($folderA->id);
        $this->assertResourceIsSoftDeleted($resource1->id);
        $this->assertFolderNotExist($folderB->id);
        $this->assertResourceIsSoftDeleted($resource2->id);
    }

    private function insertPersoSuccess3Fixture()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of resource R1
        // Resource R1 is in folder A
        // Ada is OWNER of folder B
        // Folder B is in folder A
        // Ada is OWNER of resource R2
        // Resource R2 is in folder B
        // ---
        // A (Ada:O)
        // |- R1 (Ada:O)
        // |- B (Ada:O)
        //    |- R2 (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $resource1 = $this->addResourceFor(['name' => 'R1', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);
        $resource2 = $this->addResourceFor(['name' => 'R2', 'folder_parent_id' => $folderB->id], [$userId => Permission::OWNER]);

        return [$folderA, $resource1, $folderB, $resource2];
    }

    public function testDeleteFolder_PersoSuccess4_CascadeDeleteContentMoveToRootWhenInsufficientPermission()
    {
        list ($folderA, $resource1) = $this->insertPersoSuccess4Fixture();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);

        $this->service->delete($uac, $folderA->id, true);

        $this->assertFolderNotExist($folderA->id);
        $this->assertResourceIsNotSoftDeleted($resource1->id);
        $this->assertPermission($resource1->id, $userId, Permission::READ);
        $this->assertFolderRelation($resource1->id, 'Resource', $userId, null);
    }

    private function insertPersoSuccess4Fixture()
    {
        // Ada is OWNER of folder A
        // Ada has read ACCESS on resource R1
        // Betty is OWNER of resource R1
        // Resource R1 is in folder A
        // ---
        //  A (Ada:O)
        // |- R1 (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $resource1 = $this->addResourceFor(
            ['name' => 'R1', 'folder_parent_id' => $folderA->id],
            [$userBId => Permission::OWNER, $userAId => Permission::READ]
        );

        return [$folderA, $resource1];
    }

    /* ************************************************************** */
    /* SHARED FOLDER */
    /* ************************************************************** */

    public function testDeleteFolder_SharedError1_InsufficientPermission()
    {
        list ($folderA) = $this->insertSharedErrorFixture();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);

        $this->expectException(ForbiddenException::class);
        $this->service->delete($uac, $folderA->id, true);
    }

    private function insertSharedErrorFixture()
    {
        // Ada has access to folder A as a READ
        // Betty is OWNER of folder A
        // A (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);

        return [$folderA];
    }

    public function testDeleteFolder_SharedSuccess1_DeleteSharedFolder()
    {
        list ($folderA) = $this->insertSharedSuccess1Fixture();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);

        $this->service->delete($uac, $folderA->id, true);
        $this->assertFolderNotExist($folderA->id);
    }

    private function insertSharedSuccess1Fixture()
    {
        // Ada has access to folder A as a READ
        // Betty is OWNER of folder A
        // A (Ada:O, Betty:R)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userCId, 'is_admin' => true],
        ]]);
        $folderA = $this->addFolderFor(['name' => 'A'], [
            $userAId => Permission::OWNER,
            $userBId => Permission::READ,
            $g1->id => Permission::READ,
        ]);

        return [$folderA];
    }

    public function testDeleteFolder_SharedSuccess2_NoCascadeMoveChildrenToRoot()
    {
        list($folderA, $folderB) = $this->insertSharedSuccess2Fixture();

        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);
        $this->service->delete($uac, $folderA->id, false);
        $this->assertFolderNotExist($folderA->id);
        $this->assertFolder($folderB->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
    }

    private function insertSharedSuccess2Fixture()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Folder B is in A
        // ---
        // A (Ada:O, Betty:O)
        // |
        // B (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$folderA, $folderB, $userAId, $userBId];
    }

    public function testDeleteFolder_SharedSuccess3_CascadeDelete()
    {
        list ($folderA, $resource1, $folderB, $resource2) = $this->insertSharedSucces3Fixture();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $this->service->delete($uac, $folderA->id, true);
        $this->assertFolderNotExist($folderA->id);
        $this->assertResourceIsSoftDeleted($resource1->id);
        $this->assertFolderNotExist($folderB->id);
        $this->assertResourceIsSoftDeleted($resource2->id);
    }

    private function insertSharedSucces3Fixture()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // Resource R1 is in folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Folder B is in folder A
        // Ada is OWNER of resource R2
        // Betty is OWNER of resource R2
        // Resource R2 is in folder B
        // ---
        // A (Ada:O, Betty:O)
        // |- R1 (Ada:O, Betty:O)
        // |- B (Ada:O, Betty:O)
        //    |- R2 (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $resource1 = $this->addResourceFor(['name' => 'R1', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $resource2 = $this->addResourceFor(['name' => 'R2', 'folder_parent_id' => $folderB->id], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$folderA, $resource1, $folderB, $resource2];
    }

    public function testDeleteFolder_SharedSuccess4_CascadeDeleteContentMoveToRootWhenInsufficientPermission()
    {
        list ($folderA, $resource1, $folderB, $resource2, $folderC) = $this->insertSharedSuccess4Fixture();

        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);
        $this->service->delete($uac, $folderA->id, true);
        $this->assertFolderNotExist($folderA->id);
        $this->assertResourceIsNotSoftDeleted($resource1->id);
        $this->assertPermission($resource1->id, $userAId, Permission::READ);
        $this->assertPermission($resource1->id, $userBId, Permission::OWNER);
        $this->assertFolderRelation($resource1->id, 'Resource', $userAId, null);
        $this->assertFolderRelation($resource1->id, 'Resource', $userBId, null);
        $this->assertFolder($folderB->id);
        $this->assertPermission($folderB->id, $userAId, Permission::READ);
        $this->assertPermission($folderB->id, $userBId, Permission::OWNER);
        $this->assertFolderRelation($folderB->id, 'Folder', $userAId, null);
        $this->assertFolderRelation($folderB->id, 'Folder', $userBId, null);
        $this->assertResourceIsNotSoftDeleted($resource2->id);
        $this->assertPermission($resource2->id, $userBId, Permission::OWNER);
        $this->assertFolderRelation($resource2->id, 'Resource', $userBId, null);
        $this->assertFolder($folderC->id);
        $this->assertPermission($folderC->id, $userBId, Permission::OWNER);
        $this->assertFolderRelation($folderC->id, 'Folder', $userBId, null);
    }

    private function insertSharedSuccess4Fixture()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada has read ACCESS on resource R1
        // Betty is OWNER of resource R1
        // Resource R1 is in folder A
        // Ada has read ACCESS on folderB
        // Betty is OWNER of folder B
        // Folder B is in folder A
        // Betty is OWNER of folder C
        // Folder C is in folder A
        // Betty is OWNER of resource R2
        // Resource R2 is in folder A
        // ---
        // A (Ada:R, Betty:O)
        // |- R1 (Ada:R, Betty:O)
        // |- B (Ada:R, Betty:O)
        // |- R2 (Betty:O)
        // |- C (Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $resource1 = $this->addResourceFor(['name' => 'R1', 'folder_parent_id' => $folderA->id], [$userAId => Permission::READ, $userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userAId => Permission::READ, $userBId => Permission::OWNER]);
        $resource2 = $this->addResourceFor(['name' => 'R2', 'folder_parent_id' => $folderA->id], [$userBId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C', 'folder_parent_id' => $folderA->id], [$userBId => Permission::OWNER]);

        return [$folderA, $resource1, $folderB, $resource2, $folderC];
    }
}
