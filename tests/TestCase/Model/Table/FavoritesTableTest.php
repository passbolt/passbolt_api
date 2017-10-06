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
    public $fixtures = ['app.users', 'app.resources', 'app.secrets', 'app.favorites'];

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

    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testSuccessValidateResourceExists()
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
        $this->assertTrue($this->Favorites->validateResourceExists($favorite, []));
    }

    public function testErrorValidateResourceExistsRandomUuid()
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
        $this->assertFalse($this->Favorites->validateResourceExists($favorite, []));
    }

    public function testErrorValidateResourceExistsDeletedResource()
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
        $this->assertFalse($this->Favorites->validateResourceExists($favorite, []));
    }


    public function testErrorValidateResourceExistsAccessDenied()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testSuccessValidateUserExists()
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
        $this->assertTrue($this->Favorites->validateUserExists($favorite, []));
    }

    public function testErrorValidateUserExistsRandomUuid()
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
        $this->assertFalse($this->Favorites->validateUserExists($favorite, []));
    }

    public function testErrorValidateUserExistsDeletedUser()
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
        $this->assertFalse($this->Favorites->validateUserExists($favorite, []));
    }

    public function testSuccessValidateFavoriteUnique()
    {
        $favorite = $this->Favorites->newEntity(
            [
                'user_id' => Common::uuid('user.id.ada'),
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
        $this->assertTrue($this->Favorites->validateFavoriteUnique($favorite, []));
    }

    public function testErrorValidateFavoriteUnique()
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
        $this->assertFalse($this->Favorites->validateFavoriteUnique($favorite, []));
    }
}
