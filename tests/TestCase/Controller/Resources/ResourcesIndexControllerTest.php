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

class ResourcesIndexControllerTest extends ApplicationTest
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

    public function testIndexSuccess()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?api-version=2');
        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));

        // Expected fields.
        $this->_assertResourceAttributes($this->_responseJsonBody[0]);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('secrets', $this->_responseJsonBody[0]);
    }

    public function testIndexApiV1Success()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json');
        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));

        // Expected fields.
        $this->assertObjectHasAttribute('Resource', $this->_responseJsonBody[0]);
        $this->_assertResourceAttributes($this->_responseJsonBody[0]->Resource);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('Secret', $this->_responseJsonBody[0]);
    }

    public function testIndexSuccessContainsSecrets()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?api-version=2&contain[secret]=1');
        $this->assertSuccess();

        // Expected fields.
        $this->_assertResourceAttributes($this->_responseJsonBody[0]);
        $this->assertObjectHasAttribute('secrets', $this->_responseJsonBody[0]);
        $this->assertCount(1, $this->_responseJsonBody[0]->secrets);
        $this->_assertSecretAttributes($this->_responseJsonBody[0]->secrets[0]);
    }

    public function testIndexSuccessApiV1ContainsSecrets()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?contain[secret]=1');
        $this->assertSuccess();

        // Expected fields.
        $this->assertObjectHasAttribute('Resource', $this->_responseJsonBody[0]);
        $this->_assertResourceAttributes($this->_responseJsonBody[0]->Resource);
        $this->assertObjectHasAttribute('Secret', $this->_responseJsonBody[0]);
        $this->assertCount(1, $this->_responseJsonBody[0]->Secret);
        $this->_assertSecretAttributes($this->_responseJsonBody[0]->Secret[0]);
    }

    public function testIndexErrorNotAuthenticated()
    {
        $this->getJson('/resources.json');
        $this->assertAuthenticationError();
    }
}
