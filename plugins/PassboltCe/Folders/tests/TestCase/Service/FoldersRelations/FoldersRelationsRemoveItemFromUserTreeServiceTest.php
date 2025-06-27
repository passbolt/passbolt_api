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

use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\FixtureProviderTrait;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsRemoveItemFromUserTreeService;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;

/**
 * Passbolt\Folders\Service\FoldersRelations\FoldersRelationsRemoveItemFromUserTreeService Test Case
 *
 * @covers \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsRemoveItemFromUserTreeService
 */
class FoldersRelationsRemoveItemFromUserTreeServiceTest extends FoldersTestCase
{
    use FixtureProviderTrait;
    use FoldersModelTrait;

    /**
     * @var FoldersRelationsRemoveItemFromUserTreeService
     */
    private $service;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private $permissionsTable;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->service = new FoldersRelationsRemoveItemFromUserTreeService();
    }

    /* FOLDERS */

    public function testRemoveItemFromUserTreeSuccess_Folder1_NoParentNoChildren()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // A (Ada:O, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA, $userB])->withFoldersRelationsFor([$userA, $userB])->persist();

        // Remove the permission for the user we want to remove the folder from the tree.
        $this->permissionsTable->deleteAll(['aco_foreign_key' => $folderA->id, 'aro_foreign_key' => $userB->id]);

        $this->service->removeItemFromUserTree($folderA->id, $userB->id, true);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
    }

    public function testRemoveItemFromUserTreeSuccess_Folder2_HavingAParent()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Ada sees B in A
        // Betty sees B in A
        // ----
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA, $userB])->withFoldersRelationsFor([$userA, $userB])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()->withPermissionsFor([$userA, $userB])->withFoldersRelationsFor([$userA, $userB], $folderA)->persist();

        // Remove the permission for the user we want to remove the folder from the tree.
        $this->permissionsTable->deleteAll(['aco_foreign_key' => $folderB->id, 'aro_foreign_key' => $userB->id]);

        $this->service->removeItemFromUserTree($folderB->id, $userB->id, true);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, null);
        // Folder B.
        $this->assertItemIsInTrees($folderB->id, 1);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
    }

    public function testRemoveItemFromUserTreeSuccess_Folder3_HavingAChild()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Ada sees B in A
        // Betty sees B in A
        // ----
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA, $userB])->withFoldersRelationsFor([$userA, $userB])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()->withPermissionsFor([$userA, $userB])->withFoldersRelationsFor([$userA, $userB], $folderA)->persist();

        // Remove the permission for the user we want to remove the folder from the tree.
        $this->permissionsTable->deleteAll(['aco_foreign_key' => $folderA->id, 'aro_foreign_key' => $userB->id]);

        $this->service->removeItemFromUserTree($folderA->id, $userB->id, true);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
        // Folder B.
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, null);
    }

    public function testRemoveItemFromUserTreeSuccess_Folder4_HavingChildren()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Ada is OWNER of folder C
        // Betty is OWNER of folder C
        // Ada sees B in A
        // Betty sees B in A
        // Ada sees C in A
        // Betty sees C in A
        // ----
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Betty:O)
        // |- C (Ada:O, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA, $userB])->withFoldersRelationsFor([$userA, $userB])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        [$folderB, $folderC] = FolderFactory::make(2)->withPermissionsFor([$userA, $userB])->withFoldersRelationsFor([$userA, $userB], $folderA)->persist();

        // Remove the permission for the user we want to remove the folder from the tree.
        $this->permissionsTable->deleteAll(['aco_foreign_key' => $folderA->id, 'aro_foreign_key' => $userB->id]);

        $this->service->removeItemFromUserTree($folderA->id, $userB->id, true);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
        // Folder B.
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, null);
        // Folder C.
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, null);
    }

    /* RESOURCES */

    public function testRemoveItemFromUserTreeSuccess_Resource1_NoParent()
    {
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // R1 (Ada:O, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$userA, $userB])->withFoldersRelationsFor([$userA, $userB])->persist();

        // Remove the permission for the user we want to remove the folder from the tree.
        $this->permissionsTable->deleteAll(['aco_foreign_key' => $r1->id, 'aro_foreign_key' => $userB->id]);

        $this->service->removeItemFromUserTree($r1->id, $userB->id);

        $this->assertItemIsInTrees($r1->id, 1);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
    }

    public function testRemoveItemFromUserTreeSuccess_Resource2_HavingAParent()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // Ada sees R1 in A
        // Betty sees R1 in A
        // ----
        // A (Ada:O, Betty:O)
        // |- R1 (Ada:O, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA, $userB])->withFoldersRelationsFor([$userA, $userB])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$userA, $userB])->withFoldersRelationsFor([$userA, $userB], $folderA)->persist();

        // Remove the permission for the user we want to remove the folder from the tree.
        $this->permissionsTable->deleteAll(['aco_foreign_key' => $r1->id, 'aro_foreign_key' => $userB->id]);

        $this->service->removeItemFromUserTree($r1->id, $userB->id);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, null);
        // Resource1
        $this->assertItemIsInTrees($r1->id, 1);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderA->id);
    }
}
