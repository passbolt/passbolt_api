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
 * @since         4.1.0
 */

namespace Passbolt\Folders\Test\TestCase\Service\FoldersRelations;

use App\Test\Factory\UserFactory;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Utility\FixtureProviderTrait;
use Passbolt\Folders\Model\Dto\FolderRelationDto;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsDetectStronglyConnectedComponentsService;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\FoldersRelationFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\FoldersRelations\FoldersRelationsDetectStronglyConnectedComponentsService Test Case
 *
 * @covers \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsDetectStronglyConnectedComponentsService
 */
class FoldersRelationsDetectStronglyConnectedComponentsServiceTest extends FoldersTestCase
{
    use FixtureProviderTrait;
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;

    public $fixtures = [
        UsersFixture::class,
    ];

    /**
     * @var FoldersRelationsDetectStronglyConnectedComponentsService
     */
    private FoldersRelationsDetectStronglyConnectedComponentsService $service;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new FoldersRelationsDetectStronglyConnectedComponentsService();
    }

    public function assertScc(array $scc, array $expectedScc): void
    {
        $this->assertCount(count($expectedScc), $scc, 'The found SCC doe not have the same count of element than the expected one.');

        foreach ($scc as $sccFolderRelation) {
            foreach ($expectedScc as $expectedSccFolderRelation) {
                if (
                    $expectedSccFolderRelation->foreignId === $sccFolderRelation->foreign_id
                    && $expectedSccFolderRelation->folderParentId === $sccFolderRelation->folder_parent_id
                ) {
                    continue 2;
                }
                $this->assertFalse(false, "Folder relation expected in the SCC not found (foreign_id: {$expectedSccFolderRelation->foreignId}, folder_parent_id: {$expectedSccFolderRelation->folderParentId})");
            }
        }
    }

    /*
     * SCC found in one user tree.
     */

    public function testFoldersRelationsDetectStronglyConnectedComponentsService_SCCFound_InOnUserTree_Ada_ABA_Betty_A_Carole_B()
    {
        [$folderA, $folderB, $userA, $userB, $userC] = $this->insertFixture_Ada_ABA_Betty_A_Carole_B();
        $expectedScc = [
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id, $folderB->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, $folderA->id),
        ];

        $result = $this->service->detectFirstInSharedFolders();
        $this->assertScc($result, $expectedScc);
    }

    public function insertFixture_Ada_ABA_Betty_A_Carole_B()
    {
        // Ada sees A in B
        // Ada sees B in A
        // Betty sees A at the root
        // Carole sees B at the root
        // ---
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Carole:O)
        //    |- A (Ada:O, Betty:O)

        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $userC = UserFactory::make()->persist();
        $folderA = FolderFactory::make(['name' => 'A'])->withFoldersRelationsFor([$userB])->persist();
        $folderB = FolderFactory::make(['name' => 'B'])->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userC])->persist();
        FoldersRelationFactory::make()->user($userA)
            ->foreignModelFolder($folderA)->folderParent($folderB)->persist();

        return [$folderA, $folderB, $userA, $userB, $userC];
    }

    /*
     * Multiple SCCs found in two users trees, returning the smallest of all.
     */

    public function testFoldersRelationsDetectStronglyConnectedComponentsService_SCCFound_InOnUserTreeReturnSmallest_Ada_ABA_ABCA_Betty_A_Carole_B_Dame_C()
    {
        [$folderA, $folderB, $folderC, $userA, $userB, $userC, $userD] = $this->insertFixture_Ada_ABA_ABCA_Betty_A_Carole_B_Dame_C();
        $expectedScc = [
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id, $folderB->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, $folderA->id),
        ];

        $result = $this->service->detectFirstInSharedFolders();
        $this->assertScc($result, $expectedScc);
    }

    public function insertFixture_Ada_ABA_ABCA_Betty_A_Carole_B_Dame_C()
    {
        // Ada sees A in B
        // Ada sees B in A
        // Ada sees C in B
        // Ada sees A in C
        // Betty sees A at the root
        // Carole sees B at the root
        // Came sees C at the root
        // ---
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Carole:O)
        //    |- A (Ada:O, Betty:O)
        //    |- C (Ada:O, Carole:O)
        //       |- A (Ada:O, Betty:O)

        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $userC = UserFactory::make()->persist();
        $userD = UserFactory::make()->persist();
        $folderA = FolderFactory::make(['name' => 'A'])->withFoldersRelationsFor([$userB])->persist();
        $folderB = FolderFactory::make(['name' => 'B'])->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userC])->persist();
        $folderC = FolderFactory::make(['name' => 'C'])->withFoldersRelationsFor([$userA], $folderB)
            ->withFoldersRelationsFor([$userD])->persist();
        FoldersRelationFactory::make()->user($userA)
            ->foreignModelFolder($folderA)->folderParent($folderB)->persist();
        FoldersRelationFactory::make()->user($userA)
            ->foreignModelFolder($folderA)->folderParent($folderC)->persist();

        return [$folderA, $folderB, $folderC, $userA, $userB, $userC, $userD];
    }

    /*
     * SCC found in two users trees with all the folders visible by each users.
     */

    public function testFoldersRelationsDetectStronglyConnectedComponentsService_SCCFound_InTwoUserTreesWithoutNotCommonIntermediaryFolders_Ada_AB_Betty_BA()
    {
        [$folderA, $folderB, $userA, $userB] = $this->insertFixture_Ada_AB_Betty_BA();
        $expectedScc = [
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id, $folderB->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, $folderA->id),
        ];

        $result = $this->service->detectFirstInSharedFolders();
        $this->assertScc($result, $expectedScc);
    }

    public function insertFixture_Ada_AB_Betty_BA()
    {
        // Ada is owner of all folders
        // Betty is owner of all folders
        // Ada sees A at the root
        // Ada sees B in A
        // Betty sees B at the root
        // Betty sees A in B
        // ---
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Betty:O)
        //    |- A (Ada:O, Betty:O)

        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA])->persist();
        $folderB = FolderFactory::make()
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userB])->persist();
        FoldersRelationFactory::make()->user($userB)
            ->foreignModelFolder($folderA)->folderParent($folderB)->persist();

        return [$folderA, $folderB, $userA, $userB];
    }

    /*
     * SCC found in two users trees which includes an intermediary folder in the first tree not visible by the other
     * user the cycle is formed with.
     */

    public function testFoldersRelationsDetectStronglyConnectedComponentsService_SCCFound_InTwoUserTreesWithOneNotCommonIntermediaryFolders_Ada_ABC_Betty_CA_Carole_B()
    {
        [$folderA, $folderB, $folderC, $userA, $userB] = $this->insertFixture_Ada_ABC_Betty_CA_Carole_B();
        $expectedScc = [
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id, $folderC->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, $folderA->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderC->id, $folderB->id),
        ];

        $result = $this->service->detectFirstInSharedFolders();
        $this->assertScc($result, $expectedScc);
    }

    public function insertFixture_Ada_ABC_Betty_CA_Carole_B()
    {
        // Ada ABC
        // Betty CA
        // Carole B
        // ---
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Carole:O)
        //    |- C (Ada:O, Betty:O)
        //       |- A (Ada:O, Betty:O)

        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $userC = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA])->persist();
        $folderB = FolderFactory::make()
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userC])
            ->persist();
        $folderC = FolderFactory::make()
            ->withFoldersRelationsFor([$userA], $folderB)
            ->withFoldersRelationsFor([$userB])->persist();
        FoldersRelationFactory::make()->user($userB)
            ->foreignModelFolder($folderA)->folderParent($folderC)->persist();

        return [$folderA, $folderB, $folderC, $userA, $userB];
    }

    /*
     * SCC found in two users trees which includes intermediary folders in each tree not visible by the other users
     * the cycle is formed with.
     */

    public function testFoldersRelationsDetectStronglyConnectedComponentsService_SCCFound_InTwoUserTreesWithTwoNotCommonIntermediaryFolders_Ada_ABC_Betty_CDA_Carole_B_D()
    {
        [$folderA, $folderB, $folderC, $folderD, $userA, $userB, $userC] = $this->insertFixture_Ada_ABC_Betty_CDA_Carole_B_D();
        $expectedScc = [
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id, $folderC->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, $folderA->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderC->id, $folderB->id),
            new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderD->id, $folderC->id),
        ];

        $result = $this->service->detectFirstInSharedFolders();
        $this->assertScc($result, $expectedScc);
    }

    public function insertFixture_Ada_ABC_Betty_CDA_Carole_B_D()
    {
        // Ada ABC
        // Betty CDA
        // Carole BD
        // ---
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Carole:O)
        //    |- C (Ada:O, Betty:O)
        //       |- D (Betty:O, Carole:O)
        //          |- A (Ada:O, Betty:O)

        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $userC = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA])->persist();
        $folderB = FolderFactory::make()
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userC])
            ->persist();
        $folderC = FolderFactory::make()
            ->withFoldersRelationsFor([$userA], $folderB)
            ->withFoldersRelationsFor([$userB])->persist();
        $folderD = FolderFactory::make()
            ->withFoldersRelationsFor([$userB], $folderC)
            ->withFoldersRelationsFor([$userC])
            ->persist();
        FoldersRelationFactory::make()->user($userB)
            ->foreignModelFolder($folderA)->folderParent($folderD)->persist();

        return [$folderA, $folderB, $folderC, $folderD, $userA, $userB, $userC];
    }

    /*
     * No SCC found if there are none to be detected.
     */

    public function testFoldersRelationsDetectStronglyConnectedComponentsService_SCCNotFound_InMultipleUserTrees_Ada_ABC_Betty_ABC()
    {
        $this->insertFixture_Ada_ABC_Betty_CA();

        $result = $this->service->detectFirstInSharedFolders();
        $this->assertEmpty($result);
    }

    public function insertFixture_Ada_ABC_Betty_ABC()
    {
        // Ada ABC
        // Betty ABC
        // ---
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Betty:O)
        //    |- C (Ada:O, Betty:O)

        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA, $userB])->persist();
        $folderB = FolderFactory::make()->withFoldersRelationsFor([$userA, $userB], $folderA)->persist();
        $folderC = FolderFactory::make()->withFoldersRelationsFor([$userA, $userB], $folderB)->persist();

        return [$folderA, $folderB, $folderC, $userA, $userB];
    }

    /*
     * No SCC found in one user tree if it includes a personal folder.
     */

    public function testFoldersRelationsDetectStronglyConnectedComponentsService_SCCNotFound_InOneUserTreesWithOnePersonalIntermediaryFolder_Ada_ABCA_Betty_A_Carole_C()
    {
        $this->insertFixture_Ada_ABCA_Betty_A_Carole_C();

        $result = $this->service->detectFirstInSharedFolders();
        $this->assertEmpty($result);
    }

    public function insertFixture_Ada_ABCA_Betty_A_Carole_C()
    {
        // Ada ABCA
        // Betty A
        // Carole C
        // ---
        // A (Ada:O, Betty:O)
        // |- B (Ada:O)
        //    |- C (Ada:O, Carole:O)
        //       |- A (Ada:O, Betty:O)

        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $userC = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userB])->persist();
        $folderB = FolderFactory::make()->withFoldersRelationsFor([$userA], $folderA)->persist();
        $folderC = FolderFactory::make()
            ->withFoldersRelationsFor([$userA], $folderB)
            ->withFoldersRelationsFor([$userC])->persist();
        FoldersRelationFactory::make()->user($userA)
            ->foreignModelFolder($folderA)->folderParent($folderC)->persist();

        return [$folderA, $folderB, $folderC, $userA, $userB, $userC];
    }

    /*
     * No SCC found in two users trees if it includes a personal folder in one user tree.
     */

    public function testFoldersRelationsDetectStronglyConnectedComponentsService_SCCNotFound_InTwoUserTreesWithOnePersonalIntermediaryFolders_Ada_ABC_Betty_CA()
    {
        $this->insertFixture_Ada_ABC_Betty_CA();

        $result = $this->service->detectFirstInSharedFolders();
        $this->assertEmpty($result);
    }

    public function insertFixture_Ada_ABC_Betty_CA()
    {
        // Ada ABC
        // Betty CA
        // ---
        // A (Ada:O, Betty:O)
        // |- B (Ada:O)
        //    |- C (Ada:O, Betty:O)
        //       |- A (Ada:O, Betty:O)

        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA])->persist();
        $folderB = FolderFactory::make()
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();
        $folderC = FolderFactory::make()
            ->withFoldersRelationsFor([$userA], $folderB)
            ->withFoldersRelationsFor([$userB])->persist();
        FoldersRelationFactory::make()->user($userB)
            ->foreignModelFolder($folderA)->folderParent($folderC)->persist();

        return [$folderA, $folderB, $folderC, $userA, $userB];
    }

    /*
     * No SCC found if it cannot be detected in one or two users trees but in three users trees.
     */

    public function testFoldersRelationsDetectStronglyConnectedComponentsService_NoSCCFound_InThreeUserTrees_Ada_AB_Betty_BC_Carole_CA()
    {
        $this->insertFixture_Ada_AB_Betty_BC_Carole_CA();

        $result = $this->service->detectFirstInSharedFolders();
        $this->assertEmpty($result);
    }

    public function insertFixture_Ada_AB_Betty_BC_Carole_CA()
    {
        // Ada AC
        // Betty BC
        // Carole CA
        // ---
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Betty:O)
        //    |- C (Betty:O, Carole:O)
        //       |- A (Ada:O, Carole:O)

        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $userC = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA])->persist();
        $folderB = FolderFactory::make()
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userB])
            ->persist();
        $folderC = FolderFactory::make()
            ->withFoldersRelationsFor([$userB], $folderB)
            ->withFoldersRelationsFor([$userC])->persist();
        FoldersRelationFactory::make()->user($userC)
            ->foreignModelFolder($folderA)->folderParent($folderC)->persist();

        return [$folderA, $folderB, $folderC, $userA, $userB, $userC];
    }
}
