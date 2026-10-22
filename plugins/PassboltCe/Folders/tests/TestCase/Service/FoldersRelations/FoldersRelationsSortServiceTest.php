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
use App\Utility\UserAccessControl;
use Cake\Event\EventManager;
use Cake\I18n\DateTime;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsSortService;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\FoldersRelationFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;

/**
 * @covers \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsSortService
 */
class FoldersRelationsSortServiceTest extends FoldersTestCase
{
    /**
     * @var FoldersRelationsSortService
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
        $this->service = new FoldersRelationsSortService();
    }

    /*
     * Sort by priority "In Operator Tree"
     */

    /*
     * Ensure the "in operator tree" has a better priority in a simple scenario:
     * A (Ada:O)
     * B (Ada:O)
     * C (Other:O)
     * With Ada as operator the sort should return A, B, C or B, A, C
     */

    public function testFoldersRelationsSortService_SortByPriorityInOperatorTree()
    {
        $userA = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);
        $folderRelationA = FoldersRelationFactory::make()->user($userA)->persist();
        $folderRelationB = FoldersRelationFactory::make()->user($userA)->persist();
        $folderRelationC = FoldersRelationFactory::make()->persist();
        $foldersRelations = [$folderRelationB, $folderRelationC, $folderRelationA];

        $this->service->sort($foldersRelations, $uac);
        $this->assertContains($foldersRelations[0]->id, [$folderRelationA->id, $folderRelationB->id]);
        $this->assertContains($foldersRelations[1]->id, [$folderRelationA->id, $folderRelationB->id]);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
     * Ensure the "in operator tree" has a better priority combined with most used rule.
     * A (Ada:O, Betty:O)
     * B (Ada:O)
     * C (Other:O)
     * With Ada as operator the sort should return A, B, C
     */

