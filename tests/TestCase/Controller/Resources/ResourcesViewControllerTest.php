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

class ResourcesViewControllerTest extends ApplicationTest
{
    public $fixtures = ['app.users', 'app.resources', 'app.secrets', 'app.favorites', 'app.permissions'];

    public function testViewSuccess()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('resource.id.apache');
        $this->getJson("/resources/$resourceId.json?api-version=2");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);

        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('secrets', $this->_responseJsonBody);
    }

    public function testViewApiV1Success()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('resource.id.apache');
        $this->getJson("/resources/$resourceId.json");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);

        // Expected fields.
        $this->assertObjectHasAttribute('Resource', $this->_responseJsonBody);
        $this->assertResourceAttributes($this->_responseJsonBody->Resource);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('Secret', $this->_responseJsonBody);
    }

    public function testViewSuccessContainsSecrets()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('resource.id.apache');
        $this->getJson("/resources/$resourceId.json?api-version=2&contain[secret]=1");
        $this->assertSuccess();

        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody);
        $this->assertObjectHasAttribute('secrets', $this->_responseJsonBody);
        $this->assertCount(1, $this->_responseJsonBody->secrets);
        $this->assertSecretAttributes($this->_responseJsonBody->secrets[0]);
    }

    public function testViewSuccessApiV1ContainsSecrets()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('resource.id.apache');
        $this->getJson("/resources/$resourceId.json?contain[secret]=1");
        $this->assertSuccess();

        // Expected fields.
        $this->assertObjectHasAttribute('Resource', $this->_responseJsonBody);
        $this->assertResourceAttributes($this->_responseJsonBody->Resource);
        $this->assertObjectHasAttribute('Secret', $this->_responseJsonBody);
        $this->assertCount(1, $this->_responseJsonBody->Secret);
        $this->assertSecretAttributes($this->_responseJsonBody->Secret[0]);
    }

    public function testViewSuccessContainsCreator()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('resource.id.apache');
        $this->getJson("/resources/$resourceId.json?api-version=2&contain[creator]=1");
        $this->assertSuccess();

        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody);
        $this->assertObjectHasAttribute('creator', $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->creator);
    }

    public function testViewSuccessApiV1ContainsCreator()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('resource.id.apache');
        $this->getJson("/resources/$resourceId.json?contain[creator]=1");
        $this->assertSuccess();

        // Expected fields.
        $this->assertObjectHasAttribute('Resource', $this->_responseJsonBody);
        $this->assertResourceAttributes($this->_responseJsonBody->Resource);
        $this->assertObjectHasAttribute('Creator', $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->Creator);
    }

    public function testViewSuccessContainsModifier()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('resource.id.apache');
        $this->getJson("/resources/$resourceId.json?api-version=2&contain[modifier]=1");
        $this->assertSuccess();

        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody);
        $this->assertObjectHasAttribute('modifier', $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->modifier);
    }

    public function testViewSuccessApiV1ContainsModifier()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('resource.id.apache');
        $this->getJson("/resources/$resourceId.json?contain[modifier]=1");
        $this->assertSuccess();

        // Expected fields.
        $this->assertObjectHasAttribute('Resource', $this->_responseJsonBody);
        $this->assertResourceAttributes($this->_responseJsonBody->Resource);
        $this->assertObjectHasAttribute('Modifier', $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->Modifier);
    }

    public function testViewSuccessContainsFavorite()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('resource.id.apache');
        $this->getJson("/resources/$resourceId.json?api-version=2&contain[favorite]=1");
        $this->assertSuccess();

        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody);
        $this->assertObjectHasAttribute('favorite', $this->_responseJsonBody);
        $this->assertFavoriteAttributes($this->_responseJsonBody->favorite);
    }

    public function testViewSuccessApiV1ContainsFavorite()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('resource.id.apache');
        $this->getJson("/resources/$resourceId.json?contain[favorite]=1");
        $this->assertSuccess();

        // Expected fields.
        $this->assertObjectHasAttribute('Resource', $this->_responseJsonBody);
        $this->assertResourceAttributes($this->_responseJsonBody->Resource);
        $this->assertObjectHasAttribute('Favorite', $this->_responseJsonBody);
        $this->assertFavoriteAttributes($this->_responseJsonBody->Favorite);
    }

    public function testViewErrorNotAuthenticated()
    {
        $resourceId = Common::uuid('resource.id.bower');
        $this->getJson("/resources/$resourceId.json");
        $this->assertAuthenticationError();
    }

    public function testViewErrorNotValidId()
    {
        $this->authenticateAs('dame');
        $resourceId = 'invalid-id';
        $this->getJson("/resources/$resourceId.json");
        $this->assertError(400, 'The resource id is not valid.');
    }

    public function testViewErrorNotFound()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('not-found');
        $this->getJson("/resources/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testViewErrorDeletedResource()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('resource.id.jquery');
        $this->getJson("/resources/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }
}
