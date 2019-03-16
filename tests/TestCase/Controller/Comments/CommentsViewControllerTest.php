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

namespace App\Test\TestCase\Controller\Comments;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\CommentsModelTrait;
use App\Utility\UuidFactory;

class CommentsViewControllerTest extends AppIntegrationTestCase
{
    use CommentsModelTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Avatars', 'app.Base/Groups', 'app.Base/GroupsUsers',
        'app.Base/Permissions', 'app.Base/Resources', 'app.Base/Comments'
    ];

    public function testCommentsViewSuccess()
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

    public function testCommentsViewApiV1Success()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->authenticateAs('ada');
        $this->getJson("/comments/resource/$resourceId.json");
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

    public function testCommentsViewContainSuccess()
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

    public function testCommentsViewContainApiV1Success()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->authenticateAs('ada');
        $urlParameter = 'contain[modifier]=1&contain[creator]=1';
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

    public function testCommentsViewErrorNotFound()
    {
        $this->authenticateAs('ada');
        // jquery is soft deleted. Hence, not reachable.
        $resourceId = UuidFactory::uuid('Resource.id.jquery');
        $this->getJson("/comments/resource/$resourceId.json");
        $this->assertError(404, 'Could not find comments for the requested model');
    }

    public function testCommentsViewErrorWrongModelNameParameter()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->authenticateAs('ada');
        $this->getJson("/comments/WrongModelName/$resourceId.json");
        $this->assertError(500, 'Invalid model name');
    }

    public function testCommentsViewErrorWrongUuidParameter()
    {
        $this->authenticateAs('ada');
        $this->getJson("/comments/resource/wrong-uuid.json");
        $this->assertError(500, 'Invalid id');
    }

    public function testCommentsViewErrorNotAuthenticated()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->getJson("/comments/resource/$resourceId.json");
        $this->assertAuthenticationError();
    }
}