    public function testFoldersRelationsSortService_SortByPriorityInOperatorTree_CombinedWithMostUsed()
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);
        $folderA = FolderFactory::make()->persist();
        $folderB = FolderFactory::make()->persist();

        $folderRelationAForAda = FoldersRelationFactory::make()->user($userA)
            ->foreignModelFolder($folderA)->folderParent($folderB)->persist();
        FoldersRelationFactory::make()->user($userB)->foreignModelFolder($folderA)->folderParent($folderB)->persist();
        $folderRelationB = FoldersRelationFactory::make()->user($userA)->persist();
        $folderRelationC = FoldersRelationFactory::make()->persist();
        $foldersRelations = [$folderRelationB, $folderRelationC, $folderRelationAForAda];

        $this->service->sort($foldersRelations, $uac);
        $this->assertEquals($foldersRelations[0]->id, $folderRelationAForAda->id);
        $this->assertEquals($foldersRelations[1]->id, $folderRelationB->id);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
     * Ensure the "in operator tree" has a better priority combined with the "In User Tree" priority
     * A (Ada:O, Betty:O)
     * B (Ada:O, Other:O)
     * C (Other:O)
     * With Ada as operator, and Betty as target user tree, the sort should return A, B, C
     */

    public function testFoldersRelationsSortService_SortByPriorityInOperatorTree_CombinedWithUserTree()
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);

        $folderA = FolderFactory::make()->persist();
        $folderB = FolderFactory::make()->persist();
        $folderRelationAForAda = FoldersRelationFactory::make()->user($userA)
            ->foreignModelFolder($folderA)->folderParent($folderB)->persist();
        FoldersRelationFactory::make()->user($userB)->foreignModelFolder($folderA)->folderParent($folderB)->persist();

        $folderC = FolderFactory::make()->persist();
        $folderD = FolderFactory::make()->persist();
        FoldersRelationFactory::make()->user($userA)->foreignModelFolder($folderC)->folderParent($folderD)->persist();
        FoldersRelationFactory::make()->foreignModelFolder($folderC)->folderParent($folderD)->persist();

        $folderRelationB = FoldersRelationFactory::make()->user($userA)->persist();
        $folderRelationC = FoldersRelationFactory::make()->persist();
        $foldersRelations = [$folderRelationB, $folderRelationC, $folderRelationAForAda];

        $this->service->sort($foldersRelations, $uac, $userB->id);
        $this->assertEquals($foldersRelations[0]->id, $folderRelationAForAda->id);
        $this->assertEquals($foldersRelations[1]->id, $folderRelationB->id);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
     * Ensure the "in operator tree" has a better priority combined with the "Grand Pa" priority
     * A (Ada:O, older than B)
     * B (Ada:O)
     * C (Other:O)
     * With Ada as operator the sort should return A, B, C
     */

    public function testFoldersRelationsSortService_SortByPriorityInOperatorTree_CombinedWithGrandPa()
    {
        $userA = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);
        $folderRelationA = FoldersRelationFactory::make(['created' => '1970-01-01 00:00:00'])->persist();
        $folderRelationB = FoldersRelationFactory::make(['created' => '1970-01-02 00:00:00'])->persist();
        $folderRelationC = FoldersRelationFactory::make()->persist();
        $foldersRelations = [$folderRelationB, $folderRelationC, $folderRelationA];

        $this->service->sort($foldersRelations, $uac);
        $this->assertEquals($foldersRelations[0]->id, $folderRelationA->id);
        $this->assertEquals($foldersRelations[1]->id, $folderRelationB->id);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
     * Sort by priority "Most Used"
     */

    /*
     * Ensure the "in operator tree" has a better priority in a simple scenario:
     * A (Any:O, Any:O, Any:O)
     * B (Ada:O, Any:O)
     * C (Other:O)
     * With Ada as operator the sort should return A, B, C
     */

    public function testFoldersRelationsSortService_SortByPriorityMostUsed()
    {
        $userA = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);

        $folderA = FolderFactory::make()->persist();
        $folderB = FolderFactory::make()->persist();
        [$folderRelationAForOther1] = FoldersRelationFactory::make(3)
            ->foreignModelFolder($folderA)->folderParent($folderB)->persist();

        $folderC = FolderFactory::make()->persist();
        $folderD = FolderFactory::make()->persist();
        [$folderRelationBForOther1] = FoldersRelationFactory::make(2)
            ->foreignModelFolder($folderC)->folderParent($folderD)->persist();

        $folderRelationC = FoldersRelationFactory::make()->persist();

        $foldersRelations = [$folderRelationC, $folderRelationBForOther1, $folderRelationAForOther1];

        $this->service->sort($foldersRelations, $uac);
        $this->assertEquals($folderRelationAForOther1->id, $foldersRelations[0]->id);
        $this->assertEquals($folderRelationBForOther1->id, $foldersRelations[1]->id);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
     * Ensure the "most used" has a better priority combined with the "User tree" priority
     * A (Betty:O, Any:O)
     * B (Any:O, Any:O)
     * C (Any:O)
     * With Ada as operator the sort should return A, B, C
     */

    public function testFoldersRelationsSortService_SortByPriorityMostUsed_CombinedUserTree()
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);

        $folderA = FolderFactory::make()->persist();
        $folderB = FolderFactory::make()->persist();
        $folderRelationAForBetty = FoldersRelationFactory::make()
            ->foreignModelFolder($folderA)->folderParent($folderB)->user($userB)->persist();
        FoldersRelationFactory::make()->foreignModelFolder($folderA)->folderParent($folderB)->persist();

        $folderC = FolderFactory::make()->persist();
        $folderD = FolderFactory::make()->persist();
        [$folderRelationBForOther1] = FoldersRelationFactory::make(2)
            ->foreignModelFolder($folderC)->folderParent($folderD)->persist();

        $folderRelationC = FoldersRelationFactory::make()->persist();
        $foldersRelations = [$folderRelationC, $folderRelationBForOther1, $folderRelationAForBetty];

        $this->service->sort($foldersRelations, $uac, $userB->id);
        $this->assertEquals($folderRelationAForBetty->id, $foldersRelations[0]->id);
        $this->assertEquals($folderRelationBForOther1->id, $foldersRelations[1]->id);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
     * Ensure the "most used" has a better priority combined with the "Grand Pa" priority
     * A (Any:O, Any:O, older than B)
     * B (Any:O, Any:O)
     * C (Any:O)
     * With Ada as operator the sort should return A, B, C
     */

    public function testFoldersRelationsSortService_SortByPriorityMostUsed_CombinedGrandPa()
    {
        $userA = UserFactory::make()->persist();
        UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);

        $folderA = FolderFactory::make()->persist();
        $folderB = FolderFactory::make()->persist();
        $folderRelationAForOther1 = FoldersRelationFactory::make(['created' => '1970-01-01 00:00:00'])
            ->foreignModelFolder($folderA)->folderParent($folderB)->persist();
        FoldersRelationFactory::make(['created' => '1970-01-01 00:00:00'])
            ->foreignModelFolder($folderA)->folderParent($folderB)->persist();

        $folderC = FolderFactory::make()->persist();
        $folderD = FolderFactory::make()->persist();
        [$folderRelationBForOther1] = FoldersRelationFactory::make(2)
            ->foreignModelFolder($folderC)->folderParent($folderD)->persist();

        $folderRelationC = FoldersRelationFactory::make()->persist();
        $foldersRelations = [$folderRelationC, $folderRelationBForOther1, $folderRelationAForOther1];

        $this->service->sort($foldersRelations, $uac);
        $this->assertEquals($folderRelationAForOther1->id, $foldersRelations[0]->id);
        $this->assertEquals($folderRelationBForOther1->id, $foldersRelations[1]->id);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
     * Sort by priority "In user tree"
     */

    /*
     * Ensure the "in operator tree" has a better priority in a simple scenario:
     * A (Betty:O)
     * B (Betty:O)
     * C (Other:O)
     * With Ada as operator the sort should return A, B, C or B, A, C
     */

    public function testFoldersRelationsSortService_SortByPriorityInUserTree()
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);
        $folderRelationA = FoldersRelationFactory::make()->user($userB)->persist();
        $folderRelationB = FoldersRelationFactory::make()->user($userB)->persist();
        $folderRelationC = FoldersRelationFactory::make()->persist();
        $foldersRelations = [$folderRelationC, $folderRelationB, $folderRelationA];

        $this->service->sort($foldersRelations, $uac, $userB->id);
        $this->assertContains($foldersRelations[0]->id, [$folderRelationA->id, $folderRelationB->id]);
        $this->assertContains($foldersRelations[1]->id, [$folderRelationA->id, $folderRelationB->id]);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
      * Ensure the "in operator tree" has a better priority in a simple scenario:
      * A (Betty:O older than B)
      * B (Betty:O)
      * C (Other:O)
      * With Ada as operator the sort should return A, B, C
      */

    public function testFoldersRelationsSortService_SortByPriorityInUserTree_CombinedGrandPa()
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);
        $folderRelationA = FoldersRelationFactory::make(['created' => '1970-01-01 00:00:00'])->user($userB)->persist();
        $folderRelationB = FoldersRelationFactory::make()->user($userB)->persist();
        $folderRelationC = FoldersRelationFactory::make()->persist();
        $foldersRelations = [$folderRelationC, $folderRelationB, $folderRelationA];

        $this->service->sort($foldersRelations, $uac, $userB->id);
        $this->assertEquals($foldersRelations[0]->id, $folderRelationA->id);
        $this->assertEquals($foldersRelations[1]->id, $folderRelationB->id);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
     * Sort by priority "Grand Pa"
     */

    /*
     * Ensure the "Grand Pa" has a better priority in a simple scenario:
     * A (Any:O older than B)
     * B (Any:O older than C)
     * C (Any:O)
     * With Ada as operator the sort should return A, B, C
     */

    public function testFoldersRelationsSortService_SortByPriorityCreatedOldestTree()
    {
        $userA = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);
        $folderRelationA = FoldersRelationFactory::make(['created' => '1970-01-01 00:00:00'])->persist();
        $folderRelationB = FoldersRelationFactory::make(['created' => '1970-01-02 00:00:00'])->persist();
        $folderRelationC = FoldersRelationFactory::make(['created' => '1970-01-03 00:00:00'])->persist();
        $foldersRelations = [$folderRelationB, $folderRelationC, $folderRelationA];

        $this->service->sort($foldersRelations, $uac);
        $this->assertEquals($folderRelationA->id, $foldersRelations[0]->id);
        $this->assertEquals($folderRelationB->id, $foldersRelations[1]->id);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
     * Sort by priority "Shared Parent"
     */

    /*
     * Ensure a relation whose parent is a shared folder has priority over a relation whose parent is a personal
     * folder, once the "in operator tree", "most used" and "in user tree" rules all tie. This is the tie-breaker
     * that keeps cycle repairs deterministic: personal-parent edges are broken first (see `repairPersonal`).
     * A (Other:O) - parent is a shared folder
     * B (Other:O) - parent is a personal folder
     * With Ada as operator the sort should return A, B
     */

    public function testFoldersRelationsSortService_SortByPrioritySharedParent()
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);

        // Shared parent: referenced by more than one relation -> not personal.
        $sharedParentFolder = FolderFactory::make()->persist();
        FoldersRelationFactory::make(2)->foreignModelFolder($sharedParentFolder)->persist();

        // Personal parent: referenced by a single relation -> personal.
        $personalParentFolder = FolderFactory::make()->persist();
        FoldersRelationFactory::make()->foreignModelFolder($personalParentFolder)->persist();

        /** @var \Passbolt\Folders\Model\Entity\FoldersRelation $folderRelationSharedParent */
        $folderRelationSharedParent = FoldersRelationFactory::make()
            ->foreignModelFolder(FolderFactory::make()->persist())
            ->folderParent($sharedParentFolder)
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\FoldersRelation $folderRelationPersonalParent */
        $folderRelationPersonalParent = FoldersRelationFactory::make()
            ->foreignModelFolder(FolderFactory::make()->persist())
            ->folderParent($personalParentFolder)
            ->persist();

        $foldersRelations = [$folderRelationPersonalParent, $folderRelationSharedParent];

        $this->service->sort($foldersRelations, $uac);
        $this->assertEquals($folderRelationSharedParent->id, $foldersRelations[0]->id);
        $this->assertEquals($folderRelationPersonalParent->id, $foldersRelations[1]->id);
    }

    public function testFoldersRelationsSortService_SortByPriorityMostUsed_RelationAtTheRootIsSortedLast()
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);

        // Personal parent: referenced by a single relation -> personal.
        $personalParentFolder = FolderFactory::make()->persist();
        FoldersRelationFactory::make()->foreignModelFolder($personalParentFolder)->persist();

        /** @var \Passbolt\Folders\Model\Entity\FoldersRelation $folderRelationRoot */
        $folderRelationRoot = FoldersRelationFactory::make(['created' => '1970-01-01 00:00:00'])
            ->foreignModelFolder(FolderFactory::make()->persist())
            ->root()
            ->persist();
        $folderRelationPersonalParent = $this->makeFolderRelationWithParent(
            '1970-01-02 00:00:00',
            $personalParentFolder,
        );

        $foldersRelations = [$folderRelationRoot, $folderRelationPersonalParent];

        $this->service->sort($foldersRelations, $uac);
        $this->assertEquals($folderRelationPersonalParent->id, $foldersRelations[0]->id);
        $this->assertEquals($folderRelationRoot->id, $foldersRelations[1]->id);
    }

    public function testFoldersRelationsSortService_SortByPrioritySharedParent_AllRelationsAtTheRoot()
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);

        /** @var \Passbolt\Folders\Model\Entity\FoldersRelation $folderRelationA */
        $folderRelationA = FoldersRelationFactory::make(['created' => DateTime::today()->toDateTimeString()])
            ->foreignModelFolder(FolderFactory::make()->persist())
            ->root()
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\FoldersRelation $folderRelationB */
        $folderRelationB = FoldersRelationFactory::make(['created' => DateTime::yesterday()->toDateTimeString()])
            ->foreignModelFolder(FolderFactory::make()->persist())
            ->root()
            ->persist();

        $foldersRelations = [$folderRelationA, $folderRelationB];

        $this->service->sort($foldersRelations, $uac);
        $this->assertEquals($folderRelationA->id, $foldersRelations[0]->id);
        $this->assertEquals($folderRelationB->id, $foldersRelations[1]->id);
    }

    public function testFoldersRelationsSortService_SortByPrioritySharedParent_ParentWithoutRelationIsNotPersonal()
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);

        // Personal parent: referenced by a single relation -> personal.
        $personalParentFolder = FolderFactory::make()->persist();
        FoldersRelationFactory::make()->foreignModelFolder($personalParentFolder)->persist();

        // The factory defaults the folder parent to a random uuid, no folder relation references it.
        /** @var \Passbolt\Folders\Model\Entity\FoldersRelation $folderRelationUnknownParent */
        $folderRelationUnknownParent = FoldersRelationFactory::make(['created' => '1970-01-02 00:00:00'])
            ->foreignModelFolder(FolderFactory::make()->persist())
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\FoldersRelation $folderRelationPersonalParent */
        $folderRelationPersonalParent = FoldersRelationFactory::make(['created' => '1970-01-01 00:00:00'])
            ->foreignModelFolder(FolderFactory::make()->persist())
            ->folderParent($personalParentFolder)
            ->persist();

        $foldersRelations = [$folderRelationPersonalParent, $folderRelationUnknownParent];

        $this->service->sort($foldersRelations, $uac);
        $this->assertEquals($folderRelationUnknownParent->id, $foldersRelations[0]->id);
        $this->assertEquals($folderRelationPersonalParent->id, $foldersRelations[1]->id);
    }

    public function testFoldersRelationsSortService_SortByPrioritySharedParent_BothParentsPersonal_OldestRelationFirst()
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);

        $personalParentFolderA = FolderFactory::make()->persist();
        FoldersRelationFactory::make()->foreignModelFolder($personalParentFolderA)->persist();
        $personalParentFolderB = FolderFactory::make()->persist();
        FoldersRelationFactory::make()->foreignModelFolder($personalParentFolderB)->persist();

        /** @var \Passbolt\Folders\Model\Entity\FoldersRelation $folderRelationOldest */
        $folderRelationOldest = FoldersRelationFactory::make(['created' => DateTime::yesterday()->toDateTimeString()])
            ->foreignModelFolder(FolderFactory::make()->persist())
            ->folderParent($personalParentFolderA)
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\FoldersRelation $folderRelationNewest */
        $folderRelationNewest = FoldersRelationFactory::make(['created' => DateTime::today()->toDateTimeString()])
            ->foreignModelFolder(FolderFactory::make()->persist())
            ->folderParent($personalParentFolderB)
            ->persist();

        $foldersRelations = [$folderRelationNewest, $folderRelationOldest];

        $this->service->sort($foldersRelations, $uac);
        $this->assertEquals($folderRelationOldest->id, $foldersRelations[0]->id);
        $this->assertEquals($folderRelationNewest->id, $foldersRelations[1]->id);
    }

    public function testFoldersRelationsSortService_SortByPrioritySharedParent_BothParentsShared_OldestRelationFirst()
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);

        $sharedParentFolderA = FolderFactory::make()->persist();
        FoldersRelationFactory::make(2)->foreignModelFolder($sharedParentFolderA)->persist();
        $sharedParentFolderB = FolderFactory::make()->persist();
        FoldersRelationFactory::make(2)->foreignModelFolder($sharedParentFolderB)->persist();

        /** @var \Passbolt\Folders\Model\Entity\FoldersRelation $folderRelationOldest */
        $folderRelationOldest = FoldersRelationFactory::make(['created' => DateTime::yesterday()->toDateTimeString()])
            ->foreignModelFolder(FolderFactory::make()->persist())
            ->folderParent($sharedParentFolderA)
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\FoldersRelation $folderRelationNewest */
        $folderRelationNewest = FoldersRelationFactory::make(['created' => DateTime::today()->toDateTimeString()])
            ->foreignModelFolder(FolderFactory::make()->persist())
            ->folderParent($sharedParentFolderB)
            ->persist();

        $foldersRelations = [$folderRelationNewest, $folderRelationOldest];

        $this->service->sort($foldersRelations, $uac);
        $this->assertEquals($folderRelationOldest->id, $foldersRelations[0]->id);
        $this->assertEquals($folderRelationNewest->id, $foldersRelations[1]->id);
    }

    /**
     * In case of personal and shared relations, the shared parent with oldest come first from each group.
     *
     * E.g.
     * A (Other:O) - parent is a shared folder, oldest
     * B (Other:O) - parent is a shared folder, newest
     * C (Other:O) - parent is a personal folder, oldest
     * D (Other:O) - parent is a personal folder, newest
     * The sort should return A, B, C, D
     *
     * @return void
     */
    public function testFoldersRelationsSortService_SortByPrioritySharedParent_ManyRelations()
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);

        $sharedParentFolder = FolderFactory::make()->persist();
        FoldersRelationFactory::make(2)->foreignModelFolder($sharedParentFolder)->persist();
        $personalParentFolder = FolderFactory::make()->persist();
        FoldersRelationFactory::make()->foreignModelFolder($personalParentFolder)->persist();

        $sharedParentOldest = $this->makeFolderRelationWithParent(DateTime::today()->subDays(3)->toDateTimeString(), $sharedParentFolder);
        $sharedParentNewest = $this->makeFolderRelationWithParent(DateTime::today()->subDays(2)->toDateTimeString(), $sharedParentFolder);
        $personalParentOldest = $this->makeFolderRelationWithParent(DateTime::yesterday()->toDateTimeString(), $personalParentFolder);
        $personalParentNewest = $this->makeFolderRelationWithParent(DateTime::today()->toDateTimeString(), $personalParentFolder);

        $foldersRelations = [
            $personalParentNewest,
            $sharedParentNewest,
            $personalParentOldest,
            $sharedParentOldest,
        ];

        $this->service->sort($foldersRelations, $uac);
        $this->assertEquals($sharedParentOldest->id, $foldersRelations[0]->id);
        $this->assertEquals($sharedParentNewest->id, $foldersRelations[1]->id);
        $this->assertEquals($personalParentOldest->id, $foldersRelations[2]->id);
        $this->assertEquals($personalParentNewest->id, $foldersRelations[3]->id);
    }

    /**
     * Performance test to make sure the database reads does not grow with the number when sorting the folder relations.
     *
     * @return void
     */
    public function testFoldersRelationsSortService_Sort_NumberOfReadsDoesNotGrowWithTheNumberOfRelations()
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);

        $smallBatchReads = $this->countReadsToSort($this->makeFoldersRelationsToSort(10), $uac);
        $largeBatchReads = $this->countReadsToSort($this->makeFoldersRelationsToSort(80), $uac);

        $this->assertSame($smallBatchReads, $largeBatchReads);
    }

    public function testFoldersRelationsSortService_Sort_EmptyInputIsANoop(): void
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);

        $foldersRelations = [];
        $reads = $this->countReadsToSort($foldersRelations, $uac);

        $this->assertSame(0, $reads);
        $this->assertSame([], $foldersRelations);
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    /**
     * Create a folder relation attached to the given folder parent.
     *
     * @param string $created The creation date of the folder relation.
     * @param \Passbolt\Folders\Model\Entity\Folder $folderParent The folder parent.
     * @return \Passbolt\Folders\Model\Entity\FoldersRelation
     */
    private function makeFolderRelationWithParent(string $created, Folder $folderParent): FoldersRelation
    {
        /** @var \Passbolt\Folders\Model\Entity\FoldersRelation $folderRelation */
        $folderRelation = FoldersRelationFactory::make(['created' => $created])
            ->foreignModelFolder(FolderFactory::make()->persist())
            ->folderParent($folderParent)
            ->persist();

        return $folderRelation;
    }

    /**
     * @param int $size The number of folders relations to create.
     * @return array<\Passbolt\Folders\Model\Entity\FoldersRelation>
     */
    private function makeFoldersRelationsToSort(int $size): array
    {
        $sharedParentFolder = FolderFactory::make()->persist();
        FoldersRelationFactory::make(2)->foreignModelFolder($sharedParentFolder)->persist();
        $personalParentFolder = FolderFactory::make()->persist();
        FoldersRelationFactory::make()->foreignModelFolder($personalParentFolder)->persist();

        $halfSize = intdiv($size, 2);

        return array_merge(
            FoldersRelationFactory::make($halfSize)->folderParent($sharedParentFolder)->persist(),
            FoldersRelationFactory::make($halfSize)->folderParent($personalParentFolder)->persist(),
        );
    }

    /**
     * Count the database reads performed by the sort by attaching a listener to the `Model.beforeFind`.
     *
     * @param array<\Passbolt\Folders\Model\Entity\FoldersRelation> $foldersRelations The folders relations to sort.
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the action.
     * @return int
     */
    private function countReadsToSort(array $foldersRelations, UserAccessControl $uac): int
    {
        $reads = 0;
        $listener = function () use (&$reads): void {
            $reads++;
        };

        EventManager::instance()->on('Model.beforeFind', $listener);
        try {
            $this->service->sort($foldersRelations, $uac);
        } finally {
            EventManager::instance()->off('Model.beforeFind', $listener);
        }

        return $reads;
    }
}
