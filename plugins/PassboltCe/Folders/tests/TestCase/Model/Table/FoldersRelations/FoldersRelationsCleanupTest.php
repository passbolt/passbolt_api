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

namespace Passbolt\Folders\Test\TestCase\Model\Table\FoldersRelations;

use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\CleanupTrait;
use Cake\I18n\FrozenTime;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\FoldersRelationFactory;
use Passbolt\Folders\Test\Factory\PermissionFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Model\Table\FoldersRelationsTable Test Case
 */
class FoldersRelationsCleanupTest extends FoldersTestCase
{
    use CleanupTrait;
    use FoldersRelationsModelTrait;

    public function testCleanupFoldersRelationsSoftDeletedResourcesSuccess()
    {
        // The folder relation to cleanup.
        FoldersRelationFactory::make()
            ->withForeignModelResource(ResourceFactory::make()->deleted())
            ->persist();

        // Witness folders relations to not cleanup.
        $folderRelationWithNonDeletedResource = FoldersRelationFactory::make()
            ->foreignModelResource()
            ->with('Resources')
            ->persist();
        $folderRelationWithHardDeletedResource = FoldersRelationFactory::make()->foreignModelResource()->persist();

        $this->runCleanupChecks('Passbolt/Folders.FoldersRelations', 'cleanupSoftDeletedResources', 2);

        $foldersRelationsIdsPostCleanup = FoldersRelationFactory::find()->all()->extract('id')->toArray();
        $this->assertCount(2, $foldersRelationsIdsPostCleanup);
        $this->assertContains($folderRelationWithNonDeletedResource->id, $foldersRelationsIdsPostCleanup);
        $this->assertContains($folderRelationWithHardDeletedResource->id, $foldersRelationsIdsPostCleanup);
    }

    public function testCleanupFoldersRelationsHardDeletedResourcesSuccess()
    {
        // The folder relation to cleanup.
        FoldersRelationFactory::make()->foreignModelResource()->persist();

        // Witness folders relations to not cleanup.
        $folderRelationWithNonDeletedResource = FoldersRelationFactory::make()
            ->foreignModelResource()
            ->with('Resources')
            ->persist();
        $folderRelationWithSoftDeletedResource = FoldersRelationFactory::make()
            ->withForeignModelResource(ResourceFactory::make()->deleted())
            ->persist();

        $this->runCleanupChecks('Passbolt/Folders.FoldersRelations', 'cleanupHardDeletedResources', 2);

        $foldersRelationsIdsPostCleanup = FoldersRelationFactory::find()->all()->extract('id')->toArray();
        $this->assertCount(2, $foldersRelationsIdsPostCleanup);
        $this->assertContains($folderRelationWithNonDeletedResource->id, $foldersRelationsIdsPostCleanup);
        $this->assertContains($folderRelationWithSoftDeletedResource->id, $foldersRelationsIdsPostCleanup);
    }

    public function testCleanupFoldersRelationsHardDeletedFoldersSuccess()
    {
        // The folder relation to cleanup.
        FoldersRelationFactory::make()->foreignModelFolder()->persist();

        // Witness folders relations to not cleanup.
        $folderRelationWithNonDeletedFolder = FoldersRelationFactory::make()
            ->foreignModelFolder()
            ->with('Folders')
            ->persist();
        $folderRelationWithSoftDeletedFolder = FoldersRelationFactory::make()
            ->withForeignModelFolder(FolderFactory::make()->deleted())
            ->persist();

        $this->runCleanupChecks('Passbolt/Folders.FoldersRelations', 'cleanupHardDeletedFolders', 2);

        $foldersRelationsIdsPostCleanup = FoldersRelationFactory::find()->all()->extract('id')->toArray();
        $this->assertCount(2, $foldersRelationsIdsPostCleanup);
        $this->assertContains($folderRelationWithNonDeletedFolder->id, $foldersRelationsIdsPostCleanup);
        $this->assertContains($folderRelationWithSoftDeletedFolder->id, $foldersRelationsIdsPostCleanup);
    }

