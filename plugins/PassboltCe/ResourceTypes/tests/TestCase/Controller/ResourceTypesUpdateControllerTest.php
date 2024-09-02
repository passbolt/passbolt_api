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
use Passbolt\Metadata\Test\Factory\MetadataSettingsFactory;
use Passbolt\ResourceTypes\ResourceTypesPlugin;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;
use Passbolt\ResourceTypes\Test\Lib\Model\ResourceTypesModelTrait;

/**
 * @covers \Passbolt\ResourceTypes\Controller\ResourceTypesUpdateController
 */
class ResourceTypesUpdateControllerTest extends AppIntegrationTestCaseV5
{
    use ResourceTypesModelTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(ResourceTypesPlugin::class);
    }

    public function testResourceTypesUpdateController_Success(): void
    {
        MetadataSettingsFactory::make()->v4()->persist();

        /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $resourceType */
        $resourceType = ResourceTypeFactory::make()->passwordString()->deleted()->persist();
        $resourceTypeId = $resourceType->id;

        $this->logInAsAdmin();
        $this->putJson("/resource-types/$resourceTypeId.json", ['deleted' => null]);

        $this->assertSuccess();
    }

    public function testResourceTypesUpdateController_ErrorNotDeleted(): void
    {
        MetadataSettingsFactory::make()->v4()->persist();

        /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $resourceType */
        $resourceType = ResourceTypeFactory::make()->passwordString()->persist();
        $resourceTypeId = $resourceType->id;

        $this->logInAsAdmin();
        $this->putJson("/resource-types/$resourceTypeId.json", ['deleted' => null]);

        $this->assertError(400);
    }

    public function testResourceTypesUpdateController_ErrorNotValidId(): void
    {
        $resourceTypeId = 'invalid-id';
        $this->logInAsAdmin();
        $this->putJson("/resource-types/$resourceTypeId.json", ['deleted' => null]);
        $this->assertError(400);
    }

    public function testResourceTypesUpdateController_ErrorNotFound(): void
    {
        $resourceTypeId = UuidFactory::uuid();
        $this->logInAsAdmin();
        $this->putJson("/resource-types/$resourceTypeId.json", ['deleted' => null]);
        $this->assertError(404);
    }

    public function testResourceTypesUpdateController_ErrorNotAdmin(): void
    {
        $resourceTypeId = UuidFactory::uuid();
        $this->logInAsUser();
        $this->putJson("/resource-types/$resourceTypeId.json", ['deleted' => null]);
        $this->assertError(403);
    }

    public function testResourceTypesUpdateController_ErrorNotAuthenticated(): void
    {
        $resourceTypeId = UuidFactory::uuid();
        $this->putJson("/resource-types/$resourceTypeId.json", ['deleted' => null]);
        $this->assertAuthenticationError();
    }
}
