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
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\TableRegistry;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\ResourceTypes\ResourceTypesPlugin;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * Part of the logic of this test is handled in the ResourcesAddControllerTest.
 *
 * @covers \App\Service\Resources\ResourcesAddService
 * @see \App\Controller\Resources\ResourcesAddController
 */
class MetadataResourcesAddServiceTest extends AppTestCaseV5
{
    use ResourcesModelTrait;
    use GpgMetadataKeysTestTrait;

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
        $this->service = new ResourcesAddService();
        $this->enableFeaturePlugin(ResourceTypesPlugin::class);
        MetadataTypesSettingsFactory::make()->v5()->persist();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testMetadataResourceAddService_Success_SharedKey()
    {
        $user = UserFactory::make()->user()->persist();
        // Create two metadata keys to ensure that IsSharedMetadataKeyUniqueActiveRule is skipped
        [$metadataKey] = MetadataKeyFactory::make(2)->withCreatorAndModifier($user)->withServerPrivateKey()->persist();
        $v4ResourceTypeId = ResourceTypeFactory::make()->default()->persist()->get('id');
        $resourceTypeId = ResourceTypeFactory::make()->v5Default()->persist()->get('id');
        $metadataKeyId = $metadataKey->get('id');
        $dummyResourceData = $this->getDummyResourcesPostData([
            'resource_type_id' => $v4ResourceTypeId, // v4 here is intentional, needed for mapping
        ]);
        $resourceDto = MetadataResourceDto::fromArray($dummyResourceData);
        $clearTextMetadata = json_encode($resourceDto->getClearTextMetadata());
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        $metadataKeyType = 'shared_key';

        $payload = [
            MetadataResourceDto::METADATA_KEY_TYPE => $metadataKeyType,
            MetadataResourceDto::METADATA => $metadata,
            MetadataResourceDto::METADATA_KEY_ID => $metadataKeyId,
            'resource_type_id' => $resourceTypeId,
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];
        $uac = UserFactory::make()->persistedUAC();
        $resource = $this->service->add($uac, MetadataResourceDto::fromArray($payload));

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertInstanceOf(Secret::class, $resource->secrets[0]);
        $this->assertSame(1, ResourceFactory::count());
        $this->assertSame(1, SecretFactory::count());
    }

    public function testMetadataResourceAddService_Success_UserKey()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $resourceTypeId = ResourceTypeFactory::make()->v5Default()->persist()->get('id');

        $metadataJson = json_encode($this->getDummyResourcesPostData([
            'resource_type_id' => $resourceTypeId,
        ]));
        $metadata = $this->encryptForUser($metadataJson, $user, $this->getAdaNoPassphraseKeyInfo());

