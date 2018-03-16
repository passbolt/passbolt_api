<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
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
    public $fixtures = ['app.Base/users', 'app.Base/resources', 'app.Base/secrets', 'app.Base/favorites'];

    public function testDeleteSuccess()
    {
        $this->authenticateAs('dame');
        $favoriteId = UuidFactory::uuid('favorite.id.dame-apache');
        $this->deleteJson("/favorites/$favoriteId.json?api-version=2");
        $this->assertSuccess();
        $Favorites = TableRegistry::get('Favorites');
        $deletedFavorite = $Favorites->find('all')->where(['Favorites.id' => $favoriteId])->first();
        $this->assertempty($deletedFavorite);
    }

    public function testDeleteErrorNotValidId()
    {
        $this->authenticateAs('dame');
        $favoriteId = 'invalid-id';
        $this->deleteJson("/favorites/$favoriteId.json?api-version=v1");
        $this->assertError(400, 'The favorite id is not valid.');
    }

    public function testDeleteErrorFavoritesNotFound()
    {
        $this->authenticateAs('dame');
        $favoriteId = UuidFactory::uuid();
        $this->deleteJson("/favorites/$favoriteId.json?api-version=v1");
        $this->assertError(404, 'The favorite does not exist.');
    }

    public function testDeleteErrorFavoritesOfSomeoneElse()
    {
        $this->authenticateAs('ada');
        $favoriteId = UuidFactory::uuid('favorite.id.dame-apache');
        $this->deleteJson("/favorites/$favoriteId.json?api-version=v1");
        $this->assertError(404, 'The favorite does not exist.');
    }

    public function testDeleteErrorNotAuthenticated()
    {
        $favoriteId = UuidFactory::uuid('favorite.id.dame-apache');
        $this->deleteJson("/favorites/$favoriteId.json?api-version=2");
        $this->assertAuthenticationError();
    }
}
