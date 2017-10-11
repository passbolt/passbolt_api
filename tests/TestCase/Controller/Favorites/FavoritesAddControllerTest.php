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

namespace App\Test\TestCase\Controller;

use App\Test\TestCase\ApplicationTest;
use App\Utility\Common;
use Cake\ORM\TableRegistry;

class FavoritesAddControllerTest extends ApplicationTest
{
    public $fixtures = ['app.users', 'app.groups', 'app.groups_users', 'app.resources', 'app.favorites', 'app.permissions'];

    public function testAddSuccess()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('resource.id.bower');
        $this->postJson("/favorites/resource/$resourceId.json?api-version=2");
        $this->assertSuccess();

        // Expected fields.
        $this->assertFavoriteAttributes($this->_responseJsonBody);
    }

    public function testAddSuccessApiV1()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('resource.id.bower');
        $this->postJson("/favorites/resource/$resourceId.json");
        $this->assertSuccess();

        // Expected fields.
        $this->assertObjectHasAttribute('Favorite', $this->_responseJsonBody);
        $this->assertFavoriteAttributes($this->_responseJsonBody->Favorite);
    }

    public function testAddErrorNotValidId()
    {
        $this->authenticateAs('dame');
        $resourceId = 'invalid-id';
        $this->postJson("/favorites/resource/$resourceId.json");
        $this->assertError(400, 'The resource id is not valid.');
    }

    public function testAddErrorSoftDeletedResource()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('resource.id.jquery');
        $this->postJson("/favorites/resource/$resourceId.json?api-version=2");
        $this->assertError('400', '');
        $this->markTestIncomplete('The message should be explicit');
    }

    public function testAddErrorDoesntExistResource()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid();
        $this->postJson("/favorites/resource/$resourceId.json?api-version=2");
        $this->assertError('400', '');
        $this->markTestIncomplete('The message should be explicit');
    }

    public function testAddErrorResourceAccessDenied()
    {
        $resourceId = Common::uuid('resource.id.canjs');

        // Check that the resource exists.
        $Resources = TableRegistry::get('Resources');
        $resource = $Resources->get($resourceId);
        $this->assertNotNull($resource);

        // Check that the user cannot access the resource
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('resource.id.canjs');
        $this->postJson("/favorites/resource/$resourceId.json?api-version=2");
        $this->assertError('400', '');
    }
}
