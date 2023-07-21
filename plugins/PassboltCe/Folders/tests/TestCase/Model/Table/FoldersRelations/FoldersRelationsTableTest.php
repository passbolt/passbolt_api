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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Test\TestCase\Model\Table\FoldersRelations;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Model\Table\FoldersRelationsTable Test Case
 */
class FoldersRelationsTableTest extends FoldersTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use FormatValidationTrait;

    /**
     * Test subject
     *
     * @var \Passbolt\Folders\Model\Table\FoldersRelationsTable
     */
    public $FoldersRelations;
    public $Folders;
    public $Permissions;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        Configure::write('passbolt.plugins.folders', ['enabled' => true]);
        $config = TableRegistry::getTableLocator()->exists('FoldersRelations') ? [] : ['className' => FoldersRelationsTable::class];
        $this->FoldersRelations = TableRegistry::getTableLocator()->get('FoldersRelations', $config);
        $config = TableRegistry::getTableLocator()->exists('Folders') ? [] : ['className' => FoldersTable::class];
        $this->Folders = TableRegistry::getTableLocator()->get('Folders', $config);
        $config = TableRegistry::getTableLocator()->exists('Permissions') ? [] : ['className' => PermissionsTable::class];
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->FoldersRelations);

        parent::tearDown();
    }

    /**
     * Get default folder entity options.
     */
    public function getDummyFolderRelationsEntityDefaultOptions()
    {
        return [
            'checkRules' => true,
            'accessibleFields' => [
                '*' => true,
            ],
        ];
    }

    /* FORMAT VALIDATION TESTS */

    public function testFoldersRelationsValidationForeignModel()
    {
        $testCases = [
            'inList' => self::getInListTestCases($this->FoldersRelations::ALLOWED_FOREIGN_MODELS),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation(
            $this->FoldersRelations,
            'foreign_model',
            self::getDummyFolderRelation(),
            self::getDummyFolderRelationsEntityDefaultOptions(),
            $testCases
        );
    }

    public function testFoldersRelationsValidationForeignId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation(
            $this->FoldersRelations,
            'foreign_id',
            self::getDummyFolderRelation(),
            self::getDummyFolderRelationsEntityDefaultOptions(),
            $testCases
        );
    }

    public function testFoldersRelationsValidationUserId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation(
            $this->FoldersRelations,
            'user_id',
            self::getDummyFolderRelation(),
            self::getDummyFolderRelationsEntityDefaultOptions(),
            $testCases
        );
    }

    public function testFoldersRelationsValidationFolderParentId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'allowEmpty' => self::getAllowEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation(
            $this->FoldersRelations,
            'folder_parent_id',
            self::getDummyFolderRelation(),
            self::getDummyFolderRelationsEntityDefaultOptions(),
            $testCases
        );
    }

    /* BUILD RULES TESTS */

    public function testFoldersRelationsErrorBuildRuleCreate_FolderRelationUnique()
    {
        // Insert fixtures.
        // Ada has access to folder A as a OWNER
        // A (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folder = $this->addFolderFor(['name' => 'folder'], [$userId => Permission::OWNER]);

        $data = [
            'foreign_model' => 'Folder',
            'foreign_id' => $folder->id,
            'user_id' => $userId,
        ];
        $folder = self::getDummyFolderRelationEntity($data);
        $save = $this->FoldersRelations->save($folder);
        $this->assertFalse($save);
        $errors = $folder->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['foreign_id']['folder_relation_unique']);
    }

    public function testFoldersRelationsErrorBuildRuleCreate_ForeignIdExists_FolderNotExist()
    {
        $data = [
            'foreign_model' => 'Folder',
            'foreign_id' => UuidFactory::uuid('folder.id.not-exist'),
        ];
        $folder = self::getDummyFolderRelationEntity($data);
        $save = $this->FoldersRelations->save($folder);
        $this->assertFalse($save);
        $errors = $folder->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['foreign_id']['foreign_model_exists']);
    }

    public function testFoldersRelationsErrorBuildRuleCreate_ForeignIdExists_ResourceNotExist()
    {
        $data = [
            'foreign_model' => 'Resource',
            'foreign_id' => UuidFactory::uuid('resource.id.not-exist'),
        ];
        $folder = self::getDummyFolderRelationEntity($data);
        $save = $this->FoldersRelations->save($folder);
        $this->assertFalse($save);
        $errors = $folder->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['foreign_id']['foreign_model_exists']);
    }

    public function testFoldersRelationsErrorBuildRuleCreate_ForeignIdExists_ResourceSoftDeleted()
    {
        $data = [
            'foreign_model' => 'Resource',
            'foreign_id' => UuidFactory::uuid('resource.id.jquery'),
        ];
        $folder = self::getDummyFolderRelationEntity($data);
        $save = $this->FoldersRelations->save($folder);
        $this->assertFalse($save);
        $errors = $folder->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['foreign_id']['foreign_model_exists']);
    }

    public function testFoldersRelationsErrorBuildRuleCreate_UserIdExists_UserNotExist()
    {
        // Insert fixtures.
        // Ada has access to folder A as a OWNER
        // A (Ada:O)
        $folder = $this->addFolderFor(['name' => 'folder'], [UuidFactory::uuid('user.id.ada') => Permission::OWNER]);

        $data = [
            'foreign_model' => 'Folder',
            'foreign_id' => $folder->id,
            'user_id' => UuidFactory::uuid('user.id.not-exist'),
        ];
        $folder = self::getDummyFolderRelationEntity($data);
        $save = $this->FoldersRelations->save($folder);
        $this->assertFalse($save);
        $errors = $folder->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['user_exists']);
    }

    public function testFoldersRelationsErrorBuildRuleCreate_UserIdExists_UserSoftDeleted()
    {
        // Insert fixtures.
        // Ada has access to folder A as a OWNER
        // A (Ada:O)
        $folder = $this->addFolderFor(['name' => 'folder'], [UuidFactory::uuid('user.id.ada') => Permission::OWNER]);

        $data = [
            'foreign_model' => 'Folder',
            'foreign_id' => $folder->id,
            'user_id' => UuidFactory::uuid('user.id.sofia'),
        ];
        $folder = self::getDummyFolderRelationEntity($data);
        $save = $this->FoldersRelations->save($folder);
        $this->assertFalse($save);
        $errors = $folder->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['user_is_not_soft_deleted']);
    }
}
