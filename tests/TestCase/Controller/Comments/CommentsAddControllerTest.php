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
use App\Model\Table\ResourcesTable;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class CommentsAddControllerTest extends AppIntegrationTestCase
{
    public $Comments;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Comments',
        'app.Base/Permissions', 'app.Base/Avatars', 'app.Base/Roles', 'app.Base/Profiles', 'app.Base/EmailQueue',
        'app.Base/Gpgkeys', 'app.Base/OrganizationSettings',
    ];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Comments') ? [] : ['className' => CommentsTable::class];
        $this->Comments = TableRegistry::getTableLocator()->get('Comments', $config);
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
    }

    public function testCommentsAddSuccess()
    {
        $this->authenticateAs('ada');
        $commentContent = 'this is a test';
        $postData = [
            'content' => $commentContent,
        ];
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->postJson("/comments/resource/$resourceId.json?api-version=v2", $postData);
        $this->assertSuccess();

        // Check that the groups and its sub-models are saved as expected.
        $comment = $this->Comments->find()
            ->where(['id' => $this->_responseJsonBody->id])
            ->first();
        $this->assertEquals($commentContent, $comment->content);
    }

    public function testCommentsAddApiV1Success()
    {
        $this->authenticateAs('ada');
        $commentContent = 'this is a test';
        $postData = [
            'Comment' => [
                'content' => $commentContent,
            ],
        ];
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertSuccess();

        // Check that the groups and its sub-models are saved as expected.
        $comment = $this->Comments->find()
            ->where(['id' => $this->_responseJsonBody->Comment->id])
            ->first();
        $this->assertEquals($commentContent, $comment->content);
    }

    public function testCommentsAddApiV1WithParentIdSuccess()
    {
        $this->authenticateAs('ada');
        $commentContent = 'this is a test with parent_id';
        $postData = [
            'Comment' => [
                'content' => $commentContent,
                'parent_id' => UuidFactory::uuid('comment.id.apache-1')
            ],
        ];
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertSuccess();

        // Check that the groups and its sub-models are saved as expected.
        $comment = $this->Comments->find()
            ->where(['id' => $this->_responseJsonBody->Comment->id])
            ->first();
        $this->assertEquals($commentContent, $comment->content);
    }

    public function testCommentsAddErrorCsrfToken()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->post("/comments/resource/$resourceId.json?api-version=v2");
        $this->assertResponseCode(403);
    }

    public function testCommentsAddErrorInvalidResourceId()
    {
        $this->authenticateAs('ada');
        $commentContent = 'this is a test';
        $postData = [];
        $resourceId = 'testBadUuid';
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertError(400, 'The resource id is not valid.');
        $this->assertEmpty($this->_responseJsonBody);
    }

    public function testCommentsAddRuleValidationResourceDoesNotExist()
    {
        $this->authenticateAs('ada');
        $commentContent = 'this is a test';
        $postData = ['Comment' => ['content' => $commentContent]];
        $resourceId = UuidFactory::uuid('resource.id.notexist');
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertError(404, 'The resource does not exist.');
        $this->assertEmpty($this->_responseJsonBody);
    }

    public function testCommentsAddRuleValidationResourceIsSoftDeleted()
    {
        // Soft delete resource "cakephp".
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $resource = $this->Resources->find()
            ->where(['id' => $resourceId])
            ->first();
        $resource->deleted = 1;
        $this->Resources->save($resource);

        // Now authenticate as Ada and try to access the soft deleted resource.
        $this->authenticateAs('ada');
        $commentContent = 'this is a test';
        $postData = ['Comment' => ['content' => $commentContent]];
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertError(404, 'The resource does not exist.');
        $this->assertEmpty($this->_responseJsonBody);
    }

    public function testCommentsAddRuleValidationResourceAccessDenied()
    {
        $this->authenticateAs('ada');
        $commentContent = 'this is a test';
        $postData = ['Comment' => ['content' => $commentContent]];
        $resourceId = UuidFactory::uuid('resource.id.chai');
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertError(404, 'The resource does not exist.');
        $this->assertEmpty($this->_responseJsonBody);
    }

    public function testCommentsAddErrorValidationParentIdDoesNotExist()
    {
        $this->authenticateAs('ada');
        $commentContent = 'this is a test with parent_id';
        $postData = [
            'Comment' => [
                'content' => $commentContent,
                'parent_id' => UuidFactory::uuid('comment.id.doesNotExist')
            ],
        ];
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertError(400, 'Could not validate comment data.');
        $this->assertEmpty($this->_responseJsonBody);
    }

    public function testCommentsAddErrorValidationContentNotProvided()
    {
        $this->authenticateAs('ada');
        $postData = [
            'Comment' => [
                'content' => '',
            ],
        ];
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertError(400, 'Could not validate comment data.');
        $this->assertEmpty($this->_responseJsonBody);
    }

    public function testCommentsAddCannotModifyNotAccessibleFields()
    {
        $this->authenticateAs('ada');
        $createdDate = '2012-01-01 00:00:00';
        $createdBy = UuidFactory::uuid('user.id.betty');
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
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
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

    public function testCommentsAddErrorNotAuthenticated()
    {
        $postData = [];
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $this->postJson("/comments/resource/$resourceId.json", $postData);
        $this->assertAuthenticationError();
    }
}
