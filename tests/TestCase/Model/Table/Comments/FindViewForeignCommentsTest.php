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
namespace App\Test\TestCase\Model\Table\Comments;

use App\Model\Table\CommentsTable;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\CommentsModelTrait;
use App\Utility\UuidFactory;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;

class FindViewForeignCommentsTest extends AppTestCase
{
    use CommentsModelTrait;
    public $Comments;

    public $fixtures = ['app.Base/Resources', 'app.Base/Users', 'app.Base/Profiles', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Permissions', 'app.Base/Comments', 'app.Base/Avatars'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Comments') ? [] : ['className' => CommentsTable::class];
        $this->Comments = TableRegistry::getTableLocator()->get('Comments', $config);
    }

    public function tearDown()
    {
        unset($this->Comments);

        parent::tearDown();
    }

    public function testSuccess()
    {
        $comments = $this->Comments->findViewForeignComments(UuidFactory::uuid('user.id.ada'), 'Resource', UuidFactory::uuid('resource.id.apache'))->all();
        $this->assertEquals(1, count($comments));

        $firstComment = $comments->first();
        $this->assertCommentAttributes($firstComment);

        $this->assertEquals(1, count($firstComment->children));
        $this->assertCommentAttributes($firstComment->children[0]);
    }

    public function testContainCreator()
    {
        $options['contain']['creator'] = true;
        $comments = $this->Comments->findViewForeignComments(UuidFactory::uuid('user.id.ada'), 'Resource', UuidFactory::uuid('resource.id.apache'), $options)->all();
        $comment = $comments->first();

        // Expected content.
        $this->assertCommentAttributes($comment);
        $this->assertObjectHasAttribute('creator', $comment);
        $this->assertUserAttributes($comment->creator);
    }

    public function testContainModifier()
    {
        $options['contain']['modifier'] = true;
        $comments = $this->Comments->findViewForeignComments(UuidFactory::uuid('user.id.ada'), 'Resource', UuidFactory::uuid('resource.id.apache'), $options)->all();
        $comment = $comments->first();

        // Expected content.
        $this->assertCommentAttributes($comment);
        $this->assertObjectHasAttribute('modifier', $comment);
        $this->assertUserAttributes($comment->modifier);
    }

    public function testErrorInvalidModelNameParameter()
    {
        try {
            $this->Comments->findViewForeignComments(UuidFactory::uuid('user.id.ada'), 'UnsupportedModel', UuidFactory::uuid('resource.id.apache'));
        } catch (\InvalidArgumentException $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Calling findViewForeignComments with a wrong model name should have triggered an exception');
    }

    public function testErrorInvalidModelIdParameter()
    {
        try {
            $this->Comments->findViewForeignComments(UuidFactory::uuid('user.id.ada'), 'Resource', 'wrong-uuid');
        } catch (\InvalidArgumentException $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Calling findViewForeignComments with a incorrectly formatted uuid should have triggered an exception');
    }

    public function testErrorNonExistingModelIdParameter()
    {
        try {
            $this->Comments->findViewForeignComments(UuidFactory::uuid('user.id.ada'), 'Resource', UuidFactory::uuid('Resource.id.doesnotexist'));
        } catch (RecordNotFoundException $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Calling findViewForeignComments with a non existing uuid should have triggered a RecordNotFoundException exception');
    }

    public function testErrorDeletedModelIdParameter()
    {
        // Delete resource before testing.
        $Resources = TableRegistry::getTableLocator()->get('Resources');
        $resource = $Resources->get(UuidFactory::uuid('resource.id.apache'));
        $resource->deleted = 1;
        $Resources->save($resource);

        try {
            $this->Comments->findViewForeignComments(UuidFactory::uuid('user.id.ada'), 'Resource', UuidFactory::uuid('resource.id.apache'));
        } catch (RecordNotFoundException $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Calling findViewForeignComments with a non existing uuid should have triggered a RecordNotFoundException exception');
    }

    public function testErrorNonAccessibleModelIdParameter()
    {
        // Test to access the comments of a resource that is not readable by a given user. Frances in our case.
        try {
            $this->Comments->findViewForeignComments(UuidFactory::uuid('user.id.frances'), 'Resource', UuidFactory::uuid('resource.id.apache'));
        } catch (RecordNotFoundException $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Calling findViewForeignComments with a non existing uuid should have triggered a RecordNotFoundException exception');
    }
}
