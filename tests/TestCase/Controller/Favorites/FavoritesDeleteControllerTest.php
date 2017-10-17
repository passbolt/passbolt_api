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

use App\Test\TestCase\ApplicationTest;
use App\Utility\Common;
use Cake\ORM\TableRegistry;

class FavoritesDeleteControllerTest extends ApplicationTest
{
    public $fixtures = ['app.users', 'app.resources', 'app.secrets', 'app.favorites'];

    public function testDeleteSuccess()
    {
        $this->authenticateAs('dame');
        $favoriteId = Common::uuid('favorite.id.dame-apache');
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
        $this->deleteJson("/favorites/$favoriteId.json");
        $this->assertError(400, 'The favorite id is not valid.');
    }

    public function testDeleteErrorFavoritesNotFound()
    {
        $this->authenticateAs('dame');
        $favoriteId = Common::uuid();
        $this->deleteJson("/favorites/$favoriteId.json");
        $this->assertError(404, 'The favorite does not exist.');
    }

    public function testDeleteErrorFavoritesOfSomeoneElse()
    {
        $this->authenticateAs('ada');
        $favoriteId = Common::uuid('favorite.id.dame-apache');
        $this->deleteJson("/favorites/$favoriteId.json");
        $this->assertError(404, 'The favorite does not exist.');
    }

    public function testDeleteErrorNotAuthenticated()
    {
        $favoriteId = Common::uuid('favorite.id.dame-apache');
        $this->deleteJson("/favorites/$favoriteId.json?api-version=2");
        $this->assertAuthenticationError();
    }
}
