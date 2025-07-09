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

namespace Passbolt\Metadata\Test\TestCase\Controller\Resources;

use App\Service\Resources\ResourcesAddService;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;
use Passbolt\ResourceTypes\ResourceTypesPlugin;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class ResourcesAddControllerTest extends AppIntegrationTestCaseV5
{
    use EmailQueueTrait;
    use GpgMetadataKeysTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->setEmailNotificationsSetting('password.create', true);
        $this->enableFeaturePlugin(ResourceTypesPlugin::class);
        // enable event tracking
        EventManager::instance()->setEventList(new EventList());
    }

    public function v5ResourceTypesSlugProvider()
    {
        $resourceTypeSlugs = [];
        foreach (ResourceType::V5_RESOURCE_TYPE_SLUGS as $v5ResourceTypeSlug) {
            // simple password string isn't possible in v5
            if ($v5ResourceTypeSlug === ResourceType::SLUG_V5_PASSWORD_STRING) {
                continue;
            }

            $resourceTypeSlugs[] = [$v5ResourceTypeSlug];
        }

        return $resourceTypeSlugs;
    }

    /**
     * @dataProvider v5ResourceTypesSlugProvider
     * @return void
     */
    public function testResourcesAddController_Success_SharedKeyType(string $resourceTypeSlug): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        $user = UserFactory::make()->user()->persist();
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($user)->withServerPrivateKey()->persist();
        $v4ResourceTypeId = ResourceTypeFactory::make()->passwordString()->persist()->get('id');
        $resourceTypeId = ResourceTypeFactory::make([
            'id' => UuidFactory::uuid('resource-types.id.' . $resourceTypeSlug),
            'slug' => $resourceTypeSlug,
        ])->persist()->get('id');
        $metadataKeyId = $metadataKey->get('id');
        $dummyResourceData = $this->getDummyResourcesPostData([
            'resource_type_id' => $v4ResourceTypeId, // v4 here is intentional, needed for mapping
        ]);
        $resourceDto = MetadataResourceDto::fromArray($dummyResourceData);
        $clearTextMetadata = json_encode($resourceDto->getClearTextMetadata());
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        $metadataKeyType = 'shared_key';
        // login
        $this->logInAs($user);

        $data = [
            'metadata_key_id' => $metadataKeyId,
            'metadata' => $metadata,
            'metadata_key_type' => $metadataKeyType,
            'resource_type_id' => $resourceTypeId,
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];
        $this->postJson('/resources.json', $data);

        $this->assertSuccess();
        $resource = ResourceFactory::firstOrFail();
        $this->assertSame($metadataKeyId, $resource->metadata_key_id);
        $this->assertSame($metadata, $resource->metadata);
        $this->assertSame($metadataKeyType, $resource->metadata_key_type);
        $this->assertSame($resourceTypeId, $resource->resource_type_id);
        $this->assertObjectNotHasAttribute('name', $this->_responseJsonBody);
        // assert event
        $this->assertEventFiredWith(
            ResourcesAddService::ADD_SUCCESS_EVENT_NAME,
            'isV5',
            true
        );
    }

    public function testResourcesAddController_Success_UserKeyType(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        MetadataKeyFactory::make()->withCreatorAndModifier($user)->withServerPrivateKey()->persist();
        $v4ResourceTypeId = ResourceTypeFactory::make()->passwordString()->persist()->get('id');
        $resourceTypeId = ResourceTypeFactory::make()->v5Default()->persist()->get('id');
        $metadataKeyId = $user->gpgkey->id;
        $dummyResourceData = $this->getDummyResourcesPostData([
            'resource_type_id' => $v4ResourceTypeId, // v4 here is intentional, needed for mapping
        ]);
        $resourceDto = MetadataResourceDto::fromArray($dummyResourceData);
        $clearTextMetadata = json_encode($resourceDto->getClearTextMetadata());
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        $metadataKeyType = 'user_key';
        // login
        $this->logInAs($user);

        $data = [
            'metadata_key_id' => $metadataKeyId,
            'metadata' => $metadata,
            'metadata_key_type' => $metadataKeyType,
            'resource_type_id' => $resourceTypeId,
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];
        $this->postJson('/resources.json', $data);

        $this->assertSuccess();
        $resource = ResourceFactory::firstOrFail();
        $this->assertSame($user->gpgkey->id, $resource->metadata_key_id);
        $this->assertSame($metadata, $resource->metadata);
        $this->assertSame($metadataKeyType, $resource->metadata_key_type);
        $this->assertSame($resourceTypeId, $resource->resource_type_id);
        $this->assertObjectNotHasAttribute('name', $this->_responseJsonBody);
    }

    public function testResourcesAddController_Success_UserKeyType_KeyIdNull(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        MetadataKeyFactory::make()->withCreatorAndModifier($user)->withServerPrivateKey()->persist();
        $v4ResourceTypeId = ResourceTypeFactory::make()->passwordString()->persist()->get('id');
        $resourceTypeId = ResourceTypeFactory::make()->v5Default()->persist()->get('id');
        $dummyResourceData = $this->getDummyResourcesPostData([
            'resource_type_id' => $v4ResourceTypeId, // v4 here is intentional, needed for mapping
        ]);
        $resourceDto = MetadataResourceDto::fromArray($dummyResourceData);
        $clearTextMetadata = json_encode($resourceDto->getClearTextMetadata());
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        $metadataKeyType = 'user_key';
        // login
        $this->logInAs($user);

        $data = [
            'metadata_key_id' => null, // will be set by controller
            'metadata' => $metadata,
            'metadata_key_type' => $metadataKeyType,
            'resource_type_id' => $resourceTypeId,
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];
        $this->postJson('/resources.json', $data);

        $this->assertSuccess();
        $resource = ResourceFactory::firstOrFail();
        $this->assertSame($user->gpgkey->id, $resource->metadata_key_id);
        $this->assertSame($metadata, $resource->metadata);
        $this->assertSame($metadataKeyType, $resource->metadata_key_type);
        $this->assertSame($resourceTypeId, $resource->resource_type_id);
        $this->assertObjectNotHasAttribute('name', $this->_responseJsonBody);
    }

    public function testResourcesAddController_Error_NotCurrentUser(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\User $betty */
        $betty = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())
            ->user()
            ->active()
            ->persist();
        $v4ResourceTypeId = ResourceTypeFactory::make()->passwordString()->persist()->get('id');
        $resourceTypeId = ResourceTypeFactory::make()->v5Default()->persist()->get('id');
        $metadataKeyId = $betty->gpgkey->id;
        $dummyResourceData = $this->getDummyResourcesPostData([
            'resource_type_id' => $v4ResourceTypeId, // v4 here is intentional, needed for mapping
        ]);
        $resourceDto = MetadataResourceDto::fromArray($dummyResourceData);
        $clearTextMetadata = json_encode($resourceDto->getClearTextMetadata());
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        $metadataKeyType = 'user_key';
        // login
        $this->logInAs($betty);

        // Metadata is encrypted with ada's key but id is set to betty's
        $data = [
            'metadata_key_id' => $metadataKeyId,
            'metadata' => $metadata,
            'metadata_key_type' => $metadataKeyType,
            'resource_type_id' => $resourceTypeId,
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];
        $this->postJson('/resources.json', $data);

        $this->assertError(400);
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayHasKey('isValidEncryptedResourceMetadata', $response['metadata']);
    }

    public function testResourcesAddController_Error_MixV4V5Fields(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        $user = UserFactory::make()->user()->persist();
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($user)->withServerPrivateKey()->persist();
        $v4ResourceTypeId = ResourceTypeFactory::make()->passwordString()->persist()->get('id');
        $metadataKeyId = $metadataKey->get('id');
        $dummyResourceData = $this->getDummyResourcesPostData([
            'resource_type_id' => $v4ResourceTypeId, // v4 here is intentional, needed for mapping
        ]);
        $resourceDto = MetadataResourceDto::fromArray($dummyResourceData);
        $clearTextMetadata = json_encode($resourceDto->getClearTextMetadata());
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        $metadataKeyType = 'shared_key';
        $this->logInAs($user);

        $data = $this->getDummyResourcesPostData([
            'metadata_key_id' => $metadataKeyId,
            'metadata' => $metadata,
            'metadata_key_type' => $metadataKeyType,
            'name' => '新的專用資源名稱',
            'username' => 'username@domain.com',
            'uri' => 'https://www.域.com',
            'description' => '新的資源描述',
        ]);

        $this->postJson('/resources.json', $data);

        $this->assertBadRequestError('V4 related fields are not supported for V5');
        $this->assertSame(0, ResourceFactory::count());
    }

    public function testResourcesAddController_Success_V5DisabledMixV4V5FieldsWorks(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        Configure::write('passbolt.v5.enabled', false);
        $user = UserFactory::make()->user()->persist();
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($user)->withServerPrivateKey()->persist();
        $metadataKeyId = $metadataKey->get('id');
        ResourceTypeFactory::make()->default()->persist();
        $this->logInAs($user);

        $data = $this->getDummyResourcesPostData([
            'metadata_key_id' => $metadataKeyId,
            'name' => '新的專用資源名稱',
            'username' => 'username@domain.com',
            'uri' => 'https://www.域.com',
            'description' => '新的資源描述',
        ]);

        $this->postJson('/resources.json', $data);
        $this->assertSuccess();

        $resource = ResourceFactory::firstOrFail();
        $this->assertNull($resource->metadata_key_id);
        $this->assertObjectNotHasAttribute('metadata', $this->_responseJsonBody);
    }

    public function testResourcesAddController_Error_AllowCreationOfV5ResourceDisabled(): void
    {
        // Allow only V4 format
        MetadataTypesSettingsFactory::make()->v4()->persist();
        $user = UserFactory::make()->user()->persist();
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($user)->withServerPrivateKey()->persist();
        $v4ResourceTypeId = ResourceTypeFactory::make()->passwordString()->persist()->get('id');
        $resourceTypeId = ResourceTypeFactory::make()->v5Default()->persist()->get('id');
        $metadataKeyId = $metadataKey->get('id');
        $dummyResourceData = $this->getDummyResourcesPostData([
            'resource_type_id' => $v4ResourceTypeId, // v4 here is intentional, needed for mapping
        ]);
        $resourceDto = MetadataResourceDto::fromArray($dummyResourceData);
        $clearTextMetadata = json_encode($resourceDto->getClearTextMetadata());
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        $metadataKeyType = 'shared_key';
        // login
        $this->logInAs($user);

        $data = [
            'metadata_key_id' => $metadataKeyId,
            'metadata' => $metadata,
            'metadata_key_type' => $metadataKeyType,
            'resource_type_id' => $resourceTypeId,
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];
        $this->postJson('/resources.json', $data);

        // `\` here is to pass regex in the assertion method
        $this->assertBadRequestError('Resource creation\/modification with encrypted metadata not allowed');
    }

    public function testResourcesAddController_Error_AllowCreationOfV4ResourceDisabled(): void
    {
        // Allow only V4 format
        MetadataTypesSettingsFactory::make()->v6()->persist();
        $user = UserFactory::make()->user()->persist();
        // login
        $this->logInAs($user);

        $data = [
            'name' => 'Test',
            'username' => 'username',
            'uri' => 'https://www.test.org',
            'description' => 'Test',
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];
        $this->postJson('/resources.json', $data);

        $this->assertBadRequestError('Resource creation\/modification with cleartext metadata not allowed');
    }

    public function testResourcesAddController_Error_MetadataKeySettings_PersonalKeysDisabled(): void
    {
        $data = MetadataKeysSettingsFactory::getDefaultData();
        $data[MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS] = false;
        MetadataKeysSettingsFactory::make()->value($data)->persist();
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

        $this->logInAs($user);
        $data = [
            'metadata' => $metadata,
            'metadata_key_type' => 'user_key',
            'metadata_key_id' => $user->gpgkey->id,
            'resource_type_id' => $resourceTypeId,
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];
        $this->postJson('/resources.json', $data);
        $this->assertError(400);
        $this->assertResponseContains('isMetadataKeyTypeAllowedBySettings');
    }

    public function testResourcesAddController_Error_SharedKeyType_Empty(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        $user = UserFactory::make()->user()->persist();
        MetadataKeyFactory::make()->withCreatorAndModifier($user)->withServerPrivateKey()->persist();
        $v4ResourceTypeId = ResourceTypeFactory::make()->passwordString()->persist()->get('id');
        $resourceTypeId = ResourceTypeFactory::make()->v5Default()->persist()->get('id');
        $dummyResourceData = $this->getDummyResourcesPostData([
            'resource_type_id' => $v4ResourceTypeId, // v4 here is intentional, needed for mapping
        ]);
        $resourceDto = MetadataResourceDto::fromArray($dummyResourceData);
        $clearTextMetadata = json_encode($resourceDto->getClearTextMetadata());
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        $metadataKeyType = 'shared_key';
        // login
        $this->logInAs($user);

        $data = [
            'metadata_key_id' => null,
            'metadata' => $metadata,
            'metadata_key_type' => $metadataKeyType,
            'resource_type_id' => $resourceTypeId,
            'secrets' => [
                ['data' => $this->getDummyGpgMessage()],
            ],
        ];

        $this->postJson('/resources.json', $data);
        $this->assertError(400);
    }
}
