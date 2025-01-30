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
 * @since         4.11.0
 */
namespace Passbolt\Metadata\Test\TestCase\Model\Table;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

/**
 * @covers \App\Model\Table\ResourcesTable
 */
class MetadataResourcesTableTest extends TestCase
{
    use TruncateDirtyTables;

    /**
     * Test subject
     *
     * @var \App\Model\Table\ResourcesTable
     */
    protected $Resources;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->Resources);

        parent::tearDown();
    }

    public function testMetadataResourcesTable_findMetadataUpgradeIndex(): void
    {
        // V4 resource shared with multiple users
        $resourceSharedWithMultipleUsersV4 = ResourceFactory::make()->withPermissionsFor(
            UserFactory::make(2)->persist()
        )->persist();
        // V5 resource shared with multiple users
        ResourceFactory::make()->v5Fields()->withPermissionsFor(
            UserFactory::make(2)->persist()
        )->persist();

        // V4 resource shared with one group
        $resourceSharedWithOneGroupV4 = ResourceFactory::make()->withPermissionsFor([
            GroupFactory::make()->persist(),
        ])->persist();
        // V5 resource shared with one group
        ResourceFactory::make()->v5Fields()->withPermissionsFor([
            GroupFactory::make()->persist(),
        ])->persist();

        // V4 resource shared with one user
        $resourceSharedWithOneUserV4 = ResourceFactory::make()->withPermissionsFor([
            UserFactory::make()->persist(),
        ])->persist();
        // V5 resource shared with one user
        ResourceFactory::make()->v5Fields()->withPermissionsFor([
            UserFactory::make()->persist(),
        ])->persist();

        // V4 resource deleted shared with multiple users
        ResourceFactory::make()->deleted()->withPermissionsFor(
            UserFactory::make(2)->persist()
        )->persist();

        // V4 resource deleted shared with one user
        ResourceFactory::make()->deleted()->withPermissionsFor([
            UserFactory::make()->persist(),
        ])->persist();

        // V4 resource shared with one group and one user
        $resourceSharedWithOneGroupAndOneUserV4 = ResourceFactory::make()
            ->withPermissionsFor([
                UserFactory::make()->persist(),
            ])->withPermissionsFor([
                GroupFactory::make()->persist(),
            ])->persist();

        // V5 resource shared with one group and one user
        ResourceFactory::make()
            ->v5Fields()
            ->withPermissionsFor([
                GroupFactory::make()->persist(),
            ])->withPermissionsFor([
                UserFactory::make()->persist(),
            ])->persist();

        ResourceFactory::make()->persist();

        $resourcesToBeRetrievedIfIsSharedIsTrue = [
            $resourceSharedWithMultipleUsersV4,
            $resourceSharedWithOneGroupV4,
            $resourceSharedWithOneGroupAndOneUserV4,
        ];

        $resourcesToBeRetrievedIfIsSharedIsFalse = [
            $resourceSharedWithOneUserV4,
        ];

        $resourcesShared = $this->Resources
            ->findMetadataUpgradeIndex(['filter' => ['is-shared' => true]])
            ->find('list')
            ->all()
            ->toArray();
        $this->assertSame(count($resourcesToBeRetrievedIfIsSharedIsTrue), count($resourcesShared));
        foreach ($resourcesToBeRetrievedIfIsSharedIsTrue as $resource) {
            $this->assertArrayHasKey($resource['id'], $resourcesShared);
        }

        $resourcesNotShared = $this->Resources
            ->findMetadataUpgradeIndex(['filter' => ['is-shared' => false]])
            ->find('list')
            ->all()
            ->toArray();
        $this->assertSame(count($resourcesToBeRetrievedIfIsSharedIsFalse), count($resourcesNotShared));
        foreach ($resourcesToBeRetrievedIfIsSharedIsFalse as $resource) {
            $this->assertArrayHasKey($resource['id'], $resourcesNotShared);
        }
    }
}
