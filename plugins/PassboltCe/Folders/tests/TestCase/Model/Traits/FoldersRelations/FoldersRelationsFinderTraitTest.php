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
 * @since         3.7.0
 */

namespace Passbolt\Folders\Test\TestCase\Model\Traits\FoldersRelations;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Closure;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

class FoldersRelationsFinderTraitTest extends FoldersTestCase
{
    use FoldersRelationsModelTrait;

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersRelationsTable
     */
    private $foldersRelationsTable;

    public function setUp(): void
    {
        parent::setUp();
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
    }

    /**
     * Find and assert the missing folders relations for a target foreign model
     *
     * @param string $foreignModel The foreign model to find and assert missing relations for
     * @param array $expectedMissingFoldersRelations The expected missing folders relations
     * [
     *   [<Folder|Resources>, <User>],
     *   ...
     * ]
     */
    public function findAndAssertMissingFoldersRelations(string $foreignModel, array $expectedMissingFoldersRelations): void
    {
        $missingFoldersRelations = $this->foldersRelationsTable->findMissingFoldersRelations($foreignModel)->disableHydration()->all();
        $this->assertCount(count($expectedMissingFoldersRelations), $missingFoldersRelations);
        foreach ($expectedMissingFoldersRelations as $expectedMissingFoldersRelation) {
            $this->assertContains(
                ['foreign_id' => $expectedMissingFoldersRelation[0]->id, 'user_id' => $expectedMissingFoldersRelation[1]->id],
                $missingFoldersRelations
            );
        }
    }

    /**
     * @dataProvider noMissingResourcesFoldersRelationsDataProvider
     */
    public function testFoldersRelationsFinderTrait_findMissingFoldersRelations_noMissingResourcesFoldersRelations(Closure $dataProviderClosure): void
    {
        $dataProviderClosure();
        $missingFoldersRelations = $this->foldersRelationsTable->findMissingFoldersRelations(FoldersRelation::FOREIGN_MODEL_RESOURCE)
            ->all()
            ->toArray();
        $this->assertEmpty($missingFoldersRelations);
    }

