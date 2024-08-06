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

namespace Passbolt\Folders\Test\TestCase\Model\Table\Folders;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * @covers \Passbolt\Folders\Model\Table\FoldersTable
 */
class FoldersTableTest extends FoldersTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use FormatValidationTrait;
    use PermissionsModelTrait;

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersTable
     */
    public $Folders;

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersRelationsTable
     */
    public $FoldersRelations;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    public $Permissions;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Folders = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $config = TableRegistry::getTableLocator()->exists('FoldersRelations') ? [] : ['className' => FoldersRelationsTable::class];
        $this->FoldersRelations = TableRegistry::getTableLocator()->get('FoldersRelations', $config);
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
        unset($this->Folders);

        parent::tearDown();
    }

    public function testValidationName()
    {
        $testCases = [
            'utf8Extended' => self::getUtf8ExtendedTestCases(256),
            'maxLength' => self::getMaxLengthTestCases(256),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $data = FolderFactory::make()->getEntity()->toArray();
        $this->assertFieldFormatValidation($this->Folders, 'name', $data, self::getDummyFolderEntityDefaultOptions(), $testCases);
    }

    /* FORMAT VALIDATION TESTS */

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

    /* CONTAINS TESTS */

    public function testFindView_ContainChildrenFolders()
    {
        $userId = UuidFactory::uuid('user.id.ada');

        // Insert fixtures.
        // Ada has access to folder A, B and C as a OWNER
        // Ada sees folder folders B and C in A
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
        $this->assertCount(2, $folder->get('children_folders'));
        foreach ($folder->get('children_folders') as $childFolder) {
            $this->assertFolderAttributes($childFolder);
        }
        $childrenFoldersIds = Hash::extract($folder->get('children_folders'), '{n}.id');
        $this->assertContains($folderB->id, $childrenFoldersIds);
        $this->assertContains($folderC->id, $childrenFoldersIds);
    }

    public function testFindView_ContainFolderParentId()
    {
        $userId = UuidFactory::uuid('user.id.ada');

        // Insert fixtures.
        // Ada has access to folder A, B as a OWNER
        // Ada sees folder folders B in A
        // A (Ada:O)
        // |- B (Ada:O)
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);

        $folder = $this->Folders->findView($userId, $folderB->id)->first();

        // Expected fields.
        $this->assertEquals($folderA->id, $folder->get('folder_parent_id'));
    }

    public function testFindView_ContainPersonal()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');

        // Insert fixtures.
        // Ada has access to folder A, B, C as OWNER
        // Betty has access to folder B as OWNER
        // A (Ada:O)
        // B (Ada:O)
        // C does not have any folder relation
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderC = FolderFactory::make()->withPermissionsFor([UserFactory::make(['id' => $userAId])->getEntity()])->persist();

        $folderA = $this->Folders->findView($userAId, $folderA->id)->first();
        $this->assertTrue($folderA->get('personal'));
        $folderB = $this->Folders->findView($userAId, $folderB->id)->first();
        $this->assertFalse($folderB->get('personal'));
        $folderC = $this->Folders->findView($userAId, $folderC->get('id'))->first();
        $this->assertNull($folderC->get('personal'));
    }

    /* FINDER TESTS */

    public function testFindIndex_FilterByParentId()
    {
        $this->insertTestCase1();

        // Relations are expressed as follow: folder_parent_id => [child_folder_id]
        $expectedFoldersForParentId = [
            UuidFactory::uuid('folder.id.a') => [
                UuidFactory::uuid('folder.id.b'),
            ],
            UuidFactory::uuid('folder.id.c') => [
                UuidFactory::uuid('folder.id.e'),
            ],
        ];

        // One parent should return only its children
        foreach ($expectedFoldersForParentId as $parentIdToFilter => $expectedFolderIds) {
            $folders = $this->Folders->filterQueryByParentIds($this->Folders->find(), [$parentIdToFilter]);
            $resultFolderIds = Hash::extract($folders->toArray(), '{n}.id');
            $this->assertEquals(asort($expectedFolderIds), asort($resultFolderIds), 'List of folders returned does not contain expected folders.');
        }

        // Multiple parents should retrieve the children of all parents once
        $expectedFolderIds = $this->getAllChildrenFromExpectedFolders($expectedFoldersForParentId);
        $parentIdsToFilter = array_keys($expectedFoldersForParentId);

        $folders = $this->Folders->filterQueryByParentIds($this->Folders->find(), $parentIdsToFilter);
        $resultFolderIds = Hash::extract($folders->toArray(), '{n}.id');
        $this->assertSame(asort($expectedFolderIds), asort($resultFolderIds), 'List of folders returned does not contain expected folders. Filtering with multiple parent ids should return all children.');
    }

    private function insertTestCase1()
    {
        $userId = UuidFactory::uuid('user.id.ada');

        // Relations are expressed as follow: folder_parent_id => [child_folder_id]
        $expectedFoldersForParentId = [
            UuidFactory::uuid('folder.id.a') => [
                UuidFactory::uuid('folder.id.b'),
            ],
            UuidFactory::uuid('folder.id.c') => [
                UuidFactory::uuid('folder.id.e'),
            ],
        ];

        foreach ($expectedFoldersForParentId as $folderParentId => $childrenFolders) {
            $this->addFolderFor(['id' => $folderParentId], [
                $userId => Permission::OWNER,
            ]);
            foreach ($childrenFolders as $childrenFolderId) {
                $this->addFolderFor(['id' => $childrenFolderId, 'folder_parent_id' => $folderParentId, ], [$userId => Permission::OWNER]);
            }
        }
    }

    private function getAllChildrenFromExpectedFolders(array $expectedFoldersForParentId)
    {
        return array_reduce($expectedFoldersForParentId, function ($accumulator, $current) {
            if (empty($accumulator)) {
                $accumulator = [];
            }

            return array_merge($accumulator, $current);
        });
    }

    public function testFindIndex_FilterBySearch()
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
            'folderwithspecialname', // Lower case
        ];

        foreach ($matchingNames as $matchingName) {
            $folders = $this->Folders->filterQueryBySearch($query, $matchingName);
            $this->assertCount(1, $folders, sprintf('FoldersTable::findAllByName() should match the given input: `%s`', $matchingName));
        }

        $nonMatchingNames = [
            'FolderSpecial',
            'FolderWithSpecialNameButVerySpecial',
        ];

        foreach ($nonMatchingNames as $nonMatchingName) {
            $folders = $this->Folders->filterQueryBySearch($query, $nonMatchingName);
            $this->assertCount(0, $folders, sprintf('FoldersTable::findAllByName() should not match the given input: `%s`', $nonMatchingName));
        }
    }

    public function testFindIndex_FilterByIds()
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
                'modified_by' => UuidFactory::uuid('user.id.ada'),
            ];
            $this->addFolder($folderData);
        }

        $expectedFolderIds = [
            UuidFactory::uuid('folder.id.b'),
            UuidFactory::uuid('folder.id.c'),
        ];

        $folders = $this->Folders->filterByIds($this->Folders->find(), $expectedFolderIds);
        $folderIds = Hash::extract($folders->toArray(), '{n}.id');

        $this->assertSame($folderIds, $expectedFolderIds, 'List of folders returned does not contain expected folders.');
    }

    public function testFoldersTable_Name_Should_Be_Max_256()
    {
        $name = str_repeat('a', 256);
        $folder = FolderFactory::make(compact('name'))->getEntity();
        $folder = $this->Folders->patchEntity($folder, $folder->toArray());
        $this->assertFalse($folder->hasErrors());
        $folder = $this->Folders->saveOrFail($folder);
        $this->assertFalse($folder->hasErrors());

        $name = str_repeat('a', 257);
        $folder = $this->Folders->patchEntity($folder, compact('name'));
        $this->assertTrue($folder->hasErrors());
        $this->expectException(PersistenceFailedException::class);
        $this->Folders->saveOrFail($folder);
    }
}
