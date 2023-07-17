<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.7.2
 */
namespace Passbolt\DirectorySync\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Passbolt\DirectorySync\Test\Factory\DirectoryRelationFactory;

class DirectoryRelationsTableTest extends TestCase
{
    /**
     * @var \Passbolt\DirectorySync\Model\Table\DirectoryRelationsTable
     */
    public $DirectoryRelations;

    public function setUp(): void
    {
        parent::setUp();
        $this->DirectoryRelations = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryRelations');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->DirectoryRelations);
    }

    public function testDirectoryRelationsTableTest_cleanupHardDeletedUserGroups_EmptyEntry()
    {
        $n1 = rand(3, 5);
        $n2 = rand(6, 7);
        // These should not be deleted
        DirectoryRelationFactory::make($n1)->with('GroupUser')->persist();
        // These should be deleted as they do not have a GroupUser
        DirectoryRelationFactory::make($n2)->persist();

        $numberOfDeletedItems = $this->DirectoryRelations->cleanupHardDeletedUserGroups([]);

        $this->assertSame($n2, $numberOfDeletedItems);
    }

    public function testDirectoryRelationsTableTest_cleanupHardDeletedUserGroups_NotEmptyEntry()
    {
        $n1 = rand(3, 5);
        $n2 = rand(6, 7);
        // These should not be deleted
        DirectoryRelationFactory::make($n1)->with('GroupUser')->persist();
        // These should be deleted as they do not have a GroupUser
        DirectoryRelationFactory::make($n2)->persist();
        // These should not be deleted as their parent_key are passed as argument
        [$drToSki1, $drToSkip2] = DirectoryRelationFactory::make(2)->persist();

        $parentKeyToSkip = [
            $drToSki1->parent_key,
            $drToSkip2->parent_key,
        ];
        $numberOfDeletedItems = $this->DirectoryRelations->cleanupHardDeletedUserGroups($parentKeyToSkip);

        $this->assertSame($n2, $numberOfDeletedItems);
        $drsToSkipCount = DirectoryRelationFactory::find()->where(['parent_key IN' => $parentKeyToSkip])->count();
        $this->assertSame(2, $drsToSkipCount);
    }
}
