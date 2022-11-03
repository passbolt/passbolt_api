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

use App\Service\Comments\CommentsDeleteService;
use App\Test\Factory\CommentFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class CommentsDeleteServiceTest extends TestCase
{
    use TruncateDirtyTables;

    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new CommentsDeleteService();
    }

    public function testCommentsDeleteService_Success()
    {
        $user = UserFactory::make()->user()->persist();
        $comment = CommentFactory::make()->withUser($user)->persist();
        $commentId = $comment->get('id');

        $this->service->delete($commentId, $user->get('id'));

        $comment = CommentFactory::find()
            ->where(['id' => $commentId])
            ->first();
        $this->assertNull($comment);
    }

    public function testCommentsDeleteService_ErrorNotValidId()
    {
        $userId = UserFactory::make()->user()->persist()->get('id');
        $commentId = 'invalid-id';

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The comment id is not valid.');
        $this->service->delete($commentId, $userId);
    }

    public function testCommentsDeleteService_ErrorCommentsNotFound()
    {
        $userId = UserFactory::make()->user()->persist()->get('id');
        $commentId = UuidFactory::uuid();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The comment does not exist.');
        $this->service->delete($commentId, $userId);
    }

    public function testCommentsDeleteService_ErrorCommentsNotOwner()
    {
        $userId = UserFactory::make()->user()->persist()->get('id');
        $commentId = CommentFactory::make()->persist()->get('id');

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The comment does not exist.');
        $this->service->delete($commentId, $userId);
    }
}
