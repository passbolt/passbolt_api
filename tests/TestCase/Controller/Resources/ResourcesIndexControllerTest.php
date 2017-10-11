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
use Cake\Utility\Hash;

class ResourcesIndexControllerTest extends ApplicationTest
{
    public $fixtures = ['app.users', 'app.groups', 'app.groups_users', 'app.resources', 'app.secrets', 'app.favorites', 'app.permissions'];

    public function testIndexSuccess()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?api-version=2');
        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));

        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody[0]);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('secrets', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('creator', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('modifier', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('favorite', $this->_responseJsonBody[0]);
    }

    public function testIndexApiV1Success()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json');
        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));

        // Expected fields.
        $this->assertObjectHasAttribute('Resource', $this->_responseJsonBody[0]);
        $this->assertResourceAttributes($this->_responseJsonBody[0]->Resource);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('Secret', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('Creator', $this->_responseJsonBody[0]);
    }

    public function testIndexSuccessContainsSecrets()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?api-version=2&contain[secret]=1');
        $this->assertSuccess();

        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody[0]);
        $this->assertObjectHasAttribute('secrets', $this->_responseJsonBody[0]);
        $this->assertCount(1, $this->_responseJsonBody[0]->secrets);
        $this->assertSecretAttributes($this->_responseJsonBody[0]->secrets[0]);
    }

    public function testIndexSuccessApiV1ContainsSecrets()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?contain[secret]=1');
        $this->assertSuccess();

        // Expected fields.
        $this->assertObjectHasAttribute('Resource', $this->_responseJsonBody[0]);
        $this->assertResourceAttributes($this->_responseJsonBody[0]->Resource);
        $this->assertObjectHasAttribute('Secret', $this->_responseJsonBody[0]);
        $this->assertCount(1, $this->_responseJsonBody[0]->Secret);
        $this->assertSecretAttributes($this->_responseJsonBody[0]->Secret[0]);
    }

    public function testIndexSuccessContainsCreator()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?api-version=2&contain[creator]=1');
        $this->assertSuccess();

        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody[0]);
        $this->assertObjectHasAttribute('creator', $this->_responseJsonBody[0]);
        $this->assertUserAttributes($this->_responseJsonBody[0]->creator);
    }

    public function testIndexSuccessApiV1ContainsCreator()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?contain[creator]=1');
        $this->assertSuccess();

        // Expected fields.
        $this->assertObjectHasAttribute('Resource', $this->_responseJsonBody[0]);
        $this->assertResourceAttributes($this->_responseJsonBody[0]->Resource);
        $this->assertObjectHasAttribute('Creator', $this->_responseJsonBody[0]);
        $this->assertUserAttributes($this->_responseJsonBody[0]->Creator);
    }

    public function testIndexSuccessContainsModifier()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?api-version=2&contain[modifier]=1');
        $this->assertSuccess();

        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody[0]);
        $this->assertObjectHasAttribute('modifier', $this->_responseJsonBody[0]);
        $this->assertUserAttributes($this->_responseJsonBody[0]->modifier);
    }

    public function testIndexSuccessApiV1ContainsModifier()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?contain[modifier]=1');
        $this->assertSuccess();

        // Expected fields.
        $this->assertObjectHasAttribute('Resource', $this->_responseJsonBody[0]);
        $this->assertResourceAttributes($this->_responseJsonBody[0]->Resource);
        $this->assertObjectHasAttribute('Modifier', $this->_responseJsonBody[0]);
        $this->assertUserAttributes($this->_responseJsonBody[0]->Modifier);
    }

    public function testIndexSuccessContainsFavorite()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?api-version=2&contain[favorite]=1');
        $this->assertSuccess();

        // Expected fields.
        $resourceId = Common::uuid('resource.id.apache');
        $favoriteResource = current(array_filter($this->_responseJsonBody, function($resource) use ($resourceId) {
            return $resource->id == $resourceId;
        }));
        $this->assertResourceAttributes($favoriteResource);
        $this->assertObjectHasAttribute('favorite', $favoriteResource);
        $this->assertFavoriteAttributes($favoriteResource->favorite);
    }

    public function testIndexSuccessApiV1ContainsFavorite()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?contain[favorite]=1');
        $this->assertSuccess();

        // Expected fields.
        $resourceId = Common::uuid('resource.id.apache');
        $favoriteResource = current(array_filter($this->_responseJsonBody, function($resource) use ($resourceId) {
            return $resource->Resource->id == $resourceId;
        }));
        $this->assertObjectHasAttribute('Resource', $favoriteResource);
        $this->assertResourceAttributes($favoriteResource->Resource);
        $this->assertObjectHasAttribute('Favorite', $favoriteResource);
        $this->assertFavoriteAttributes($favoriteResource->Favorite);
    }

    public function testIndexSuccessFilterOnFavorite()
    {
        $this->authenticateAs('dame');
        $this->getJson('/resources.json?api-version=2&filter[is-favorite]=1');
        $this->assertSuccess();
        $this->assertCount(2, $this->_responseJsonBody);

        // Check that the result contain only the expected favorite resources.
        $favoriteResourcesIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $expectedResources = [Common::uuid('resource.id.apache'), Common::uuid('resource.id.april')];
        $this->assertEquals(0, count(array_diff($expectedResources, $favoriteResourcesIds)));

        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody[0]);

        // Favorite field shouldn't be present by default even when filtering by favorite.
        $this->assertObjectNotHasAttribute('favorite', $this->_responseJsonBody[0]);
    }

    public function testIndexSuccessFilterOnFavoriteContainFavorite()
    {
        $this->authenticateAs('dame');
        $this->getJson('/resources.json?api-version=2&filter[is-favorite]=1&contain[favorite]=1');
        $this->assertSuccess();
        $this->assertCount(2, $this->_responseJsonBody);

        // Check that the result contain only the expected favorite resources.
        $favoriteResourcesIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $expectedResources = [Common::uuid('resource.id.apache'), Common::uuid('resource.id.april')];
        $this->assertEquals(0, count(array_diff($expectedResources, $favoriteResourcesIds)));

        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody[0]);

        // Favorite field shouldn't be added automatically.
        $this->assertObjectHasAttribute('favorite', $this->_responseJsonBody[0]);
        $this->assertFavoriteAttributes($this->_responseJsonBody[0]->favorite);
    }

    public function testIndexSuccessFilterOutFavorite()
    {
        $this->authenticateAs('dame');
        $this->getJson('/resources.json?api-version=2&filter[is-favorite]=0');
        $this->assertSuccess();
        $this->assertGreaterThan(2, count($this->_responseJsonBody));

        // Check that the result doesn\'t contain the favorite resources.
        $favoriteResourcesIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $expectedResources = [Common::uuid('resource.id.apache'), Common::uuid('resource.id.april')];
        $this->assertEquals(0, count(array_intersect($expectedResources, $favoriteResourcesIds)));
    }

    public function testIndexErrorNotAuthenticated()
    {
        $this->getJson('/resources.json');
        $this->assertAuthenticationError();
    }

    public function testIndexAndPermissions()
    {
        $this->authenticateAs('dame');
        $this->getJson('/resources.json?api-version=2');
        $this->assertSuccess();
        $this->assertGreaterThan(2, count($this->_responseJsonBody));

        // Check that the result doesn't contain resources the user doesn't have a permission for.
        $resourcesIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $notExpectedResources = [Common::uuid('resource.id.canjs'), Common::uuid('resource.id.docker'), Common::uuid('resource.id.ftp')];
        // Check that the resources exist individually.
        $Resources = TableRegistry::get('Resources');
        foreach ($notExpectedResources as $notExpectedResource) {
            $resource = $Resources->get($notExpectedResource);
            $this->assertNotNull($resource);
        }
        // Check that the resources are not in the request result.
        $this->assertEquals(0, count(array_intersect($notExpectedResources, $resourcesIds)));
    }
}
