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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Model\Table\Favorites;

use App\Test\Factory\FavoriteFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\CleanupTrait;
use App\Utility\UuidFactory;
use Cake\I18n\Time;

class CleanupTest extends AppTestCase
{
    use CleanupTrait;

    public function testCleanupFavoritesSoftDeletedUsersSuccess()
    {
        // The favorite to cleanup.
        FavoriteFactory::make()
            ->with('Users', UserFactory::make()->deleted())
            ->persist();
        // The favorites to keep.
        $favoriteWithUser = FavoriteFactory::make()
            ->with('Users')
            ->persist();
        $favoriteWithHardDeletedUser = FavoriteFactory::make()->persist();

        $this->runCleanupChecks('Favorites', 'cleanupSoftDeletedUsers', 2);

        $favoritesIdsPostCleanup = FavoriteFactory::find()->extract('id')->toArray();
        $this->assertCount(2, $favoritesIdsPostCleanup);
        $this->assertContains($favoriteWithUser->id, $favoritesIdsPostCleanup);
        $this->assertContains($favoriteWithHardDeletedUser->id, $favoritesIdsPostCleanup);
    }

    public function testCleanupFavoritesHardDeletedUsersSuccess()
    {
        // The favorite to cleanup.
        FavoriteFactory::make()->persist();
        // The favorites to keep.
        $favoriteWithUser = FavoriteFactory::make()
            ->with('Users')
            ->persist();
        $favoriteWithSoftDeletedUser = FavoriteFactory::make()
            ->with('Users', UserFactory::make()->deleted())
            ->persist();

        $this->runCleanupChecks('Favorites', 'cleanupHardDeletedUsers', 2);

        $favoritesIdsPostCleanup = FavoriteFactory::find()->extract('id')->toArray();
        $this->assertCount(2, $favoritesIdsPostCleanup);
        $this->assertContains($favoriteWithUser->id, $favoritesIdsPostCleanup);
        $this->assertContains($favoriteWithSoftDeletedUser->id, $favoritesIdsPostCleanup);
    }

    public function testCleanupFavoritesSoftDeletedResourcesSuccess()
    {
        // The favorite to cleanup.
        FavoriteFactory::make()
            ->with('Resources', ResourceFactory::make()->deleted())
            ->persist();
        // The favorites to keep.
        $favoriteWithResource = FavoriteFactory::make()
            ->with('Resources')
            ->persist();
        $favoriteWithHardDeletedResource = FavoriteFactory::make()->persist();

        $this->runCleanupChecks('Favorites', 'cleanupSoftDeletedResources', 2);

        $favoritesIdsPostCleanup = FavoriteFactory::find()->extract('id')->toArray();
        $this->assertCount(2, $favoritesIdsPostCleanup);
        $this->assertContains($favoriteWithResource->id, $favoritesIdsPostCleanup);
        $this->assertContains($favoriteWithHardDeletedResource->id, $favoritesIdsPostCleanup);
    }

    public function testCleanupFavoritesHardDeletedResourcesSuccess()
    {
        // The favorite to cleanup.
        FavoriteFactory::make()->persist();
        // The favorites to keep.
        $favoriteWithResource = FavoriteFactory::make()
            ->with('Resources')
            ->persist();
        $favoriteWithSoftDeletedResource = FavoriteFactory::make()
            ->with('Resources', ResourceFactory::make()->deleted())
            ->persist();

        $this->runCleanupChecks('Favorites', 'cleanupHardDeletedResources', 2);

        $favoritesIdsPostCleanup = FavoriteFactory::find()->extract('id')->toArray();
        $this->assertCount(2, $favoritesIdsPostCleanup);
        $this->assertContains($favoriteWithResource->id, $favoritesIdsPostCleanup);
        $this->assertContains($favoriteWithSoftDeletedResource->id, $favoritesIdsPostCleanup);
    }

    public function testCleanupFavoritesDuplicatedFavorites()
    {
        // Duplicated favorites to cleanup.
        $duplicateFavoriteMeta = [
            'user_id' => UuidFactory::uuid(),
            'foreign_key' => UuidFactory::uuid(),
            'foreign_model' => 'Resource',
            'modified' => Time::now(),
        ];
        FavoriteFactory::make($duplicateFavoriteMeta)->persist();

        // Duplicate favorite to keep as it is the oldest.
        $duplicateFavoriteToKeep = FavoriteFactory::make($duplicateFavoriteMeta)->patchData(['modified' => Time::now()->subDay()])->persist();

        // Witness favorites to not cleanup:
        // - A favorite including a user involved in the cleanup
        // - A favorite including a foreign key involved in the cleanup
        // - A user having 2 different resources as favorite.
        // - A resource marked as favorite by 2 different users.
        $favoriteWithUserInvolvedInCleanup = FavoriteFactory::make($duplicateFavoriteMeta)->patchData(['foreign_key' => UuidFactory::uuid()])->persist();
        $favoriteWithForeignKeyInvolvedInCleanup = FavoriteFactory::make($duplicateFavoriteMeta)->patchData(['user_id' => UuidFactory::uuid()])->persist();
        $resources = ResourceFactory::make(2)->persist();
        $users = UserFactory::make(2)->persist();
        $favoriteToKeep1 = FavoriteFactory::make(['user_id' => $users[0]->id, 'foreign_key' => $resources[0]->id, 'foreign_model' => 'Resource'])->persist();
        $favoriteToKeep2 = FavoriteFactory::make(['user_id' => $users[0]->id, 'foreign_key' => $resources[1]->id, 'foreign_model' => 'Resource'])->persist();
        $favoriteToKeep3 = FavoriteFactory::make(['user_id' => $users[1]->id, 'foreign_key' => $resources[0]->id, 'foreign_model' => 'Resource'])->persist();
        $favoriteToKeep4 = FavoriteFactory::make(['user_id' => $users[1]->id, 'foreign_key' => $resources[1]->id, 'foreign_model' => 'Resource'])->persist();

        $this->runCleanupChecks('Favorites', 'cleanupDuplicatedFavorites', 7);

        $favoritesIdsPostCleanup = FavoriteFactory::find()->extract('id')->toArray();
        $this->assertCount(7, $favoritesIdsPostCleanup);
        $this->assertContains($duplicateFavoriteToKeep->id, $favoritesIdsPostCleanup);
        $this->assertContains($favoriteWithUserInvolvedInCleanup->id, $favoritesIdsPostCleanup);
        $this->assertContains($favoriteWithForeignKeyInvolvedInCleanup->id, $favoritesIdsPostCleanup);
        $this->assertContains($favoriteToKeep1->id, $favoritesIdsPostCleanup);
        $this->assertContains($favoriteToKeep2->id, $favoritesIdsPostCleanup);
        $this->assertContains($favoriteToKeep3->id, $favoritesIdsPostCleanup);
        $this->assertContains($favoriteToKeep4->id, $favoritesIdsPostCleanup);
    }
}
