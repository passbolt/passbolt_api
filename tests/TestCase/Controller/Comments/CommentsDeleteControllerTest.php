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
 * @since         3.8.0
 */

namespace App\Test\TestCase\Controller\Comments;

use App\Test\Factory\CommentFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class CommentsDeleteControllerTest extends AppIntegrationTestCase
{
    public function testCommentsDeleteController_Success(): void
    {
        RoleFactory::make()->guest()->persist();
        $user = UserFactory::make()->user()->persist();
        $comment = CommentFactory::make()->withUser($user)->persist();
        $commentId = $comment->get('id');
        $this->logInAs($user);
        $this->deleteJson("/comments/$commentId.json");
        $this->assertSuccess();
        $Comments = TableRegistry::getTableLocator()->get('Comments');
        $deletedComment = $Comments->find('all')->where(['Comments.id' => $commentId])->first();
        $this->assertempty($deletedComment);
    }

    public function testCommentsDeleteController_Error_CsrfToken(): void
    {
        $this->disableCsrfToken();
        $user = UserFactory::make()->user()->persist();
        $comment = CommentFactory::make()->withUser($user)->persist();
        $commentId = $comment->get('id');
        $this->logInAs($user);
        $this->delete("/comments/$commentId.json");
        $this->assertResponseCode(403);
    }

    public function testCommentsDeleteController_Error_NotAuthenticated(): void
    {
        $commentId = UuidFactory::uuid();
        $this->deleteJson("/comments/$commentId.json");
        $this->assertAuthenticationError();
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testCommentsDeleteController_Error_NotJson(): void
    {
        RoleFactory::make()->guest()->persist();
        $user = UserFactory::make()->user()->persist();
        $comment = CommentFactory::make()->withUser($user)->persist();
        $commentId = $comment->get('id');
        $this->logInAs($user);
        $this->delete("/comments/$commentId");
        $this->assertResponseCode(404);
    }
}
