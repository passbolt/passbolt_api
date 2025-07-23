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
 * @since         2.0.0
 */

namespace Passbolt\ResourceTypes\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCaseV5;
use App\Utility\UuidFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;
use Passbolt\ResourceTypes\ResourceTypesPlugin;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;
use Passbolt\ResourceTypes\Test\Lib\Model\ResourceTypesModelTrait;

/**
 * @covers \Passbolt\ResourceTypes\Controller\ResourceTypesDeleteController
 */
class ResourceTypesDeleteControllerTest extends AppIntegrationTestCaseV5
{
    use ResourceTypesModelTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(ResourceTypesPlugin::class);
    }

    public function testResourceTypesDeleteController_Success(): void
    {
        MetadataTypesSettingsFactory::make()->v4()->persist();

        /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $resourceType */
        $resourceType = ResourceTypeFactory::make()->passwordString()->persist();
        ResourceTypeFactory::make()->passwordAndDescription()->persist();
        $resourceTypeId = $resourceType->id;

        $this->logInAsAdmin();
        $this->deleteJson("/resource-types/$resourceTypeId.json");

        $this->assertSuccess();
        $this->assertEquals(2, ResourceTypeFactory::count());
        $updatedResourceType = ResourceTypeFactory::get($resourceTypeId);
        $this->assertNotNull($updatedResourceType->deleted);
    }

    public function testResourceTypesDeleteController_ErrorHighlander(): void
    {
        MetadataTypesSettingsFactory::make()->v4()->persist();

        /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $resourceType */
        $resourceType = ResourceTypeFactory::make()->passwordString()->persist();
        $resourceTypeId = $resourceType->id;

        $this->logInAsAdmin();
        $this->deleteJson("/resource-types/$resourceTypeId.json");

        $this->assertResponseContains('last'); // There can be only one!
        $this->assertError(400);
    }

    public function testResourceTypesDeleteController_ErrorSomeResourcesExists(): void
    {
        MetadataTypesSettingsFactory::make()->v4()->persist();
        ResourceTypeFactory::make()->passwordAndDescription()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->with('ResourceTypes', ResourceTypeFactory::make()->passwordString())
            ->persist();
        $resourceTypeId = $resource->resource_type->id;

        $this->logInAsAdmin();
        $this->deleteJson("/resource-types/{$resourceTypeId}.json");

        $this->assertResponseContains('resources of this type still exist');
        $this->assertError(400);
    }

    public function testResourceTypesDeleteController_ErrorNotValidId(): void
    {
        $resourceTypeId = 'invalid-id';
        $this->logInAsAdmin();
        $this->deleteJson("/resource-types/$resourceTypeId.json");
        $this->assertError(400);
    }

    public function testResourceTypesDeleteController_ErrorNotFound(): void
    {
        $resourceTypeId = UuidFactory::uuid();
        $this->logInAsAdmin();
        $this->deleteJson("/resource-types/$resourceTypeId.json");
        $this->assertError(404);
    }

    public function testResourceTypesDeleteController_ErrorNotAdmin(): void
    {
        $resourceTypeId = UuidFactory::uuid();
        $this->logInAsUser();
        $this->deleteJson("/resource-types/$resourceTypeId.json");
        $this->assertError(403);
    }

    public function testResourceTypesDeleteController_ErrorNotAuthenticated(): void
    {
        $resourceTypeId = UuidFactory::uuid();
        $this->deleteJson("/resource-types/$resourceTypeId.json");
        $this->assertAuthenticationError();
    }
}
