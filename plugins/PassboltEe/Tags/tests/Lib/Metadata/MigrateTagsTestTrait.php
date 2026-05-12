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
namespace Passbolt\Tags\Test\Lib\Metadata;

use App\Model\Entity\Gpgkey;
use App\Service\OpenPGP\MessageRecipientValidationService;
use App\Service\OpenPGP\MessageValidationService;
use App\Service\OpenPGP\PublicKeyValidationService;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Tags\Model\Entity\Tag;

/**
 * Helper methods with common assertions.
 */
trait MigrateTagsTestTrait
{
    public function assertionsForPersonalTag(Tag $tag, Tag $oldTag, Gpgkey $userGpgkey, array $userKeyInfo): void
    {
        $this->assertNull($tag->slug);
        // Assertions for metadata
        $metadata = $tag->get('metadata');
        $this->assertNotNull($metadata);
        $this->assertSame($userGpgkey->id, $tag->get('metadata_key_id'));
        $this->assertSame(MetadataKey::TYPE_USER_KEY, $tag->get('metadata_key_type'));
        // Assert is valid OpenPGP message
        $this->assertTrue(MessageValidationService::isParsableArmoredMessage($metadata));
        // Assert encrypted with ada key
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
        $this->assertArrayEqualsCanonicalizing([
            'object_type' => 'PASSBOLT_TAG_METADATA',
            'slug' => $oldTag->slug,
            'color' => null,
            'description' => null,
            'icon' => null,
        ], $metadataArray);
    }

    public function assertionsForSharedTag(Tag $updatedTag, Tag $oldTag, MetadataKey $metadataKey): void
    {
        $this->assertNull($updatedTag->slug);
        // Assertions for metadata
        $metadata = $updatedTag->get('metadata');
        $this->assertNotNull($metadata);
        $this->assertSame($metadataKey->id, $updatedTag->get('metadata_key_id'));
        $this->assertSame(MetadataKey::TYPE_SHARED_KEY, $updatedTag->get('metadata_key_type'));
        // Assert is valid OpenPGP message
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
        $this->assertArrayEqualsCanonicalizing([
            'object_type' => 'PASSBOLT_TAG_METADATA',
            'slug' => $oldTag->slug,
            'color' => null,
            'description' => null,
            'icon' => null,
        ], $metadataArray);
    }
}
