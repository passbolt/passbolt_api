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

use App\Model\Table\CommentsTable;
use App\Test\Factory\CommentFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\CommentsModelTrait;
use App\Utility\UuidFactory;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use InvalidArgumentException;

class FindViewForeignCommentsTest extends AppTestCase
{
    use CommentsModelTrait;

    public $Comments;

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Comments') ? [] : ['className' => CommentsTable::class];
        $this->Comments = TableRegistry::getTableLocator()->get('Comments', $config);
    }

    public function tearDown(): void
    {
        unset($this->Comments);

        parent::tearDown();
    }

    public function testSuccess()
    {
        $user = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $comment = CommentFactory::make()->withUser($user)->withResource($resource)->persist();
        CommentFactory::make()->withUser($user)->withResource($resource)->withParent($comment)->persist();
        $comments = $this->Comments->findViewForeignComments($user->id, 'Resource', $resource->id)->all();
        $this->assertEquals(1, count($comments));
        $firstComment = $comments->first();
        $this->assertCommentAttributes($firstComment);

        $this->assertEquals(1, count($firstComment->children));
        $this->assertCommentAttributes($firstComment->children[0]);
    }

    public function testContainCreator()
    {
        $user = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        CommentFactory::make()->withCreator($user)->withUser($user)->withResource($resource)->persist();
        $options['contain']['creator'] = true;
        $comments = $this->Comments->findViewForeignComments($user->id, 'Resource', $resource->id, $options)->all();
        $comment = $comments->first();

        // Expected content.
        $this->assertCommentAttributes($comment);
        $this->assertObjectHasAttribute('creator', $comment);
        $this->assertUserAttributes($comment->creator);
    }

    public function testContainModifier()
    {
        $user = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        CommentFactory::make()->withModifier($user)->withUser($user)->withResource($resource)->persist();
        $options['contain']['modifier'] = true;
        $comments = $this->Comments->findViewForeignComments($user->id, 'Resource', $resource->id, $options)->all();
        $comment = $comments->first();

        // Expected content.
        $this->assertCommentAttributes($comment);
        $this->assertObjectHasAttribute('modifier', $comment);
        $this->assertUserAttributes($comment->modifier);
    }

    public function testErrorInvalidModelNameParameter()
    {
        $user = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        CommentFactory::make()->withUser($user)->withResource($resource)->persist();
        try {
            $this->Comments->findViewForeignComments($user->id, 'UnsupportedModel', $resource->id);
        } catch (InvalidArgumentException $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Calling findViewForeignComments with a wrong model name should have triggered an exception');
    }

    public function testErrorInvalidModelIdParameter()
    {
        $user = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        CommentFactory::make()->withUser($user)->withResource($resource)->persist();
        try {
            $this->Comments->findViewForeignComments($user->id, 'Resource', 'wrong-uuid');
        } catch (InvalidArgumentException $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Calling findViewForeignComments with a incorrectly formatted uuid should have triggered an exception');
    }

    public function testErrorNonExistingModelIdParameter()
    {
        $user = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        CommentFactory::make()->withUser($user)->withResource($resource)->persist();
        try {
            $this->Comments->findViewForeignComments($user->id, 'Resource', UuidFactory::uuid('Resource.id.doesnotexist'));
        } catch (RecordNotFoundException $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Calling findViewForeignComments with a non existing uuid should have triggered a RecordNotFoundException exception');
    }

    public function testErrorDeletedModelIdParameter()
    {
        $user = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        CommentFactory::make()->withUser($user)->withResource($resource)->persist();
        // Delete resource before testing.
        $Resources = TableRegistry::getTableLocator()->get('Resources');
        $resource = $Resources->get($resource->id);
        $resource->deleted = 1;
        $Resources->save($resource);

        try {
            $this->Comments->findViewForeignComments($user->id, 'Resource', $resource->id);
        } catch (RecordNotFoundException $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Calling findViewForeignComments with a non existing uuid should have triggered a RecordNotFoundException exception');
    }

    public function testErrorNonAccessibleModelIdParameter()
    {
        [$user, $userNoAccess] = UserFactory::make(2)->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        CommentFactory::make()->withUser($user)->withResource($resource)->persist();
        // Test to access the comments of a resource that is not readable by a given user. Frances in our case.
        try {
            $this->Comments->findViewForeignComments($userNoAccess->id, 'Resource', $resource->id);
        } catch (RecordNotFoundException $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Calling findViewForeignComments with a non existing uuid should have triggered a RecordNotFoundException exception');
    }
}
