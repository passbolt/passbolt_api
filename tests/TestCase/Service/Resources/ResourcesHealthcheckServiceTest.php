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
 * @since         5.8.0
 */
namespace App\Test\TestCase\Service\Resources;

use App\Service\Resources\ResourcesHealthcheckService;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Utility\Application\FeaturePluginAwareTrait;
use App\Utility\Healthchecks\Healthcheck;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;

/**
 * @covers \App\Service\Resources\ResourcesHealthcheckService
 */
class ResourcesHealthcheckServiceTest extends TestCase
{
    use FeaturePluginAwareTrait;
    use TruncateDirtyTables;

    private ?ResourcesHealthcheckService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new ResourcesHealthcheckService();
        $this->enableFeaturePlugin(MetadataPlugin::class);
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testResourcesHealthcheckService_IsMetadataKeyExistAndActive_SkipDeletedResources(): void
    {
        $owner = UserFactory::make()->persist();
        // Create an active metadata key
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        // Create a deleted metadata key
        $deletedMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->deleted()->persist();

        // Create a deleted V5 resource with a deleted metadata key - should be skipped
        ResourceFactory::make()
            ->withSecretsFor([$owner])
            ->withPermissionsFor([$owner])
            ->v5Fields(true, ['metadata_key_id' => $deletedMetadataKey->id])
            ->setDeleted()
            ->persist();

        // Create an active V5 resource with an active metadata key - should pass
        ResourceFactory::make()
            ->withSecretsFor([$owner])
            ->withPermissionsFor([$owner])
            ->v5Fields(true, ['metadata_key_id' => $activeMetadataKey->id])
            ->persist();

        $result = $this->service->check();

        // Verify the metadata key check passed (no failures for deleted resources)
        $metadataKeyCheck = $result[ResourcesHealthcheckService::CATEGORY][ResourcesHealthcheckService::CHECK_IS_METADATA_KEY_EXIST_AND_ACTIVE];
        $this->assertInstanceOf(Healthcheck::class, $metadataKeyCheck);

        // The check should not have any error details for the deleted resource
        $details = $metadataKeyCheck->getDetails();
        $errorDetails = array_filter($details, fn ($d) => $d['status'] === Healthcheck::STATUS_ERROR);
        $this->assertEmpty($errorDetails, 'Deleted resources should not trigger metadata key errors');

        // Should have only one success detail for the active resource
        $successDetails = array_filter($details, fn ($d) => $d['status'] === Healthcheck::STATUS_SUCCESS);
        $this->assertCount(1, $successDetails, 'Only the active resource should have a success detail');
    }

    public function testResourcesHealthcheckService_IsMetadataKeyExistAndActive_FailsForActiveResourceWithDeletedKey(): void
    {
        $owner = UserFactory::make()->persist();
        // Create a deleted metadata key
        $deletedMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->deleted()->persist();

        // Create an active V5 resource with a deleted metadata key - should fail
        ResourceFactory::make()
            ->withSecretsFor([$owner])
            ->withPermissionsFor([$owner])
            ->v5Fields(true, ['metadata_key_id' => $deletedMetadataKey->id])
            ->persist();

        $result = $this->service->check();

        // Verify the metadata key check failed
        $metadataKeyCheck = $result[ResourcesHealthcheckService::CATEGORY][ResourcesHealthcheckService::CHECK_IS_METADATA_KEY_EXIST_AND_ACTIVE];
        $this->assertInstanceOf(Healthcheck::class, $metadataKeyCheck);

        // The check should have an error detail for the active resource with deleted key
        $details = $metadataKeyCheck->getDetails();
        $errorDetails = array_filter($details, fn ($d) => $d['status'] === Healthcheck::STATUS_ERROR);
        $this->assertCount(1, $errorDetails, 'Active resource with deleted metadata key should trigger an error');
    }
}