        $payload = [
            MetadataResourceDto::METADATA_KEY_TYPE => 'user_key',
            MetadataResourceDto::METADATA => $metadata,
            MetadataResourceDto::METADATA_KEY_ID => $user->gpgkey->id,
            'resource_type_id' => $resourceTypeId,
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];
        $uac = UserFactory::make()->persistedUAC();
        $resource = $this->service->add($uac, MetadataResourceDto::fromArray($payload));

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertInstanceOf(Secret::class, $resource->secrets[0]);
        $this->assertSame(1, ResourceFactory::count());
        $this->assertSame(1, SecretFactory::count());
    }

    public function testMetadataResourceAddService_Error_ValidationResourceTypeNotFoundInDB()
    {
        $payload = [
            MetadataResourceDto::METADATA_KEY_TYPE => 'user_key',
            MetadataResourceDto::METADATA => $this->getDummyGpgMessage(),
            MetadataResourceDto::METADATA_KEY_ID => UuidFactory::uuid(),
            'resource_type_id' => ResourceTypeFactory::make()->v5Default()->getEntity()->get('id'),
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
            $this->assertSame(
                'The resource type does not exist.',
                $exception->getErrors()['resource_type_id']['resource_type_exists']
            );
        }
    }

    public function testMetadataResourceAddService_Error_ValidationResourceTypeDeleted()
    {
        $resourceType = ResourceTypeFactory::make()->v5Default()->deleted()->persist();
        $payload = [
            MetadataResourceDto::METADATA_KEY_TYPE => 'user_key',
            MetadataResourceDto::METADATA => $this->getDummyGpgMessage(),
            MetadataResourceDto::METADATA_KEY_ID => UuidFactory::uuid(),
            'resource_type_id' => $resourceType->get('id'),
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];

        $uac = UserFactory::make()->persistedUAC();
        try {
            $this->service->add($uac, new MetadataResourceDto($payload));
        } catch (ValidationException $exception) {
            $this->assertSame(
                'The resource type should not be deleted.',
                $exception->getErrors()['resource_type_id']['resource_type_is_not_soft_deleted']
            );
        }
    }

    public function testMetadataResourceAddService_Error_ValidationResourceTypeNotV5()
    {
        $payload = [
            MetadataResourceDto::METADATA_KEY_TYPE => 'shared_key',
            MetadataResourceDto::METADATA => $this->getDummyGpgMessage(),
            MetadataResourceDto::METADATA_KEY_ID => UuidFactory::uuid(),
            'resource_type_id' => UuidFactory::uuid(),
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];

        $uac = UserFactory::make()->persistedUAC();
        try {
            $this->service->add($uac, new MetadataResourceDto($payload));
        } catch (ValidationException $exception) {
            $this->assertSame(
                'The resource type should be one of the following: v5-password-string, v5-default, v5-totp-standalone, v5-default-with-totp, v5-custom-fields.',
                $exception->getErrors()['resource_type_id']['inList']
            );
        }
    }

    public function testMetadataResourceAddService_Error_ValidationV4AndV5Fields()
    {
        $payload = [
            'name' => 'foo',
            MetadataResourceDto::METADATA_KEY_TYPE => 'shared_key',
            MetadataResourceDto::METADATA => $this->getDummyGpgMessage(),
            MetadataResourceDto::METADATA_KEY_ID => UuidFactory::uuid(),
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];
        $uac = UserFactory::make()->persistedUAC();

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('V4 related fields are not supported for V5');

        $this->service->add($uac, new MetadataResourceDto($payload));
    }

    public function testMetadataResourceAddService_Error_ValidationValidMetadataOpenPGPMessage()
    {
        $payload = [
            MetadataResourceDto::METADATA_KEY_TYPE => 'shared_key',
            MetadataResourceDto::METADATA => 'foo',
            MetadataResourceDto::METADATA_KEY_ID => UuidFactory::uuid(),
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];

        $uac = UserFactory::make()->persistedUAC();
        try {
            $this->service->add($uac, new MetadataResourceDto($payload));
        } catch (ValidationException $exception) {
            $this->assertSame(
                'The message should be a valid ASCII-armored OpenPGP message.',
                $exception->getErrors()['metadata']['isMetadataParsable']
            );
        }
    }

    public function testMetadataResourceAddService_Error_ValidationMetadataKeyIdIsNotValidUuid()
    {
        $payload = [
            MetadataResourceDto::METADATA_KEY_TYPE => 'shared_key',
            MetadataResourceDto::METADATA => $this->getDummyGpgMessage(),
            MetadataResourceDto::METADATA_KEY_ID => 'foo',
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];

        $uac = UserFactory::make()->persistedUAC();
        try {
            $this->service->add($uac, new MetadataResourceDto($payload));
        } catch (ValidationException $exception) {
            $this->assertSame($exception->getErrors()['metadata_key_id'], [
                'uuid' => 'The metadata key ID should be a valid UUID.',
            ]);
        }
    }

    public function testMetadataResourceAddService_Error_InvalidMetadataKeyType()
    {
        $payload = [
            MetadataResourceDto::METADATA_KEY_TYPE => 'foo',
            MetadataResourceDto::METADATA => $this->getDummyGpgMessage(),
            MetadataResourceDto::METADATA_KEY_ID => UuidFactory::uuid(),
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];

        $uac = UserFactory::make()->persistedUAC();
        try {
            $this->service->add($uac, new MetadataResourceDto($payload));
        } catch (ValidationException $exception) {
            $this->assertSame($exception->getErrors()['metadata_key_type'], [
                'inList' => 'The metadata key type should be one of the following: user_key, shared_key.',
            ]);
        }
    }

    public function testMetadataResourceAddService_Error_UserKey_NotAllowedBySettings()
    {
        MetadataKeysSettingsFactory::make()->disableUsageOfPersonalKeys()->persist();
        MetadataTypesSettingsFactory::make()->v5()->persist();

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $resourceTypeId = ResourceTypeFactory::make()->v5Default()->persist()->get('id');

        $metadataJson = json_encode($this->getDummyResourcesPostData([
            'resource_type_id' => $resourceTypeId,
        ]));
        $metadata = $this->encryptForUser($metadataJson, $user, $this->getAdaNoPassphraseKeyInfo());

        $payload = [
            MetadataResourceDto::METADATA => $metadata,
            MetadataResourceDto::METADATA_KEY_TYPE => 'user_key',
            MetadataResourceDto::METADATA_KEY_ID => $user->gpgkey->id,
            'resource_type_id' => $resourceTypeId,
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];
        $uac = UserFactory::make()->persistedUAC();
        try {
            $this->service->add($uac, MetadataResourceDto::fromArray($payload));
            $this->fail();
        } catch (ValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertNotEmpty($errors['metadata_key_type']['isMetadataKeyTypeAllowedBySettings']);
        }
    }

    public function testMetadataResourceAddService_Error_SharedKeyExpired()
    {
        $user = UserFactory::make()->user()->persist();
        $metadataKey = MetadataKeyFactory::make()
            ->withCreatorAndModifier($user)->withServerPrivateKey()
            ->expired()->persist();
        $v4ResourceTypeId = ResourceTypeFactory::make()->default()->persist()->get('id');
        $resourceTypeId = ResourceTypeFactory::make()->v5Default()->persist()->get('id');
        $metadataKeyId = $metadataKey->get('id');
        $dummyResourceData = $this->getDummyResourcesPostData([
            'resource_type_id' => $v4ResourceTypeId, // v4 here is intentional, needed for mapping
        ]);
        $resourceDto = MetadataResourceDto::fromArray($dummyResourceData);
        $clearTextMetadata = json_encode($resourceDto->getClearTextMetadata());
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);

        $payload = [
            MetadataResourceDto::METADATA_KEY_TYPE => 'shared_key',
            MetadataResourceDto::METADATA => $metadata,
            MetadataResourceDto::METADATA_KEY_ID => $metadataKeyId,
            'resource_type_id' => $resourceTypeId,
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];
        $uac = UserFactory::make()->persistedUAC();

        try {
            $this->service->add($uac, MetadataResourceDto::fromArray($payload));
            $this->fail();
        } catch (ValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertTrue(isset($errors['metadata_key_id']['isMetadataKeyNotExpired']));
        }
    }

    public function testMetadataResourceAddService_Error_SharedKeyDeleted()
    {
        $user = UserFactory::make()->user()->persist();
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($user)->withServerPrivateKey()
            ->deleted()->persist();
        $v4ResourceTypeId = ResourceTypeFactory::make()->default()->persist()->get('id');
        $resourceTypeId = ResourceTypeFactory::make()->v5Default()->persist()->get('id');
        $metadataKeyId = $metadataKey->get('id');
        $dummyResourceData = $this->getDummyResourcesPostData([
            'resource_type_id' => $v4ResourceTypeId, // v4 here is intentional, needed for mapping
        ]);
        $resourceDto = MetadataResourceDto::fromArray($dummyResourceData);
        $clearTextMetadata = json_encode($resourceDto->getClearTextMetadata());
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);

        $payload = [
            MetadataResourceDto::METADATA_KEY_TYPE => 'shared_key',
            MetadataResourceDto::METADATA => $metadata,
            MetadataResourceDto::METADATA_KEY_ID => $metadataKeyId,
            'resource_type_id' => $resourceTypeId,
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];
        $uac = UserFactory::make()->persistedUAC();

        try {
            $this->service->add($uac, MetadataResourceDto::fromArray($payload));
            $this->fail();
        } catch (ValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertTrue(isset($errors['metadata_key_id']['metadata_key_exists']));
        }
    }
}
