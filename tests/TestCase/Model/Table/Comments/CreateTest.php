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
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class CreateTest extends AppTestCase
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
                'user_id' => true,
                'parent_id' => true,
                'foreign_key' => true,
                'foreign_model' => true,
                'content' => true,
                'created_by' => true,
                'modified_by' => true,
            ],
        ];

        return $entityOptions;
    }

    /**
     * Build default comment using factories.
     */
    private function generateDummyComment(array $data = [], bool $persist = true): array
    {
        if ($persist) {
            $user = UserFactory::make()->persist();
            $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
            $userId = $user->id;
            $resourceId = $resource->id;
        } else {
            $userId = UuidFactory::uuid();
            $resourceId = UuidFactory::uuid();
        }

        return array_merge([
            'user_id' => $userId,
            'foreign_key' => $resourceId,
            'foreign_model' => 'Resource',
            'content' => 'this is a test comment',
            'parent_id' => null,
            'created_by' => $userId,
            'modified_by' => $userId,
        ], $data);
    }

    /* FORMAT VALIDATION TESTS */

    public function testValidationUserId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Comments, 'user_id', $this->generateDummyComment(persist: false), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationParentId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'allowEmpty' => self::getAllowEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Comments, 'parent_id', $this->generateDummyComment(persist: false), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationForeignModel()
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'inList' => self::getInListTestCases(CommentsTable::ALLOWED_FOREIGN_MODELS),
        ];
        $this->assertFieldFormatValidation($this->Comments, 'foreign_model', $this->generateDummyComment(persist: false), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationForeignId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Comments, 'foreign_key', $this->generateDummyComment(persist: false), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationContent()
    {
        $testCases = [
            'utf8Extended' => self::getUtf8ExtendedTestCases(50),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'lengthBetween' => self::getLengthBetweenTestCases(1, 255),
        ];
        $this->assertFieldFormatValidation($this->Comments, 'content', $this->generateDummyComment(persist: false), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationCreatedBy()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Comments, 'created_by', $this->generateDummyComment(persist: false), self::getEntityDefaultOptions(), $testCases);
    }

    /* LOGIC VALIDATION TESTS */

    public function testErrorUserDoesNotExist()
    {
        $data = $this->generateDummyComment(['user_id' => UuidFactory::uuid()]);
        $comment = $this->Comments->newEntity($data, self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['user_id']['user_exists']);
    }

    public function testErrorUserNotSoftDeleted()
    {
        $softDeletedUser = UserFactory::make()->deleted()->persist();
        $data = $this->generateDummyComment(['user_id' => $softDeletedUser->id]);
        $comment = $this->Comments->newEntity($data, self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['user_id']['user_is_soft_deleted']);
    }

    public function testErrorResourceDoesNotExist()
    {
        $data = $this->generateDummyComment(['foreign_key' => UuidFactory::uuid()]);
        $comment = $this->Comments->newEntity($data, self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['foreign_key']['resource_exists']);
    }

    public function testErrorResourceIsSoftDeleted()
    {
        $softDeletedResource = ResourceFactory::make()->deleted()->persist();
        $data = $this->generateDummyComment(['foreign_key' => $softDeletedResource->id]);
        $comment = $this->Comments->newEntity($data, self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['foreign_key']['resource_is_soft_deleted']);
    }

    public function testErrorParentIdDoesNotExist()
    {
        $data = $this->generateDummyComment(['parent_id' => UuidFactory::uuid()]);
        $comment = $this->Comments->newEntity($data, self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['parent_id']['has_valid_parent_id']);
    }

    public function testErrorParentIdParentHasDifferentForeignId()
    {
        $parentComment = CommentFactory::make()->withResource()->withUser()->persist();
        $data = $this->generateDummyComment(['parent_id' => $parentComment->id]);
        $comment = $this->Comments->newEntity($data, self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['parent_id']['has_valid_parent_id']);
    }

    public function testErrorHasResourceAccessRule()
    {
        $user = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->persist();
        $data = $this->generateDummyComment(['user_id' => $user->id, 'foreign_key' => $resource->id]);
        $comment = $this->Comments->newEntity($data, self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['foreign_key']['has_resource_access']);
    }

    public function testErrorCreatedByDoesNotExist()
    {
        $data = $this->generateDummyComment(['created_by' => UuidFactory::uuid()]);
        $comment = $this->Comments->newEntity($data, self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['created_by']['creator_exists']);
    }

    public function testSuccess()
    {
        $data = $this->generateDummyComment();
        $comment = $this->Comments->newEntity($data, self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment);
        $this->assertNotEmpty($save);
        $errors = $comment->getErrors();
        $this->assertEmpty($errors);

        // Check the comment exists in db.
        $addedComment = $this->Comments->get($save->id);
        $this->assertNotEmpty($addedComment);
        $this->assertEquals($data['user_id'], $addedComment->user_id);
        $this->assertEquals($data['foreign_key'], $addedComment->foreign_key);
        $this->assertEquals('Resource', $addedComment->foreign_model);
        $this->assertNull($addedComment->parent_id);
    }
}
