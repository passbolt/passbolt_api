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

use App\Utility\UuidFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;

/**
 * Passbolt\Folders\Model\Table\FoldersTable Test Case
 */
class FoldersTableTest extends AppTestCase
{
    use FoldersModelTrait;
    use FormatValidationTrait;

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
        'plugin.Passbolt/Folders.Folders',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Folders') ? [] : ['className' => FoldersTable::class];
        $this->Folders = TableRegistry::getTableLocator()->get('Folders', $config);
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

}
