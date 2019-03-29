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

namespace App\Test\TestCase\Controller\Resources;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\FavoritesModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class ResourcesViewControllerTest extends AppIntegrationTestCase
{
    use FavoritesModelTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources',
        'app.Base/Secrets', 'app.Base/Favorites', 'app.Base/Permissions', 'app.Base/Avatars'
    ];

    public function testSuccess()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->getJson("/resources/$resourceId.json?api-version=2");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);

        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('secrets', $this->_responseJsonBody);
    }

    public function testApiV1Success()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->getJson("/resources/$resourceId.json");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);

        // Expected fields.
        $this->assertObjectHasAttribute('Resource', $this->_responseJsonBody);
        $this->assertResourceAttributes($this->_responseJsonBody->Resource);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('Secret', $this->_responseJsonBody);
    }

    public function testContainSuccess()
    {
        $this->authenticateAs('ada');
        $urlParameter = 'contain[creator]=1&contain[favorite]=1&contain[modifier]=1&contain[permission]=1&contain[secret]=1';
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->getJson("/resources/$resourceId.json?$urlParameter&api-version=2");
        $this->assertSuccess();

        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody);
        // Contain creator.
        $this->assertObjectHasAttribute('creator', $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->creator);
        // Contain modifier.
        $this->assertObjectHasAttribute('modifier', $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->modifier);
        // Contain permission.
        $this->assertObjectHasAttribute('permission', $this->_responseJsonBody);
        $this->assertPermissionAttributes($this->_responseJsonBody->permission);
        // Contain secret.
        $this->assertObjectHasAttribute('secrets', $this->_responseJsonBody);
        $this->assertCount(1, $this->_responseJsonBody->secrets);
        $this->assertSecretAttributes($this->_responseJsonBody->secrets[0]);
        // Contain favorite.
        $this->assertObjectHasAttribute('favorite', $this->_responseJsonBody);
        // A resource marked as favorite contains the favorite data.
        $this->assertObjectHasAttribute('favorite', $this->_responseJsonBody);
        $this->assertFavoriteAttributes($this->_responseJsonBody->favorite);
    }

    public function testContainApiV1Success()
    {
        $this->authenticateAs('ada');
        $urlParameter = 'contain[creator]=1&contain[favorite]=1&contain[modifier]=1&contain[permission]=1&contain[secret]=1';
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->getJson("/resources/$resourceId.json?$urlParameter");
        $this->assertSuccess();

        // Expected fields.
        $this->assertObjectHasAttribute('Resource', $this->_responseJsonBody);
        $this->assertResourceAttributes($this->_responseJsonBody->Resource);
        // Contain creator.
        $this->assertObjectHasAttribute('Creator', $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->Creator);
        // Contain modifier.
        $this->assertObjectHasAttribute('Modifier', $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->Modifier);
        // Contain permission.
        $this->assertObjectHasAttribute('Permission', $this->_responseJsonBody);
        $this->assertPermissionAttributes($this->_responseJsonBody->Permission);
        // Contain secret.
        $this->assertObjectHasAttribute('Secret', $this->_responseJsonBody);
        $this->assertCount(1, $this->_responseJsonBody->Secret);
        $this->assertSecretAttributes($this->_responseJsonBody->Secret[0]);
        // Contain favorite.
        $this->assertObjectHasAttribute('Favorite', $this->_responseJsonBody);
        // A resource marked as favorite contains the favorite data.
        $this->assertObjectHasAttribute('Favorite', $this->_responseJsonBody);
        $this->assertFavoriteAttributes($this->_responseJsonBody->Favorite);
    }

    public function testErrorNotAuthenticated()
    {
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->getJson("/resources/$resourceId.json");
        $this->assertAuthenticationError();
    }

    public function testErrorNotValidId()
    {
        $this->authenticateAs('dame');
        $resourceId = 'invalid-id';
        $this->getJson("/resources/$resourceId.json");
        $this->assertError(400, 'The resource id is not valid.');
    }

    public function testErrorNotFound()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('not-found');
        $this->getJson("/resources/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testErrorSoftDeletedResource()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.jquery');
        $this->getJson("/resources/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testErrorResourceAccessDenied()
    {
        $resourceId = UuidFactory::uuid('resource.id.canjs');

        // Check that the resource exists.
        $Resources = TableRegistry::getTableLocator()->get('Resources');
        $resource = $Resources->get($resourceId);
        $this->assertNotNull($resource);

        // Check that the user cannot access the resource
        $this->authenticateAs('dame');
        $this->getJson("/resources/$resourceId.json?api-version=2");
        $this->assertError(404, 'The resource does not exist.');
    }
}
