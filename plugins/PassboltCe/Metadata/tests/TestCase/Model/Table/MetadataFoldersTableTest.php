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
 * @since         4.12.0
 */
namespace Passbolt\Metadata\Test\TestCase\Model\Table;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Folders\Test\Factory\FolderFactory;

class MetadataFoldersTableTest extends TestCase
{
    use TruncateDirtyTables;

    /**
     * Test subject
     *
     * @var \Passbolt\Folders\Model\Table\FoldersTable
     */
    protected $Folders;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->Folders = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->Folders);

        parent::tearDown();
    }

    public function testMetadataFoldersTable_findMetadataUpgradeIndex(): void
    {
        // V4 folder shared with multiple users
        $folderSharedWithMultipleUsersV4 = FolderFactory::make()->withPermissionsFor(
            UserFactory::make(2)->persist()
        )->persist();
        // V5 folder shared with multiple users
        FolderFactory::make()->v5Fields(['metadata' => 'foo'])->withPermissionsFor(
            UserFactory::make(2)->persist()
        )->persist();

        // V4 folder shared with one group
        $folderSharedWithOneGroupV4 = FolderFactory::make()->withPermissionsFor([
            GroupFactory::make()->persist(),
        ])->persist();
        // V5 folder shared with one group
        FolderFactory::make()->v5Fields(['metadata' => 'foo'])->withPermissionsFor([
            GroupFactory::make()->persist(),
        ])->persist();

        // V4 folder shared with one user
        $folderSharedWithOneUserV4 = FolderFactory::make()->withPermissionsFor([
            UserFactory::make()->persist(),
        ])->persist();
        // V5 folder shared with one user
        FolderFactory::make()->v5Fields(['metadata' => 'foo'])->withPermissionsFor([
            UserFactory::make()->persist(),
        ])->persist();

        // V4 folder shared with one group and one user
        $folderSharedWithOneGroupAndOneUserV4 = FolderFactory::make()
            ->withPermissionsFor([
                UserFactory::make()->persist(),
            ])->withPermissionsFor([
                GroupFactory::make()->persist(),
            ])->persist();

        // V5 folder shared with one group and one user
        FolderFactory::make()
            ->v5Fields(['metadata' => 'foo'])
            ->withPermissionsFor([
                GroupFactory::make()->persist(),
            ])->withPermissionsFor([
                UserFactory::make()->persist(),
            ])->persist();

        FolderFactory::make()->persist();

        $foldersToBeRetrievedIfIsSharedIsTrue = [
            $folderSharedWithMultipleUsersV4,
            $folderSharedWithOneGroupV4,
            $folderSharedWithOneGroupAndOneUserV4,
        ];

        $foldersToBeRetrievedIfIsSharedIsFalse = [
            $folderSharedWithOneUserV4,
        ];

        $foldersShared = $this->Folders
            ->findMetadataUpgradeIndex(['filter' => ['is-shared' => true]])
            ->find('list')
            ->all()
            ->toArray();
        $this->assertSame(count($foldersToBeRetrievedIfIsSharedIsTrue), count($foldersShared));
        foreach ($foldersToBeRetrievedIfIsSharedIsTrue as $resource) {
            $this->assertArrayHasKey($resource['id'], $foldersShared);
        }

        $foldersNotShared = $this->Folders
            ->findMetadataUpgradeIndex(['filter' => ['is-shared' => false]])
            ->find('list')
            ->all()
            ->toArray();
        $this->assertSame(count($foldersToBeRetrievedIfIsSharedIsFalse), count($foldersNotShared));
        foreach ($foldersToBeRetrievedIfIsSharedIsFalse as $resource) {
            $this->assertArrayHasKey($resource['id'], $foldersNotShared);
        }
    }

    public function testMetadataFoldersTable_findMetadataUpgradeIndex_Contain_Permissions(): void
    {
        // V4 folders shared with multiple users
        FolderFactory::make()->withPermissionsFor(UserFactory::make(2)->persist())->persist();

        $resourcesShared = $this->Folders
            ->findMetadataUpgradeIndex(['contain' => [
                'permissions' => 1,
            ]])
            ->all()
            ->toArray();
        foreach ($resourcesShared as $resource) {
            $this->assertIsArray($resource['permissions']);
            $this->assertNotEmpty($resource['permissions']);
        }
    }
}
