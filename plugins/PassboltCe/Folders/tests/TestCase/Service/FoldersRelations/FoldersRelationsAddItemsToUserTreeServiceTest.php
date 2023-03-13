<?php
declare(strict_types=1);

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

namespace Passbolt\Folders\Test\TestCase\Service\FoldersRelations;

use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Model\Table\PermissionsTable;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Utility\FixtureProviderTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Passbolt\Folders\Model\Dto\FolderRelationDto;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsAddItemsToUserTreeService;
use Passbolt\Folders\Test\Factory\FoldersRelationFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\FoldersRelations\FoldersRelationsAddItemsToUserTreeService Test Case
 *
 * @covers \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsAddItemsToUserTreeService
 */
class FoldersRelationsAddItemsToUserTreeServiceTest extends FoldersTestCase
{
    use FixtureProviderTrait;
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;

    public $fixtures = [
        UsersFixture::class,
    ];

    /**
     * @var FoldersRelationsAddItemsToUserTreeService
     */
    private $service;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new FoldersRelationsAddItemsToUserTreeService();
    }

    public function testAddItemsToUserTreeSuccess_NoItemToAdd()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        $this->assertSame(0, FoldersRelationFactory::count());
    }

    /* ADD A UNIQUE ITEM - FOLDER - FOLDER NO PARENT NO CHILD */

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_NoParentNoChild()
    {
        [$folderA, $userAId, $userBId] = $this->insertFixture_Folder_NoParentNoChild1();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

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

    /* ADD A UNIQUE ITEM - FOLDER - FOLDER HAVING A PARENT */

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingOneParent1_ParentVisibleInOperatorTree()
    {
        [$folderA, $folderB, $userAId, $userBId] = $this->insertFixture_Folder_HavingOneParent1();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
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

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingOneParent2_ParentNotVisibleInOperatorTree()
    {
        [$folderA, $folderB, $userAId, $userBId] = $this->insertFixture_Folder_HavingOneParent2();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Assert Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        // Assert Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
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

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingOneParent3_ParentVisibleInOtherUserTree()
    {
        [$folderA, $folderB, $userAId, $userBId, $userCId] = $this->insertFixture_Folder_HavingOneParent3();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
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
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addPermission('Folder', $folderB->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userCId, 'folder_parent_id' => $folderA->id]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderB->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $userAId, $userBId, $userCId];
    }

    /* ADD A UNIQUE ITEM - FOLDER - FOLDER HAVING MULTIPLE PARENT */

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingMultipleParents1_PriorityToTheOperatorParent()
    {
        [$folderA, $folderB, $folderC, $userAId, $userBId, $userCId] = $this->insertFixture_Folder_HavingMultipleParents1();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
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

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingMultipleParents2_NoParentInOperatorTree_PriorityToTheMostUsed()
    {
        [$folderA, $folderB, $folderC, $userAId, $userBId, $userCId, $userDId, $userEId] = $this->insertFixture_Folder_HavingMultipleParents2();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 5);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderC->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, $folderC->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userEId, $folderC->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 3);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userEId, FoldersRelation::ROOT);
    }

    public function insertFixture_Folder_HavingMultipleParents2()
    {
        // Betty is OWNER of A
        // Carol is OWNER of A
        // Ada is OWNER of folder B
        // Carol is OWNER of folder B
        // Dame is OWNER of folder B
        // Edith is OWNER of folder B
        // Carol is OWNER of C
        // Dame is OWNER of C
        // Edith is OWNER of B
        // Carol sees B in A
        // Dame sees B in C
        // Edit sees B in C
        // A is older than C
        // ---
        // A (Betty:O, Carol:O)
        // |- B (Ada:O, Carol:O, Dame:O, Edith:O)
        // C (Betty:O, Dame:O, Edith:O)
        // |- B (Ada:O, Carol:O, Dame:O, Edith:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $userDId = UuidFactory::uuid('user.id.dame');
        $userEId = UuidFactory::uuid('user.id.edith');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userBId => Permission::OWNER, $userCId => Permission::OWNER]);
        sleep(1);
        $folderB = $this->addFolder(['name' => 'B']);
        $folderC = $this->addFolderFor(['name' => 'C'], [$userBId => Permission::OWNER, $userDId => Permission::OWNER, $userEId => Permission::OWNER]);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addPermission('Folder', $folderB->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userCId, 'folder_parent_id' => $folderA->id]);
        $this->addPermission('Folder', $folderB->id, 'User', $userDId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userDId, 'folder_parent_id' => $folderC->id]);
        $this->addPermission('Folder', $folderB->id, 'User', $userEId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userEId, 'folder_parent_id' => $folderC->id]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderB->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $folderC, $userAId, $userBId, $userCId, $userDId, $userEId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingMultipleParents3_NoParentInOperatorTree_PriorityToTheOldestParent()
    {
        [$folderA, $folderB, $folderC, $userAId, $userBId, $userCId, $userDId] = $this->insertFixture_Folder_HavingMultipleParents3();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 4);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, FoldersRelation::ROOT);
    }

    public function insertFixture_Folder_HavingMultipleParents3()
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
        $folderB = $this->addFolder(['name' => 'B']);
        $folderC = $this->addFolderFor(['name' => 'C'], [$userBId => Permission::OWNER, $userDId => Permission::OWNER]);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addPermission('Folder', $folderB->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userCId, 'folder_parent_id' => $folderA->id]);
        $this->addPermission('Folder', $folderB->id, 'User', $userDId, Permission::OWNER);
        sleep(1);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userDId, 'folder_parent_id' => $folderC->id]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderB->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $folderC, $userAId, $userBId, $userCId, $userDId];
    }

    /* ADD A UNIQUE ITEM - FOLDER - FOLDER HAVING CHILDREN */

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingChildren1_ChildInOperatorTree()
    {
        [$folderA, $folderB, $userAId, $userBId] = $this->insertFixture_Folder_HavingChildren1();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
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
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderA->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $userAId, $userBId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingChildren2_ChildInOperatorTree_ChildAlreadyOrganizedInTargetUserTree()
    {
        [$folderA, $folderB, $folderC, $userAId, $userBId] = $this->insertFixture_Folder_HavingChildren2();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 1);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
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

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingChildren3_VisibleChildInOtherUsersTrees()
    {
        [$folderA, $folderB, $userAId, $userBId, $userCId] = $this->insertFixture_Folder_HavingChildren3();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 3);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderA->id);
    }

    public function insertFixture_Folder_HavingChildren3()
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
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addPermission('Folder', $folderB->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userCId, 'folder_parent_id' => $folderA->id]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderA->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $userAId, $userBId, $userCId];
    }

    /**
     * Ada is OWNER of folder A
     * Ada is OWNER of resource R1
     * Betty has OWNER on resource R1
     * ---
     * A (Ada: O)
     * |-R1 (Ada: O, Betty: O)
     */
    public function insertFixture_FolderHavingChildrenResources1()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $resource1 = $this->addResource(['name' => 'R1'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::RESOURCE_ACO, 'foreign_id' => $resource1->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::RESOURCE_ACO, 'foreign_id' => $resource1->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);

        return [$folderA, $resource1, $userAId, $userBId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingChildrenResources1()
    {
        [$folderA, $resource1, $userAId, $userBId] = $this->insertFixture_FolderHavingChildrenResources1();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Resource 1
        $this->assertItemIsInTrees($resource1->id, 2);
        $this->assertFolderRelation($resource1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelation($resource1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
    }

    /**
     * Ada is OWNER of folder A
     * Ada is OWNER of resource R1
     * Betty has READ on resource R1
     * Betty is OWNER of folder B
     * ---
     * A (Ada: O)
     * |-R1 (Ada: O, Betty: R)
     * B (Betty: O)
     * |-R1 (Ada: O, Betty: R)
     */
    public function insertFixture_FolderHavingChildrenResources2()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userBId => Permission::OWNER]);
        $resource1 = $this->addResource(['name' => 'R1'], [$userAId => Permission::OWNER, $userBId => Permission::READ]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::RESOURCE_ACO, 'foreign_id' => $resource1->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::RESOURCE_ACO, 'foreign_id' => $resource1->id, 'user_id' => $userBId, 'folder_parent_id' => $folderB->id]);

        return [$folderA, $folderB, $resource1, $userAId, $userBId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingChildrenResources2()
    {
        [$folderA, $folderB, $resource1, $userAId, $userBId] = $this->insertFixture_FolderHavingChildrenResources2();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Folder B.
        $this->assertItemIsInTrees($folderB->id, 1);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Resource 1
        $this->assertItemIsInTrees($resource1->id, 2);
        $this->assertFolderRelation($resource1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelation($resource1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
    }

    /**
     * Ada is OWNER of folder A
     * Betty is OWNER of resource R1
     * Carol is OWNER of folder A
     * Carol is OWNER of resource R1
     * ---
     * A (Ada: O, Carol: O)
     * |- R1 (Betty: O, Carol: O)
     */
    public function insertFixture_FolderHavingChildrenResources4()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userCId => Permission::OWNER]);
        $resource1 = $this->addResource(['name' => 'R1'], [$userBId => Permission::OWNER, $userCId => Permission::OWNER]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::RESOURCE_ACO, 'foreign_id' => $resource1->id, 'user_id' => $userCId, 'folder_parent_id' => $folderA->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::RESOURCE_ACO, 'foreign_id' => $resource1->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);

        return [$folderA, $resource1, $userAId, $userBId, $userCId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingChildrenResources4()
    {
        [$folderA, $resource1, $userAId, $userBId, $userCId] = $this->insertFixture_FolderHavingChildrenResources4();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 3);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        // Resource 1
        $this->assertItemIsInTrees($resource1->id, 2);
        $this->assertFolderRelation($resource1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
        $this->assertFolderRelation($resource1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userCId, $folderA->id);
    }

    /* ADD A UNIQUE ITEM - FOLDER - FOLDER HAVING PARENT(S) AND CHILDREN */

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren1_CycleDetectedWhenReconstructingParent_ParentInOperatorTree()
    {
        [$folderA, $folderB, $folderC, $userAId, $userBId, $userCId] = $this->insertFixture_Folder_HavingParentsAndChildren1();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($uac, $userCId, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderA->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderB->id);
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
        // A (Ada:O, Carol:O)
        // |- B (Ada:O, Betty:O)
        //    |- C (Betty:O, Carol:O)
        //        |- A (Ada:O, Carol:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $folderA = $this->addFolder(['name' => 'A']);
        $folderB = $this->addFolder(['name' => 'B']);
        $folderC = $this->addFolder(['name' => 'C']);
        // Folder A
        $this->addPermission('Folder', $folderA->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderA->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userCId, 'folder_parent_id' => $folderC->id]);
        // Folder B
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Folder C
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => $folderB->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userCId, 'folder_parent_id' => FoldersRelation::ROOT]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderB->id, PermissionsTable::USER_ARO, $userCId);

        return [$folderA, $folderB, $folderC, $userAId, $userBId, $userCId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren2_CycleDetectedWhenReconstructingParent_ParentInOperatorTree_CycleDetectedInParentAncestors()
    {
        [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId, $userDId] =
            $this->insertFixture_Folder_HavingParentsAndChildren2();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderC->id)];

        $this->service->addItemsToUserTree($uac, $userCId, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        // Folder B.
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, FoldersRelation::ROOT);
        // Folder C.
        $this->assertItemIsInTrees($folderC->id, 3);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        // Folder D.
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderC->id);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderC->id);
    }

    public function insertFixture_Folder_HavingParentsAndChildren2()
    {
        // Ada is OWNER of folder A
        // Carol is OWNER of folder A
        // Ada is OWNER of folder B
        // Dame is OWNER of folder B
        // Ada is OWNER of folder C
        // Betty is OWNER of folder C
        // Betty is OWNER of folder D
        // Carol is OWNER of folder D
        // Ada sees B in A
        // Ada sess C in B
        // Betty sees D in C
        // Carol sees D in A
        // ---
        // A (Ada:O, Carol:O)
        // |- B (Ada:O, Dame:O)
        //    |- C (Ada:O, Betty:O)
        //        |- D (Betty:O, Carol:O)
        //            |- A (Ada:O, Carol:O)
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
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userCId, 'folder_parent_id' => $folderD->id]);
        // Folder B
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userDId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userDId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Folder C
        $this->addPermission('Folder', $folderC->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userAId, 'folder_parent_id' => $folderB->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Folder D
        $this->addPermission('Folder', $folderD->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderD->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userBId, 'folder_parent_id' => $folderC->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userCId, 'folder_parent_id' => FoldersRelation::ROOT]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderC->id, PermissionsTable::USER_ARO, $userCId);

        return [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId, $userDId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren2_1_CycleNotDetectedWhenReconstructingParent_ParentInOperatorTree_PersonalFolderInvolved()
    {
        [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId] = $this->insertFixture_Folder_HavingParentsAndChildren2_1();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderC->id)];

        $this->service->addItemsToUserTree($uac, $userCId, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderD->id);
        // Folder B.
        $this->assertItemIsInTrees($folderB->id, 1);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        // Folder C.
        $this->assertItemIsInTrees($folderC->id, 3);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        // Folder D.
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderC->id);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderC->id);
    }

    public function insertFixture_Folder_HavingParentsAndChildren2_1()
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
        // A (Ada:O, Carol:O)
        // |- B (Ada:O)
        //    |- C (Ada:O, Betty:O)
        //        |- D (Betty:O, Carol:O)
        //            |- A (Ada:O, Carol:O)
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
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Ada sees B in A
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        // Ada sees C in B
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userAId, 'folder_parent_id' => $folderB->id]);
        // Betty sees C at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Betty sees D in C
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userBId, 'folder_parent_id' => $folderC->id]);
        // Caro sees D at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userCId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Betty sees A in D
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userCId, 'folder_parent_id' => $folderD->id]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderC->id, PermissionsTable::USER_ARO, $userCId);

        return [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren3_CycleDetectedWhenReconstructingChildren_ChildrenInOperatorTree()
    {
        [$folderA, $folderB, $folderC, $userAId, $userBId, $userCId] = $this->insertFixture_Folder_HavingParentsAndChildren3();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 3);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderC->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderC->id);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
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
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Ada sees B in A
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        // Betty sees B at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Betty sees C in B
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => $folderB->id]);
        // Caro sees C at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userCId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Betty sees C in A
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userCId, 'folder_parent_id' => $folderC->id]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderA->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $folderC, $userAId, $userBId, $userCId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren4_CycleDetectedWhenReconstructingChildren_ChildrenInOperatorTree_CycleDetectedInChildrenOfChildren()
    {
        [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId, $userDId] =
            $this->insertFixture_Folder_HavingParentsAndChildren4();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($uac, $userCId, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderA->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderB->id);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderC->id);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, FoldersRelation::ROOT);
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
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userCId, 'folder_parent_id' => $folderD->id]);
        // Folder B
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Folder C
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => $folderB->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userCId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Folder D
        $this->addPermission('Folder', $folderD->id, 'User', $userCId, Permission::OWNER);
        $this->addPermission('Folder', $folderD->id, 'User', $userDId, Permission::OWNER);
        // As both CD & DA will be in the userC tree, to ensure a similar resolution at each execution, let the algorithm preserves the oldest folder relation here C->D
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userCId, 'folder_parent_id' => $folderC->id, 'created' => '2022-01-01 00:00:00']);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userDId, 'folder_parent_id' => FoldersRelation::ROOT]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderB->id, PermissionsTable::USER_ARO, $userCId);

        return [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId, $userDId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren5_CycleDetectedWhenReconstructingParent_ParentInAnotherUserTree()
    {
        [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId, $userDId] =
            $this->insertFixture_Folder_HavingParentsAndChildren5();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 3);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderD->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderD->id);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
    }

    public function insertFixture_Folder_HavingParentsAndChildren5()
    {
        // Ada is OWNER of folder A
        // Carol is OWNER of folder A
        // Ada is OWNER of folder B
        // Dame is OWNER of folder B
        // Ada is OWNER of folder C
        // Betty is OWNER of folder C
        // Betty is OWNER of folder D
        // Carol is OWNER of folder D
        // Ada sees B in A
        // Ada sess C in B
        // Betty sees D in C
        // Carol sees D in A
        // ---
        // A (Ada:O, Carol:O)
        // |- B (Ada:O, Dame:O)
        //    |- C (Ada:O, Betty:O)
        //        |- D (Betty:O, Carol:O)
        //            |- A (Ada:O, Carol:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $userDId = UuidFactory::uuid('user.id.dame');
        $folderA = $this->addFolder(['name' => 'A']);
        $folderB = $this->addFolder(['name' => 'B']);
        $folderC = $this->addFolder(['name' => 'C']);
        $folderD = $this->addFolder(['name' => 'D']);
        $this->addPermission('Folder', $folderA->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderA->id, 'User', $userCId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userDId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderD->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderD->id, 'User', $userCId, Permission::OWNER);
        // Ada sees A at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Ada sees B in A
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        // Ada sees C in B
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userAId, 'folder_parent_id' => $folderB->id]);
        // Betty sees C at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Betty sees D in C
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userBId, 'folder_parent_id' => $folderC->id]);
        // Caro sees D at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userCId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Betty sees A in D
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userCId, 'folder_parent_id' => $folderD->id]);
        // Dame sees B at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userDId, 'folder_parent_id' => FoldersRelation::ROOT]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderA->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId, $userDId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren5_1()
    {
        [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId] =
            $this->insertFixture_Folder_HavingParentsAndChildren5_1();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 3);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderD->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderD->id);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 1);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderC->id);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
    }

    public function insertFixture_Folder_HavingParentsAndChildren5_1()
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
        // A (Ada:O, Carol:O)
        // |- B (Ada:O)
        //    |- C (Ada:O, Betty:O)
        //        |- D (Betty:O, Carol:O)
        //            |- A (Ada:O, Carol:O)
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
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Ada sees B in A
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        // Ada sees C in B
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userAId, 'folder_parent_id' => $folderB->id]);
        // Betty sees C at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Betty sees D in C
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userBId, 'folder_parent_id' => $folderC->id]);
        // Caro sees D at her root
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userCId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Betty sees A in D
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userCId, 'folder_parent_id' => $folderD->id]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderA->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren6()
    {
        [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId, $userDId] =
            $this->insertFixture_Folder_HavingParentsAndChildren6();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($uac, $userCId, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderD->id);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 4);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, $folderB->id);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
    }

    public function insertFixture_Folder_HavingParentsAndChildren6()
    {
        // Ada is OWNER of folder A
        // Carol is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Betty is OWNER of folder C
        // Dame is OWNER of folder C
        // Betty is OWNER of folder D
        // Carol is OWNER of folder D
        // Ada sees B in A
        // Betty sees C in B
        // Betty sees D in C
        // Carol sees A in D
        // ----
        // A (Ada:O, Carol:O)
        // |- B (Ada:O, Betty:O)
        //     |- C (Betty:O, Dame:O)
        //         |- D (Betty:O, Carol:O)
        //             |- A (Ada:O, Carol:O)
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
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userCId, 'folder_parent_id' => $folderD->id]);
        // Folder B
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userDId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userDId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Folder C
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userDId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => $folderB->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userDId, 'folder_parent_id' => $folderB->id]);
        // Folder D
        $this->addPermission('Folder', $folderD->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderD->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userBId, 'folder_parent_id' => $folderC->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userCId, 'folder_parent_id' => FoldersRelation::ROOT]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderB->id, PermissionsTable::USER_ARO, $userCId);

        return [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId, $userDId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren6_1()
    {
        [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId] =
            $this->insertFixture_Folder_HavingParentsAndChildren6_1();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($uac, $userCId, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderD->id);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderA->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 1);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderB->id);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderC->id);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
    }

    public function insertFixture_Folder_HavingParentsAndChildren6_1()
    {
        // Ada is OWNER of folder A
        // Carol is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Betty is OWNER of folder C
        // Betty is OWNER of folder D
        // Carol is OWNER of folder D
        // Ada sees B in A
        // Betty sees C in B
        // Betty sees D in C
        // Carol sees A in D
        // -----
        // A (Ada:O, Carol:O)
        // |- B (Ada:O, Betty:O)
        //     |- C (Betty:O)
        //         |- D (Betty:O, Carol:O)
        //             |- A (Ada:O, Carol:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $folderA = $this->addFolder(['name' => 'A']);
        $folderB = $this->addFolder(['name' => 'B']);
        $folderC = $this->addFolder(['name' => 'C']);
        $folderD = $this->addFolder(['name' => 'D']);

        // Folder A
        $this->addPermission('Folder', $folderA->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderA->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userCId, 'folder_parent_id' => $folderD->id]);
        // Folder B
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Folder C
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => $folderB->id]);
        // Folder D
        $this->addPermission('Folder', $folderD->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderD->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userBId, 'folder_parent_id' => $folderC->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userCId, 'folder_parent_id' => FoldersRelation::ROOT]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderB->id, PermissionsTable::USER_ARO, $userCId);

        return [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren7()
    {
        [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId, $userDId, $userEId, $userFId] =
            $this->insertFixture_Folder_HavingParentsAndChildren7();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 5);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderD->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderD->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userFId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userEId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userFId, $folderA->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, FoldersRelation::ROOT);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderC->id);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
    }

    public function insertFixture_Folder_HavingParentsAndChildren7()
    {
        // ----
        // A (Ada:O, Carol:O, Dame:O, Frances:O)
        // |- B (Dame:O, Edith:O, Frances:O)
        //     |- C (Betty:O, Dame:O)
        //         |- D (Betty:O, Carol:O)
        //             |- A (Ada:O, Carol:O, Dame:O, Frances:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $userDId = UuidFactory::uuid('user.id.dame');
        $userEId = UuidFactory::uuid('user.id.edith');
        $userFId = UuidFactory::uuid('user.id.frances');
        $folderA = $this->addFolder(['name' => 'A']);
        $folderB = $this->addFolder(['name' => 'B']);
        $folderC = $this->addFolder(['name' => 'C']);
        $folderD = $this->addFolder(['name' => 'D']);

        // Folder A
        $this->addPermission('Folder', $folderA->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderA->id, 'User', $userCId, Permission::OWNER);
        $this->addPermission('Folder', $folderA->id, 'User', $userDId, Permission::OWNER);
        $this->addPermission('Folder', $folderA->id, 'User', $userFId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userCId, 'folder_parent_id' => $folderD->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userDId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userFId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Folder B
        $this->addPermission('Folder', $folderB->id, 'User', $userDId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userEId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userFId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userDId, 'folder_parent_id' => $folderA->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userEId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userFId, 'folder_parent_id' => $folderA->id]);
        // Folder C
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userDId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userDId, 'folder_parent_id' => $folderB->id]);
        // Folder D
        $this->addPermission('Folder', $folderD->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderD->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userBId, 'folder_parent_id' => $folderC->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userCId, 'folder_parent_id' => FoldersRelation::ROOT]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderA->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId, $userDId, $userEId, $userFId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren8_SCCBetweenTargetUserAndAnotherOne_SolveWithUsagePriority()
    {
        [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId, $userDId, $userEId, $userFId] =
            $this->insertFixture_Folder_HavingParentsAndChildren8();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 5);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderC->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, $folderC->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userFId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 3);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userEId, FoldersRelation::ROOT);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 3);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderA->id);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userEId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userFId, $folderA->id);
    }

    public function insertFixture_Folder_HavingParentsAndChildren8()
    {
        // ----
        // A (Ada:O, Carol:O, Dame:O, Frances:O)
        // |- D (Carol:O, Edith:O, Frances:O)
        //     |- B (Betty:O, Carol:O)
        //         |- C (Betty:O, Dame:O, Edith:O)
        //             |- A (Ada:O, Carol:O, Dame:O, Frances:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $userDId = UuidFactory::uuid('user.id.dame');
        $userEId = UuidFactory::uuid('user.id.edith');
        $userFId = UuidFactory::uuid('user.id.frances');
        $folderA = $this->addFolder(['name' => 'A']);
        $folderB = $this->addFolder(['name' => 'B']);
        $folderC = $this->addFolder(['name' => 'C']);
        $folderD = $this->addFolder(['name' => 'D']);

        // Folder A
        $this->addPermission('Folder', $folderA->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderA->id, 'User', $userCId, Permission::OWNER);
        $this->addPermission('Folder', $folderA->id, 'User', $userDId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userCId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userDId, 'folder_parent_id' => $folderC->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userFId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Folder B
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userCId, 'folder_parent_id' => $folderD->id]);
        // Folder C
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userDId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userEId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => $folderB->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userDId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userEId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Folder D
        $this->addPermission('Folder', $folderD->id, 'User', $userCId, Permission::OWNER);
        $this->addPermission('Folder', $folderD->id, 'User', $userEId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userCId, 'folder_parent_id' => $folderA->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userEId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userFId, 'folder_parent_id' => $folderA->id]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderA->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId, $userDId, $userEId, $userFId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren9_SolveCycleInTargetUserTreeInvolvingPersonalFolder()
    {
        [$folderA, $folderB, $folderC, $folderP, $userAId, $userBId, $userCId] =
            $this->insertFixture_Folder_HavingParentsAndChildren9();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderB->id);
        // Folder P
        $this->assertItemIsInTrees($folderP->id, 1);
        $this->assertFolderRelation($folderP->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderC->id);
    }

    public function insertFixture_Folder_HavingParentsAndChildren9()
    {
        // ----
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Carol:O)
        //     |- C (Ada:O, Betty:O)
        //         |- P (Betty:O)
        //             |- A (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $folderA = $this->addFolder(['name' => 'A']);
        $folderB = $this->addFolder(['name' => 'B']);
        $folderC = $this->addFolder(['name' => 'C']);
        $folderP = $this->addFolderFor(['name' => 'P', 'folder_parent_id' => $folderC->id], [$userBId => Permission::OWNER]);

        // Folder A
        $this->addPermission('Folder', $folderA->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderA->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userBId, 'folder_parent_id' => $folderP->id]);
        // Folder B
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userCId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Folder C
        $this->addPermission('Folder', $folderC->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userAId, 'folder_parent_id' => $folderB->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderB->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $folderB, $folderC, $folderP, $userAId, $userBId, $userCId];
    }

    /* ADD A UNIQUE ITEM - RESOURCE - RESOURCE NO PARENT */

    public function testAddItemsToUserTreeSuccess_OneItem_Resource_NotParent1()
    {
        [$r1, $userAId, $userBId] = $this->insertFixture_OneItem_Resource_NotParent1();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        $this->assertItemIsInTrees($r1->id, 2);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, FoldersRelation::ROOT);
    }

    public function insertFixture_OneItem_Resource_NotParent1()
    {
        // Ada is OWNER of resource R1
        // R1 (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->get('id'), PermissionsTable::USER_ARO, $userBId);

        return [$r1, $userAId, $userBId];
    }

    /* ADD A UNIQUE ITEM - RESOURCE - RESOURCE HAVING A PARENT */

    public function testAddItemsToUserTreeSuccess_OneItem_Resource_HavingOneParent1_VisibleParentInOperatorTree()
    {
        [$folderA, $r1, $userAId, $userBId] = $this->insertFixture_OneItem_Resource_HavingOneParent1();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Resource R1
        $this->assertItemIsInTrees($r1->id, 2);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
    }

    public function insertFixture_OneItem_Resource_HavingOneParent1()
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
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->get('id'), PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $r1, $userAId, $userBId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Resource_HavingOneParent1_1_VisibleParentInOperatorTree_OperatorReadOnParent()
    {
        [$folderA, $r1, $userAId, $userBId] = $this->insertFixture_OneItem_Resource_HavingOneParent1_1();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Resource R1
        $this->assertItemIsInTrees($r1->id, 2);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
    }

    public function insertFixture_OneItem_Resource_HavingOneParent1_1()
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
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->get('id'), PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $r1, $userAId, $userBId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Resource_HavingOneParent2_NotVisibleParentInOperatorTree()
    {
        [$folderA, $r1, $userAId, $userBId] = $this->insertFixture_OneItem_Resource_HavingOneParent2();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        // Resource R1
        $this->assertItemIsInTrees($r1->id, 2);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, FoldersRelation::ROOT);
    }

    public function insertFixture_OneItem_Resource_HavingOneParent2()
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
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->get('id'), PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $r1, $userAId, $userBId];
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Resource_HavingOneParent3_VisibleParentInOtherUserTree()
    {
        [$folderA, $r1, $userAId, $userBId, $userCId] = $this->insertFixture_OneItem_Resource_HavingOneParent3();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        // Resource R1
        $this->assertItemIsInTrees($r1->id, 3);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userCId, $folderA->id);
    }

    public function insertFixture_OneItem_Resource_HavingOneParent3()
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
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE, 'foreign_id' => $r1->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE, 'foreign_id' => $r1->id, 'user_id' => $userCId, 'folder_parent_id' => $folderA->get('id')]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $r1, $userAId, $userBId, $userCId];
    }

    /* ADD A UNIQUE ITEM - RESOURCE - RESOURCE HAVING MULTIPLE PARENT */

    public function testAddItemsToUserTreeSuccess_OneItem_Resource_HavingMultipleParents1_MultipleVisibleParents_OneParentInOperatorTree()
    {
        [$folderA, $r1, $folderC, $userAId, $userBId, $userCId] = $this->insertFixture_OneItem_Resource_HavingMultipleParents1();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->id)];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Resource R1
        $this->assertItemIsInTrees($r1->id, 3);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userCId, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
    }

    public function insertFixture_OneItem_Resource_HavingMultipleParents1()
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
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE, 'foreign_id' => $r1->get('id'), 'user_id' => $userAId, 'folder_parent_id' => $folderA->get('id')]);
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE, 'foreign_id' => $r1->get('id'), 'user_id' => $userCId, 'folder_parent_id' => $folderC->get('id')]);

        // Add a permission for the user the folder will be added in the tree.
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userBId);

        return [$folderA, $r1, $folderC, $userAId, $userBId, $userCId];
    }

    /* ADD MULTIPLE ITEMS - MULTIPLE RESOURCES/FOLDERS - */

    public function testAddItemsToUserTreeSuccess_MultipleItems_FolderResource_ItemsInEachOthers()
    {
        [$folderA, $folderB, $folderC, $folderD, $folderE, $folderF, $r1, $r2, $r3, $r4, $r5, $userAId] =
            $this->insertFixture_MultipleItems_Folder_FoldersInEachOthers();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userBId = UuidFactory::uuid('user.id.betty');
        $items = [
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderC->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderD->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderE->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderF->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r2->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r3->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r4->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r5->id),
        ];

        $this->service->addItemsToUserTree($uac, $userBId, $items);

        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId);
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderB->id);
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId);
        $this->assertItemIsInTrees($folderE->id, 2);
        $this->assertFolderRelation($folderE->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderD->id);
        $this->assertFolderRelation($folderE->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderD->id);
        $this->assertItemIsInTrees($folderF->id, 2);
        $this->assertFolderRelation($folderF->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderE->id);
        $this->assertFolderRelation($folderF->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderE->id);

        $this->assertItemIsInTrees($r1->id, 2);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId);
        $this->assertItemIsInTrees($r2->id, 2);
        $this->assertFolderRelation($r2->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelation($r2->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
        $this->assertItemIsInTrees($r3->id, 2);
        $this->assertFolderRelation($r3->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderB->id);
        $this->assertFolderRelation($r3->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderB->id);
        $this->assertItemIsInTrees($r4->id, 2);
        $this->assertFolderRelation($r4->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderD->id);
        $this->assertFolderRelation($r4->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderD->id);
        $this->assertItemIsInTrees($r5->id, 2);
        $this->assertFolderRelation($r5->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderE->id);
        $this->assertFolderRelation($r5->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderE->id);
    }

    public function insertFixture_MultipleItems_Folder_FoldersInEachOthers()
    {
        // Ada is OWNER of all resources and folders
        // A (Ada:O)
        // |- B (Ada:O)
        //   |- R2 (Ada:O)
        //   |-C (Ada:O)
        //     |- R3 (Ada:O)
        // D (Ada:O)
        // |- E (Ada:O)
        //   |- R4 (Ada:O)
        //   |-F (Ada:O)
        //     |- R5 (Ada:O)
        // R1 (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER]);

        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C', 'folder_parent_id' => $folderB->id], [$userAId => Permission::OWNER]);
        $r2 = $this->addResourceFor(['name' => 'R2', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER]);
        $r3 = $this->addResourceFor(['name' => 'R3', 'folder_parent_id' => $folderB->id], [$userAId => Permission::OWNER]);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER, 'foreign_id' => $folderB->get('id'), 'user_id' => $userAId, 'folder_parent_id' => $folderA->get('id')]);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE, 'foreign_id' => $r1->get('id'), 'user_id' => $userAId, 'folder_parent_id' => $folderA->get('id')]);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER, 'foreign_id' => $folderC->get('id'), 'user_id' => $userAId, 'folder_parent_id' => $folderB->get('id')]);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE, 'foreign_id' => $r2->get('id'), 'user_id' => $userAId, 'folder_parent_id' => $folderB->get('id')]);

        $folderD = $this->addFolderFor(['name' => 'D'], [$userAId => Permission::OWNER]);
        $folderE = $this->addFolderFor(['name' => 'E', 'folder_parent_id' => $folderD->id], [$userAId => Permission::OWNER]);
        $folderF = $this->addFolderFor(['name' => 'F', 'folder_parent_id' => $folderE->id], [$userAId => Permission::OWNER]);
        $r4 = $this->addResourceFor(['name' => 'R4', 'folder_parent_id' => $folderD->id], [$userAId => Permission::OWNER]);
        $r5 = $this->addResourceFor(['name' => 'R5', 'folder_parent_id' => $folderE->id], [$userAId => Permission::OWNER]);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER, 'foreign_id' => $folderE->get('id'), 'user_id' => $userAId, 'folder_parent_id' => $folderD->get('id')]);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE, 'foreign_id' => $r3->get('id'), 'user_id' => $userAId, 'folder_parent_id' => $folderD->get('id')]);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER, 'foreign_id' => $folderF->get('id'), 'user_id' => $userAId, 'folder_parent_id' => $folderE->get('id')]);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE, 'foreign_id' => $r4->get('id'), 'user_id' => $userAId, 'folder_parent_id' => $folderE->get('id')]);

        return [$folderA, $folderB, $folderC, $folderD, $folderE, $folderF, $r1, $r2, $r3, $r4, $r5, $userAId];
    }
}
