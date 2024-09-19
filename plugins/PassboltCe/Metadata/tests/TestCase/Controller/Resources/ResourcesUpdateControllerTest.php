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

use App\Service\Resources\ResourcesUpdateService;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\ResourceTypes\ResourceTypesPlugin;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class ResourcesUpdateControllerTest extends AppIntegrationTestCaseV5
{
    use EmailQueueTrait;
    use GpgMetadataKeysTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->setEmailNotificationsSetting('password.create', true);
        $this->enableFeaturePlugin(ResourceTypesPlugin::class);
        RoleFactory::make()->guest()->persist();
        // enable event tracking
        EventManager::instance()->setEventList(new EventList());
    }

    public function testResourcesUpdateController_Success_SharedKey(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($user)->withServerPrivateKey()->persist();
        $v4ResourceTypeId = ResourceTypeFactory::make()->passwordString()->persist()->get('id');
        $resourceTypeId = ResourceTypeFactory::make()->v5Default()->persist()->get('id');
        $metadataKeyId = $metadataKey->get('id');
        $resource = ResourceFactory::make(['resource_type_id' => $v4ResourceTypeId])->withPermissionsFor([$user])->persist();
        $resourceDto = MetadataResourceDto::fromArray($resource->toArray());
        $clearTextMetadata = json_encode($resourceDto->getClearTextMetadata());
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        $metadataKeyType = 'shared_key';
        $resourceId = $resource->get('id');
        $this->logInAs($user);

        $data = [
            'metadata_key_id' => $metadataKeyId,
            'metadata' => $metadata,
            'metadata_key_type' => $metadataKeyType,
            'resource_type_id' => $resourceTypeId,
        ];
        $this->putJson("/resources/{$resourceId}.json", $data);

        $this->assertSuccess();
        // Check the server response.
        $response = $this->_responseJsonBody;
        // Check the resource attributes.
        $this->assertResourceV5Attributes($response);
        $this->assertObjectNotHasAttribute('name', $response);
        $this->assertEquals($data['metadata_key_id'], $response->metadata_key_id);
        $this->assertEquals($data['metadata'], $response->metadata);
        $this->assertEquals($data['metadata_key_type'], $response->metadata_key_type);
        $this->assertEquals($resourceTypeId, $response->resource_type_id);
        $this->assertEquals($resource->get('created_by'), $response->created_by);
        $this->assertEquals($user->id, $response->modified_by);
        // assert event
        $this->assertEventFiredWith(
            ResourcesUpdateService::UPDATE_SUCCESS_EVENT_NAME,
            'isV5',
            true
        );
    }

    public function testResourcesUpdateController_Success_UserKey(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        ResourceTypeFactory::make()->passwordString()->persist()->get('id');
        $resourceTypeId = ResourceTypeFactory::make()->v5Default()->persist()->get('id');
        $metadataKeyId = $user->gpgkey->id;
        $resource = ResourceFactory::make(['resource_type_id' => $resourceTypeId])
            ->v5Fields()
            ->withPermissionsFor([$user])
            ->persist();
        $resourceDto = MetadataResourceDto::fromArray($resource->toArray());
        $clearTextMetadata = json_encode($resourceDto->getClearTextMetadata(false));
        $metadata = $this->encryptForUser($clearTextMetadata, $user, [
            'passphrase' => 'ada@passbolt.com',
            'privateKey' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private.key'),
        ]);
        $metadataKeyType = 'user_key';
        $this->logInAs($user);
        $resourceId = $resource->get('id');

        $data = [
            'metadata_key_id' => $metadataKeyId,
            'metadata' => $metadata,
            'metadata_key_type' => $metadataKeyType,
            'resource_type_id' => $resourceTypeId,
        ];
        $this->putJson("/resources/{$resourceId}.json", $data);

        $this->assertSuccess();
        // Check the server response.
        $response = $this->_responseJsonBody;
        // Check the resource attributes.
        $this->assertResourceV5Attributes($response);
        $this->assertObjectNotHasAttribute('name', $response);
        $this->assertEquals($user->gpgkey->id, $response->metadata_key_id);
        $this->assertEquals($data['metadata'], $response->metadata);
        $this->assertEquals($data['metadata_key_type'], $response->metadata_key_type);
        $this->assertEquals($resourceTypeId, $response->resource_type_id);
        $this->assertEquals($resource->get('created_by'), $response->created_by);
        $this->assertEquals($user->id, $response->modified_by);
    }

    public function testResourcesUpdateController_Error_MixV4V5Fields(): void
    {
        $user = $this->logInAsUser();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $metadataKeyId = UuidFactory::uuid();
        $metadata = 'metadata';
        $metadataKeyType = 'shared_key';
        $data = [
            'metadata_key_id' => $metadataKeyId,
            'metadata' => $metadata,
            'metadata_key_type' => $metadataKeyType,
            'name' => '新的專用資源名稱',
        ];

        $this->putJson("/resources/{$resource->get('id')}.json", $data);

        $this->assertBadRequestError('V4 related fields are not supported for V5');
    }

    public function testResourcesUpdateController_Success_MetadataDisabledMixV4AndV5FieldsAllowed(): void
    {
        Configure::write('passbolt.v5.enabled', false);
        $user = $this->logInAsUser();
        ResourceTypeFactory::make()->default()->persist()->get('id');
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $metadataKeyId = UuidFactory::uuid();
        $metadata = 'metadata';
        $metadataKeyType = 'shared_key';

        $data = [
            'metadata_key_id' => $metadataKeyId,
            'metadata' => $metadata,
            'metadata_key_type' => $metadataKeyType,
            'name' => '新的專用資源名稱',
        ];
        $this->putJson("/resources/{$resource->get('id')}.json", $data);

        $this->assertSuccess();
        $resource = ResourceFactory::firstOrFail();
        $this->assertNull($resource->metadata_key_id);
        $this->assertObjectNotHasAttribute('metadata', $this->_responseJsonBody);
    }
}
