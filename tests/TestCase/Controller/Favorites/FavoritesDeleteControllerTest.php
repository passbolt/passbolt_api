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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Controller\Favorites;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class FavoritesDeleteControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/Users', 'app.Base/Resources', 'app.Base/Secrets', 'app.Base/Favorites'];

    public function testFavoritesDeleteSuccess()
    {
        $this->authenticateAs('dame');
        $favoriteId = UuidFactory::uuid('favorite.id.dame-apache');
        $this->deleteJson("/favorites/$favoriteId.json?api-version=2");
        $this->assertSuccess();
        $Favorites = TableRegistry::getTableLocator()->get('Favorites');
        $deletedFavorite = $Favorites->find('all')->where(['Favorites.id' => $favoriteId])->first();
        $this->assertempty($deletedFavorite);
    }

    public function testFavoritesDeleteErrorCsrfToken()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('dame');
        $favoriteId = UuidFactory::uuid('favorite.id.dame-apache');
        $this->delete("/favorites/$favoriteId.json?api-version=2");
        $this->assertResponseCode(403);
    }

    public function testFavoritesDeleteErrorNotValidId()
    {
        $this->authenticateAs('dame');
        $favoriteId = 'invalid-id';
        $this->deleteJson("/favorites/$favoriteId.json");
        $this->assertError(400, 'The favorite id is not valid.');
    }

    public function testFavoritesDeleteErrorFavoritesNotFound()
    {
        $this->authenticateAs('dame');
        $favoriteId = UuidFactory::uuid();
        $this->deleteJson("/favorites/$favoriteId.json");
        $this->assertError(404, 'The favorite does not exist.');
    }

    public function testFavoritesDeleteErrorFavoritesOfSomeoneElse()
    {
        $this->authenticateAs('ada');
        $favoriteId = UuidFactory::uuid('favorite.id.dame-apache');
        $this->deleteJson("/favorites/$favoriteId.json");
        $this->assertError(404, 'The favorite does not exist.');
    }

    public function testFavoritesDeleteErrorNotAuthenticated()
    {
        $favoriteId = UuidFactory::uuid('favorite.id.dame-apache');
        $this->deleteJson("/favorites/$favoriteId.json?api-version=2");
        $this->assertAuthenticationError();
    }
}
