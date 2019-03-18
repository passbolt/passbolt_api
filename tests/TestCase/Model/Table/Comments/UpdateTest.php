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

class UpdateTest extends AppTestCase
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
                'user_id' => false,
                'parent_id' => false,
                'foreign_key' => false,
                'foreign_model' => false,
                'content' => true,
                'created_by' => false,
                'modified_by' => true,
            ]
        ];

        return $entityOptions;
    }

    /* ************************************************************** */
    /* FORMAT VALIDATION TESTS */
    /* ************************************************************** */

    public function testValidationContent()
    {
        $testCases = [
            'scalar' => self::getScalarTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'utf8Extended' => self::getUtf8ExtendedTestCases(50),
            'lengthBetween' => self::getLengthBetweenTestCases(1, 255),
        ];
        $comment = self::getDummyComment([
            'id' => UuidFactory::uuid('comment.id.apache-1'),
            'modified_by' => UuidFactory::uuid('user.id.ada')
        ]);
        $this->assertFieldFormatValidation($this->Comments, 'content', $comment, self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationModifiedBy()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
        ];
        $comment = self::getDummyComment([
            'id' => UuidFactory::uuid('comment.id.apache-1'),
            'modified_by' => UuidFactory::uuid('user.id.ada')
        ]);
        $this->assertFieldFormatValidation($this->Comments, 'modified_by', $comment, self::getEntityDefaultOptions(), $testCases);
    }

    /* ************************************************************** */
    /* LOGIC VALIDATION TESTS */
    /* ************************************************************** */

    public function testErrorModifiedByUserDoesNotExist()
    {
        $comment = $this->Comments->get(UuidFactory::uuid('comment.id.apache-1'));
        $comment = $this->Comments->patchEntity($comment, ['content' => 'test', 'modified_by' => UuidFactory::uuid('user.id.notexist')], self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment, ['Comments.user_id' => UuidFactory::uuid('user.id.irene')]);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['modified_by']['modifier_exists']);
    }

    public function testErrorModifiedByUserIsNotOwner()
    {
        $comment = $this->Comments->get(UuidFactory::uuid('comment.id.apache-1'));
        $comment = $this->Comments->patchEntity($comment, ['content' => 'test', 'modified_by' => UuidFactory::uuid('user.id.jean')], self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment, ['Comments.user_id' => UuidFactory::uuid('user.id.jean')]);
        $this->assertFalse($save);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['user_id']['is_owner']);
    }

    public function testSuccess()
    {
        $comment = $this->Comments->get(UuidFactory::uuid('comment.id.apache-1'));
        $comment = $this->Comments->patchEntity($comment, ['content' => 'updated comment', 'modified_by' => UuidFactory::uuid('user.id.irene')], self::getEntityDefaultOptions());
        $save = $this->Comments->save($comment, ['Comments.user_id' => UuidFactory::uuid('user.id.irene')]);
        $this->assertTrue((bool)$save);
        $errors = $comment->getErrors();
        $this->assertEmpty($errors);

        // Check the favorite exists in db.
        $updatedComment = $this->Comments->get($save->id);
        $this->assertEquals(UuidFactory::uuid('user.id.irene'), $updatedComment->modified_by);
        $this->assertEquals('updated comment', $updatedComment->content);
    }
}
