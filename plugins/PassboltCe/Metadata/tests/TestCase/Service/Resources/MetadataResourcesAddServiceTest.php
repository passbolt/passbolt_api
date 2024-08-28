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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Test\TestCase\Service\Resources;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Resource;
use App\Model\Entity\Secret;
use App\Service\Resources\ResourcesAddService;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Utility\Application\FeaturePluginAwareTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * Part of the logic of this test is handled in the ResourcesAddControllerTest.
 *
 * @covers \App\Service\Resources\ResourcesAddService
 * @see \App\Controller\Resources\ResourcesAddController
 */
class MetadataResourcesAddServiceTest extends TestCase
{
    use FeaturePluginAwareTrait;
    use ResourcesModelTrait;
    use TruncateDirtyTables;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    private $Resources;

    /**
     * @var \App\Model\Table\SecretsTable
     */
    private $Secrets;

    private ResourcesAddService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->Secrets = TableRegistry::getTableLocator()->get('Secrets');
        ResourceTypeFactory::make()->default()->persist();
        $this->service = new ResourcesAddService();
        $this->enableFeaturePlugin(MetadataPlugin::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->service);
    }

    public function testMetadataResourceAddServiceSuccess()
    {
        $payload = [
            MetadataResourceDto::METADATA_KEY_TYPE => 'user_key',
            MetadataResourceDto::METADATA => 'foo',
            MetadataResourceDto::METADATA_KEY_ID => UuidFactory::uuid(),
            'secrets' => [
                [
                    'data' => $this->getDummyGpgMessage(),
                ],
            ],
        ];

        $uac = UserFactory::make()->persistedUAC();
        $resource = $this->service->add($uac, new MetadataResourceDto($payload));

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertInstanceOf(Secret::class, $resource->secrets[0]);
        $this->assertSame(1, ResourceFactory::count());
        $this->assertSame(1, SecretFactory::count());
    }

    public function testMetadataResourceAddService_With_v4_and_v5_fields_Deleted_Should_Fail()
    {
        $payload = [
            'name' => 'foo',
            MetadataResourceDto::METADATA_KEY_TYPE => 'user_key',
            MetadataResourceDto::METADATA => 'foo',
            MetadataResourceDto::METADATA_KEY_ID => UuidFactory::uuid(),
            'secrets' => [
                [
                    'data' => $this->getDummyGpgMessage(),
                ],
            ],
        ];

        $uac = UserFactory::make()->persistedUAC();
        try {
            $this->service->add($uac, new MetadataResourceDto($payload));
        } catch (ValidationException $exception) {
            $this->assertSame($exception->getErrors()['resource_type_id'], [
                'resource_type_is_not_soft_deleted' => 'The resource type should not be deleted.',
            ]);
        }
    }

    public function testMetadataResourceAddService_With_ResourceType_Deleted_Should_Fail()
    {
        $resourceType = ResourceTypeFactory::make()->deleted()->persist();
        $payload = [
            MetadataResourceDto::METADATA_KEY_TYPE => 'user_key',
            MetadataResourceDto::METADATA => 'foo',
            MetadataResourceDto::METADATA_KEY_ID => UuidFactory::uuid(),
            'resource_type_id' => $resourceType->get('id'),
            'secrets' => [
                [
                    'data' => $this->getDummyGpgMessage(),
                ],
            ],
        ];

        $uac = UserFactory::make()->persistedUAC();
        try {
            $this->service->add($uac, new MetadataResourceDto($payload));
        } catch (ValidationException $exception) {
            $this->assertSame($exception->getErrors()['resource_type_id'], [
                'resource_type_is_not_soft_deleted' => 'The resource type should not be deleted.',
            ]);
        }
    }
}
