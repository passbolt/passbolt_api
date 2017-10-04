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
    public $fixtures = ['app.users', 'app.roles', 'app.profiles', 'app.authentication_tokens', 'app.resources', 'app.secrets'];

    protected function _assertResourceAttributes($resource) {
        $this->assertObjectHasAttribute('id', $resource);
        $this->assertObjectHasAttribute('name', $resource);
        $this->assertObjectHasAttribute('username', $resource);
        $this->assertObjectHasAttribute('uri', $resource);
        $this->assertObjectHasAttribute('description', $resource);
        $this->assertObjectHasAttribute('deleted', $resource);
        $this->assertObjectHasAttribute('created', $resource);
        $this->assertObjectHasAttribute('modified', $resource);
        $this->assertObjectHasAttribute('created_by', $resource);
        $this->assertObjectHasAttribute('modified_by', $resource);
    }

    protected function _assertSecretAttributes($secret) {
        $this->assertObjectHasAttribute('id', $secret);
        $this->assertObjectHasAttribute('user_id', $secret);
        $this->assertObjectHasAttribute('resource_id', $secret);
        $this->assertObjectHasAttribute('data', $secret);
        $this->assertObjectHasAttribute('created', $secret);
        $this->assertObjectHasAttribute('modified', $secret);
    }

    public function testViewSuccess()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('resource.id.apache');
        $this->getJson("/resources/$resourceId.json?api-version=2");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);

        // Expected fields.
        $this->_assertResourceAttributes($this->_responseJsonBody);
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
        $this->_assertResourceAttributes($this->_responseJsonBody->Resource);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('Secret', $this->_responseJsonBody);
    }

    public function testViewSuccessContainsSecrets()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('resource.id.apache');
        $this->getJson("/resources/$resourceId.json?api-version=2&contain[secrets]=1");
        $this->assertSuccess();

        // Expected fields.
        $this->_assertResourceAttributes($this->_responseJsonBody);
        $this->assertObjectHasAttribute('secrets', $this->_responseJsonBody);
        $this->assertCount(1, $this->_responseJsonBody->secrets);
        $this->_assertSecretAttributes($this->_responseJsonBody->secrets[0]);
    }

    public function testViewSuccessApiV1ContainsSecrets()
    {
        $this->authenticateAs('dame');
        $resourceId = Common::uuid('resource.id.apache');
        $this->getJson("/resources/$resourceId.json?contain[secrets]=1");
        $this->assertSuccess();

        // Expected fields.
        $this->assertObjectHasAttribute('Resource', $this->_responseJsonBody);
        $this->_assertResourceAttributes($this->_responseJsonBody->Resource);
        $this->assertObjectHasAttribute('Secret', $this->_responseJsonBody);
        $this->assertCount(1, $this->_responseJsonBody->Secret);
        $this->_assertSecretAttributes($this->_responseJsonBody->Secret[0]);
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
