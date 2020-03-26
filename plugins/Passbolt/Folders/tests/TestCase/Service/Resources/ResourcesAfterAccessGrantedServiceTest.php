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

namespace Passbolt\Folders\Test\TestCase\Service\Resources;

use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Model\Table\PermissionsTable;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\Resources\ResourcesAfterAccessGrantedService;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\Folders\ResourcesAfterAccessGrantedService Test Case
 *
 * @uses \Passbolt\Folders\Service\Resources\ResourcesAfterAccessGrantedService
 */
class ResourcesAfterAccessGrantedServiceTest extends FoldersTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;

    public $fixtures = [
        FoldersFixture::class,
        FoldersRelationsFixture::class,
        GroupsFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        ResourcesFixture::class,
        SecretsFixture::class,
        UsersFixture::class,
    ];

    /**
     * @var ResourcesAfterAccessGrantedService
     */
    private $service;

    public function setUp()
    {
        parent::setUp();
        $this->service = new ResourcesAfterAccessGrantedService();
    }

    /* ************************************************************** */
    /* ADD USER PERMISSION - RESOURCE NO PARENT */
    /* ************************************************************** */

    public function testAfterResourceShareAddUserSuccess_NotParent1()
    {
        list($r1, $userAId) = $this->insertFixture_AfterResourceShareAddUserSuccess_NotParent1();
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);

        $permission = $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userBId);
        $this->service->afterAccessGranted($uac, $permission);

        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, null);
    }

    public function insertFixture_AfterResourceShareAddUserSuccess_NotParent1()
    {
        // Ada is OWNER of resource R1
        // R1 (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER]);

        return [$r1, $userAId];
    }

    /* ************************************************************** */
    /* ADD USER PERMISSION - RESOURCE HAVING A PARENT */
    /* ************************************************************** */

    public function testAfterShareResourceAddUserSuccess_HavingOneParent1_VisibleParentInOperatorTree()
    {
        list($folderA, $r1, $userAId, $userBId) = $this->insertFixture_AfterShareResourceAddUserSuccess_HavingOneParent1();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $permission = $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userBId);
        $this->service->afterAccessGranted($uac, $permission);

        // Folder A
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertPermission($folderA->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderA->id, $userBId, Permission::OWNER);
        $this->assertItemIsInTrees($folderA->id, 2);
        // Resource R1
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
        $this->assertPermission($r1->id, $userAId, Permission::OWNER);
        $this->assertPermission($r1->id, $userBId, Permission::OWNER);
        $this->assertItemIsInTrees($r1->id, 2);
    }

    public function insertFixture_AfterShareResourceAddUserSuccess_HavingOneParent1()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of resource R1
        // Add sees R1 in A
        // ---
        // A (Ada:O, Betty:O)
        // |- R1 (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $r1 = $this->addResourceFor(['name' => 'R1', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER]);

        return [$folderA, $r1, $userAId, $userBId];
    }

    public function testAfterShareResourceAddUserSuccess_HavingOneParent1_1_VisibleParentInOperatorTree_OperatorReadOnParent()
    {
        list($folderA, $r1, $userAId, $userBId) = $this->insertFixture_AfterShareResourceAddUserSuccess_HavingOneParent1_1();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $permission = $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userBId);
        $this->service->afterAccessGranted($uac, $permission);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertPermission($folderA->id, $userAId, Permission::READ);
        $this->assertPermission($folderA->id, $userBId, Permission::OWNER);
        // Resource R1
        $this->assertItemIsInTrees($r1->id, 2);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
        $this->assertPermission($r1->id, $userAId, Permission::OWNER);
        $this->assertPermission($r1->id, $userBId, Permission::OWNER);
    }

    public function insertFixture_AfterShareResourceAddUserSuccess_HavingOneParent1_1()
    {
        // Ada has READ on folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of resource R1
        // Add sees R1 in A
        // ---
        // A (Ada:R, Betty:O)
        // |- R1 (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);
        $r1 = $this->addResourceFor(['name' => 'R1', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER]);

        return [$folderA, $r1, $userAId, $userBId];
    }

    public function testAfterShareResourceAddUserSuccess_HavingOneParent2_NotVisibleParentInOperatorTree()
    {
        list($folderA, $r1, $userAId) = $this->insertFixture_AfterShareResourceAddUserSuccess_HavingOneParent2();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userBId = UuidFactory::uuid('user.id.betty');

        $permission = $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userBId);
        $this->service->afterAccessGranted($uac, $permission);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertPermission($folderA->id, $userAId, Permission::OWNER);
        $this->assertPermissionNotExist($folderA->id, $userBId);
        // Resource R1
        $this->assertItemIsInTrees($r1->id, 2);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, null);
        $this->assertPermission($r1->id, $userAId, Permission::OWNER);
        $this->assertPermission($r1->id, $userBId, Permission::OWNER);
    }

    public function insertFixture_AfterShareResourceAddUserSuccess_HavingOneParent2()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of resource R1
        // Add sees R1 in A
        // ---
        // A (Ada:O)
        // |- R1 (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $r1 = $this->addResourceFor(['name' => 'R1', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER]);

        return [$folderA, $r1, $userAId];
    }

    public function testAfterShareResourceAddUserSuccess_AddUser_FolderHasParent3_VisibleParentInOtherUserTree()
    {
        list($folderA, $r1, $userAId, $userBId, $userCId) = $this->insertFixture_AfterShareResourceAddUserSuccess_HavingOneParent3();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $permission = $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userBId);
        $this->service->afterAccessGranted($uac, $permission);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        $this->assertPermissionNotExist($folderA->id, $userAId);
        $this->assertPermission($folderA->id, $userBId, Permission::OWNER);
        $this->assertPermission($folderA->id, $userCId, Permission::OWNER);
        // Resource R1
        $this->assertItemIsInTrees($r1->id, 3);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userCId, $folderA->id);
        $this->assertPermission($r1->id, $userAId, Permission::OWNER);
        $this->assertPermission($r1->id, $userBId, Permission::OWNER);
        $this->assertPermission($r1->id, $userCId, Permission::OWNER);
    }

    public function insertFixture_AfterShareResourceAddUserSuccess_HavingOneParent3()
    {
        // Betty is OWNER of folder A
        // Carol is OWNER of folder A
        // Ada is OWNER of resource R1
        // Carol is OWNER of resource R1
        // Carol sees R1 in A
        // ---
        // A (Betty:O, Carol:O)
        // |- R1 (Ada:O, Carol:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userBId => Permission::OWNER, $userCId => Permission::OWNER]);
        $r1 = $this->addResource(['name' => 'R1']);
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE, 'foreign_id' => $r1->id, 'user_id' => $userAId, 'folder_parent_id' => null]);
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE, 'foreign_id' => $r1->id, 'user_id' => $userCId, 'folder_parent_id' => $folderA->id]);

        return [$folderA, $r1, $userAId, $userBId, $userCId];
    }

    /* ************************************************************** */
    /* ADD USER PERMISSION - RESOURCE HAVING MULTIPLE PARENT */
    /* ************************************************************** */

    public function testAfterShareResourceAddUserSuccess_HavingMultipleParents1_MultipleVisibleParents_OneParentInOperatorTree()
    {
        list($folderA, $r1, $folderC, $userAId, $userBId, $userCId) = $this->insertFixture_AfterShareResourceAddUserSuccess_HavingMultipleParents1();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $permission = $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userBId);
        $this->service->afterAccessGranted($uac, $permission);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertPermission($folderA->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderA->id, $userBId, Permission::OWNER);
        $this->assertPermissionNotExist($folderA->id, $userCId);
        // Resource R1
        $this->assertItemIsInTrees($r1->id, 3);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userCId, null);
        $this->assertPermission($r1->id, $userAId, Permission::OWNER);
        $this->assertPermission($r1->id, $userBId, Permission::OWNER);
        $this->assertPermission($r1->id, $userCId, Permission::OWNER);
        // Folder C
        $this->assertFolderRelationNotExist($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        $this->assertPermissionNotExist($folderC->id, $userAId);
        $this->assertPermission($folderC->id, $userBId, Permission::OWNER);
        $this->assertPermission($folderC->id, $userCId, Permission::OWNER);
    }

    public function insertFixture_AfterShareResourceAddUserSuccess_HavingMultipleParents1()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Betty is OWNER of resource R1
        // Carol is OWNER ofesource R1
        // Ada is OWNER of folder C
        // Betty is OWNER of folder C
        // Carol is OWNER of folder C
        // Ada sees R1 in A
        // Carol sees R1 in C
        // ---
        // A (Ada:O, Betty:O)
        // |- R1 (Ada:O, Carol:O)
        //
        // C (Betty:O, Carol:O)
        // |- R1 (Ada:O, Carol:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C'], [$userBId => Permission::OWNER, $userCId => Permission::OWNER]);
        $r1 = $this->addResource(['name' => 'R1']);
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE, 'foreign_id' => $r1->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE, 'foreign_id' => $r1->id, 'user_id' => $userCId, 'folder_parent_id' => $folderC->id]);

        return [$folderA, $r1, $folderC, $userAId, $userBId, $userCId];
    }

    public function testAfterShareResourceAddUserSuccess_HavingMultipleParents2_MultipleVisibleParents_NoParentInOperatorTree()
    {
        list($folderA, $r1, $folderC, $userAId, $userBId, $userCId, $userDId) = $this->insertFixture_AfterShareResourceAddUserSuccess_HavingMultipleParents2();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $permission = $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userBId);
        $this->service->afterAccessGranted($uac, $permission);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        $this->assertPermissionNotExist($folderA->id, $userAId);
        $this->assertPermission($folderA->id, $userBId, Permission::OWNER);
        $this->assertPermission($folderA->id, $userCId, Permission::OWNER);
        $this->assertPermissionNotExist($folderA->id, $userDId);
        // Resource R1
        $this->assertItemIsInTrees($r1->id, 4);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, null);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userCId, null);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userDId, null);
        $this->assertPermission($r1->id, $userAId, Permission::OWNER);
        $this->assertPermission($r1->id, $userBId, Permission::OWNER);
        $this->assertPermission($r1->id, $userCId, Permission::OWNER);
        $this->assertPermission($r1->id, $userDId, Permission::OWNER);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, null);
        $this->assertPermissionNotExist($folderC->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderC->id, $userBId, Permission::OWNER);
        $this->assertPermissionNotExist($folderC->id, $userCId, Permission::OWNER);
        $this->assertPermission($folderC->id, $userDId, Permission::OWNER);
    }

    public function insertFixture_AfterShareResourceAddUserSuccess_HavingMultipleParents2()
    {
        // Betty is OWNER of A
        // Carol is OWNER of A
        // Ada is OWNER of resource R1
        // Carol is OWNER of resource R1
        // Dame is OWNER of resource R1
        // Carol is OWNER of C
        // Dame is OWNER of C
        // Carol sees R1 in A
        // Dame sees R1 in C
        // ---
        // A (Betty:O, Carol:O)
        // |- R1 (Ada:O, Carol:O, Dame:O)
        // C (Betty:O, Dame:O)
        // |- R1 (Ada:O, Carol:O, Dame:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $userDId = UuidFactory::uuid('user.id.dame');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userBId => Permission::OWNER, $userCId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C'], [$userBId => Permission::OWNER, $userDId => Permission::OWNER]);
        $r1 = $this->addResource(['name' => 'R1']);
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE, 'foreign_id' => $r1->id, 'user_id' => $userAId, 'folder_parent_id' => null]);
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE, 'foreign_id' => $r1->id, 'user_id' => $userCId, 'folder_parent_id' => $folderA->id]);
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, 'User', $userDId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE, 'foreign_id' => $r1->id, 'user_id' => $userDId, 'folder_parent_id' => $folderC->id]);

        return [$folderA, $r1, $folderC, $userAId, $userBId, $userCId, $userDId];
    }
}
