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

use App\Model\Table\FavoritesTable;
use App\Test\Factory\FavoriteFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;

class DeleteTest extends AppTestCase
{
    public $Favorites;

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Favorites') ? [] : ['className' => FavoritesTable::class];
        $this->Favorites = TableRegistry::getTableLocator()->get('Favorites', $config);
    }

    public function tearDown(): void
    {
        unset($this->Favorites);

        parent::tearDown();
    }

    public function testSuccess()
    {
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $favorite = FavoriteFactory::make()->setUser($user)->setResource($resource)->persist();
        $favoriteId = $favorite->get('id');
        $favorite = $this->Favorites->get($favoriteId);
        $delete = $this->Favorites->delete($favorite, ['Favorites.user_id' => $user->id]);
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
        $user = UserFactory::make()->user()->persist();
        $owner = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($owner)->persist();
        $favorite = FavoriteFactory::make()->setUser($owner)->setResource($resource)->persist();
        $favoriteId = $favorite->get('id');
        $favorite = $this->Favorites->get($favoriteId);
        $delete = $this->Favorites->delete($favorite, ['Favorites.user_id' => $user->id]);
        $this->assertFalse($delete);
        $errors = $favorite->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['is_owner']);
    }
}
