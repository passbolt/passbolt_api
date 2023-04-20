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

use App\Error\Exception\ValidationException;
use App\Service\Comments\CommentsUpdateService;
use App\Test\Factory\CommentFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\UserAccessControlTrait;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class CommentsUpdateServiceTest extends TestCase
{
    use TruncateDirtyTables;
    use UserAccessControlTrait;

    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new CommentsUpdateService();
    }

    public function testCommentsUpdateService_Success()
    {
        $user = UserFactory::make()->user()->persist();
        $commentId = CommentFactory::make()->withUser($user)->persist()->get('id');

        $commentContent = 'this is a test';
        $comment = $this->service->update($user->get('id'), $commentId, $commentContent);

        // Check that the groups and its sub-models are saved as expected.
        $comment = CommentFactory::find()
            ->where(['id' => $commentId])
            ->first();
        $this->assertEquals($commentContent, $comment->content);
    }

    public function testCommentsUpdateService_ErrorInvalidCommentId()
    {
        $user = UserFactory::make()->user()->persist();
        $commentId = 'testBadUuid';
        $commentContent = 'updated comment content';

        $this->expectException(BadRequestException::class);
        $this->service->update($user->get('id'), $commentId, $commentContent);
    }

    public function testCommentsUpdateService_ErrorContentEmpty()
    {
        $user = UserFactory::make()->user()->persist();
        $commentId = CommentFactory::make()->withUser($user)->persist()->get('id');
        $commentContent = '';

        $this->expectException(ValidationException::class);
        $this->service->update($user->get('id'), $commentId, $commentContent);
    }

    public function testCommentsUpdateService_RuleValidationCommentDoesNotExist()
    {
        $user = UserFactory::make()->user()->persist();
        $commentId = UuidFactory::uuid();
        $commentContent = 'updated comment content';

        $this->expectException(NotFoundException::class);
        $this->service->update($user->get('id'), $commentId, $commentContent);
    }

    public function testCommentsUpdateService_RuleValidationCommentNotOwner()
    {
        $user = UserFactory::make()->user()->persist();
        $commentId = CommentFactory::make()->persist()->get('id');
        $commentContent = 'updated comment content';

        $this->expectException(ForbiddenException::class);
        $this->service->update($user->get('id'), $commentId, $commentContent);
    }
}
