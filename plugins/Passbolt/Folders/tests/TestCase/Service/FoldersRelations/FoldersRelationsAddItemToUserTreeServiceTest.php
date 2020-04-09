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

namespace Passbolt\Folders\Test\TestCase\Service\FoldersRelations;

use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Model\Table\PermissionsTable;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Test\Lib\Utility\FixtureProviderTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\TestSuite\IntegrationTestTrait;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsAddItemToUserTreeService;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\FoldersRelations\FoldersRelationsAddItemToUserTreeService Test Case
 *
 * @covers \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsAddItemToUserTreeService
 */
class FoldersRelationsAddItemToUserTreeServiceTest extends FoldersTestCase
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
        ResourcesFixture::class,
        SecretsFixture::class,
        UsersFixture::class,
    ];

    /**
     * @var FoldersRelationsAddItemToUserTreeService
     */
    private $service;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->service = new FoldersRelationsAddItemToUserTreeService();
    }

    /* ************************************************************** */
    /* FOLDER - FOLDER NO PARENT NO CHILD */
    /* ************************************************************** */

    public function testAddItemToUserTreeSuccess_Folder_NoParentNoChild()
    {
        list ($folderA, $userAId, $userBId) = $this->insertFixture_Folder_NoParentNoChild1();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id, $userBId);

        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId);
    }

    public function insertFixture_Folder_NoParentNoChild1()
    {
        // Ada is OWNER of folder A
        // A (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderA->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $userAId, $userBId];
    }

    /* ************************************************************** */
    /* FOLDER - FOLDER HAVING A PARENT */
    /* ************************************************************** */

    public function testAddItemToUserTreeSuccess_Folder_HavingOneParent1_ParentVisibleInOperatorTree()
    {
        list($folderA, $folderB, $userAId, $userBId) = $this->insertFixture_Folder_HavingOneParent1();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, $userBId);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
    }

    public function insertFixture_Folder_HavingOneParent1()
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

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderB->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $userAId, $userBId];
    }

    public function testAddItemToUserTreeSuccess_Folder_HavingOneParent2_ParentNotVisibleInOperatorTree()
    {
        list($folderA, $folderB, $userAId, $userBId) = $this->insertFixture_Folder_HavingOneParent2();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, $userBId);

        // Assert Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        // Assert Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
    }

    public function insertFixture_Folder_HavingOneParent2()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of folder B
        // Add sees B in A
        // ---
        // A (Ada:O)
        // |- B (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderB->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $userAId, $userBId];
    }

    public function testAddItemToUserTreeSuccess_Folder_HavingOneParent3_ParentVisibleInOtherUserTree()
    {
        list($folderA, $folderB, $userAId, $userBId, $userCId) = $this->insertFixture_Folder_HavingOneParent3();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, $userBId);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderA->id);
    }

    public function insertFixture_Folder_HavingOneParent3()
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

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderB->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $userAId, $userBId, $userCId];
    }

    /* ************************************************************** */
    /* FOLDER - FOLDER HAVING MULTIPLE PARENT */
    /* ************************************************************** */

    public function testAddItemToUserTreeSuccess_Folder_HavingMultipleParents1_MultipleParentsVisible_OneParentInOperatorTree()
    {
        list($folderA, $folderB, $folderC, $userAId, $userBId, $userCId) = $this->insertFixture_Folder_HavingMultipleParents1();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, $userBId);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
    }

    public function insertFixture_Folder_HavingMultipleParents1()
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

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderB->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $folderC, $userAId, $userBId, $userCId];
    }

    public function testAddItemToUserTreeSuccess_Folder_HavingMultipleParents2_MultipleParentsVisible_NoParentInOperatorTree()
    {
        list($folderA, $folderB, $folderC, $userAId, $userBId, $userCId, $userDId) = $this->insertFixture_Folder_HavingMultipleParents2();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, $userBId);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 4);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, null);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, null);
    }

    public function insertFixture_Folder_HavingMultipleParents2()
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

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderB->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $folderC, $userAId, $userBId, $userCId, $userDId];
    }

    /* ************************************************************** */
    /* FOLDER - FOLDER HAVING CHILDREN */
    /* ************************************************************** */

    public function testAddItemToUserTreeSuccess_Folder_HavingChildren1_ChildInOperatorTree()
    {
        list($folderA, $folderB, $userAId, $userBId) = $this->insertFixture_Folder_HavingChildren1();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id, $userBId);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
    }

    public function insertFixture_Folder_HavingChildren1()
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

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderA->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $userAId, $userBId];
    }

    public function testAddItemToUserTreeSuccess_Folder_HavingChildren2_ChildInOperatorTree_ChildAlreadyOrganizedInTargetUserTree()
    {
        list($folderA, $folderB, $folderC, $userAId, $userBId) = $this->insertFixture_Folder_HavingChildren2();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id, $userBId);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 1);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
    }

    public function insertFixture_Folder_HavingChildren2()
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

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderA->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $folderC, $userAId, $userBId];
    }

    // @todo what if self organized for both of them ? :P

    public function testAddItemToUserTreeSuccess_Folder_HavingChildren3_ChildInOperatorTree_ChildAlreadyOrganizedInTargetUserTree_ChildSelfOrganizedForOperator()
    {
        list($folderA, $folderB, $folderC, $userAId, $userBId) = $this->insertFixture_Folder_HavingChildren3();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id, $userBId, true);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderC->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 1);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
    }

    public function insertFixture_Folder_HavingChildren3()
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

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderA->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $folderC, $userAId, $userBId];
    }

    // Variation of HavingChildren2_1 but with multiple children.
    // In order to test that the self organized flag is not changed after the first child is treated.

    public function testAddItemToUserTreeSuccess_Folder_HavingChildren4_ChildrenInOperatorTree_ChildrenAlreadyOrganizedInTargetUserTree_ChildrenSelfOrganizedForOperator()
    {
        list($folderA, $folderB, $folderC, $folderD, $userAId, $userBId) = $this->insertFixture_Folder_HavingChildren4();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id, $userBId, true);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderD->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderD->id);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 1);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
    }

    public function insertFixture_Folder_HavingChildren4()
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

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderA->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId];
    }

    public function testAddItemToUserTreeSuccess_Folder_HavingChildren5_VisibleChildInOtherUsersTrees()
    {
        list($folderA, $folderB, $userAId, $userBId, $userCId) = $this->insertFixture_Folder_HavingChildren5();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id, $userBId);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 3);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderA->id);
    }

    public function insertFixture_Folder_HavingChildren5()
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

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderA->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $userAId, $userBId, $userCId];
    }

    /* ************************************************************** */
    /* FOLDER - FOLDER HAVING PARENT(S) AND CHILDREN */
    /* ************************************************************** */

    public function testAddItemToUserTreeSuccess_Folder_HavingParentsAndChildren1_CycleDetectedWhenReconstructingParent_ParentInOperatorTree()
    {
        list($folderA, $folderB, $folderC, $userAId, $userBId, $userCId) = $this->insertFixture_Folder_HavingParentsAndChildren1();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, $userCId);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderC->id);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderA->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
    }

    public function insertFixture_Folder_HavingParentsAndChildren1()
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

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderB->id, PermissionsTable::USER_ARO, $userCId);

        return [$folderA, $folderB, $folderC, $userAId, $userBId, $userCId];
    }

    public function testAddItemToUserTreeSuccess_Folder_HavingParentsAndChildren2_CycleDetectedWhenReconstructingParent_ParentInOperatorTree_CycleDetectedInParentAncestors()
    {
        list($folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId) = $this->insertFixture_Folder_HavingParentsAndChildren2();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderC->id, $userCId);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderD->id);
        // Folder B.
        $this->assertItemIsInTrees($folderB->id, 1);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        // Folder C.
        $this->assertItemIsInTrees($folderC->id, 3);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        // Folder D.
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
    }

    public function insertFixture_Folder_HavingParentsAndChildren2()
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

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderC->id, PermissionsTable::USER_ARO, $userCId);

        return [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId];
    }

    public function testAddItemToUserTreeSuccess_Folder_HavingParentsAndChildren3_CycleDetectedWhenReconstructingChildren_ChildrenInOperatorTree()
    {
        list($folderA, $folderB, $folderC, $userAId, $userBId, $userCId) = $this->insertFixture_Folder_HavingParentsAndChildren3();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id, $userBId);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 3);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
    }

    public function insertFixture_Folder_HavingParentsAndChildren3()
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
        // A (Ada:O, Carol:O)
        // |- B (Ada:O, Betty:O)
        //    |- C (Betty:O, Carol:O)
        //       |- A (Ada:O, Carol:O)
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

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderA->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $folderC, $userAId, $userBId, $userCId];
    }

    public function testAddItemToUserTreeSuccess_Folder_HavingParentsAndChildren4_CycleDetectedWhenReconstructingChildren_ChildrenInOperatorTree_CycleDetectedInChildrenOfChildren()
    {
        list($folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId, $userDId) =
            $this->insertFixture_Folder_HavingParentsAndChildren4();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, $userCId);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderD->id);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderA->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderC->id);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, null);
    }

    public function insertFixture_Folder_HavingParentsAndChildren4()
    {
        //  Ada is OWNER of folder A
        //  Carol is OWNER of folder A
        //  Ada is OWNER of folder B
        //  Dame is OWNER of folder B
        //  Betty is OWNER of folder C
        //  Carol is OWNER of folder C
        //  Carol is OWNER of folder D
        //  Dame is OWNER of folder D
        //  Ada sees B in A
        //  Betty sess C in B
        //  Carol sees D in C
        //  Carol sees A in D
        // ---
        // A (Ada:O, Carol:O)
        // |- B (Ada:O, Betty:O)
        //    |- C (Betty:O, Carol:O)
        //       |- D (Carol:O, Dame:O)
        //          |- A (Ada:O, Carol:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $userDId = UuidFactory::uuid('user.id.dame');
        $folderA = $this->addFolder(['name' => 'A']);
        $folderB = $this->addFolder(['name' => 'B']);
        $folderC = $this->addFolder(['name' => 'C']);
        $folderD = $this->addFolder(['name' => 'D']);
        // Folder A
        $this->addPermission('Folder', $folderA->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderA->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => null]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userCId, 'folder_parent_id' => $folderD->id]);
        // Folder B
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => null]);
        // Folder C
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => $folderB->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userCId, 'folder_parent_id' => null]);
        // Folder D
        $this->addPermission('Folder', $folderD->id, 'User', $userCId, Permission::OWNER);
        $this->addPermission('Folder', $folderD->id, 'User', $userDId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userCId, 'folder_parent_id' => $folderC->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userDId, 'folder_parent_id' => null]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderB->id, PermissionsTable::USER_ARO, $userCId);

        return [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId, $userDId];
    }

    /* ************************************************************** */
    /* RESOURCE - RESOURCE NO PARENT */
    /* ************************************************************** */

    public function testAddItemToUserTreeSuccess_Resource_NotParent1()
    {
        list($r1, $userAId, $userBId) = $this->insertFixture_Resource_NotParent1();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->id, $userBId);

        $this->assertItemIsInTrees($r1->id, 2);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, null);
    }

    public function insertFixture_Resource_NotParent1()
    {
        // Ada is OWNER of resource R1
        // R1 (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userBId);

        return [$r1, $userAId, $userBId];
    }

    /* ************************************************************** */
    /* RESOURCE - RESOURCE HAVING A PARENT */
    /* ************************************************************** */

    public function testAddItemToUserTreeSuccess_Resource_HavingOneParent1_VisibleParentInOperatorTree()
    {
        list($folderA, $r1, $userAId, $userBId) = $this->insertFixture_Resource_HavingOneParent1();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->id, $userBId);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        // Resource R1
        $this->assertItemIsInTrees($r1->id, 2);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
    }

    public function insertFixture_Resource_HavingOneParent1()
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

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $r1, $userAId, $userBId];
    }

    public function testAddItemToUserTreeSuccess_Resource_HavingOneParent1_1_VisibleParentInOperatorTree_OperatorReadOnParent()
    {
        list($folderA, $r1, $userAId, $userBId) = $this->insertFixture_Resource_HavingOneParent1_1();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->id, $userBId);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        // Resource R1
        $this->assertItemIsInTrees($r1->id, 2);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
    }

    public function insertFixture_Resource_HavingOneParent1_1()
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

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $r1, $userAId, $userBId];
    }

    public function testAddItemToUserTreeSuccess_Resource_HavingOneParent2_NotVisibleParentInOperatorTree()
    {
        list($folderA, $r1, $userAId, $userBId) = $this->insertFixture_Resource_HavingOneParent2();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->id, $userBId);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        // Resource R1
        $this->assertItemIsInTrees($r1->id, 2);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, null);
    }

    public function insertFixture_Resource_HavingOneParent2()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of resource R1
        // Add sees R1 in A
        // ---
        // A (Ada:O)
        // |- R1 (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $r1 = $this->addResourceFor(['name' => 'R1', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $r1, $userAId, $userBId];
    }

    public function testAddItemToUserTreeSuccess_Resource_HavingOneParent3_VisibleParentInOtherUserTree()
    {
        list($folderA, $r1, $userAId, $userBId, $userCId) = $this->insertFixture_Resource_HavingOneParent3();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->id, $userBId);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        // Resource R1
        $this->assertItemIsInTrees($r1->id, 3);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userCId, $folderA->id);
    }

    public function insertFixture_Resource_HavingOneParent3()
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

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $r1, $userAId, $userBId, $userCId];
    }

    /* ************************************************************** */
    /* RESOURCE - RESOURCE HAVING MULTIPLE PARENT */
    /* ************************************************************** */

    public function testAddItemToUserTreeSuccess_Resource_HavingMultipleParents1_MultipleVisibleParents_OneParentInOperatorTree()
    {
        list($folderA, $r1, $folderC, $userAId, $userBId, $userCId) = $this->insertFixture_Resource_HavingMultipleParents1();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->id, $userBId);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        // Resource R1
        $this->assertItemIsInTrees($r1->id, 3);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userCId, null);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
    }

    public function insertFixture_Resource_HavingMultipleParents1()
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

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $r1, $folderC, $userAId, $userBId, $userCId];
    }

    public function testAddItemToUserTreeSuccess_Resource_HavingMultipleParents2_MultipleVisibleParents_NoParentInOperatorTree()
    {
        list($folderA, $r1, $folderC, $userAId, $userBId, $userCId, $userDId) = $this->insertFixture_Resource_HavingMultipleParents2();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->id, $userBId);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, null);
        // Resource R1
        $this->assertItemIsInTrees($r1->id, 4);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, null);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userCId, null);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userDId, null);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, null);
    }

    public function insertFixture_Resource_HavingMultipleParents2()
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

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $r1, $folderC, $userAId, $userBId, $userCId, $userDId];
    }
}