    public function noMissingResourcesFoldersRelationsDataProvider(): array
    {
        return [
            'No resources in database' => [
                function (): void {
                },
            ],
            'Only folders in database' => [
                function (): void {
                    $userAda = UserFactory::make()->persist();
                    $groupA = GroupFactory::make()->withGroupsManagersFor([$userAda])->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userAda])->withPermissionsFor([$userAda])->persist();
                    FolderFactory::make()->withPermissionsFor([$userAda])->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userAda])->withPermissionsFor([$groupA])->persist();
                    FolderFactory::make()->withPermissionsFor([$groupA])->persist();
                },
            ],
            'A single user having access to a single resource' => [
                function (): void {
                    $userAda = UserFactory::make()->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userAda])->withPermissionsFor([$userAda])->persist();
                },
            ],
            'A single group having access to a single resource' => [
                function (): void {
                    $userAda = UserFactory::make()->persist();
                    $groupA = GroupFactory::make()->withGroupsManagersFor([$userAda])->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userAda])->withPermissionsFor([$groupA])->persist();
                },
            ],
            'Multiple users having access to a single resource' => [
                function (): void {
                    [$userAda, $userBetty] = UserFactory::make(2)->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userAda, $userBetty])->withPermissionsFor([$userAda, $userBetty])->persist();
                },
            ],
            'Multiple groups having access to a single resource' => [
                function (): void {
                    [$userAda, $userBetty] = UserFactory::make(2)->persist();
                    [$groupA, $groupB] = GroupFactory::make(2)->withGroupsManagersFor([$userAda, $userBetty])->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userAda, $userBetty])->withPermissionsFor([$groupA, $groupB])->persist();
                },
            ],
            'A single user having access to a multiple resources' => [
                function (): void {
                    $userAda = UserFactory::make()->persist();
                    ResourceFactory::make(2)->withFoldersRelationsFor([$userAda])->withPermissionsFor([$userAda])->persist();
                },
            ],
            'Multiples users having access to a multiple resources' => [
                function (): void {
                    [$userAda, $userBetty] = UserFactory::make(2)->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userAda])->withPermissionsFor([$userAda])->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userBetty])->withPermissionsFor([$userBetty])->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userAda, $userBetty])->withPermissionsFor([$userAda, $userBetty])->persist();
                },
            ],
            'Multiples groups having access to a multiple resources' => [
                function (): void {
                    [$userAda, $userBetty] = UserFactory::make(2)->persist();
                    $groupA = GroupFactory::make()->withGroupsManagersFor([$userAda])->persist();
                    $groupB = GroupFactory::make()->withGroupsManagersFor([$userBetty])->persist();
                    $groupAB = GroupFactory::make()->withGroupsManagersFor([$userAda, $userBetty])->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userAda])->withPermissionsFor([$groupA])->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userBetty])->withPermissionsFor([$groupB])->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userAda, $userBetty])->withPermissionsFor([$groupAB])->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userAda, $userBetty])->withPermissionsFor([$groupA, $groupB, $groupAB])->persist();
                },
            ],
            'Multiples users and groups having access to a multiple resources' => [
                function (): void {
                    [$userAda, $userBetty] = UserFactory::make(2)->persist();
                    $groupA = GroupFactory::make()->withGroupsManagersFor([$userAda])->persist();
                    $groupB = GroupFactory::make()->withGroupsManagersFor([$userBetty])->persist();
                    $groupAB = GroupFactory::make()->withGroupsManagersFor([$userAda, $userBetty])->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userAda])->withPermissionsFor([$userAda])->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userBetty])->withPermissionsFor([$userBetty])->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userAda, $userBetty])->withPermissionsFor([$userAda, $userBetty])->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userAda])->withPermissionsFor([$groupA])->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userBetty])->withPermissionsFor([$groupB])->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userAda, $userBetty])->withPermissionsFor([$groupAB])->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userAda, $userBetty])->withPermissionsFor([$groupA, $groupB, $groupAB])->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userAda])->withPermissionsFor([$groupA, $userAda])->persist();
                },
            ],
        ];
    }

    // Find a missing relation for a user having a direct access to a single resource.

    public function testFoldersRelationsFinderTrait_findMissingFoldersRelations_missingResourcesFoldersRelationsOnDirectUserAccess(): void
    {
        $userAda = UserFactory::make()->persist();
        $resourceA = ResourceFactory::make()->withPermissionsFor([$userAda])->persist();
        $this->findAndAssertMissingFoldersRelations(
            FoldersRelation::FOREIGN_MODEL_RESOURCE,
            [[$resourceA, $userAda]]
        );
    }

    // Find a missing relation for a user having a direct access to a single folder and additional control data.

    public function testFoldersRelationsFinderTrait_findMissingFoldersRelations_missingResourcesFoldersRelationsOnDirectUserAccess_WithOtherUsersHavingDirectAndIndirectAccess(): void
    {
        [$userAda, $userBetty, $userCarol] = UserFactory::make(3)->persist();
        $groupC = GroupFactory::make()->withGroupsManagersFor([$userCarol])->persist();
        $resourceA = ResourceFactory::make()->withFoldersRelationsFor([$userBetty, $userCarol])->withPermissionsFor([$userAda, $userBetty, $groupC])->persist();
        $this->findAndAssertMissingFoldersRelations(
            FoldersRelation::FOREIGN_MODEL_RESOURCE,
            [[$resourceA, $userAda]]
        );
    }

    // Find a missing relation for a user having an inherited access to a single folder.

    public function testFoldersRelationsFinderTrait_findMissingFoldersRelations_missingResourcesFoldersRelationsOnInheritedUserAccess(): void
    {
        $userAda = UserFactory::make()->persist();
        $groupA = GroupFactory::make()->withGroupsManagersFor([$userAda])->persist();
        $resourceA = ResourceFactory::make()->withPermissionsFor([$groupA])->persist();
        $this->findAndAssertMissingFoldersRelations(
            FoldersRelation::FOREIGN_MODEL_RESOURCE,
            [[$resourceA, $userAda]]
        );
    }

    // Find a missing relation for a user having an inherited access to a single folder and additional control data.

    public function testFoldersRelationsFinderTrait_findMissingFoldersRelations_missingResourcesFoldersRelationsOnInheritedUserAccess_WithOtherUsersHavingDirectAndIndirectAccess(): void
    {
        [$userAda, $userBetty, $userCarol] = UserFactory::make(3)->persist();
        $groupA = GroupFactory::make()->withGroupsManagersFor([$userAda])->persist();
        $groupC = GroupFactory::make()->withGroupsManagersFor([$userCarol])->persist();
        $resourceA = ResourceFactory::make()->withFoldersRelationsFor([$userBetty, $userCarol])->withPermissionsFor([$groupA, $userBetty, $groupC])->persist();
        $this->findAndAssertMissingFoldersRelations(
            FoldersRelation::FOREIGN_MODEL_RESOURCE,
            [[$resourceA, $userAda]]
        );
    }

    /**
     * @dataProvider noMissingFoldersFoldersRelationsDataProvider
     */
    public function testFoldersRelationsFinderTrait_findMissingFoldersRelations_noMissingFoldersFoldersRelations(Closure $dataProviderClosure): void
    {
        $dataProviderClosure();
        $missingFoldersRelations = $this->foldersRelationsTable->findMissingFoldersRelations(FoldersRelation::FOREIGN_MODEL_FOLDER)->all()->toArray();
        $this->assertEmpty($missingFoldersRelations);
    }

    public function noMissingFoldersFoldersRelationsDataProvider(): array
    {
        return [
            'No folders in database' => [
                function (): void {
                },
            ],
            'Only resources in database' => [
                function (): void {
                    $userAda = UserFactory::make()->persist();
                    $groupA = GroupFactory::make()->withGroupsManagersFor([$userAda])->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userAda])->withPermissionsFor([$userAda])->persist();
                    ResourceFactory::make()->withPermissionsFor([$userAda])->persist();
                    ResourceFactory::make()->withFoldersRelationsFor([$userAda])->withPermissionsFor([$groupA])->persist();
                    ResourceFactory::make()->withPermissionsFor([$groupA])->persist();
                },
            ],
            'A single user having access to a single folder' => [
                function (): void {
                    $userAda = UserFactory::make()->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userAda])->withPermissionsFor([$userAda])->persist();
                },
            ],
            'A single group having access to a single folder' => [
                function (): void {
                    $userAda = UserFactory::make()->persist();
                    $groupA = GroupFactory::make()->withGroupsManagersFor([$userAda])->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userAda])->withPermissionsFor([$groupA])->persist();
                },
            ],
            'Multiple users having access to a single folder' => [
                function (): void {
                    [$userAda, $userBetty] = UserFactory::make(2)->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userAda, $userBetty])->withPermissionsFor([$userAda, $userBetty])->persist();
                },
            ],
            'Multiple groups having access to a single folder' => [
                function (): void {
                    [$userAda, $userBetty] = UserFactory::make(2)->persist();
                    [$groupA, $groupB] = GroupFactory::make(2)->withGroupsManagersFor([$userAda, $userBetty])->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userAda, $userBetty])->withPermissionsFor([$groupA, $groupB])->persist();
                },
            ],
            'A single user having access to a multiple folders' => [
                function (): void {
                    $userAda = UserFactory::make()->persist();
                    FolderFactory::make(2)->withFoldersRelationsFor([$userAda])->withPermissionsFor([$userAda])->persist();
                },
            ],
            'Multiples users having access to a multiple folders' => [
                function (): void {
                    [$userAda, $userBetty] = UserFactory::make(2)->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userAda])->withPermissionsFor([$userAda])->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userBetty])->withPermissionsFor([$userBetty])->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userAda, $userBetty])->withPermissionsFor([$userAda, $userBetty])->persist();
                },
            ],
            'Multiples groups having access to a multiple folders' => [
                function (): void {
                    [$userAda, $userBetty] = UserFactory::make(2)->persist();
                    $groupA = GroupFactory::make()->withGroupsManagersFor([$userAda])->persist();
                    $groupB = GroupFactory::make()->withGroupsManagersFor([$userBetty])->persist();
                    $groupAB = GroupFactory::make()->withGroupsManagersFor([$userAda, $userBetty])->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userAda])->withPermissionsFor([$groupA])->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userBetty])->withPermissionsFor([$groupB])->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userAda, $userBetty])->withPermissionsFor([$groupAB])->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userAda, $userBetty])->withPermissionsFor([$groupA, $groupB, $groupAB])->persist();
                },
            ],
            'Multiples users and groups having access to a multiple folders' => [
                function (): void {
                    [$userAda, $userBetty] = UserFactory::make(2)->persist();
                    $groupA = GroupFactory::make()->withGroupsManagersFor([$userAda])->persist();
                    $groupB = GroupFactory::make()->withGroupsManagersFor([$userBetty])->persist();
                    $groupAB = GroupFactory::make()->withGroupsManagersFor([$userAda, $userBetty])->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userAda])->withPermissionsFor([$userAda])->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userBetty])->withPermissionsFor([$userBetty])->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userAda, $userBetty])->withPermissionsFor([$userAda, $userBetty])->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userAda])->withPermissionsFor([$groupA])->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userBetty])->withPermissionsFor([$groupB])->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userAda, $userBetty])->withPermissionsFor([$groupAB])->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userAda, $userBetty])->withPermissionsFor([$groupA, $groupB, $groupAB])->persist();
                    FolderFactory::make()->withFoldersRelationsFor([$userAda])->withPermissionsFor([$groupA, $userAda])->persist();
                },
            ],
        ];
    }

    // Find a missing relation for a user having a direct access to a single folder.

    public function testFoldersRelationsFinderTrait_findMissingFoldersRelations_missingFoldersFoldersRelationsOnDirectUserAccess(): void
    {
        $userAda = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withPermissionsFor([$userAda])->persist();
        $this->findAndAssertMissingFoldersRelations(
            FoldersRelation::FOREIGN_MODEL_FOLDER,
            [[$folderA, $userAda]]
        );
    }

    // Find a missing relation for a user having a direct access to a single folder and additional control data.

    public function testFoldersRelationsFinderTrait_findMissingFoldersRelations_missingFoldersFoldersRelationsOnDirectUserAccess_WithOtherUsersHavingDirectAndIndirectAccess(): void
    {
        [$userAda, $userBetty, $userCarol] = UserFactory::make(3)->persist();
        $groupC = GroupFactory::make()->withGroupsManagersFor([$userCarol])->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userBetty, $userCarol])->withPermissionsFor([$userAda, $userBetty, $groupC])->persist();
        $this->findAndAssertMissingFoldersRelations(
            FoldersRelation::FOREIGN_MODEL_FOLDER,
            [[$folderA, $userAda]]
        );
    }

    // Find a missing relation for a user having an inherited access to a single folder.

    public function testFoldersRelationsFinderTrait_findMissingFoldersRelations_missingFoldersFoldersRelationsOnInheritedUserAccess(): void
    {
        $userAda = UserFactory::make()->persist();
        $groupA = GroupFactory::make()->withGroupsManagersFor([$userAda])->persist();
        $folderA = FolderFactory::make()->withPermissionsFor([$groupA])->persist();
        $this->findAndAssertMissingFoldersRelations(
            FoldersRelation::FOREIGN_MODEL_FOLDER,
            [[$folderA, $userAda]]
        );
    }

    // Find a missing relation for a user having an inherited access to a single folder and additional control data.
    public function testFoldersRelationsFinderTrait_findMissingFoldersRelations_missingFoldersFoldersRelationsOnInheritedUserAccess_WithOtherUsersHavingDirectAndIndirectAccess(): void
    {
        [$userAda, $userBetty, $userCarol] = UserFactory::make(3)->persist();
        $groupA = GroupFactory::make()->withGroupsManagersFor([$userAda])->persist();
        $groupC = GroupFactory::make()->withGroupsManagersFor([$userCarol])->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userBetty, $userCarol])->withPermissionsFor([$groupA, $userBetty, $groupC])->persist();
        $this->findAndAssertMissingFoldersRelations(
            FoldersRelation::FOREIGN_MODEL_FOLDER,
            [[$folderA, $userAda]]
        );
    }
}
