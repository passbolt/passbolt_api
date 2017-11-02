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

namespace App\Test\TestCase\Controller\Groups;

use App\Model\Entity\Role;
use App\Model\Table\CommentsTable;
use App\Model\Table\ResourcesTable;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\Common;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class CommentsAddControllerTest extends AppIntegrationTestCase
{
    public $Comments;

    public $fixtures = ['app.users', 'app.groups', 'app.groups_users', 'app.resources', 'app.comments', 'app.permissions'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Comments') ? [] : ['className' => CommentsTable::class];
        $this->Comments = TableRegistry::get('Comments', $config);
        $this->Resources = TableRegistry::get('Resources');
    }

    public function testAddApiV1Success()
    {
        $this->authenticateAs('ada', Role::USER);
        $commentContent = 'this is a test';
        $postData = [
            'Comment' => [
                'content' => $commentContent,
            ],
        ];
        $resourceId = Common::uuid('resource.id.bower');
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertSuccess();

        // Check that the groups and its sub-models are saved as expected.
        $comment = $this->Comments->find()
            ->where(['id' => $this->_responseJsonBody->Comment->id])
            ->first();
        $this->assertEquals($commentContent, $comment->content);
    }

    public function testAddApiV1WithParentIdSuccess()
    {
        $this->authenticateAs('ada', Role::USER);
        $commentContent = 'this is a test with parent_id';
        $postData = [
            'Comment' => [
                'content' => $commentContent,
                'parent_id' => Common::uuid('comment.id.apache-1')
            ],
        ];
        $resourceId = Common::uuid('resource.id.apache');
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertSuccess();

        // Check that the groups and its sub-models are saved as expected.
        $comment = $this->Comments->find()
            ->where(['id' => $this->_responseJsonBody->Comment->id])
            ->first();
        $this->assertEquals($commentContent, $comment->content);
    }

    public function testErrorInvalidResourceId()
    {
        $this->authenticateAs('ada', Role::USER);
        $commentContent = 'this is a test';
        $postData = [];
        $resourceId = 'testBadUuid';
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertError(400, 'The resource id is not valid.');
        $this->assertEmpty($this->_responseJsonBody);
    }

    public function testAddRuleValidationResourceDoesNotExist()
    {
        $this->authenticateAs('ada', Role::USER);
        $commentContent = 'this is a test';
        $postData = ['Comment' => ['content' => $commentContent]];
        $resourceId = Common::uuid('resource.id.notexist');
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertError(404, 'The resource does not exist.');
        $this->assertEmpty($this->_responseJsonBody);
    }

    public function testAddRuleValidationResourceIsSoftDeleted()
    {
        // Soft delete resource "cakephp".
        $resourceId = Common::uuid('resource.id.cakephp');
        $resource = $this->Resources->find()
            ->where(['id' => $resourceId])
            ->first();
        $resource->deleted = 1;
        $this->Resources->save($resource);

        // Now authenticate as Ada and try to access the soft deleted resource.
        $this->authenticateAs('ada', Role::USER);
        $commentContent = 'this is a test';
        $postData = ['Comment' => ['content' => $commentContent]];
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertError(404, 'The resource does not exist.');
        $this->assertEmpty($this->_responseJsonBody);
    }

    public function testAddRuleValidationResourceAccessDenied()
    {
        $this->authenticateAs('ada', Role::USER);
        $commentContent = 'this is a test';
        $postData = ['Comment' => ['content' => $commentContent]];
        $resourceId = Common::uuid('resource.id.chai');
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertError(404, 'The resource does not exist.');
        $this->assertEmpty($this->_responseJsonBody);
    }

    public function testAddErrorValidationParentIdDoesNotExist()
    {
        $this->authenticateAs('ada', Role::USER);
        $commentContent = 'this is a test with parent_id';
        $postData = [
            'Comment' => [
                'content' => $commentContent,
                'parent_id' => Common::uuid('comment.id.doesNotExist')
            ],
        ];
        $resourceId = Common::uuid('resource.id.apache');
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertError(400, 'Could not validate comment data.');
        $this->assertEmpty($this->_responseJsonBody);
    }

    public function testAddErrorValidationContentNotProvided()
    {
        $this->authenticateAs('ada', Role::USER);
        $postData = [
            'Comment' => [
                'content' => '',
            ],
        ];
        $resourceId = Common::uuid('resource.id.cakephp');
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertError(400, 'Could not validate comment data.');
        $this->assertEmpty($this->_responseJsonBody);
    }

    public function testCannotModifyNotAccessibleFields()
    {
        $this->authenticateAs('ada', Role::USER);
        $createdDate = '2012-01-01 00:00:00';
        $createdBy = Common::uuid('user.id.betty');
        $commentContent = 'this is a test';
        $postData = [
            'Comment' => [
                'content' => $commentContent,
                'user_id' => $createdBy,
                'created_by' => $createdBy,
                'modified_by' => $createdBy,
                'created' => $createdDate,
                'modified' => $createdDate
            ],
        ];
        $resourceId = Common::uuid('resource.id.cakephp');
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertSuccess();

        // Check that the groups and its sub-models are saved as expected.
        $comment = $this->Comments->find()
            ->where(['id' => $this->_responseJsonBody->Comment->id])
            ->first();
        $this->assertEquals($commentContent, $comment->content);
        $this->assertNotEquals($createdDate, $comment->created);
        $this->assertNotEquals($createdDate, $comment->modified);
        $this->assertNotEquals($createdBy, $comment->created_by);
        $this->assertNotEquals($createdBy, $comment->modified_by);
        $this->assertNotEquals($createdBy, $comment->user_id);
    }

    public function testAddErrorNotAuthenticated()
    {
        $postData = [];
        $resourceId = Common::uuid('resource.id.cakephp');
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertAuthenticationError();
    }
}
