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

class FavoritesAddControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/users', 'app.Base/groups', 'app.Base/groups_users', 'app.Base/resources', 'app.Base/favorites', 'app.Base/permissions'];

    public function testAddSuccess()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->postJson("/favorites/resource/$resourceId.json?api-version=2");
        $this->assertSuccess();

        // Expected fields.
        $this->assertFavoriteAttributes($this->_responseJsonBody);
    }

    public function testAddSuccessApiV1()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->postJson("/favorites/resource/$resourceId.json?api-version=v1");
        $this->assertSuccess();

        // Expected fields.
        $this->assertObjectHasAttribute('Favorite', $this->_responseJsonBody);
        $this->assertFavoriteAttributes($this->_responseJsonBody->Favorite);
    }

    public function testAddCannotModifyNotAccessibleFields()
    {
        $this->markTestIncomplete();
    }

    public function testAddErrorNotValidId()
    {
        $this->authenticateAs('dame');
        $resourceId = 'invalid-id';
        $this->postJson("/favorites/resource/$resourceId.json?api-version=v1");
        $this->assertError(400, 'The resource id is not valid.');
    }

    public function testAddErrorDoesNotExistResource()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid();
        $this->postJson("/favorites/resource/$resourceId.json?api-version=2");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testAddErrorSoftDeletedResource()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.jquery');
        $this->postJson("/favorites/resource/$resourceId.json?api-version=2");
        $this->assertError('404', 'The resource does not exist.');
    }

    public function testAddErrorResourceAccessDenied()
    {
        $resourceId = UuidFactory::uuid('resource.id.canjs');

        // Check that the resource exists.
        $Resources = TableRegistry::get('Resources');
        $resource = $Resources->get($resourceId);
        $this->assertNotNull($resource);

        // Check that the user cannot access the resource
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.canjs');
        $this->postJson("/favorites/resource/$resourceId.json?api-version=2");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testAddErrorAlreadyMarkedAsFavorite()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->postJson("/favorites/resource/$resourceId.json?api-version=2");
        $this->assertError(400, 'This record is already marked as favorite.');
    }

    public function testAddErrorNotAuthenticated()
    {
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->postJson("/favorites/resource/$resourceId.json?api-version=2");
        $this->assertAuthenticationError();
    }
}
