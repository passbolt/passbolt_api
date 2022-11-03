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

use App\Service\Comments\CommentsViewService;
use App\Test\Factory\CommentFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\UserAccessControlTrait;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class CommentsViewServiceTest extends TestCase
{
    use TruncateDirtyTables;
    use UserAccessControlTrait;

    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new CommentsViewService();
    }

    public function testCommentsViewService_Success()
    {
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $comment = CommentFactory::make()->withUser($user)->withResource($resource)->persist();
        CommentFactory::make()->withUser($user)->withResource($resource)->withParent($comment)->persist();
        $resourceId = $resource->get('id');

        $comments = $this->service->view($user->get('id'), 'Resource', $resourceId, []);
        $commentsArr = $comments->toArray();
        $this->assertGreaterThan(0, count($commentsArr));

        // Expected content.
        $this->assertEquals(count($commentsArr[0]['children']), 1);

        // Not expected content.
        $this->assertObjectNotHasAttribute('modifier', $commentsArr[0]);
        $this->assertObjectNotHasAttribute('creator', $commentsArr[0]);
    }

    public function testCommentsViewService_ErrorNotFound()
    {
        $user = UserFactory::make()->user()->persist();
        $resourceId = UuidFactory::uuid();

        $this->expectException(NotFoundException::class);
        $this->service->view($user->get('id'), 'Resource', $resourceId, []);
    }

    public function testCommentsViewService_ErrorWrongModelNameParameter()
    {
        $user = UserFactory::make()->user()->persist();
        $resourceId = ResourceFactory::make()->withCreatorAndPermission($user)->persist()->get('id');

        $this->expectException(BadRequestException::class);
        $this->service->view($user->get('id'), 'WrongModelName', $resourceId, []);
    }

    public function testCommentsViewService_ErrorWrongUuidParameter()
    {
        $user = UserFactory::make()->user()->persist();
        $resourceId = 'wrong-uuid';

        $this->expectException(BadRequestException::class);
        $this->service->view($user->get('id'), 'Resource', $resourceId, []);
    }
}
