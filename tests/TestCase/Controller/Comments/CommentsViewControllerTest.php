<?php
declare(strict_types=1);

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

use App\Test\Factory\CommentFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\CommentsModelTrait;
use App\Utility\UuidFactory;

class CommentsViewControllerTest extends AppIntegrationTestCase
{
    use CommentsModelTrait;

    public function testCommentsViewController_Success(): void
    {
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $comment = CommentFactory::make()->withUser($user)->withResource($resource)->persist();
        CommentFactory::make()->withUser($user)->withResource($resource)->withParent($comment)->persist();
        $resourceId = $resource->get('id');
        $this->logInAs($user);

        $this->getJson("/comments/resource/$resourceId.json");
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

    public function testCommentsViewController_ContainSuccess(): void
    {
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        CommentFactory::make()->withUser($user)->withResource($resource)->withCreatorAndModifier($user)->persist();
        $resourceId = $resource->get('id');
        $this->logInAs($user);

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

    public function testCommentsViewController_Error_NotFound(): void
    {
        $user = UserFactory::make()->user()->persist();
        // Resource is soft-deleted. Hence, not reachable.
        $resourceId = ResourceFactory::make()
            ->withCreatorAndPermission($user)
            ->setDeleted()
            ->persist()
            ->get('id');
        $this->logInAs($user);

        $this->getJson("/comments/resource/$resourceId.json");

        $this->assertError(404, 'Could not find comments for the requested model');
    }

    public function testCommentsViewController_Error_WrongModelNameParameter(): void
    {
        $user = UserFactory::make()->user()->persist();
        $resourceId = ResourceFactory::make()->withCreatorAndPermission($user)->persist()->get('id');
        $this->logInAs($user);

        $this->getJson("/comments/WrongModelName/$resourceId.json");

        $this->assertBadRequestError('Invalid model name');
    }

    public function testCommentsViewController_Error_WrongUuidParameter(): void
    {
        $this->logInAsUser();
        $this->getJson('/comments/resource/wrong-uuid.json');
        $this->assertBadRequestError('Invalid id');
    }

    public function testCommentsViewController_Erro_NotAuthenticated(): void
    {
        $resourceId = UuidFactory::uuid();
        $this->getJson("/comments/resource/$resourceId.json");
        $this->assertAuthenticationError();
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testCommentsViewController_Error_NotJson(): void
    {
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $comment = CommentFactory::make()->withUser($user)->withResource($resource)->persist();
        CommentFactory::make()->withUser($user)->withResource($resource)->withParent($comment)->persist();
        $resourceId = $resource->get('id');
        $this->logInAs($user);
        $this->get("/comments/resource/$resourceId");
        $this->assertResponseCode(404);
    }
}
