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
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;

class CommentsAddControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public function testCommentsAddController_Success()
    {
        RoleFactory::make()->guest()->persist();
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $this->logInAs($user);

        $commentContent = 'this is a test';
        $postData = [
            'content' => $commentContent,
        ];
        $resourceId = $resource->get('id');
        $this->postJson("/comments/resource/$resourceId.json?api-version=v2", $postData);
        $this->assertSuccess();

        // Check that the groups and its sub-models are saved as expected.
        $comment = CommentFactory::find()
            ->where(['id' => $this->_responseJsonBody->id])
            ->first();
        $this->assertEquals($commentContent, $comment->content);

        // Since the resource is not shared, no email are sent
        $this->assertEmailQueueIsEmpty();
    }

    public function testCommentsAddController_SharedResourceSuccessEMail()
    {
        RoleFactory::make()->guest()->persist();
        [$user1, $user2] = UserFactory::make(2)->user()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user1, $user2])->persist();
        $this->logInAs($user1);

        $commentContent = 'this is a test reply comment';
        $postData = [
            'content' => $commentContent,
        ];
        $resourceId = $resource->get('id');
        $this->postJson("/comments/resource/$resourceId.json?api-version=v2", $postData);
        $this->assertSuccess();

        // Check that the groups and its sub-models are saved as expected.
        $comment = CommentFactory::find()
            ->where(['id' => $this->_responseJsonBody->id])
            ->first();
        $this->assertEquals($commentContent, $comment->content);

        // Since the resource is shared, check email is sent
        $this->assertEmailQueueCount(1);
        $this->assertEmailInBatchContains("{$user1->profile->first_name} commented on {$resource->name}", $user2->username);
    }

    public function testCommentsAddController_ErrorCsrfToken()
    {
        $this->disableCsrfToken();
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $this->logInAs($user);
        $resourceId = $resource->get('id');

        $this->post("/comments/resource/$resourceId.json?api-version=v2");
        $this->assertResponseCode(403);
    }

    public function testCommentsAddController_ErrorNotAuthenticated()
    {
        $resource = ResourceFactory::make()->withCreator(UserFactory::make()->user())->persist();
        $resourceId = $resource->get('id');
        $postData = [];

        $this->postJson("/comments/resource/$resourceId.json?api-version=v2", $postData);
        $this->assertAuthenticationError();

        // Since the resource is not shared, no email are sent
        $this->assertEmailQueueIsEmpty();
    }
}
