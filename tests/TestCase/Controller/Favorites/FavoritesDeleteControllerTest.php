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

namespace App\Test\TestCase\Controller\Favorites;

use App\Test\Factory\FavoriteFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;

class FavoritesDeleteControllerTest extends AppIntegrationTestCase
{
    public function testFavoritesDeleteController_Success()
    {
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $favorite = FavoriteFactory::make()->setUser($user)->setResource($resource)->persist();
        $favoriteId = $favorite->get('id');
        $this->logInAs($user);

        $this->deleteJson("/favorites/$favoriteId.json?api-version=2");

        $this->assertSuccess();
        $deletedFavorite = FavoriteFactory::find()->where(['Favorites.id' => $favoriteId])->first();
        $this->assertempty($deletedFavorite);
    }

    public function testFavoritesDeleteController_ErrorCsrfToken()
    {
        $this->disableCsrfToken();
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $favorite = FavoriteFactory::make()->setUser($user)->setResource($resource)->persist();
        $favoriteId = $favorite->get('id');
        $this->logInAs($user);

        $this->delete("/favorites/$favoriteId.json");

        $this->assertResponseCode(403);
    }

    public function testFavoritesDeleteController_ErrorNotValidId()
    {
        $user = UserFactory::make()->user()->persist();
        $favoriteId = 'invalid-id';
        $this->logInAs($user);

        $this->deleteJson("/favorites/$favoriteId.json");

        $this->assertError(400, 'The favorite id is not valid.');
    }

    public function testFavoritesDeleteController_ErrorNotAuthenticated()
    {
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $favorite = FavoriteFactory::make()->setUser($user)->setResource($resource)->persist();
        $favoriteId = $favorite->get('id');

        $this->deleteJson("/favorites/$favoriteId.json");

        $this->assertAuthenticationError();
    }
}
