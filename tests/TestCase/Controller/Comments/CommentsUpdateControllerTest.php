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

use App\Model\Table\CommentsTable;
use App\Test\Factory\CommentFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class CommentsUpdateControllerTest extends AppIntegrationTestCase
{
    public $Comments;

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Comments') ? [] : ['className' => CommentsTable::class];
        $this->Comments = TableRegistry::getTableLocator()->get('Comments', $config);
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
    }

    public function testCommentsUpdateController_Success()
    {
        RoleFactory::make()->user()->persist();
        $user = UserFactory::make()->user()->persist();
        $comment = CommentFactory::make()->withUser($user)->persist();
        $commentId = $comment->get('id');
        $this->logInAs($user);

        $commentContent = 'updated comment content';
        $putData = ['content' => $commentContent];
        $this->putJson("/comments/$commentId.json?api-version=2", $putData);
        $this->assertSuccess();

        $comment = CommentFactory::find()
            ->where(['id' => $this->_responseJsonBody->id])
            ->first();
        $this->assertEquals($commentContent, $comment->content);
        $this->assertEquals($user->id, $comment->modified_by);
        $this->assertEquals($user->id, $comment->modified_by);

        // Assert that modified time is within one second from the test time.
        $this->assertTrue($comment->modified->wasWithinLast('1 second'));
    }

    public function testCommentsUpdateController_ErrorCsrfToken()
    {
        $this->disableCsrfToken();

        $user = UserFactory::make()->user()->persist();
        $comment = CommentFactory::make()->withUser($user)->persist();
        $commentId = $comment->get('id');
        $this->logInAs($user);

        $this->put("/comments/$commentId.json?api-version=2");
        $this->assertResponseCode(403);
    }

    public function testCommentsUpdateController_ErrorNotAuthenticated()
    {
        $commentId = UuidFactory::uuid();
        $postData = [];
        $this->putJson("/comments/$commentId.json?api-version=v2", $postData);
        $this->assertAuthenticationError();
    }

    public function testCommentsUpdateController_NotAccessibleFields()
    {
        $commentatorId = UserFactory::make()->user()->persist()->get('id');
        $user = UserFactory::make()->user()->persist();
        $comment = CommentFactory::make()->withUser($user)->persist();
        $commentId = $comment->get('id');
        $this->logInAs($user);

        $commentData = [
            'id' => UuidFactory::uuid(),
            'content' => 'updated comment content',
            'parent_id' => UuidFactory::uuid(),
            'created' => '2015-06-06 10:00:00',
            'modified' => '2015-06-06 10:00:00',
            'created_by' => $commentatorId,
            'modified_by' => $commentatorId,
        ];

        $this->putJson("/comments/$commentId.json?api-version=v2", $commentData);
        $this->assertSuccess();

        // Check that the groups and its sub-models are saved as expected.
        $commentUpdated = CommentFactory::find()
            ->where(['id' => $this->_responseJsonBody->id])
            ->first();
        $this->assertNotEquals($commentData['id'], $commentUpdated->id);
        $this->assertEquals($commentData['content'], $commentUpdated->content);
        $this->assertNotEquals($commentData['parent_id'], $commentUpdated->parent_id);
        $this->assertNotEquals($commentData['created'], $commentUpdated->created);
        $this->assertNotEquals($commentData['modified'], $commentUpdated->modified);
        $this->assertNotEquals($commentData['created_by'], $commentUpdated->created_by);
        $this->assertNotEquals($commentData['modified_by'], $commentUpdated->modified_by);
    }
}
