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
use App\Test\Lib\Model\FavoritesModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class FavoritesAddControllerTest extends AppIntegrationTestCase
{
    use FavoritesModelTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources',
        'app.Base/Favorites', 'app.Base/Permissions'
    ];

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
        $this->postJson("/favorites/resource/$resourceId.json?api-version=v1");
        $this->assertSuccess();

        // Expected fields.
        $this->assertObjectHasAttribute('Favorite', $this->_responseJsonBody);
        $this->assertFavoriteAttributes($this->_responseJsonBody->Favorite);
    }

    public function testFavoritesAddCannotModifyNotAccessibleFields()
    {
        $this->markTestIncomplete();
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
        $this->postJson("/favorites/resource/$resourceId.json?api-version=v1");
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
