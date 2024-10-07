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
namespace Passbolt\Metadata\Test\Utility;

use App\Model\Entity\Gpgkey;
use App\Model\Entity\Resource;
use App\Service\OpenPGP\MessageRecipientValidationService;
use App\Service\OpenPGP\MessageValidationService;
use App\Service\OpenPGP\PublicKeyValidationService;
use App\Utility\UuidFactory;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * Helper methods with common assertions.
 */
trait MigrateResourcesTestTrait
{
    /**
     * Assert decrypted metadata content for given resource.
     *
     * @param Resource $resource Resource entity.
     * @param array $metadata Metadata array.
     * @return void
     */
    private function assertMetadataAgainstResource(Resource $resource, array $metadata): void
    {
        $this->assertSame($resource->name, $metadata['name']);
        $this->assertSame($resource->username, $metadata['username']);
        $this->assertSame($resource->description, $metadata['description']);
        $this->assertSame($resource->uri, $metadata['uris'][0]);
        $this->assertSame('PASSBOLT_RESOURCE_METADATA', $metadata['object_type']);
    }

    /**
     * Asserts if entity contains valid data.
     *
     * @param Resource $resource Resource entity.
     * @return void
     */
    private function assertUpdatedResource(Resource $resource): void
    {
        $this->assertNull($resource->name);
        $this->assertNull($resource->username);
        $this->assertNull($resource->uri);
        $this->assertNull($resource->description);
        // Assertions for metadata
        $metadata = $resource->get('metadata');
        $this->assertNotNull($metadata);
    }

    /**
     * Performs assertions related to updated V5 resource entity.
     *
     * @param Resource $updatedResource Updated resource.
     * @param Resource $oldResource Old entity.
     * @param Gpgkey $userGpgkey GPG key entity.
     * @param array $userKeyInfo User key info (private key, fingerprint) for decryption.
     * @return void
     */
    private function assertionsForPersonalResource(
        Resource $updatedResource,
        Resource $oldResource,
        Gpgkey $userGpgkey,
        array $userKeyInfo
    ): void {
        $this->assertUpdatedResource($updatedResource);
        $this->assertSame($userGpgkey->id, $updatedResource->get('metadata_key_id'));
        $this->assertSame('user_key', $updatedResource->get('metadata_key_type'));
        $this->isV5ResourceType($updatedResource->resource_type_id);
        // Assert is valid OpenPGP message
        $metadata = $updatedResource->get('metadata');
        $this->assertTrue(MessageValidationService::isParsableArmoredMessage($metadata));
        // Assert encrypted with user key
        $userArmoredKey = $userGpgkey->armored_key;
        $userFingerprint = $userGpgkey->fingerprint;
        $rules = MessageValidationService::getAsymmetricMessageRules();
        $msgInfo = MessageValidationService::parseAndValidateMessage($metadata, $rules);
        $keyInfo = PublicKeyValidationService::getPublicKeyInfo($userArmoredKey);
        $this->assertTrue(MessageRecipientValidationService::isMessageForRecipient($msgInfo, $keyInfo));
        // Assert decrypted content contains same data as previous one
        $decryptedMetadata = $this->decryptOpenPGPMessage($metadata, [
            'fingerprint' => $userFingerprint,
            'armored_key' => $userKeyInfo['private_key'],
            'passphrase' => $userKeyInfo['passphrase'],
        ]);
        $metadataArray = json_decode($decryptedMetadata, true);
        $this->assertMetadataAgainstResource($oldResource, $metadataArray);
        $this->isV5ResourceType($metadataArray['resource_type_id']);
    }

    public function assertionsForSharedResource(Resource $updatedResource, Resource $oldResource, MetadataKey $metadataKey): void
    {
        $this->assertUpdatedResource($updatedResource);
        $this->assertSame($metadataKey->id, $updatedResource->get('metadata_key_id'));
        $this->assertSame('shared_key', $updatedResource->get('metadata_key_type'));
        // Assert is valid OpenPGP message
        $metadata = $updatedResource->get('metadata');
        $this->assertTrue(MessageValidationService::isParsableArmoredMessage($metadata));
        // Assert encrypted with shared key
        $armoredKey = $metadataKey->armored_key;
        $rules = MessageValidationService::getAsymmetricMessageRules();
        $msgInfo = MessageValidationService::parseAndValidateMessage($metadata, $rules);
        $keyInfo = PublicKeyValidationService::getPublicKeyInfo($armoredKey);
        $this->assertTrue(MessageRecipientValidationService::isMessageForRecipient($msgInfo, $keyInfo));
        // Assert decrypted content contains same data as previous one
        $decryptedMetadata = $this->decryptOpenPGPMessage($metadata, $this->getValidPrivateKeyCleartext());
        $metadataArray = json_decode($decryptedMetadata, true);
        $this->assertMetadataAgainstResource($oldResource, $metadataArray);
    }

    private function isV5ResourceType(string $resourceTypeId): void
    {
        $v5ResourceTypes = [
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_PASSWORD_STRING),
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_DEFAULT),
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_TOTP_STANDALONE),
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_DEFAULT_WITH_TOTP),
        ];
        $resourceTypeIds = ResourceTypeFactory::find()->where(['id IN' => $v5ResourceTypes])->all()->extract('id')->toArray();
        $this->assertContains($resourceTypeId, $resourceTypeIds);
    }
}
