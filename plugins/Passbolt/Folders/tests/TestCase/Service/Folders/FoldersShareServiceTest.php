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
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Service\Folders\FoldersShareService;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\Folders\FoldersShareService Test Case
 *
 * @covers \Passbolt\Folders\Service\Folders\FoldersShareService
 */
class FoldersShareServiceTest extends FoldersTestCase
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
     * @var FoldersShareService
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
        $this->service = new FoldersShareService();
    }

    /* ************************************************************** */
    /* COMMON & VALIDATION */
    /* ************************************************************** */

    public function testShareFolder_CommonError1_NotFound()
    {
        $notExistFolderId = UuidFactory::uuid();
        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);

        $this->expectException(NotFoundException::class);
        $this->service->share($uac, $notExistFolderId);
    }

    public function testShareFolder_CommonError2_NoPermission()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);
        $folder = $this->addFolderFor(['name' => 'A'], [$userBId => Permission::READ]);

        $this->expectException(ForbiddenException::class);
        $this->service->share($uac, $folder->id);
    }

    public function testShareFolder_CommonError3_ValidationError()
    {
        $this->markTestIncomplete();
    }

    public function testShareFolder_CommonSuccess1_EmailSentAfterShare()
    {
        $this->markTestIncomplete();
    }

    /* ************************************************************** */
    /* SHARED FOLDER HAVING NO PARENT WITH USERS */
    /* ************************************************************** */

    public function testShareFolder_UsersSharedNoParentError1_InsufficientPermission()
    {
        $folder = $this->insertUsersSharedNoParentError1Fixture();
        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);

        $this->expectException(ForbiddenException::class);
        $this->service->share($uac, $folder->id);
    }

    public function insertUsersSharedNoParentError1Fixture()
    {
        // Ada has access to folder A as a READ
        // Betty has access to folder A as a OWNER
        // A (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folder = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::UPDATE, $userBId => Permission::OWNER]);

        return $folder;
    }

    public function testShareFolder_UsersSharedNoParentSuccess1_ShareFolder()
    {
        $folder = $this->insertUsersSharedNoParentSuccess1Fixture();
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folder->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::READ];
        $folder = $this->service->share($uac, $folder->id, $data);

        $this->assertTrue($folder instanceof Folder);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId);
        $this->assertPermission($folder->id, $userAId, Permission::OWNER);
        $this->assertPermission($folder->id, $userBId, Permission::READ);
        $this->assertItemIsInTrees($folder->id, 2);
    }

    public function insertUsersSharedNoParentSuccess1Fixture()
    {
        // Ada is OWNER of folder A
        // A (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $folder = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);

        return $folder;
    }

    /* ************************************************************** */
    /* SHARED FOLDER HAVING A PARENT WITH USERS */
    /* ************************************************************** */

    public function testShareFolder_UsersSharedHavingOneParentSuccess1_ShareFolderHavingSharedParentInOperatorTree()
    {
        list($folderA, $folderB) = $this->insertUsersSharedHavingOneParentSuccess1Fixture();
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderB->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::OWNER];
        $folder = $this->service->share($uac, $folderB->id, $data);

        $this->assertTrue($folder instanceof Folder);
        // Folder A
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertPermission($folderA->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderA->id, $userBId, Permission::OWNER);
        $this->assertItemIsInTrees($folderA->id, 2);
        // Folder B
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        $this->assertPermission($folderB->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderB->id, $userBId, Permission::OWNER);
        $this->assertItemIsInTrees($folderB->id, 2);
    }

    public function insertUsersSharedHavingOneParentSuccess1Fixture()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Add sees B in A
        // ---
        // A (Ada:O, Betty:O)
        // |- B (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER]);

        return [$folderA, $folderB];
    }

    public function testShareFolder_UsersSharedHavingOneParentSuccess1_1_ShareFolderHavingSharedParentInOperatorTreeWithOperatorReadOnParent()
    {
        list($folderA, $folderB) = $this->insertUsersSharedHavingOneParentSuccess1_1Fixture();
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderB->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::OWNER];
        $folder = $this->service->share($uac, $folderB->id, $data);

        $this->assertTrue($folder instanceof Folder);
        // Folder A
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertPermission($folderA->id, $userAId, Permission::READ);
        $this->assertPermission($folderA->id, $userBId, Permission::OWNER);
        // Folder B
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        $this->assertPermission($folderB->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderB->id, $userBId, Permission::OWNER);
    }

    public function insertUsersSharedHavingOneParentSuccess1_1Fixture()
    {
        // Ada has READ on folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Add sees B in A
        // ---
        // A (Ada:O, Betty:O)
        // |- B (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER]);

        return [$folderA, $folderB];
    }

    public function testShareFolder_UsersSharedHavingOneParentSuccess2_ShareFolderHavingNotSharedParentInOperatorTree()
    {
        list($folderA, $folderB) = $this->insertUsersSharedHavingOneParentSuccess2Fixture();
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderB->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::OWNER];
        $folder = $this->service->share($uac, $folderB->id, $data);

        $this->assertTrue($folder instanceof Folder);
        // Folder A
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelationNotExist($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertPermission($folderA->id, $userAId, Permission::OWNER);
        $this->assertPermissionNotExist($folderA->id, $userBId);
        // Folder B
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertPermission($folderB->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderB->id, $userBId, Permission::OWNER);
    }

    public function insertUsersSharedHavingOneParentSuccess2Fixture()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of folder B
        // Add sees B in A
        // ---
        // A (Ada:O)
        // |- B (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER]);

        return [$folderA, $folderB];
    }

    public function testShareFolder_UsersSharedHavingOneParentSuccess3_ShareFolderAlreadyInSomeoneElseSharedFolder()
    {
        list($folderA, $folderB, $userAId, $userBId, $userCId) = $this->insertUsersSharedHavingOneParentSuccess3Fixture();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderB->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderB->id}-{$userCId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::OWNER];
        $folder = $this->service->share($uac, $folderB->id, $data);

        $this->assertTrue($folder instanceof Folder);

        // Folder A
        $this->assertFolderRelationNotExist($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        $this->assertPermissionNotExist($folderA->id, $userAId);
        $this->assertPermission($folderA->id, $userBId, Permission::OWNER);
        $this->assertPermission($folderA->id, $userCId, Permission::OWNER);
        // Folder B
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderA->id);
        $this->assertPermission($folderB->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderB->id, $userBId, Permission::OWNER);
        $this->assertPermission($folderB->id, $userCId, Permission::OWNER);
    }

    public function insertUsersSharedHavingOneParentSuccess3Fixture()
    {
        // Betty is OWNER of folder A
        // Carol is OWNER of folder A
        // Ada is OWNER of folder B
        // Carol is OWNER of folder B
        // Carol sees B in C
        // ---
        // A (Betty:O, Carol:O)
        // |- B (Ada:O, Carol:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userBId => Permission::OWNER, $userCId => Permission::OWNER]);
        $folderB = $this->addFolder(['name' => 'B']);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => null]);
        $this->addPermission('Folder', $folderB->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userCId, 'folder_parent_id' => $folderA->id]);

        return [$folderA, $folderB, $userAId, $userBId, $userCId];
    }

    /* ************************************************************** */
    /* SHARED FOLDER HAVING MULTIPLE PARENTS WITH USERS */
    /* ************************************************************** */

    public function testShareFolder_UsersSharedHavingMultipleParentsSuccess1_HavingMultipleParentsWithAnOperatorRepresentation()
    {
        list($folderA, $folderB, $folderC, $userAId, $userBId, $userCId) = $this->insertUsersSharedHavingMultipleParentsSuccess1Fixture();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderB->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderB->id}-{$userCId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::OWNER];
        $folder = $this->service->share($uac, $folderB->id, $data);

        $this->assertTrue($folder instanceof Folder);
        // Folder A
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelationNotExist($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        $this->assertPermission($folderA->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderA->id, $userBId, Permission::OWNER);
        $this->assertPermissionNotExist($folderA->id, $userCId);
        // Folder B
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        $this->assertPermission($folderB->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderB->id, $userBId, Permission::OWNER);
        $this->assertPermission($folderB->id, $userCId, Permission::OWNER);
        // Folder C
        $this->assertFolderRelationNotExist($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        $this->assertPermissionNotExist($folderC->id, $userAId);
        $this->assertPermission($folderC->id, $userBId, Permission::OWNER);
        $this->assertPermission($folderC->id, $userCId, Permission::OWNER);
    }

    public function insertUsersSharedHavingMultipleParentsSuccess1Fixture()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Betty is OWNER of folder B
        // Carol is OWNER of folder B
        // Ada is OWNER of folder C
        // Betty is OWNER of folder C
        // Carol is OWNER of folder C
        // Ada sees C in A
        // Carol sees C in B
        // ---
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Carol:O)
        //
        // C (Betty:O, Carol:O)
        // |- B (Ada:O, Carol:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C'], [$userBId => Permission::OWNER, $userCId => Permission::OWNER]);
        $folderB = $this->addFolder(['name' => 'B']);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addPermission('Folder', $folderB->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userCId, 'folder_parent_id' => $folderC->id]);

        return [$folderA, $folderB, $folderC, $userAId, $userBId, $userCId];
    }

    public function testShareFolder_UsersSharedHavingMultipleParentsSuccess2_MultipleParentAndNotOperatorParent()
    {
        list($folderA, $folderB, $folderC, $userAId, $userBId, $userCId, $userDId) = $this->insertUsersSharedHavingMultipleParentsSuccess2Fixture();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderB->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderB->id}-{$userCId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderB->id}-{$userDId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::OWNER];
        $folder = $this->service->share($uac, $folderB->id, $data);

        $this->assertTrue($folder instanceof Folder);

        // Folder A
        $this->assertFolderRelationNotExist($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        $this->assertFolderRelationNotExist($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, null);
        $this->assertPermissionNotExist($folderA->id, $userAId);
        $this->assertPermission($folderA->id, $userBId, Permission::OWNER);
        $this->assertPermission($folderA->id, $userCId, Permission::OWNER);
        $this->assertPermissionNotExist($folderA->id, $userDId);
        // Folder B
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, null);
        $this->assertPermission($folderB->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderB->id, $userBId, Permission::OWNER);
        $this->assertPermission($folderB->id, $userCId, Permission::OWNER);
        $this->assertPermission($folderB->id, $userDId, Permission::OWNER);
        // Folder C
        $this->assertFolderRelationNotExist($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelationNotExist($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, null);
        $this->assertPermissionNotExist($folderC->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderC->id, $userBId, Permission::OWNER);
        $this->assertPermissionNotExist($folderC->id, $userCId, Permission::OWNER);
        $this->assertPermission($folderC->id, $userDId, Permission::OWNER);
    }

    public function insertUsersSharedHavingMultipleParentsSuccess2Fixture()
    {
        // Betty is OWNER of A
        // Carol is OWNER of A
        // Ada is OWNER of folder B
        // Carol is OWNER of folder B
        // Dame is OWNER of folder B
        // Carol is OWNER of C
        // Dame is OWNER of C
        // Carol sees B in A
        // Dame sees B in C
        // ---
        // A (Betty:O, Carol:O)
        // |- B (Ada:O, Carol:O, Dame:O)
        // C (Betty:O, Dame:O)
        // |- B (Ada:O, Carol:O, Dame:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $userDId = UuidFactory::uuid('user.id.dame');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userBId => Permission::OWNER, $userCId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C'], [$userBId => Permission::OWNER, $userDId => Permission::OWNER]);
        $folderB = $this->addFolder(['name' => 'B']);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => null]);
        $this->addPermission('Folder', $folderB->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userCId, 'folder_parent_id' => $folderA->id]);
        $this->addPermission('Folder', $folderB->id, 'User', $userDId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userDId, 'folder_parent_id' => $folderC->id]);

        return [$folderA, $folderB, $folderC, $userAId, $userBId, $userCId, $userDId];
    }

    /* ************************************************************** */
    /* SHARED FOLDER HAVING CHILDREN WITH USERS */
    /* ************************************************************** */

    public function testShareFolder_UsersSharedHavingChildrenSuccess1_ShareFolderHavingChildrenInOperatorTree()
    {
        list($folderA, $folderB,$userAId, $userBId) = $this->insertUsersSharedHavingChildrenSuccess1Fixture();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderA->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::OWNER];
        $folder = $this->service->share($uac, $folderA->id, $data);

        $this->assertTrue($folder instanceof Folder);
        // Folder A.
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertPermission($folder->id, $userAId, Permission::OWNER);
        $this->assertPermission($folder->id, $userBId, Permission::OWNER);
        // Folder B
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        $this->assertPermission($folderB->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderB->id, $userBId, Permission::OWNER);
    }

    public function insertUsersSharedHavingChildrenSuccess1Fixture()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Ada sees B in A
        // ---
        // A (Ada:O)
        // |- B (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderB = $this->addFolder(['name' => 'B']);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => null]);

        return [$folderA, $folderB, $userAId, $userBId];
    }

    public function testShareFolder_UsersSharedHavingChildrenSuccess2_ShareFolderHavingChildrenInOperatorTreeAndOtherUsersTrees()
    {
        list($folderA, $folderB, $folderC, $userAId, $userBId) = $this->insertUsersSharedHavingChildrenSuccess2Fixture();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderA->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::OWNER];
        $folder = $this->service->share($uac, $folderA->id, $data);

        $this->assertTrue($folder instanceof Folder);

        // Folder A.
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertPermission($folder->id, $userAId, Permission::OWNER);
        $this->assertPermission($folder->id, $userBId, Permission::OWNER);
        // Folder B
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        $this->assertPermission($folderB->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderB->id, $userBId, Permission::OWNER);
        // Folder C
        $this->assertFolderRelationNotExist($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertPermissionNotExist($folderC->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderC->id, $userBId, Permission::OWNER);
    }

    public function insertUsersSharedHavingChildrenSuccess2Fixture()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Betty is OWNER of folder C
        // Ada sees B in A
        // Betty sees B in C
        // ---
        // A (Ada:O)
        // |- B (Ada:O, Betty:O)
        // C (Betty:O)
        // |- B (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C'], [$userBId => Permission::OWNER]);
        $folderB = $this->addFolder(['name' => 'B']);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => $folderC->id]);

        return [$folderA, $folderB, $folderC, $userAId, $userBId];
    }

    public function testShareFolder_UsersSharedHavingChildrenSuccess2_1_ShareFolderPersonalFolderContainingPersonalOrganizedFolder()
    {
        list($folderA, $folderB, $folderC, $userAId, $userBId) = $this->insertUsersSharedHavingChildrenSuccess2_1Fixture();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderA->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::OWNER];
        $folder = $this->service->share($uac, $folderA->id, $data);

        $this->assertTrue($folder instanceof Folder);
        // Folder A.
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertPermission($folder->id, $userAId, Permission::OWNER);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertPermission($folder->id, $userBId, Permission::OWNER);
        // Folder B
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertPermission($folderB->id, $userAId, Permission::READ);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderC->id);
        $this->assertPermission($folderB->id, $userBId, Permission::OWNER);
        // Folder C
        $this->assertFolderRelationNotExist($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertPermissionNotExist($folderC->id, $userAId);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertPermission($folderC->id, $userBId, Permission::OWNER);
    }

    public function insertUsersSharedHavingChildrenSuccess2_1Fixture()
    {
        // Ada is OWNER of folder A
        // Ada has READ on folder B
        // Betty is OWNER of folder B
        // Betty is OWNER of folder C
        // Ada sees B in A
        // Betty sees B in C
        // ---
        // A (Ada:O)
        // |- B (Ada:R, Betty:O)
        // C (Betty:O)
        // |- B (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C'], [$userBId => Permission::OWNER]);
        $folderB = $this->addFolder(['name' => 'B']);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::READ);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => $folderC->id]);

        return [$folderA, $folderB, $folderC, $userAId, $userBId];
    }

    public function testShareFolder_UsersSharedHavingChildrenSuccess2_2_ShareFolderPersonalFolderContainingPersonalOrganizedFolders()
    {
        list($folderA, $folderB, $folderC, $folderD, $userAId, $userBId) = $this->insertUsersSharedHavingChildrenSuccess2_2Fixture();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderA->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::OWNER];
        $folder = $this->service->share($uac, $folderA->id, $data);

        $this->assertTrue($folder instanceof Folder);
        // Folder A.
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertPermission($folder->id, $userAId, Permission::OWNER);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertPermission($folder->id, $userBId, Permission::OWNER);
        // Folder B
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertPermission($folderB->id, $userAId, Permission::READ);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderD->id);
        $this->assertPermission($folderB->id, $userBId, Permission::OWNER);
        // Folder C
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertPermission($folderC->id, $userAId, Permission::READ);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderD->id);
        $this->assertPermission($folderC->id, $userBId, Permission::OWNER);
        // Folder D
        $this->assertFolderRelationNotExist($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertPermissionNotExist($folderD->id, $userAId);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertPermission($folderD->id, $userBId, Permission::OWNER);
    }

    public function insertUsersSharedHavingChildrenSuccess2_2Fixture()
    {
        // Ada is OWNER of folder A
        // Ada has READ on folder B
        // Ada has READ on folder C
        // Betty is OWNER of folder B
        // Betty is OWNER of folder C
        // Betty is OWNER of folder D
        // Ada sees B in A
        // Ada sees C in A
        // Betty sees B in D
        // Betty sees C in D
        // ---
        // A (Ada:O)
        // |- B (Ada:R, Betty:O)
        // |- C (Ada:R, Betty:O)
        // D (Betty:O)
        // |- B (Ada:R, Betty:O)
        // |- C (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderD = $this->addFolderFor(['name' => 'D'], [$userBId => Permission::OWNER]);
        $folderB = $this->addFolder(['name' => 'B']);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::READ);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => $folderD->id]);
        $folderC = $this->addFolder(['name' => 'C']);
        $this->addPermission('Folder', $folderC->id, 'User', $userAId, Permission::READ);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => $folderD->id]);

        return [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId];
    }

    public function testShareFolder_UsersSharedHavingChildrenSuccess3_ShareFolderHavingChildrenInAnOtherUserTrees()
    {
        list($folderA, $folderB, $userAId, $userBId, $userCId) = $this->insertUsersSharedHavingChildrenSuccess3Fixture();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderA->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderA->id}-{$userCId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::OWNER];
        $folder = $this->service->share($uac, $folderA->id, $data);

        $this->assertTrue($folder instanceof Folder);

        // Folder A.
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        $this->assertPermission($folder->id, $userAId, Permission::OWNER);
        $this->assertPermission($folder->id, $userBId, Permission::OWNER);
        $this->assertPermission($folder->id, $userCId, Permission::OWNER);
        // Folder B
        $this->assertFolderRelationNotExist($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderA->id);
        $this->assertPermissionNotExist($folderB->id, $userAId);
        $this->assertPermission($folderB->id, $userBId, Permission::OWNER);
        $this->assertPermission($folderB->id, $userCId, Permission::OWNER);
    }

    public function insertUsersSharedHavingChildrenSuccess3Fixture()
    {
        // Ada is OWNER of folder A
        // Carol is OWNER of folder A
        // Betty is OWNER of folder B
        // Carol is OWNER of folder B
        // Carol sees B in A
        // ---
        // A (Ada:O, Carol:O)
        // |- B (Betty:O, Carol:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userCId => Permission::OWNER]);
        $folderB = $this->addFolder(['name' => 'B']);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => null]);
        $this->addPermission('Folder', $folderB->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userCId, 'folder_parent_id' => $folderA->id]);

        return [$folderA, $folderB, $userAId, $userBId, $userCId];
    }

    /* ************************************************************** */
    /* SHARED FOLDER HAVING PARENTS & CHILDREN WITH USERS */
    /* ************************************************************** */

    public function testShareFolder_UsersSharedHavingChildrenAndParentSuccess1_FixCycle()
    {
        list($folderA, $folderB, $folderC, $userAId, $userBId, $userCId) = $this->insertUsersSharedHavingChildrenAndParentSuccess1Fixture();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderB->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderB->id}-{$userBId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userCId, 'type' => Permission::OWNER];
        $folder = $this->service->share($uac, $folderB->id, $data);

        $this->assertTrue($folder instanceof Folder);

        // Folder A.
        $this->assertPermission($folderA->id, $userAId, Permission::OWNER);
        $this->assertPermissionNotExist($folderA->id, $userBId);
        $this->assertPermission($folderA->id, $userCId, Permission::OWNER);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelationNotExist($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderC->id);
        // Folder B
        $this->assertPermission($folderB->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderB->id, $userBId, Permission::OWNER);
        $this->assertPermission($folderB->id, $userCId, Permission::OWNER);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderA->id);
        // Folder C
        $this->assertPermissionNotExist($folderC->id, $userAId);
        $this->assertPermission($folderC->id, $userBId, Permission::OWNER);
        $this->assertPermission($folderC->id, $userCId, Permission::OWNER);
        $this->assertFolderRelationNotExist($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
    }

    public function insertUsersSharedHavingChildrenAndParentSuccess1Fixture()
    {
        // Ada is OWNER of folder A
        // Carol is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Betty is OWNER of folder C
        // Carol is OWNER of folder C
        // Ada sees B in A
        // Betty sees C in B
        // Carol sees A in C
        // ---
        // Difficult to represent
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $folderA = $this->addFolder(['name' => 'A']);
        $folderB = $this->addFolder(['name' => 'B']);
        $folderC = $this->addFolder(['name' => 'C']);
        $this->addPermission('Folder', $folderA->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderA->id, 'User', $userCId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userCId, Permission::OWNER);
        // Ada sees A at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => null]);
        // Ada sees B in A
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        // Betty sees B at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => null]);
        // Betty sees C in B
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => $folderB->id]);
        // Caro sees C at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userCId, 'folder_parent_id' => null]);
        // Betty sees C in A
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userCId, 'folder_parent_id' => $folderC->id]);

        return [$folderA, $folderB, $folderC, $userAId, $userBId, $userCId];
    }

    public function testShareFolder_UsersSharedHavingChildrenAndParentSuccess1_1_FixCycleWithUserInReadOnly()
    {
        list($folderA, $folderB, $folderC, $userAId, $userBId, $userCId) = $this->insertUsersSharedHavingChildrenAndParentSuccess1_1Fixture();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderB->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderB->id}-{$userBId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userCId, 'type' => Permission::OWNER];
        $folder = $this->service->share($uac, $folderB->id, $data);

        $this->assertTrue($folder instanceof Folder);

        // Folder A.
        $this->assertPermission($folderA->id, $userAId, Permission::READ);
        $this->assertPermissionNotExist($folderA->id, $userBId);
        $this->assertPermission($folderA->id, $userCId, Permission::OWNER);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelationNotExist($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderC->id);
        // Folder B
        $this->assertPermission($folderB->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderB->id, $userBId, Permission::OWNER);
        $this->assertPermission($folderB->id, $userCId, Permission::OWNER);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderA->id);
        // Folder C
        $this->assertPermissionNotExist($folderC->id, $userAId);
        $this->assertPermission($folderC->id, $userBId, Permission::OWNER);
        $this->assertPermission($folderC->id, $userCId, Permission::OWNER);
        $this->assertFolderRelationNotExist($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
    }

    public function insertUsersSharedHavingChildrenAndParentSuccess1_1Fixture()
    {
        // Ada has READ on folder A
        // Carol is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Betty is OWNER of folder C
        // Carol is OWNER of folder C
        // Ada sees B in A
        // Betty sees C in B
        // Carol sees A in C
        // ---
        // Difficult to represent
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $folderA = $this->addFolder(['name' => 'A']);
        $folderB = $this->addFolder(['name' => 'B']);
        $folderC = $this->addFolder(['name' => 'C']);
        $this->addPermission('Folder', $folderA->id, 'User', $userAId, Permission::READ);
        $this->addPermission('Folder', $folderA->id, 'User', $userCId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userCId, Permission::OWNER);
        // Ada sees A at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => null]);
        // Ada sees B in A
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        // Betty sees B at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => null]);
        // Betty sees C in B
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => $folderB->id]);
        // Caro sees C at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userCId, 'folder_parent_id' => null]);
        // Betty sees A in C
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userCId, 'folder_parent_id' => $folderC->id]);

        return [$folderA, $folderB, $folderC, $userAId, $userBId, $userCId];
    }

    public function testShareFolder_UsersSharedHavingChildrenAndParentSuccess1_2_FixCycleWithMultipleLevel()
    {
        list($folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId) = $this->insertUsersSharedHavingChildrenAndParentSuccess1_2Fixture();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderC->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderC->id}-{$userBId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userCId, 'type' => Permission::OWNER];
        $folder = $this->service->share($uac, $folderC->id, $data);

        $this->assertTrue($folder instanceof Folder);

        // Folder A.
        $this->assertPermission($folderA->id, $userAId, Permission::OWNER);
        $this->assertPermissionNotExist($folderB->id, $userBId);
        $this->assertPermission($folderA->id, $userCId, Permission::OWNER);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelationNotExist($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderD->id);
        // Folder B.
        $this->assertPermission($folderB->id, $userAId, Permission::OWNER);
        $this->assertPermissionNotExist($folderB->id, $userBId);
        $this->assertPermissionNotExist($folderB->id, $userCId);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelationNotExist($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelationNotExist($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        // Folder C.
        $this->assertPermission($folderC->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderC->id, $userBId, Permission::OWNER);
        $this->assertPermission($folderC->id, $userCId, Permission::OWNER);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        // Folder D.
        $this->assertPermissionNotExist($folderD->id, $userAId);
        $this->assertPermission($folderD->id, $userBId, Permission::OWNER);
        $this->assertPermission($folderD->id, $userCId, Permission::OWNER);
        $this->assertFolderRelationNotExist($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
    }

    public function insertUsersSharedHavingChildrenAndParentSuccess1_2Fixture()
    {
        // Ada is OWNER of folder A
        // Carol is OWNER of folder A
        // Ada is OWNER of folder B
        // Ada is OWNER of folder C
        // Betty is OWNER of folder C
        // Betty is OWNER of folder D
        // Carol is OWNER of folder D
        // Ada sees B in A
        // Ada sess C in B
        // Betty sees D in C
        // Carol sees D in A
        // ---
        // Difficult to represent
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $folderA = $this->addFolder(['name' => 'A']);
        $folderB = $this->addFolder(['name' => 'B']);
        $folderC = $this->addFolder(['name' => 'C']);
        $folderD = $this->addFolder(['name' => 'D']);
        $this->addPermission('Folder', $folderA->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderA->id, 'User', $userCId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderD->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderD->id, 'User', $userCId, Permission::OWNER);
        // Ada sees A at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => null]);
        // Ada sees B in A
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        // Ada sees C in B
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userAId, 'folder_parent_id' => $folderB->id]);
        // Betty sees C at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => null]);
        // Betty sees D in C
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userBId, 'folder_parent_id' => $folderC->id]);
        // Caro sees D at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userCId, 'folder_parent_id' => null]);
        // Betty sees A in D
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userCId, 'folder_parent_id' => $folderD->id]);

        return [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId];
    }

    public function testShareFolder_UsersSharedHavingChildrenAndParentSuccess2_FixCycle()
    {
        list($folderA, $folderB, $folderC, $userAId, $userBId, $userCId) = $this->insertUsersSharedHavingChildrenAndParentSuccess2Fixture();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderA->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderA->id}-{$userCId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::OWNER];
        $folder = $this->service->share($uac, $folderA->id, $data);

        $this->assertTrue($folder instanceof Folder);

        // Folder A.
        $this->assertPermission($folderA->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderA->id, $userBId, Permission::OWNER);
        $this->assertPermission($folderA->id, $userCId, Permission::OWNER);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        // Folder B
        $this->assertPermission($folderB->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderB->id, $userBId, Permission::OWNER);
        $this->assertPermissionNotExist($folderB->id, $userCId);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        $this->assertFolderRelationNotExist($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderA->id);
        // Folder C
        $this->assertPermissionNotExist($folderC->id, $userAId);
        $this->assertPermission($folderC->id, $userBId, Permission::OWNER);
        $this->assertPermission($folderC->id, $userCId, Permission::OWNER);
        $this->assertFolderRelationNotExist($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
    }

    public function insertUsersSharedHavingChildrenAndParentSuccess2Fixture()
    {
        // Ada is OWNER of folder A
        // Carol is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Betty is OWNER of folder C
        // Carol is OWNER of folder C
        // Ada sees B in A
        // Betty sees C in B
        // Carol sees A in C
        // ---
        // Difficult to represent
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $folderA = $this->addFolder(['name' => 'A']);
        $folderB = $this->addFolder(['name' => 'B']);
        $folderC = $this->addFolder(['name' => 'C']);
        $this->addPermission('Folder', $folderA->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderA->id, 'User', $userCId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userCId, Permission::OWNER);
        // Ada sees A at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => null]);
        // Ada sees B in A
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        // Betty sees B at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => null]);
        // Betty sees C in B
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => $folderB->id]);
        // Caro sees C at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userCId, 'folder_parent_id' => null]);
        // Betty sees C in A
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userCId, 'folder_parent_id' => $folderC->id]);

        return [$folderA, $folderB, $folderC, $userAId, $userBId, $userCId];
    }

    /* ************************************************************** */
    /* SHARED FOLDER WITH GROUPS */
    /* ************************************************************** */

    public function testShareFolder_GroupsSharedSuccess1_ShareFolderWithGroup()
    {
        list($folder, $group) = $this->insertGroupsSharedSuccess1Fixture();
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folder->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'Group', 'aro_foreign_key' => $group->id, 'type' => Permission::OWNER];
        $folder = $this->service->share($uac, $folder->id, $data);

        $this->assertTrue($folder instanceof Folder);
        $this->assertPermission($folder->id, $userAId, Permission::OWNER);
        $this->assertPermission($folder->id, $group->id, Permission::OWNER);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId);
    }

    public function insertGroupsSharedSuccess1Fixture()
    {
        // Ada is OWNER of folder A
        // ---
        // A (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folder = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $group = $this->addGroup(['name' => '1', 'groups_users' => [['user_id' => $userBId, 'is_admin' => true]]]);

        return [$folder, $group];
    }

    public function testShareFolder_GroupsSharedSuccess2_ShareFolderWithGroupIAmIn()
    {
        list($folder, $group) = $this->insertGroupsSharedSuccess2Fixture();
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folder->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'Group', 'aro_foreign_key' => $group->id, 'type' => Permission::READ];
        $folder = $this->service->share($uac, $folder->id, $data);

        $this->assertTrue($folder instanceof Folder);
        $this->assertPermission($folder->id, $userAId, Permission::OWNER);
        $this->assertComputedAccess(PermissionsTable::FOLDER_ACO, $folder->id, $userAId, Permission::OWNER);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId);
        $this->assertPermission($folder->id, $group->id, Permission::READ);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId);
    }

    public function insertGroupsSharedSuccess2Fixture()
    {
        // Ada is OWNER of folder A
        // ---
        // A (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folder = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $group = $this->addGroup(['name' => '1', 'groups_users' => [['user_id' => $userBId, 'is_admin' => true], ['user_id' => $userAId, 'is_admin' => true]]]);

        return [$folder, $group];
    }
}
