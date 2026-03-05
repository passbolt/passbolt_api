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
use App\Test\Factory\UserFactory;
use Passbolt\Folders\Model\Dto\FolderRelationDto;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsAddItemsToUserTreeService;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\FoldersRelationFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;

/**
 * Passbolt\Folders\Service\FoldersRelations\FoldersRelationsAddItemsToUserTreeService Test Case
 *
 * @covers \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsAddItemsToUserTreeService
 */
class FoldersRelationsAddItemsToUserTreeServiceTest extends FoldersTestCase
{
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
        [$userA, $userB] = UserFactory::make(2)->persist();
        $items = [];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        $this->assertSame(0, FoldersRelationFactory::count());
    }

    /* ADD A UNIQUE ITEM - FOLDER - FOLDER NO PARENT NO CHILD */

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_NoParentNoChild()
    {
        // Ada is OWNER of folder A
        // A (Ada:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA, $userB])->withFoldersRelationsFor([$userA])->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id);
    }

    /* ADD A UNIQUE ITEM - FOLDER - FOLDER HAVING A PARENT */

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingOneParent1_ParentVisibleInOperatorTree()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Add sees B in A
        // ---
        // A (Ada:O, Betty:O)
        // |- B (Ada:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderA->id);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingOneParent2_ParentNotVisibleInOperatorTree()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of folder B
        // Add sees B in A
        // ---
        // A (Ada:O)
        // |- B (Ada:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Assert Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        // Assert Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingOneParent3_ParentVisibleInOtherUserTree()
    {
        // Betty is OWNER of folder A
        // Carol is OWNER of folder A
        // Ada is OWNER of folder B
        // Carol is OWNER of folder B
        // Carol sees B in A
        // ---
        // A (Betty:O, Carol:O)
        // |- B (Ada:O, Carol:O)
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userB, $userC])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC])
            ->withFoldersRelationsFor([$userC], $folderA)
            ->withFoldersRelationsFor([$userA])->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderA->id);
    }

    /* ADD A UNIQUE ITEM - FOLDER - FOLDER HAVING MULTIPLE PARENT */

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingMultipleParents1_PriorityToTheOperatorParent()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Carol is OWNER of folder B
        // Ada is OWNER of folder B
        // Betty is OWNER of folder C
        // Carol is OWNER of folder C
        // Ada sees B in A
        // Carol sees B in C
        // ---
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Carol:O)
        //
        // C (Betty:O, Carol:O)
        // |- B (Ada:O, Carol:O)
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userB, $userC])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userC], $folderC)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingMultipleParents2_NoParentInOperatorTree_PriorityToTheMostUsed()
    {
        // Betty is OWNER of folder A
        // Carol is OWNER of folder A
        // Ada is OWNER of folder B
        // Carol is OWNER of folder B
        // Dame is OWNER of folder B
        // Edith is OWNER of folder B
        // Betty is OWNER of folder C
        // Dame is OWNER of folder C
        // Edith is OWNER of folder C
        // Carol sees B in A
        // Dame sees B in C
        // Edit sees B in C
        // A is older than C
        // ---
        // A (Betty:O, Carol:O)
        // |- B (Ada:O, Carol:O, Dame:O, Edith:O)
        // C (Betty:O, Dame:O, Edith:O)
        // |- B (Ada:O, Carol:O, Dame:O, Edith:O)
        [$userA, $userB, $userC, $userD, $userE] = UserFactory::make(5)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userB, $userC])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userB, $userD, $userE])
            ->withFoldersRelationsFor([$userB, $userD, $userE])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()->withPermissionsFor([$userA, $userB, $userC, $userD, $userE])
            ->withFoldersRelationsFor([$userA])
            ->withFoldersRelationsFor([$userC], $folderA)
            ->withFoldersRelationsFor([$userD, $userE], $folderC)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 5);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderC->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userD->id, $folderC->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userE->id, $folderC->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 3);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userD->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userE->id, FoldersRelation::ROOT);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingMultipleParents3_NoParentInOperatorTree_PriorityToTheOldestParent()
    {
        // Betty is OWNER of folder A
        // Carol is OWNER of folder A
        // Ada is OWNER of folder B
        // Carol is OWNER of folder B
        // Dame is OWNER of folder B
        // Betty is OWNER of folder C
        // Dame is OWNER of folder C
        // Carol sees B in A
        // Dame sees B in C
        // ---
        // A (Betty:O, Carol:O)
        // |- B (Ada:O, Carol:O, Dame:O)
        // C (Betty:O, Dame:O)
        // |- B (Ada:O, Carol:O, Dame:O)
        [$userA, $userB, $userC, $userD] = UserFactory::make(4)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userB, $userC])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userB, $userD])
            ->withFoldersRelationsFor([$userB, $userD])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC, $userD])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        // To ensure a deterministic resolution, the oldest folder relation should be B in A for userC.
        FoldersRelationFactory::make()->user($userC)->foreignModelFolder($folderB)->folderParent($folderA)
            ->patchData(['created' => '2022-01-01 00:00:00'])->persist();
        FoldersRelationFactory::make()->user($userD)->foreignModelFolder($folderB)->folderParent($folderC)
            ->patchData(['created' => '2024-01-01 00:00:00'])->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 4);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userD->id, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userD->id, FoldersRelation::ROOT);
    }

    /* ADD A UNIQUE ITEM - FOLDER - FOLDER HAVING CHILDREN */

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingChildren1_ChildInOperatorTree()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Ada sees B in A
        // ---
        // A (Ada:O)
        // |- B (Ada:O, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA, $userB])->withFoldersRelationsFor([$userA])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userB])
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderA->id);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingChildren2_ChildInOperatorTree_ChildAlreadyOrganizedInTargetUserTree()
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
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA, $userB])->withFoldersRelationsFor([$userA])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()->withPermissionsFor([$userB])->withFoldersRelationsFor([$userB])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userB], $folderC)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderA->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 1);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingChildren3_VisibleChildInOtherUsersTrees()
    {
        // Ada is OWNER of folder A
        // Carol is OWNER of folder A
        // Betty is OWNER of folder B
        // Carol is OWNER of folder B
        // Carol sees B in A
        // ---
        // A (Ada:O, Carol:O)
        // |- B (Betty:O, Carol:O)
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA, $userB, $userC])->withFoldersRelationsFor([$userA, $userC])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userB])
            ->withFoldersRelationsFor([$userC], $folderA)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 3);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderA->id);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingChildrenResources1()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of resource R1
        // Betty has OWNER on resource R1
        // ---
        // A (Ada: O)
        // |-R1 (Ada: O, Betty: O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA])->persist();
        $resource1 = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userB])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Resource 1
        $this->assertItemIsInTrees($resource1->get('id'), 2);
        $this->assertFolderRelation($resource1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderA->id);
        $this->assertFolderRelation($resource1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, $folderA->id);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingChildrenResources2()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of resource R1
        // Betty has READ on resource R1
        // Betty is OWNER of folder B
        // ---
        // A (Ada: O)
        // |-R1 (Ada: O, Betty: R)
        // B (Betty: O)
        // |-R1 (Ada: O, Betty: R)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()->withPermissionsFor([$userB])->withFoldersRelationsFor([$userB])->persist();
        $resource1 = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userB], $folderB)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder B.
        $this->assertItemIsInTrees($folderB->id, 1);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Resource 1
        $this->assertItemIsInTrees($resource1->get('id'), 2);
        $this->assertFolderRelation($resource1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderA->id);
        $this->assertFolderRelation($resource1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, $folderA->id);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingChildrenResources4()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of resource R1
        // Carol is OWNER of folder A
        // Carol is OWNER of resource R1
        // ---
        // A (Ada: O, Carol: O)
        // |- R1 (Betty: O, Carol: O)
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userC])
            ->withFoldersRelationsFor([$userA, $userC])
            ->persist();
        $resource1 = ResourceFactory::make()
            ->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userC], $folderA)
            ->withFoldersRelationsFor([$userB])
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 3);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        // Resource 1
        $this->assertItemIsInTrees($resource1->get('id'), 2);
        $this->assertFolderRelation($resource1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, $folderA->id);
        $this->assertFolderRelation($resource1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userC->id, $folderA->id);
    }

    /* ADD A UNIQUE ITEM - FOLDER - FOLDER HAVING PARENT(S) AND CHILDREN */

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren1_CycleDetectedWhenReconstructingParent_ParentInOperatorTree()
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
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userC])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()->withPermissionsFor([$userA, $userB, $userC])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userB])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userB], $folderB)
            ->withFoldersRelationsFor([$userC])
            ->persist();
        FoldersRelationFactory::make()->user($userC)
            ->foreignModelFolder($folderA)->folderParent($folderC)->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userC->id, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderA->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderB->id);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren2_CycleDetectedWhenReconstructingParent_ParentInOperatorTree_CycleDetectedInParentAncestors()
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
        // Carol sees A in D
        // ---
        // A (Ada:O, Carol:O)
        // |- B (Ada:O, Dame:O)
        //    |- C (Ada:O, Betty:O)
        //        |- D (Betty:O, Carol:O)
        //            |- A (Ada:O, Carol:O)
        [$userA, $userB, $userC, $userD] = UserFactory::make(4)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userC])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userD])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userD])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC])
            ->withFoldersRelationsFor([$userA], $folderB)
            ->withFoldersRelationsFor([$userB])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderD */
        $folderD = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userB], $folderC)
            ->withFoldersRelationsFor([$userC])
            ->persist();
        FoldersRelationFactory::make()
            ->user($userC)
            ->foreignModelFolder($folderA)
            ->folderParent($folderD)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderC->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userC->id, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        // Folder B.
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userD->id, FoldersRelation::ROOT);
        // Folder C.
        $this->assertItemIsInTrees($folderC->id, 3);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        // Folder D.
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderC->id);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderC->id);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren2_1_CycleNotDetectedWhenReconstructingParent_ParentInOperatorTree_PersonalFolderInvolved()
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
        // Carol sees A in D
        // ---
        // A (Ada:O, Carol:O)
        // |- B (Ada:O)
        //    |- C (Ada:O, Betty:O)
        //        |- D (Betty:O, Carol:O)
        //            |- A (Ada:O, Carol:O)
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userC])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC])
            ->withFoldersRelationsFor([$userA], $folderB)
            ->withFoldersRelationsFor([$userB])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderD */
        $folderD = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userB], $folderC)
            ->withFoldersRelationsFor([$userC])
            ->persist();
        FoldersRelationFactory::make()
            ->user($userC)
            ->foreignModelFolder($folderA)
            ->folderParent($folderD)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderC->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userC->id, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderD->id);
        // Folder B.
        $this->assertItemIsInTrees($folderB->id, 1);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        // Folder C.
        $this->assertItemIsInTrees($folderC->id, 3);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        // Folder D.
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderC->id);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderC->id);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren3_CycleDetectedWhenReconstructingChildren_ChildrenInOperatorTree()
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
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userB])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userB], $folderB)
            ->withFoldersRelationsFor([$userC])
            ->persist();
        FoldersRelationFactory::make()
            ->user($userC)
            ->foreignModelFolder($folderA)
            ->folderParent($folderC)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 3);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderC->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderC->id);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderA->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren4_CycleDetectedWhenReconstructingChildren_ChildrenInOperatorTree_CycleDetectedInChildrenOfChildren()
    {
        //  Ada is OWNER of folder A
        //  Carol is OWNER of folder A
        //  Ada is OWNER of folder B
        //  Betty is OWNER of folder B
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
        [$userA, $userB, $userC, $userD] = UserFactory::make(4)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userC])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userB])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userB], $folderB)
            ->withFoldersRelationsFor([$userC])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderD */
        $folderD = FolderFactory::make()
            ->withPermissionsFor([$userC, $userD])
            ->withFoldersRelationsFor([$userD])
            ->persist();
        // As both CD & DA will be in the userC tree, to ensure a similar resolution at each
        // execution, let the algorithm preserve the oldest folder relation here C->D.
        FoldersRelationFactory::make()
            ->user($userC)
            ->foreignModelFolder($folderD)
            ->folderParent($folderC)
            ->patchData(['created' => '2022-01-01 00:00:00'])
            ->persist();
        FoldersRelationFactory::make()
            ->user($userC)
            ->foreignModelFolder($folderA)
            ->folderParent($folderD)
            ->patchData(['created' => '2024-01-01 00:00:00'])
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userC->id, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderA->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderB->id);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderC->id);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userD->id, FoldersRelation::ROOT);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren5_CycleDetectedWhenReconstructingParent_ParentInAnotherUserTree()
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
        [$userA, $userB, $userC, $userD] = UserFactory::make(4)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userD])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userD])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA], $folderB)
            ->withFoldersRelationsFor([$userB])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderD */
        $folderD = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userB], $folderC)
            ->withFoldersRelationsFor([$userC])
            ->persist();
        FoldersRelationFactory::make()
            ->user($userC)
            ->foreignModelFolder($folderA)
            ->folderParent($folderD)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 3);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderD->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderD->id);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userD->id, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren5_1()
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
        // Carol sees A in D
        // ---
        // A (Ada:O, Carol:O)
        // |- B (Ada:O)
        //    |- C (Ada:O, Betty:O)
        //        |- D (Betty:O, Carol:O)
        //            |- A (Ada:O, Carol:O)
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA], $folderB)
            ->withFoldersRelationsFor([$userB])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderD */
        $folderD = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userB], $folderC)
            ->withFoldersRelationsFor([$userC])
            ->persist();
        FoldersRelationFactory::make()
            ->user($userC)
            ->foreignModelFolder($folderA)
            ->folderParent($folderD)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 3);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderD->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderD->id);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 1);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderC->id);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren6()
    {
        // Ada is OWNER of folder A
        // Carol is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Dame is OWNER of folder B
        // Betty is OWNER of folder C
        // Dame is OWNER of folder C
        // Betty is OWNER of folder D
        // Carol is OWNER of folder D
        // Ada sees B in A
        // Betty sees C in B
        // Dame sees C in B
        // Betty sees D in C
        // Carol sees A in D
        // ----
        // A (Ada:O, Carol:O)
        // |- B (Ada:O, Betty:O, Dame:O)
        //     |- C (Betty:O, Dame:O)
        //         |- D (Betty:O, Carol:O)
        //             |- A (Ada:O, Carol:O)
        [$userA, $userB, $userC, $userD] = UserFactory::make(4)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userC])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC, $userD])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userB, $userD])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userB, $userD])
            ->withFoldersRelationsFor([$userB, $userD], $folderB)
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderD */
        $folderD = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userB], $folderC)
            ->withFoldersRelationsFor([$userC])
            ->persist();
        FoldersRelationFactory::make()
            ->user($userC)
            ->foreignModelFolder($folderA)
            ->folderParent($folderD)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userC->id, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderD->id);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 4);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userD->id, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userD->id, $folderB->id);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren6_1()
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
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userC])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userB])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userB], $folderB)
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderD */
        $folderD = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userB], $folderC)
            ->withFoldersRelationsFor([$userC])
            ->persist();
        FoldersRelationFactory::make()
            ->user($userC)
            ->foreignModelFolder($folderA)
            ->folderParent($folderD)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userC->id, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderD->id);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderA->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 1);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderB->id);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderC->id);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren7()
    {
        // ----
        // A (Ada:O, Carol:O, Dame:O, Frances:O)
        // |- B (Dame:O, Edith:O, Frances:O)
        //     |- C (Betty:O, Dame:O)
        //         |- D (Betty:O, Carol:O)
        //             |- A (Ada:O, Carol:O, Dame:O, Frances:O)
        [$userA, $userB, $userC, $userD, $userE, $userF] = UserFactory::make(6)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC, $userD, $userF])
            ->withFoldersRelationsFor([$userA, $userD, $userF])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userD, $userE, $userF])
            ->withFoldersRelationsFor([$userD, $userF], $folderA)
            ->withFoldersRelationsFor([$userE])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userB, $userD])
            ->withFoldersRelationsFor([$userB])
            ->withFoldersRelationsFor([$userD], $folderB)
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderD */
        $folderD = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userB], $folderC)
            ->withFoldersRelationsFor([$userC])
            ->persist();
        FoldersRelationFactory::make()
            ->user($userC)
            ->foreignModelFolder($folderA)
            ->folderParent($folderD)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 5);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderD->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderD->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userD->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userF->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userD->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userE->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userF->id, $folderA->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userD->id, FoldersRelation::ROOT);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderC->id);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren8_SCCBetweenTargetUserAndAnotherOne_SolveWithUsagePriority()
    {
        // ----
        // A (Ada:O, Carol:O, Dame:O, Frances:O)
        // |- D (Carol:O, Edith:O, Frances:O)
        //     |- B (Betty:O, Carol:O)
        //         |- C (Betty:O, Dame:O, Edith:O)
        //             |- A (Ada:O, Carol:O, Dame:O, Frances:O)
        [$userA, $userB, $userC, $userD, $userE, $userF] = UserFactory::make(6)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC, $userD])
            ->withFoldersRelationsFor([$userA, $userC, $userF])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderD */
        $folderD = FolderFactory::make()
            ->withPermissionsFor([$userC, $userE])
            ->withFoldersRelationsFor([$userC, $userF], $folderA)
            ->withFoldersRelationsFor([$userE])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userB])
            ->withFoldersRelationsFor([$userC], $folderD)
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userB, $userD, $userE])
            ->withFoldersRelationsFor([$userB], $folderB)
            ->withFoldersRelationsFor([$userD, $userE])
            ->persist();
        FoldersRelationFactory::make()
            ->user($userD)
            ->foreignModelFolder($folderA)
            ->folderParent($folderC)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 5);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderC->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userD->id, $folderC->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userF->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 3);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userD->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userE->id, FoldersRelation::ROOT);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 3);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderA->id);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userE->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userF->id, $folderA->id);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Folder_HavingParentsAndChildren9_SolveCycleInTargetUserTreeInvolvingPersonalFolder()
    {
        // ----
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Carol:O)
        //     |- C (Ada:O, Betty:O)
        //         |- P (Betty:O)
        //             |- A (Ada:O, Betty:O)
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userC])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA], $folderB)
            ->withFoldersRelationsFor([$userB])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderP */
        $folderP = FolderFactory::make()
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userB], $folderC)
            ->persist();
        FoldersRelationFactory::make()
            ->user($userB)
            ->foreignModelFolder($folderA)
            ->folderParent($folderP)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id)];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderB->id);
        // Folder P
        $this->assertItemIsInTrees($folderP->id, 1);
        $this->assertFolderRelation($folderP->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderC->id);
    }

    /* ADD A UNIQUE ITEM - RESOURCE - RESOURCE NO PARENT */

    public function testAddItemsToUserTreeSuccess_OneItem_Resource_NotParent1()
    {
        // Ada is OWNER of resource R1
        // R1 (Ada:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$userA, $userB])->withFoldersRelationsFor([$userA])->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->get('id'))];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        $this->assertItemIsInTrees($r1->get('id'), 2);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, FoldersRelation::ROOT);
    }

    /* ADD A UNIQUE ITEM - RESOURCE - RESOURCE HAVING A PARENT */

    public function testAddItemsToUserTreeSuccess_OneItem_Resource_HavingOneParent1_VisibleParentInOperatorTree()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of resource R1
        // Add sees R1 in A
        // ---
        // A (Ada:O, Betty:O)
        // |- R1 (Ada:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $r1 = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->get('id'))];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Resource R1
        $this->assertItemIsInTrees($r1->get('id'), 2);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderA->id);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, $folderA->id);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Resource_HavingOneParent1_1_VisibleParentInOperatorTree_OperatorReadOnParent()
    {
        // Ada has READ on folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of resource R1
        // Add sees R1 in A
        // ---
        // A (Ada:R, Betty:O)
        // |- R1 (Ada:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $r1 = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->get('id'))];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Resource R1
        $this->assertItemIsInTrees($r1->get('id'), 2);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderA->id);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, $folderA->id);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Resource_HavingOneParent2_NotVisibleParentInOperatorTree()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of resource R1
        // Ada sees R1 in A
        // ---
        // A (Ada:O)
        // |- R1 (Ada:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $r1 = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->get('id'))];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        // Resource R1
        $this->assertItemIsInTrees($r1->get('id'), 2);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderA->id);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, FoldersRelation::ROOT);
    }

    public function testAddItemsToUserTreeSuccess_OneItem_Resource_HavingOneParent3_VisibleParentInOtherUserTree()
    {
        // Betty is OWNER of folder A
        // Carol is OWNER of folder A
        // Ada is OWNER of resource R1
        // Carol is OWNER of resource R1
        // Carol sees R1 in A
        // ---
        // A (Betty:O, Carol:O)
        // |- R1 (Ada:O, Carol:O)
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userB, $userC])
            ->persist();
        $r1 = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC])
            ->withFoldersRelationsFor([$userA])
            ->withFoldersRelationsFor([$userC], $folderA)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->get('id'))];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        // Resource R1
        $this->assertItemIsInTrees($r1->get('id'), 3);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, $folderA->id);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userC->id, $folderA->id);
    }

    /* ADD A UNIQUE ITEM - RESOURCE - RESOURCE HAVING MULTIPLE PARENT */

    public function testAddItemsToUserTreeSuccess_OneItem_Resource_HavingMultipleParents1_MultipleVisibleParents_OneParentInOperatorTree()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Betty is OWNER of resource R1
        // Carol is OWNER of resource R1
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
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC])
            ->withFoldersRelationsFor([$userB, $userC])
            ->persist();
        $r1 = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userC], $folderC)
            ->persist();

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->get('id'))];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Resource R1
        $this->assertItemIsInTrees($r1->get('id'), 3);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderA->id);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, $folderA->id);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userC->id, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
    }

    /* ADD MULTIPLE ITEMS - MULTIPLE RESOURCES/FOLDERS - */

    public function testAddItemsToUserTreeSuccess_MultipleItems_FolderResource_ItemsInEachOthers()
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
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderB)
            ->persist();
        $r1 = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $r2 = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();
        $r3 = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderB)
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderD */
        $folderD = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderE */
        $folderE = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderD)
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderF */
        $folderF = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderE)
            ->persist();
        $r4 = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderD)
            ->persist();
        $r5 = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderE)
            ->persist();

        $items = [
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderC->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderD->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderE->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderF->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->get('id')),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r2->get('id')),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r3->get('id')),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r4->get('id')),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r5->get('id')),
        ];

        $this->service->addItemsToUserTree($this->makeUac($userA), $userB->id, $items);

        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id);
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderA->id);
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderB->id);
        $this->assertItemIsInTrees($folderD->id, 2);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id);
        $this->assertItemIsInTrees($folderE->id, 2);
        $this->assertFolderRelation($folderE->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderD->id);
        $this->assertFolderRelation($folderE->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderD->id);
        $this->assertItemIsInTrees($folderF->id, 2);
        $this->assertFolderRelation($folderF->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderE->id);
        $this->assertFolderRelation($folderF->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderE->id);

        $this->assertItemIsInTrees($r1->get('id'), 2);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id);
        $this->assertItemIsInTrees($r2->get('id'), 2);
        $this->assertFolderRelation($r2->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderA->id);
        $this->assertFolderRelation($r2->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, $folderA->id);
        $this->assertItemIsInTrees($r3->get('id'), 2);
        $this->assertFolderRelation($r3->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderB->id);
        $this->assertFolderRelation($r3->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, $folderB->id);
        $this->assertItemIsInTrees($r4->get('id'), 2);
        $this->assertFolderRelation($r4->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderD->id);
        $this->assertFolderRelation($r4->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, $folderD->id);
        $this->assertItemIsInTrees($r5->get('id'), 2);
        $this->assertFolderRelation($r5->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderE->id);
        $this->assertFolderRelation($r5->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, $folderE->id);
    }
}