    public function testCleanupFoldersRelationsHardDeletedFoldersParentsSuccess()
    {
        // The folder relation to cleanup.
        FoldersRelationFactory::make()->persist();

        // Witness folders relations to not cleanup.
        $folderRelationWithNonDeletedFolderParent = FoldersRelationFactory::make()
            ->with('FoldersParents')
            ->persist();
        $folderRelationWithSoftDeletedFolderParent = FoldersRelationFactory::make()
            ->withFolderParent(FolderFactory::make()->deleted())
            ->persist();

        $this->runCleanupChecks('Passbolt/Folders.FoldersRelations', 'cleanupHardDeletedFoldersParents', 2);

        $foldersRelationsIdsPostCleanup = FoldersRelationFactory::find()->all()->extract('id')->toArray();
        $this->assertCount(2, $foldersRelationsIdsPostCleanup);
        $this->assertContains($folderRelationWithNonDeletedFolderParent->id, $foldersRelationsIdsPostCleanup);
        $this->assertContains($folderRelationWithSoftDeletedFolderParent->id, $foldersRelationsIdsPostCleanup);
    }

    public function testCleanupFoldersRelationsSoftDeletedUsersSuccess()
    {
        // The folder relation to cleanup.
        FoldersRelationFactory::make()
            ->with('Users', UserFactory::make()->deleted())
            ->persist();

        // Witness folders relations to not cleanup.
        $folderRelationWithNonDeletedUser = FoldersRelationFactory::make()
            ->with('Users')
            ->persist();
        $folderRelationWithHardDeletedUser = FoldersRelationFactory::make()->persist();

        $this->runCleanupChecks('Passbolt/Folders.FoldersRelations', 'cleanupSoftDeletedUsers', 2);

        $foldersRelationsIdsPostCleanup = FoldersRelationFactory::find()->all()->extract('id')->toArray();
        $this->assertCount(2, $foldersRelationsIdsPostCleanup);
        $this->assertContains($folderRelationWithNonDeletedUser->id, $foldersRelationsIdsPostCleanup);
        $this->assertContains($folderRelationWithHardDeletedUser->id, $foldersRelationsIdsPostCleanup);
    }

    public function testCleanupFoldersRelationsHardDeletedUsersSuccess()
    {
        // The folder relation to cleanup.
        FoldersRelationFactory::make()->persist();

        // Witness folders relations to not cleanup.
        $folderRelationWithNonDeletedUser = FoldersRelationFactory::make()
            ->with('Users')
            ->persist();
        $folderRelationWithSoftDeletedUser = FoldersRelationFactory::make()
            ->with('Users', UserFactory::make()->deleted())
            ->persist();

        $this->runCleanupChecks('Passbolt/Folders.FoldersRelations', 'cleanupHardDeletedUsers', 2);

        $foldersRelationsIdsPostCleanup = FoldersRelationFactory::find()->all()->extract('id')->toArray();
        $this->assertCount(2, $foldersRelationsIdsPostCleanup);
        $this->assertContains($folderRelationWithNonDeletedUser->id, $foldersRelationsIdsPostCleanup);
        $this->assertContains($folderRelationWithSoftDeletedUser->id, $foldersRelationsIdsPostCleanup);
    }

    public function testCleanupMissingFoldersFoldersRelationsSuccess()
    {
        // The cleanup operation requires an admin to add the missing folders relations.
        UserFactory::make()->admin()->persist();

        [$userAda, $userBetty, $userCarol] = UserFactory::make(3)->persist();

        // Witness folders relations to not cleanup:
        // - A folder at the root of multiple users trees
        // - A sub folder of a user tree
        $folderA = FolderFactory::make()->withPermissionsFor([$userAda, $userBetty])->withFoldersRelationsFor([$userAda, $userBetty])->persist();
        $folderB = FolderFactory::make()->withPermissionsFor([$userAda])->withFoldersRelationsFor([$userAda], $folderA)->persist();

        // The folders missing a folder relation:
        // All the scenarios won't be tested as the cleanup operation relies on the addItemsToUserTree service.
        // - A user having access to a folder.
        $userFolderPermissionWithNoFolderRelation = PermissionFactory::make()->withAcoFolder()->typeOwner()->aroUser($userCarol)->persist();
        // - A user having access to a shared folder located in a shared folder visible in the user tree.
        PermissionFactory::make()->acoFolder($folderB)->typeOwner()->aroUser($userBetty)->persist();
        // - A user having access to a shared folder located in a shared folder not visible in the user tree.
        PermissionFactory::make()->acoFolder($folderB)->typeOwner()->aroUser($userCarol)->persist();

        $cleanupOptions = ['isDeleteCleanup' => false, 'cleanupCount' => 3];
        $this->runCleanupChecks('Passbolt/Folders.FoldersRelations', 'cleanupMissingFoldersFoldersRelations', 6, $cleanupOptions);

        // Assert the witness.
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAda->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBetty->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAda->id, $folderA->id);

