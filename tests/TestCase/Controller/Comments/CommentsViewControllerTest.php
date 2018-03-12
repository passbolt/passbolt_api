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

namespace App\Test\TestCase\Controller\Comments;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;

class CommentsViewControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/users', 'app.Base/groups', 'app.Base/groups_users', 'app.Base/permissions', 'app.Base/resources', 'app.Base/comments'];

    public function testSuccess()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->authenticateAs('ada');
        $this->getJson("/comments/resource/$resourceId.json?api-version=2");
        $this->assertSuccess();
        $this->assertGreaterThan(0, count($this->_responseJsonBody));

        // Expected content.
        $this->assertCommentAttributes($this->_responseJsonBody[0]);

        $this->assertObjectHasAttribute('children', $this->_responseJsonBody[0]);
        $this->assertCommentAttributes($this->_responseJsonBody[0]->children[0]);

        // Not expected content.
        $this->assertObjectNotHasAttribute('modifier', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('creator', $this->_responseJsonBody[0]);
    }

    public function testApiV1Success()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->authenticateAs('ada');
        $this->getJson("/comments/resource/$resourceId.json?api-version=v1");
        $this->assertSuccess();
        $this->assertGreaterThan(0, count($this->_responseJsonBody));

        // Expected fields.
        $this->assertObjectHasAttribute('Comment', $this->_responseJsonBody[0]);
        $this->assertCommentAttributes($this->_responseJsonBody[0]->Comment);

        $this->assertObjectHasAttribute('children', $this->_responseJsonBody[0]);
        $this->assertCommentAttributes($this->_responseJsonBody[0]->children[0]->Comment);

        // Not expected fields.
        $this->assertObjectNotHasAttribute('Modifier', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('Creator', $this->_responseJsonBody[0]);
    }

    public function testContainSuccess()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->authenticateAs('ada');
        $urlParameter = 'contain[modifier]=1&contain[creator]=1';
        $this->getJson("/comments/resource/$resourceId.json?$urlParameter&api-version=2");
        $this->assertSuccess();
        $this->assertGreaterThan(0, count($this->_responseJsonBody));

        // Expected content.
        $this->assertCommentAttributes($this->_responseJsonBody[0]);
        $this->assertObjectHasAttribute('modifier', $this->_responseJsonBody[0]);
        $this->assertUserAttributes($this->_responseJsonBody[0]->modifier);
        $this->assertObjectHasAttribute('creator', $this->_responseJsonBody[0]);
        $this->assertUserAttributes($this->_responseJsonBody[0]->creator);
    }

    public function testContainApiV1Success()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->authenticateAs('ada');
        $urlParameter = 'api-version=v1&contain[modifier]=1&contain[creator]=1';
        $this->getJson("/comments/resource/$resourceId.json?$urlParameter");
        $this->assertSuccess();
        $this->assertGreaterThan(0, count($this->_responseJsonBody));

        // Expected content.
        $this->assertObjectHasAttribute('Comment', $this->_responseJsonBody[0]);
        $this->assertCommentAttributes($this->_responseJsonBody[0]->Comment);
        $this->assertObjectHasAttribute('Modifier', $this->_responseJsonBody[0]);
        $this->assertUserAttributes($this->_responseJsonBody[0]->Modifier);
        $this->assertObjectHasAttribute('Creator', $this->_responseJsonBody[0]);
        $this->assertUserAttributes($this->_responseJsonBody[0]->Creator);
    }

    public function testErrorNotFound()
    {
        $this->authenticateAs('ada');
        // jquery is soft deleted. Hence, not reachable.
        $resourceId = UuidFactory::uuid('Resource.id.jquery');
        $this->getJson("/comments/resource/$resourceId.json?api-version=v1");
        $this->assertError('404', 'Could not find comments for the requested model');
    }

    public function testErrorWrongModelNameParameter()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->authenticateAs('ada');
        $this->getJson("/comments/WrongModelName/$resourceId.json?api-version=v1");
        $this->assertError('500', 'Invalid model name');
    }

    public function testErrorWrongUuidParameter()
    {
        $this->authenticateAs('ada');
        $this->getJson("/comments/resource/wrong-uuid.json?api-version=v1");
        $this->assertError('500', 'Invalid id');
    }

    public function testErrorNotAuthenticated()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->getJson("/comments/resource/$resourceId.json?api-version=v1");
        $this->assertAuthenticationError();
    }
}
