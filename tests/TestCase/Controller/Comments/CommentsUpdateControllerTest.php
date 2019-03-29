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

use App\Model\Entity\Role;
use App\Model\Table\CommentsTable;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class CommentsUpdateControllerTest extends AppIntegrationTestCase
{
    public $Comments;

    public $fixtures = ['app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Comments', 'app.Base/Permissions'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Comments') ? [] : ['className' => CommentsTable::class];
        $this->Comments = TableRegistry::getTableLocator()->get('Comments', $config);
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
    }

    public function testCommentsUpdateApiV1Success()
    {
        $this->authenticateAs('irene', Role::USER);
        $commentContent = 'updated comment content';
        $putData = [
            'Comment' => [
                'content' => $commentContent,
            ],
        ];
        $commentId = UuidFactory::uuid('comment.id.apache-1');
        $this->putJson("/comments/$commentId.json", $putData);
        $this->assertSuccess();

        $comment = $this->Comments->find()
            ->where(['id' => $this->_responseJsonBody->Comment->id])
            ->first();
        $this->assertEquals($commentContent, $comment->content);
        $this->assertEquals(UuidFactory::uuid('user.id.irene'), $comment->created_by);
        $this->assertEquals(UuidFactory::uuid('user.id.irene'), $comment->modified_by);

        // Assert that modified time is within one second from the test time.
        $modifiedTime = strtotime($comment->modified);
        $nowTime = strtotime(date('c'));
        $this->assertTrue($nowTime - $modifiedTime < 1000);
    }

    public function testCommentsUpdateApiV2Success()
    {
        $this->authenticateAs('irene', Role::USER);
        $commentContent = 'updated comment content';
        $putData = [
            'content' => $commentContent,
        ];
        $commentId = UuidFactory::uuid('comment.id.apache-1');
        $this->putJson("/comments/$commentId.json?api-version=2", $putData);
        $this->assertSuccess();

        $comment = $this->Comments->find()
            ->where(['id' => $this->_responseJsonBody->id])
            ->first();
        $this->assertEquals($commentContent, $comment->content);
        $this->assertEquals(UuidFactory::uuid('user.id.irene'), $comment->modified_by);
        $this->assertEquals(UuidFactory::uuid('user.id.irene'), $comment->modified_by);

        // Assert that modified time is within one second from the test time.
        $modifiedTime = strtotime($comment->modified);
        $nowTime = strtotime(date('c'));
        $this->assertTrue($nowTime - $modifiedTime < 1000);
    }

    public function testCommentsUpdateErrorCsrfToken()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('irene', Role::USER);
        $commentId = UuidFactory::uuid('comment.id.apache-1');
        $this->put("/comments/$commentId.json?api-version=2");
        $this->assertResponseCode(403);
    }

    public function testCommentsUpdateErrorInvalidCommentId()
    {
        $this->authenticateAs('irene', Role::USER);
        $commentContent = 'updated comment content';
        $putData = [
            'Comment' => [
                'content' => $commentContent,
            ],
        ];
        $commentId = 'testBadUuid';
        $this->putJson("/comments/$commentId.json", $putData);
        $this->assertError(400, 'The comment id is not valid.');
        $this->assertEmpty($this->_responseJsonBody);
    }

    public function testCommentsUpdateErrorContentEmpty()
    {
        $this->authenticateAs('irene', Role::USER);
        $putData = [
            'Comment' => [
                'content' => '',
            ],
        ];
        $commentId = UuidFactory::uuid('comment.id.apache-1');
        $this->putJson("/comments/$commentId.json", $putData);
        $this->assertError(400, 'Could not validate comment data.');
        $this->assertNotEmpty($this->_responseJsonBody);
        $this->assertNotEmpty($this->_responseJsonBody->Comment->content);
    }

    public function testCommentsUpdateRuleValidationCommentDoesNotExist()
    {
        $this->authenticateAs('irene', Role::USER);
        $commentContent = 'updated comment content';
        $putData = [
            'Comment' => [
                'content' => $commentContent,
            ],
        ];
        $commentId = UuidFactory::uuid('comment.id.notexist');
        $this->putJson("/comments/$commentId.json", $putData);
        $this->assertError(404, 'The comment does not exist.');
        $this->assertEmpty($this->_responseJsonBody);
    }

    public function testCommentsUpdateRuleValidationCommentNotOwner()
    {
        $this->authenticateAs('ada', Role::USER);
        $commentContent = 'updated comment content';
        $putData = [
            'Comment' => [
                'content' => $commentContent,
            ],
        ];
        $commentId = UuidFactory::uuid('comment.id.apache-1');
        $this->putJson("/comments/$commentId.json", $putData);
        $this->assertError(403, 'You are not allowed to edit this comment.');
        $this->assertEmpty($this->_responseJsonBody);
    }

    public function testCommentsUpdateNotAccessibleFields()
    {
        $this->authenticateAs('irene', Role::USER);
        $comment = [
            'id' => UuidFactory::uuid('comment.id.shouldnotupdate'),
            'content' => 'updated comment content',
            'parent_id' => UuidFactory::uuid('comment.id.wrongparentid'),
            'created' => '2015-06-06 10:00:00',
            'modified' => '2015-06-06 10:00:00',
            'created_by' => UuidFactory::uuid('user.id.ada'),
            'modified_by' => UuidFactory::uuid('user.id.ada'),
        ];
        $putData = ['Comment' => $comment];
        $commentId = UuidFactory::uuid('comment.id.apache-1');
        $this->putJson("/comments/$commentId.json", $putData);
        $this->assertSuccess();

        // Check that the groups and its sub-models are saved as expected.
        $commentUpdated = $this->Comments->find()
            ->where(['id' => $this->_responseJsonBody->Comment->id])
            ->first();
        $this->assertNotEquals($comment['id'], $commentUpdated->id);
        $this->assertEquals($comment['content'], $commentUpdated->content);
        $this->assertNotEquals($comment['parent_id'], $commentUpdated->parent_id);
        $this->assertNotEquals($comment['created'], $commentUpdated->created);
        $this->assertNotEquals($comment['modified'], $commentUpdated->modified);
        $this->assertNotEquals($comment['created_by'], $commentUpdated->created_by);
        $this->assertNotEquals($comment['modified_by'], $commentUpdated->modified_by);
    }

    public function testCommentsUpdateErrorNotAuthenticated()
    {
        $postData = [];
        $resourceId = UuidFactory::uuid('comment.id.apache-1');
        $this->putJson("/comments/$resourceId.json", $postData);
        $this->assertAuthenticationError();
    }
}
