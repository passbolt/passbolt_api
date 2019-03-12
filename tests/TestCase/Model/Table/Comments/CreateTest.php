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
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class CreateTest extends AppTestCase
{
    use CommentsModelTrait;
    use FormatValidationTrait;

    public $Comments;

    public $fixtures = ['app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Comments', 'app.Base/Permissions'];

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
            ]
        ];

        return $entityOptions;
    }

    /* ************************************************************** */
    /* FORMAT VALIDATION TESTS */
    /* ************************************************************** */

    public function testValidationUserId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Comments, 'user_id', self::getDummyComment(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationParentId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'allowEmpty' => self::getAllowEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Comments, 'parent_id', self::getDummyComment(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationForeignModel()
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'inList' => self::getInListTestCases(CommentsTable::ALLOWED_FOREIGN_MODELS),
        ];
        $this->assertFieldFormatValidation($this->Comments, 'foreign_model', self::getDummyComment(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationForeignId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Comments, 'foreign_key', self::getDummyComment(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationContent()
    {
        $testCases = [
            'scalar' => self::getScalarTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'utf8Extended' => self::getUtf8ExtendedTestCases(50),
            'lengthBetween' => self::getLengthBetweenTestCases(1, 255),
        ];
        $this->assertFieldFormatValidation($this->Comments, 'content', self::getDummyComment(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationCreatedBy()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Comments, 'created_by', self::getDummyComment(), self::getEntityDefaultOptions(), $testCases);
    }

    /* ************************************************************** */
    /* LOGIC VALIDATION TESTS */
    /* ************************************************************** */

    public function testErrorUserDoesNotExist()
    {
        $comment = $this->Comments->newEntity(self::getDummyComment(['user_id' => UuidFactory::uuid()]), self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['user_id']['user_exists']);
    }

    public function testErrorUserNotSoftDeleted()
    {
        $comment = $this->Comments->newEntity(self::getDummyComment(['user_id' => UuidFactory::uuid('user.id.sofia')]), self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['user_id']['user_is_soft_deleted']);
    }

    public function testErrorResourceDoesNotExist()
    {
        $comment = $this->Comments->newEntity(self::getDummyComment(['foreign_key' => UuidFactory::uuid()]), self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['foreign_key']['resource_exists']);
    }

    public function testErrorResourceIsSoftDeleted()
    {
        $comment = $this->Comments->newEntity(self::getDummyComment(['foreign_key' => UuidFactory::uuid('resource.id.jquery')]), self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['foreign_key']['resource_is_soft_deleted']);
    }

    public function testErrorParentIdDoesNotExist()
    {
        $comment = $this->Comments->newEntity(
            self::getDummyComment([
                'foreign_key' => UuidFactory::uuid('resource.id.apache'),
                'parent_id' => UuidFactory::uuid('comment.id.doesnotexist')
            ]),
            self::getEntityDefaultOptions()
        );
        $save = $this->Comments->save($comment);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['parent_id']['has_valid_parent_id']);
    }

    public function testErrorParentIdParentHasDifferentForeignId()
    {
        $comment = $this->Comments->newEntity(
            self::getDummyComment([
                'foreign_key' => UuidFactory::uuid('resource.id.bower'),
                'parent_id' => UuidFactory::uuid('comment.id.apache-1')
            ]),
            self::getEntityDefaultOptions()
        );
        $save = $this->Comments->save($comment);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['parent_id']['has_valid_parent_id']);
    }

    public function testErrorHasResourceAccessRule()
    {
        $comment = $this->Comments->newEntity(self::getDummyComment(['user_id' => UuidFactory::uuid('user.id.dame'), 'foreign_key' => UuidFactory::uuid('resource.id.canjs')]), self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['foreign_key']['has_resource_access']);
    }

    public function testErrorCreatedByDoesNotExist()
    {
        $comment = $this->Comments->newEntity(self::getDummyComment(['created_by' => UuidFactory::uuid()]), self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['created_by']['creator_exists']);
    }

    public function testSuccess()
    {
        $comment = $this->Comments->newEntity(self::getDummyComment(), self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment);
        $this->assertNotEmpty($save);
        $errors = $comment->getErrors();
        $this->assertEmpty($errors);

        // Check the favorite exists in db.
        $addedComment = $this->Comments->get($save->id);
        $this->assertNotEmpty($addedComment);
        $this->assertEquals(UuidFactory::uuid('user.id.ada'), $addedComment->user_id);
        $this->assertEquals(UuidFactory::uuid('resource.id.bower'), $addedComment->foreign_key);
        $this->assertEquals('Resource', $addedComment->foreign_model);
        $this->assertNull($addedComment->parent_id);
    }
}
