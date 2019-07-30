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

use App\Model\Entity\Favorite;
use App\Model\Table\FavoritesTable;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\FavoritesModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class FavoritesAddControllerTest extends AppIntegrationTestCase
{
    /** @var FavoritesTable */
    private $Favorites;

    use FavoritesModelTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources',
        'app.Base/Favorites', 'app.Base/Permissions'
    ];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Favorites') ? [] : ['className' => FavoritesTable::class];
        $this->Favorites = TableRegistry::getTableLocator()->get('Favorites', $config);
    }

    public function testFavoritesAddSuccess()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->postJson("/favorites/resource/$resourceId.json?api-version=2");
        $this->assertSuccess();

        // Expected fields.
        $this->assertFavoriteAttributes($this->_responseJsonBody);
    }

    public function testFavoritesAddSuccessApiV1()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->postJson("/favorites/resource/$resourceId.json");
        $this->assertSuccess();

        // Expected fields.
        $this->assertObjectHasAttribute('Favorite', $this->_responseJsonBody);
        $this->assertFavoriteAttributes($this->_responseJsonBody->Favorite);
    }

    public function testFavoritesAddCannotModifyNotAccessibleFields()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.bower');

        $favoriteData = [
            'id' => 'modified_id',
            'foreign_model' => 'modified_foreign_model',
            'foreign_key' => 'modified_foreign_key',
            'created' => '2019-07-29 10:31:35',
            'modified' => '2019-07-29 10:31:35',
            'user_id' => 'modified_user_id'
        ];

        $this->postJson("/favorites/resource/$resourceId.json", $favoriteData);
        $this->assertSuccess();

        /** @var Favorite $favorite */
        $favorite = $this->Favorites->find()
            ->where(['foreign_key' => $resourceId])
            ->first();

        $this->assertNotEquals($favoriteData['id'], $favorite->id);
        $this->assertNotEquals($favoriteData['foreign_model'], $favorite->foreign_model);
        $this->assertNotEquals($favoriteData['foreign_key'], $favorite->foreign_key);
        $this->assertNotEquals($favoriteData['modified'], $favorite->modified);
        $this->assertNotEquals($favoriteData['created'], $favorite->created);
        $this->assertNotEquals($favoriteData['user_id'], $favorite->user_id);
    }

    public function testFavoritesAddErrorCsrfToken()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->post("/favorites/resource/$resourceId.json?api-version=2");
        $this->assertResponseCode(403);
    }

    public function testFavoritesAddErrorNotValidId()
    {
        $this->authenticateAs('dame');
        $resourceId = 'invalid-id';
        $this->postJson("/favorites/resource/$resourceId.json");
        $this->assertError(400, 'The resource id is not valid.');
    }

    public function testFavoritesAddErrorDoesNotExistResource()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid();
        $this->postJson("/favorites/resource/$resourceId.json?api-version=2");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testFavoritesAddErrorSoftDeletedResource()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.jquery');
        $this->postJson("/favorites/resource/$resourceId.json?api-version=2");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testFavoritesAddErrorResourceAccessDenied()
    {
        $resourceId = UuidFactory::uuid('resource.id.canjs');

        // Check that the resource exists.
        $Resources = TableRegistry::getTableLocator()->get('Resources');
        $resource = $Resources->get($resourceId);
        $this->assertNotNull($resource);

        // Check that the user cannot access the resource
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.canjs');
        $this->postJson("/favorites/resource/$resourceId.json?api-version=2");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testFavoritesAddErrorAlreadyMarkedAsFavorite()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->postJson("/favorites/resource/$resourceId.json?api-version=2");
        $this->assertError(400, 'This record is already marked as favorite.');
    }

    public function testFavoritesAddErrorNotAuthenticated()
    {
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->postJson("/favorites/resource/$resourceId.json?api-version=2");
        $this->assertAuthenticationError();
    }
}
