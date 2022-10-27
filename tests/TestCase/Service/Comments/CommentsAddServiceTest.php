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

namespace App\Test\TestCase\Service\Comments;

use App\Service\Comments\CommentsAddService;
use App\Test\Factory\CommentFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\UserAccessControlTrait;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class CommentsAddServiceTest extends TestCase
{
    use TruncateDirtyTables;
    use UserAccessControlTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new CommentsAddService();
    }

    public function testCommentsAddService_Success()
    {
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $uac = $this->makeUac($user);

        $commentContent = 'this is a test';
        $data = [
            'content' => $commentContent,
        ];

        $comment = $this->service->add($uac, $resource->get('id'), $data);

        // Check that the groups and its sub-models are saved as expected.
        $comment = CommentFactory::find()
            ->where(['id' => $comment->id])
            ->first();
        $this->assertEquals($commentContent, $comment->content);
    }

    public function testCommentsAddService_WithParentIdSuccess()
    {
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $parentComment = CommentFactory::make()->withUser($user)->withResource($resource)->persist();
        $parentCommentId = $parentComment->get('id');
        $uac = $this->makeUac($user);

        $commentContent = 'this is a test with parent_id';
        $data = [
            'content' => $commentContent,
            'parent_id' => $parentCommentId,
        ];

        $comment = $this->service->add($uac, $resource->get('id'), $data);

        // Check that the groups and its sub-models are saved as expected.
        $comment = CommentFactory::find()
            ->where(['id' => $comment->id])
            ->where(['parent_id' => $parentCommentId])
            ->first();
        $this->assertEquals($commentContent, $comment->content);
    }

    public function testCommentsAddService_ErrorInvalidResourceId()
    {
        $user = UserFactory::make()->user()->persist();
        $uac = $this->makeUac($user);

        $data = [
            'content' => 'this is a test',
        ];
        $resourceId = 'testBadUuid';

        $this->expectException(BadRequestException::class);
        $this->service->add($uac, $resourceId, $data);
    }

    public function testCommentsAddService_RuleValidationResourceIsSoftDeleted()
    {
        $user = UserFactory::make()->user()->persist();
        $resourceId = ResourceFactory::make()->withCreatorAndPermission($user)->setDeleted()->persist()->get('id');
        $uac = $this->makeUac($user);

        $data = [
            'content' => 'this is a test',
        ];

        $this->expectException(NotFoundException::class);
        $this->service->add($uac, $resourceId, $data);
    }

    public function testCommentsAddService_RuleValidationResourceAccessDenied()
    {
        $userA = UserFactory::make()->user()->persist();
        $uac = $this->makeUac($userA);
        $userB = UserFactory::make()->user()->persist();
        $resourceId = ResourceFactory::make()->withCreatorAndPermission($userB)->persist()->get('id');

        $data = [
            'content' => 'this is a test',
        ];

        $this->expectException(NotFoundException::class);
        $this->service->add($uac, $resourceId, $data);
    }

    public function testCommentsAddService_ErrorValidationParentIdDoesNotExist()
    {
        $user = UserFactory::make()->user()->persist();
        $uac = $this->makeUac($user);
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();

        $data = [
            'content' => 'this is a test with parent_id',
            'parent_id' => 'uuidDoesNotExist',
        ];

        $this->expectException(BadRequestException::class);
        $this->service->add($uac, $resource->get('id'), $data);
    }

    public function testCommentsAddService_ErrorValidationContentNotProvided()
    {
        $user = UserFactory::make()->user()->persist();
        $uac = $this->makeUac($user);
        $resourceId = ResourceFactory::make()->withCreatorAndPermission($user)->persist()->get('id');

        $data = [
            'content' => '',
        ];

        $this->expectException(BadRequestException::class);
        $this->service->add($uac, $resourceId, $data);
    }

    public function testCommentsAddService_CannotModifyNotAccessibleFields()
    {
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $uac = $this->makeUac($user);

        $createdBy = UuidFactory::uuid();
        $createdDate = '2012-01-01 00:00:00';
        $commentContent = 'this is a test';

        $data = [
            'content' => $commentContent,
            'user_id' => $createdBy,
            'created_by' => $createdBy,
            'modified_by' => $createdBy,
            'created' => $createdDate,
            'modified' => $createdDate,
        ];

        $comment = $this->service->add($uac, $resource->get('id'), $data);

        // Check that the groups and its sub-models are saved as expected.
        $comment = CommentFactory::find()
            ->where(['id' => $comment->id])
            ->first();
        $this->assertEquals($commentContent, $comment->content);
        $this->assertNotEquals($createdDate, $comment->created);
        $this->assertNotEquals($createdDate, $comment->modified);
        $this->assertNotEquals($createdBy, $comment->created_by);
        $this->assertNotEquals($createdBy, $comment->modified_by);
        $this->assertNotEquals($createdBy, $comment->user_id);
    }
}
