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

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FavoritesTable;
use App\Test\TestCase\ApplicationTest;
use App\Utility\Common;
use Cake\ORM\TableRegistry;

class DeleteTest extends ApplicationTest
{
    public $Favorites;

    public $fixtures = ['app.users', 'app.favorites'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Favorites') ? [] : ['className' => FavoritesTable::class];
        $this->Favorites = TableRegistry::get('Favorites', $config);
    }

    public function tearDown()
    {
        unset($this->Favorites);

        parent::tearDown();
    }

    public function testSuccess()
    {
        $favoriteId = Common::uuid('favorite.id.dame-apache');
        $favorite = $this->Favorites->get($favoriteId);
        $delete = $this->Favorites->delete($favorite, ['Favorites.user_id' => Common::uuid('user.id.dame')]);
        $this->assertTrue($delete);
    }

    public function testErrorIsOwnerRule()
    {
        $favoriteId = Common::uuid('favorite.id.dame-apache');
        $favorite = $this->Favorites->get($favoriteId);
        $delete = $this->Favorites->delete($favorite, ['Favorites.user_id' => Common::uuid('user.id.ada')]);
        $this->assertFalse($delete);
        $errors = $favorite->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['is_owner']);
    }
}
