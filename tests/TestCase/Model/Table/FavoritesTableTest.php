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
use App\Utility\Common;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FavoritesTable Test Case
 */
class FavoritesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FavoritesTable
     */
    public $Favorites;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = ['app.users', 'app.groups', 'app.groups_users', 'app.resources', 'app.favorites', 'app.permissions'];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Favorites') ? [] : ['className' => FavoritesTable::class];
        $this->Favorites = TableRegistry::get('Favorites', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Favorites);

        parent::tearDown();
    }

    public function testSaveSuccess()
    {
        $favorite = $this->Favorites->newEntity(
            [
                'user_id' => Common::uuid('user.id.dame'),
                'foreign_id' => Common::uuid('resource.id.bower'),
                'foreign_model' => 'Resource',
            ],
            [
                'validate' => 'default',
                'accessibleFields' => [
                    'user_id' => true,
                    'foreign_id' => true,
                    'foreign_model' => true
                ]
            ]
        );
        $save = $this->Favorites->save($favorite);
        $this->assertNotNull($save);
        $errors = $favorite->getErrors();
        $this->assertEmpty($errors);
    }

    public function testSaveErrorUserExists()
    {
        $favorite = $this->Favorites->newEntity(
            [
                'user_id' => Common::uuid(),
                'foreign_id' => Common::uuid('resource.id.apache'),
                'foreign_model' => 'Resource',
            ],
            [
                'validate' => 'default',
                'accessibleFields' => [
                    'user_id' => true,
                    'foreign_id' => true,
                    'foreign_model' => true
                ]
            ]
        );
        $save = $this->Favorites->save($favorite);
        $this->assertFalse($save);
        $errors = $favorite->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['user_exists']);
    }

    public function testSaveErrorUserNotSoftDeleted()
    {
        $favorite = $this->Favorites->newEntity(
            [
                'user_id' => Common::uuid('user.id.sofia'),
                'foreign_id' => Common::uuid('resource.id.apache'),
                'foreign_model' => 'Resource',
            ],
            [
                'validate' => 'default',
                'accessibleFields' => [
                    'user_id' => true,
                    'foreign_id' => true,
                    'foreign_model' => true
                ]
            ]
        );
        $save = $this->Favorites->save($favorite);
        $this->assertFalse($save);
        $errors = $favorite->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['user_not_soft_deleted']);
    }

    public function testSaveErrorResourceExists()
    {
        $favorite = $this->Favorites->newEntity(
            [
                'user_id' => Common::uuid('user.id.dame'),
                'foreign_id' => Common::uuid(),
                'foreign_model' => 'Resource',
            ],
            [
                'validate' => 'default',
                'accessibleFields' => [
                    'user_id' => true,
                    'foreign_id' => true,
                    'foreign_model' => true
                ]
            ]
        );
        $save = $this->Favorites->save($favorite);
        $this->assertFalse($save);
        $errors = $favorite->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['foreign_id']['resource_exists']);
    }

    public function testSaveErrorResourceNotSoftDeleted()
    {
        $favorite = $this->Favorites->newEntity(
            [
                'user_id' => Common::uuid('user.id.dame'),
                'foreign_id' => Common::uuid('resource.id.jquery'),
                'foreign_model' => 'Resource',
            ],
            [
                'validate' => 'default',
                'accessibleFields' => [
                    'user_id' => true,
                    'foreign_id' => true,
                    'foreign_model' => true
                ]
            ]
        );
        $save = $this->Favorites->save($favorite);
        $this->assertFalse($save);
        $errors = $favorite->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['foreign_id']['resource_not_soft_deleted']);
    }

    public function testSaveErrorFavoriteUniqueRule()
    {
        $favorite = $this->Favorites->newEntity(
            [
                'user_id' => Common::uuid('user.id.dame'),
                'foreign_id' => Common::uuid('resource.id.apache'),
                'foreign_model' => 'Resource',
            ],
            [
                'validate' => 'default',
                'accessibleFields' => [
                    'user_id' => true,
                    'foreign_id' => true,
                    'foreign_model' => true
                ]
            ]
        );
        $save = $this->Favorites->save($favorite);
        $this->assertFalse($save);
        $errors = $favorite->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['favorite_unique']);
    }

    public function testSaveErrorHasResourceAccessRule()
    {
        $favorite = $this->Favorites->newEntity(
            [
                'user_id' => Common::uuid('user.id.dame'),
                'foreign_id' => Common::uuid('resource.id.canjs'),
                'foreign_model' => 'Resource',
            ],
            [
                'validate' => 'default',
                'accessibleFields' => [
                    'user_id' => true,
                    'foreign_id' => true,
                    'foreign_model' => true
                ]
            ]
        );
        $save = $this->Favorites->save($favorite);
        $this->assertFalse($save);
        $errors = $favorite->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['foreign_id']['has_resource_access']);
    }

    public function testDeleteSuccess()
    {
        $favoriteId = Common::uuid('favorite.id.dame-apache');
        $favorite = $this->Favorites->get($favoriteId);
        $delete = $this->Favorites->delete($favorite, ['Favorites.user_id' => Common::uuid('user.id.dame')]);
        $this->assertTrue($delete);
    }

    public function testDeleteErrorIsOwnerRule()
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
