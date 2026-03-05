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
namespace App\Test\TestCase\Model\Table\Comments;

use App\Test\Factory\CommentFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\CleanupTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class CleanupTest extends AppTestCase
{
    use CleanupTrait;

    public $Comments;

    public function setUp(): void
    {
        parent::setUp();
        $this->Comments = TableRegistry::getTableLocator()->get('Comments');
    }

    public function tearDown(): void
    {
        unset($this->Comments);
        parent::tearDown();
    }

    public function testCleanupCommentsSoftDeletedUsersSuccess()
    {
        $resource = ResourceFactory::make()->persist();
        $softDeletedUser = UserFactory::make()->deleted()->persist();

        $originalCount = $this->Comments->find()->all()->count();

        CommentFactory::make()->withUser($softDeletedUser)->withResource($resource)->persist();

        $this->runCleanupChecks('Comments', 'cleanupSoftDeletedUsers', $originalCount);
    }

    public function testCleanupCommentsHardDeletedUsersSuccess()
    {
        $resource = ResourceFactory::make()->persist();

        $originalCount = $this->Comments->find()->all()->count();

        CommentFactory::make(['user_id' => UuidFactory::uuid('user.id.nope')])->withResource($resource)->persist();

        $this->runCleanupChecks('Comments', 'cleanupHardDeletedUsers', $originalCount);
    }

    public function testCleanupCommentsSoftDeletedResourcesSuccess()
    {
        $resourceSoftDeleted = ResourceFactory::make()->deleted()->persist();
        $user = UserFactory::make()->persist();

        $originalCount = $this->Comments->find()->all()->count();

        CommentFactory::make()->withUser($user)->withResource($resourceSoftDeleted)->persist();

        $this->runCleanupChecks('Comments', 'cleanupSoftDeletedResources', $originalCount);
    }

    public function testCleanupCommentsHardDeletedResourcesSuccess()
    {
        $user = UserFactory::make()->persist();

        $originalCount = $this->Comments->find()->all()->count();

        CommentFactory::make(['foreign_key' => UuidFactory::uuid('resource.id.nope')])->withUser($user)->persist();

        $this->runCleanupChecks('Comments', 'cleanupHardDeletedResources', $originalCount);
    }
}
