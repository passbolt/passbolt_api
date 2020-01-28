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
 * @since         2.14.0
 */

namespace Passbolt\Folders\Test\TestCase\Model\Table;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Model\Table\FoldersTable Test Case
 */
class FoldersTableTest extends AppTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use FormatValidationTrait;
    use PermissionsModelTrait;

    /**
     * Test subject
     *
     * @var \Passbolt\Folders\Model\Table\FoldersTable
     */
    public $Folders;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Base/GroupsUsers',
        'app.Base/Resources',
        'app.Base/Users',
        'app.Base/Permissions',
        'plugin.Passbolt/Folders.Folders',
        'plugin.Passbolt/Folders.FoldersRelations',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        Configure::write('passbolt.plugins.folders', ['enabled' => true]);
        $config = TableRegistry::getTableLocator()->exists('Folders') ? [] : ['className' => FoldersTable::class];
        $this->Folders = TableRegistry::getTableLocator()->get('Folders', $config);
        $config = TableRegistry::getTableLocator()->exists('FoldersRelations') ? [] : ['className' => FoldersRelationsTable::class];
        $this->FoldersRelations = TableRegistry::getTableLocator()->get('FoldersRelations', $config);
        $config = TableRegistry::getTableLocator()->exists('Permissions') ? [] : ['className' => PermissionsTable::class];
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions', $config);
    }

    public function testThatFindAllByNamesRetrieveFolderMatchingTheName()
    {
        $folderName = 'FolderWithSpecialName';

        $userId = UuidFactory::uuid('user.id.ada');
        $folderData = ['id' => UuidFactory::uuid(), 'name' => $folderName, 'created_by' => $userId, 'modified_by' => $userId];
        $this->addFolder($folderData);

        $query = $this->Folders->find();

        $matchingNames = [
            'FolderWithSpecialName', // Exact match
            'SpecialName', // Ending Partial Match,
            'Folder', // Starting Partial Match,
            'WithSpecial', // Middle Partial Match
        ];

        foreach ($matchingNames as $matchingName) {
            $folders = $this->Folders->_filterQueryBySearch($query, $matchingName);
            $this->assertCount(1, $folders, sprintf("FoldersTable::findAllByName() should match the given input: `%s`", $matchingName));
        }

        $nonMatchingNames = [
            'FolderSpecial',
            'FolderWithSpecialNameButVerySpecial',
        ];

        foreach ($nonMatchingNames as $nonMatchingName) {
            $folders = $this->Folders->_filterQueryBySearch($query, $nonMatchingName);
            $this->assertCount(0, $folders, sprintf("FoldersTable::findAllByName() should not match the given input: `%s`", $nonMatchingName));
        }
    }

    /**
     * @return void
     */
    public function testThatFindAllByIdsRetrieveFolderWithTheGivenIds()
    {
        $allFolders = [
            UuidFactory::uuid('folder.id.a'),
            UuidFactory::uuid('folder.id.b'),
            UuidFactory::uuid('folder.id.c'),
            UuidFactory::uuid('folder.id.d'),
            UuidFactory::uuid('folder.id.e'),
        ];
        foreach ($allFolders as $folderId) {
            $folderData = [
                'id' => $folderId,
                'name' => 'folder name',
                'created_by' => UuidFactory::uuid('user.id.ada'),
                'modified_by' => UuidFactory::uuid('user.id.ada')
            ];
            $this->addFolder($folderData);
        }

        $expectedFolderIds = [
            UuidFactory::uuid('folder.id.b'),
            UuidFactory::uuid('folder.id.c'),
        ];

        $folders = $this->Folders->_filterByIds($this->Folders->find(), $expectedFolderIds);
        $folderIds = Hash::extract($folders->toArray(), '{n}.id');

        $this->assertSame($folderIds, $expectedFolderIds, "List of folders returned does not contain expected folders.");
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Folders);

        parent::tearDown();
    }

    /**
     * Get default folder entity options.
     */
    public function getDummyFolderEntityDefaultOptions()
    {
        return [
            'checkRules' => true,
            'accessibleFields' => [
                '*' => true,
            ],
        ];
    }

    /* ************************************************************** */
    /* FORMAT VALIDATION TESTS */
    /* ************************************************************** */

    public function testValidationName()
    {
        $testCases = [
            'utf8Extended' => self::getUtf8ExtendedTestCases(64),
            'maxLength' => self::getMaxLengthTestCases(64),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Folders, 'name', self::getDummyFolderData(), self::getDummyFolderEntityDefaultOptions(), $testCases);
    }

    /* ************************************************************** */
    /* CONTAINS TESTS */
    /* ************************************************************** */

    public function testFindView_ContainChildrenFolders()
    {
        $userId = UuidFactory::uuid('user.id.ada');

        // Insert fixtures.
        // Ada has access to folder A, B and C as a OWNER
        // Ada see folder folders B and C in A
        // A (Ada:O)
        // |- B (Ada:O)
        // |- C (Ada:O)
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);

        $options['contain']['children_folders'] = true;
        $folder = $this->Folders->findView($userId, $folderA->id, $options)->first();

        // Expected fields.
        $this->assertFolderAttributes($folder);
        $this->assertObjectHasAttribute('children_folders', $folder);
        $this->assertCount(2, $folder->children_folders);
        foreach($folder->children_folders as $childFolder) {
        $this->assertFolderAttributes($childFolder);
    }
        $childrenFoldersIds = Hash::extract($folder->children_folders, '{n}.id');
        $this->assertContains($folderB->id, $childrenFoldersIds);
        $this->assertContains($folderC->id, $childrenFoldersIds);
    }
}
