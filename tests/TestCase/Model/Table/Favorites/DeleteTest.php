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

namespace App\Test\TestCase\Model\Table\Favorites;

use App\Model\Table\FavoritesTable;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;

class DeleteTest extends AppTestCase
{
    public $Favorites;

    public $fixtures = ['app.Base/Users', 'app.Base/Favorites'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Favorites') ? [] : ['className' => FavoritesTable::class];
        $this->Favorites = TableRegistry::getTableLocator()->get('Favorites', $config);
    }

    public function tearDown()
    {
        unset($this->Favorites);

        parent::tearDown();
    }

    public function testSuccess()
    {
        $favoriteId = UuidFactory::uuid('favorite.id.dame-apache');
        $favorite = $this->Favorites->get($favoriteId);
        $delete = $this->Favorites->delete($favorite, ['Favorites.user_id' => UuidFactory::uuid('user.id.dame')]);
        $this->assertTrue($delete);
        // Check the favorite is well deleted in db.
        try {
            $this->Favorites->get($favoriteId);
            $this->assertFalse(true);
        } catch (RecordNotFoundException $e) {
            $this->assertTrue(true);
        }
    }

    public function testErrorIsOwnerRule()
    {
        $favoriteId = UuidFactory::uuid('favorite.id.dame-apache');
        $favorite = $this->Favorites->get($favoriteId);
        $delete = $this->Favorites->delete($favorite, ['Favorites.user_id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertFalse($delete);
        $errors = $favorite->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['is_owner']);
    }
}
