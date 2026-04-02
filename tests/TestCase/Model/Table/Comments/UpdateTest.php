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
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class UpdateTest extends AppTestCase
{
    use FormatValidationTrait;

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

    public static function getEntityDefaultOptions()
    {
        $entityOptions = [
            'validate' => 'default',
            'accessibleFields' => [
                'user_id' => false,
                'parent_id' => false,
                'foreign_key' => false,
                'foreign_model' => false,
                'content' => true,
                'created_by' => false,
                'modified_by' => true,
            ],
        ];

        return $entityOptions;
    }

    /* FORMAT VALIDATION TESTS */

    public function testValidationContent()
    {
        $testCases = [
            'utf8Extended' => self::getUtf8ExtendedTestCases(50),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'lengthBetween' => self::getLengthBetweenTestCases(1, 255),
        ];
        $user = UserFactory::make()->persist();
        $commentId = CommentFactory::make()->withUser($user)->withCreatorAndModifier($user)->persist()->id;
        $comment = [
            'id' => $commentId,
            'content' => 'this is a test comment',
            'modified_by' => $user->id,
        ];
        $this->assertFieldFormatValidation($this->Comments, 'content', $comment, self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationModifiedBy()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
        ];
        $user = UserFactory::make()->persist();
        $commentId = CommentFactory::make()->withUser($user)->withCreatorAndModifier($user)->persist()->id;
        $comment = [
            'id' => $commentId,
            'content' => 'this is a test comment',
            'modified_by' => $user->id,
        ];
        $this->assertFieldFormatValidation($this->Comments, 'modified_by', $comment, self::getEntityDefaultOptions(), $testCases);
    }

    /* LOGIC VALIDATION TESTS */

    public function testErrorModifiedByUserDoesNotExist()
    {
        $user = UserFactory::make()->persist();
        $commentId = CommentFactory::make()->withUser($user)->persist()->id;
        $comment = $this->Comments->get($commentId);
        $comment = $this->Comments->patchEntity($comment, ['content' => 'test', 'modified_by' => UuidFactory::uuid()], self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment, ['Comments.user_id' => $user->id]);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['modified_by']['modifier_exists']);
    }

    public function testErrorModifiedByUserIsNotOwner()
    {
        $user = UserFactory::make()->persist();
        $commentId = CommentFactory::make()->persist()->id;
        $comment = $this->Comments->get($commentId);
        $comment = $this->Comments->patchEntity($comment, ['content' => 'test', 'modified_by' => $user->id], self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment, ['Comments.user_id' => $user->id]);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['user_id']['is_owner']);
    }

    public function testSuccess()
    {
        $user = UserFactory::make()->persist();
        $commentId = CommentFactory::make()->withUser($user)->persist()->id;
        $comment = $this->Comments->get($commentId);
        $comment = $this->Comments->patchEntity($comment, ['content' => 'updated comment', 'modified_by' => $user->id], self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment, ['Comments.user_id' => $user->id]);
        $this->assertTrue((bool)$save);
        $errors = $comment->getErrors();
        $this->assertEmpty($errors);

        // Check the comment exists in db.
        $updatedComment = $this->Comments->get($save->id);
        $this->assertEquals($user->id, $updatedComment->modified_by);
        $this->assertEquals('updated comment', $updatedComment->content);
    }
}