        // Assert the cleaned-up.
        $this->assertFolderRelation($userFolderPermissionWithNoFolderRelation->get('folder')->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCarol->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBetty->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCarol->id, FoldersRelation::ROOT);
    }

    public function testCleanupMissingResourcesFoldersRelationsSuccess()
    {
        // The cleanup operation requires an admin to add the missing folders relations.
        UserFactory::make()->admin()->persist();

        [$userAda, $userBetty, $userCarol] = UserFactory::make(3)->persist();

        // Witness folders relations to not cleanup:
        // - A folder at the root of multiple users trees
        // - A resource placed in a folder of a user tree
        $folderA = FolderFactory::make()->withPermissionsFor([$userAda, $userBetty])->withFoldersRelationsFor([$userAda, $userBetty])->persist();
        /** @var \App\Model\Entity\Resource $resourceA */
        $resourceA = ResourceFactory::make()->withFoldersRelationsFor([$userAda], $folderA)->withPermissionsFor([$userAda])->persist();

        // The resources missing a folder relation:
        // All the scenarios won't be tested as the cleanup operation relies on the addItemsToUserTree service.
        // - A user having access to a resource.
        $userResourcePermissionWithNoFolderRelation = PermissionFactory::make()->typeOwner()->withAcoResource()->aroUser($userCarol)->persist();
        // - A user having access to a shared folder located in a shared folder visible in the user tree.
        PermissionFactory::make()->typeOwner()->acoResource($resourceA)->aroUser($userBetty)->persist();
        // - A user having access to a shared folder located in a shared folder not visible in the user tree.
        PermissionFactory::make()->typeOwner()->acoResource($resourceA)->aroUser($userCarol)->persist();

        $cleanupOptions = ['isDeleteCleanup' => false, 'cleanupCount' => 3];
        $this->runCleanupChecks('Passbolt/Folders.FoldersRelations', 'cleanupMissingResourcesFoldersRelations', 6, $cleanupOptions);

        // Assert the witness.
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAda->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBetty->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($resourceA->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAda->id, $folderA->id);

        // Assert the cleaned-up.
        $this->assertFolderRelation($userResourcePermissionWithNoFolderRelation->resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userCarol->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($resourceA->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBetty->id, $folderA->id);
        $this->assertFolderRelation($resourceA->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userCarol->id, FoldersRelation::ROOT);
    }

    public function testCleanupDuplicatedFoldersRelations()
    {
        // Original folders relations to keep.
        $originalFolderFolderRelationToKeep = FoldersRelationFactory::make(['modified' => FrozenTime::now()->subDays(1)])->withForeignModelFolder()->withUser()->withFolderParent()->persist();
        $originalFolderRelationToKeepMeta = $originalFolderFolderRelationToKeep->extractOriginal(['foreign_model', 'foreign_id', 'user_id', 'folder_parent_id', 'modified']);
        $originalResourceFolderRelationToKeep = FoldersRelationFactory::make(['modified' => FrozenTime::now()->subDays(1)])->withForeignModelResource()->withUser()->withFolderParent()->persist();
        $originalResourceRelationToKeepMeta = $originalResourceFolderRelationToKeep->extractOriginal(['foreign_model', 'foreign_id', 'user_id', 'folder_parent_id', 'modified']);
        $originalResourceFolderRelationAtRootToKeep = FoldersRelationFactory::make(['modified' => FrozenTime::now()->subDays(1)])->withForeignModelResource()->withUser()->folderParent(FoldersRelation::ROOT)->persist();
        $originalResourceRelationAtRootMeta = $originalResourceFolderRelationAtRootToKeep->extractOriginal(['foreign_model', 'foreign_id', 'user_id', 'folder_parent_id', 'modified']);

        // Duplicated foldersRelations to cleanup.
        FoldersRelationFactory::make($originalFolderRelationToKeepMeta, 2)->setField('modified', FrozenTime::now())->persist();
        FoldersRelationFactory::make($originalResourceRelationToKeepMeta, 2)->setField('modified', FrozenTime::now())->persist();
        FoldersRelationFactory::make($originalResourceRelationAtRootMeta, 2)->setField('modified', FrozenTime::now())->persist();

        // Witness folders relations to not cleanup:
        // - A folder relation including a resource involved in the cleanup
        // - A folder relation including a folder involved in the cleanup
        // - A folder relation including a user involved in the cleanup
        // - A folder relation including a folder parent involved in the cleanup
        // - A user having a folder at its root
        // - A user having a folder as sub folder
        // - A user having a resource at its root
        // - A user having a resource located in a folder
        $folderRelationInvolvingResourceCleanedUp = FoldersRelationFactory::make()->foreignModelResource($originalResourceFolderRelationToKeep->resource)->withUser()->withFolderParent()->persist();
        $folderRelationInvolvingFolderCleanedUp = FoldersRelationFactory::make()->foreignModelFolder($originalFolderFolderRelationToKeep->folder)->withUser()->withFolderParent()->persist();
        $folderRelationInvolvingUserCleanedUp = FoldersRelationFactory::make()->withForeignModelResource()->user($originalFolderFolderRelationToKeep->user)->withFolderParent()->persist();
        $rootFolderFolderRelation = FoldersRelationFactory::make()->foreignModelFolder()->folderParent(FoldersRelation::ROOT)->persist();
        $subFolderFolderRelation = FoldersRelationFactory::make()->foreignModelFolder()->withFolderParent()->persist();
        $rootResourceFolderRelation = FoldersRelationFactory::make()->foreignModelResource()->folderParent(FoldersRelation::ROOT)->persist();
        $subResourceFolderRelation = FoldersRelationFactory::make()->foreignModelResource()->withFolderParent()->persist();

        $this->runCleanupChecks('Passbolt/Folders.FoldersRelations', 'cleanupDuplicatedFoldersRelations', 10, ['cleanupCount' => 6]);

        $foldersRelationsIdsPostCleanup = FoldersRelationFactory::find()->all()->extract('id')->toArray();
        $this->assertCount(10, $foldersRelationsIdsPostCleanup);
        $this->assertContains($originalFolderFolderRelationToKeep->id, $foldersRelationsIdsPostCleanup);
        $this->assertContains($originalResourceFolderRelationToKeep->id, $foldersRelationsIdsPostCleanup);
        $this->assertContains($originalResourceFolderRelationAtRootToKeep->id, $foldersRelationsIdsPostCleanup);
        $this->assertContains($folderRelationInvolvingResourceCleanedUp->id, $foldersRelationsIdsPostCleanup);
        $this->assertContains($folderRelationInvolvingFolderCleanedUp->id, $foldersRelationsIdsPostCleanup);
        $this->assertContains($folderRelationInvolvingUserCleanedUp->id, $foldersRelationsIdsPostCleanup);
        $this->assertContains($rootFolderFolderRelation->id, $foldersRelationsIdsPostCleanup);
        $this->assertContains($subFolderFolderRelation->id, $foldersRelationsIdsPostCleanup);
        $this->assertContains($rootResourceFolderRelation->id, $foldersRelationsIdsPostCleanup);
        $this->assertContains($subResourceFolderRelation->id, $foldersRelationsIdsPostCleanup);
    }
}
