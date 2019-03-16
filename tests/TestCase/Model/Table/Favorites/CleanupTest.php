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

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\CleanupTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class CleanupTest extends AppTestCase
{

    public $Favorites;
    public $Groups;
    public $fixtures = [
        'app.Base/Users', 'app.Alt0/Permissions', 'app.Base/Resources', 'app.Base/Favorites'
    ];
    public $options;

    use CleanupTrait;

    public function setUp()
    {
        parent::setUp();
        $this->Favorites = TableRegistry::getTableLocator()->get('Favorites');
        $this->options = ['accessibleFields' => [
            'user_id' => true,
            'foreign_model' => true,
            'foreign_key' => true
        ]];
    }

    public function tearDown()
    {
        unset($this->Favorites);
        parent::tearDown();
    }

    public function testCleanupFavoritesSoftDeletedUsersSuccess()
    {
        $originalCount = $this->Favorites->find()->count();
        $fav = $this->Favorites->newEntity([
            'user_id' => UuidFactory::uuid('user.id.sofia'),
            'foreign_model' => 'Resource',
            'foreign_key' => UuidFactory::uuid('resource.id.april')
        ], $this->options);
        $this->Favorites->save($fav, ['checkRules' => false]);
        $this->runCleanupChecks('Favorites', 'cleanupSoftDeletedUsers', $originalCount);
    }

    public function testCleanupFavoritesHardDeletedUsersSuccess()
    {
        $originalCount = $this->Favorites->find()->count();
        $fav = $this->Favorites->newEntity([
            'user_id' => UuidFactory::uuid('user.id.nope'),
            'foreign_model' => 'Resource',
            'foreign_key' => UuidFactory::uuid('resource.id.april')
        ], $this->options);
        $this->Favorites->save($fav, ['checkRules' => false]);
        $this->runCleanupChecks('Favorites', 'cleanupHardDeletedUsers', $originalCount);
    }

    public function testCleanupFavoritesSoftDeletedResourcesSuccess()
    {
        $originalCount = $this->Favorites->find()->count();
        $fav = $this->Favorites->newEntity([
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'foreign_model' => 'Resource',
            'foreign_key' => UuidFactory::uuid('resource.id.jquery')
        ], $this->options);
        $this->Favorites->save($fav, ['checkRules' => false]);
        $this->runCleanupChecks('Favorites', 'cleanupSoftDeletedResources', $originalCount);
    }

    public function testCleanupFavoritesHardDeletedResourcesSuccess()
    {
        $originalCount = $this->Favorites->find()->count();
        $fav = $this->Favorites->newEntity([
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'foreign_model' => 'Resource',
            'foreign_key' => UuidFactory::uuid('resource.id.nope')
        ], $this->options);
        $this->Favorites->save($fav, ['checkRules' => false]);
        $this->runCleanupChecks('Favorites', 'cleanupHardDeletedResources', $originalCount);
    }
}
