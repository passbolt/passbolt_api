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
namespace Passbolt\Tags\Test\TestCase\Controller\Metadata;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Tags\TagsPlugin;
use Passbolt\Tags\Test\Factory\TagFactory;

/**
 * @covers \Passbolt\Tags\Controller\Tags\TagsUpdateController
 */
class MetadataTagsUpdateControllerTest extends AppIntegrationTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->enableFeaturePlugin(TagsPlugin::class);
    }

    /**
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testMetadataTagsUpdateController_Success_Personal(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'name' => 'old']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, [
            'passphrase' => 'ada@passbolt.com',
            'privateKey' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private.key'),
        ]);
        $tag = TagFactory::make()
            ->isPersonalFor($resource, $user)
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $user->gpgkey->id])
            ->persist();
        // data to update
        $newMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'name' => 'new']);
        $metadataToUpdate = $this->encryptForUser($newMetadata, $user, [
            'passphrase' => 'ada@passbolt.com',
            'privateKey' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private.key'),
        ]);
        // login
        $this->logInAs($user);

        $tagId = $tag->get('id');
        $this->putJson("/tags/{$tagId}.json?api-version=v2", [
            'metadata' => $metadataToUpdate,
            'metadata_key_id' => $user->gpgkey->id,
            'metadata_key_type' => 'user_key',
            'is_shared' => false,
        ]);

        $this->assertSuccess();
        // Assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayNotHasKey('slug', $response);
        $this->assertSame($metadataToUpdate, $response['metadata']);
        $this->assertSame($user->gpgkey->id, $response['metadata_key_id']);
        $this->assertSame('user_key', $response['metadata_key_type']);
        $this->assertFalse($response['is_shared']);
        // Assert database values
        $tag = TagFactory::firstOrFail(['id' => $tagId]);
        $this->assertSame($metadataToUpdate, $tag->get('metadata'));
        $this->assertSame($user->gpgkey->id, $tag->get('metadata_key_id'));
        $this->assertSame('user_key', $tag->get('metadata_key_type'));
        $this->assertFalse($tag->get('is_shared'));
        $this->assertNull($tag->get('slug'));
    }

    /**
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testMetadataTagsUpdateController_Success_Shared(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'name' => 'old']);
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        // create metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($user)->withServerPrivateKey()->persist();
        $tag = TagFactory::make()
            ->isPersonalFor($resource, $user)
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $metadataKey->get('id')], true)
            ->persist();
        // data to update
        $newMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'name' => 'new']);
        $metadataToUpdate = $this->encryptForMetadataKey($newMetadata);
        // login
        $this->logInAs($user);

        $tagId = $tag->get('id');
        $this->putJson("/tags/{$tagId}.json?api-version=v2", [
            'metadata' => $metadataToUpdate,
            'metadata_key_id' => $metadataKey->get('id'),
            'metadata_key_type' => 'shared_key',
            'is_shared' => true,
        ]);

        $this->assertSuccess();
        // Assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayNotHasKey('slug', $response);
        $this->assertSame($metadataToUpdate, $response['metadata']);
        $this->assertSame($metadataKey->get('id'), $response['metadata_key_id']);
        $this->assertSame('shared_key', $response['metadata_key_type']);
        $this->assertTrue($response['is_shared']);
        // Assert database values
        $tag = TagFactory::firstOrFail(['id' => $tagId]);
        $this->assertSame($metadataToUpdate, $tag->get('metadata'));
        $this->assertSame($metadataKey->get('id'), $tag->get('metadata_key_id'));
        $this->assertSame('shared_key', $tag->get('metadata_key_type'));
        $this->assertTrue($tag->get('is_shared'));
        $this->assertNull($tag->get('slug'));
    }

    /**
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testMetadataTagsUpdateController_Error_V4AndV5BothFieldsAreSent(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'name' => 'old']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, [
            'passphrase' => 'ada@passbolt.com',
            'privateKey' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private.key'),
        ]);
        $tag = TagFactory::make()
            ->isPersonalFor($resource, $user)
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $user->gpgkey->id])
            ->persist();
        // data to update
        $newMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'name' => 'new']);
        $metadataToUpdate = $this->encryptForUser($newMetadata, $user, [
            'passphrase' => 'ada@passbolt.com',
            'privateKey' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private.key'),
        ]);
        // login
        $this->logInAs($user);

        $tagId = $tag->get('id');
        $this->putJson("/tags/{$tagId}.json?api-version=v2", [
            // v4 & v5 mixed request
            'slug' => 'new',
            'metadata' => $metadataToUpdate,
            'metadata_key_id' => $user->gpgkey->id,
            'metadata_key_type' => 'user_key',
            'is_shared' => false,
        ]);

        $this->assertBadRequestError('V4 related fields are not supported for V5');
    }

    /**
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testMetadataTagsUpdateController_Error_InvalidMetadataKeyType(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'name' => 'old']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, [
            'passphrase' => 'ada@passbolt.com',
            'privateKey' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private.key'),
        ]);
        $tag = TagFactory::make()
            ->isPersonalFor($resource, $user)
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $user->gpgkey->id])
            ->persist();
        // data to update
        $newMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'name' => 'new']);
        $metadataToUpdate = $this->encryptForUser($newMetadata, $user, [
            'passphrase' => 'ada@passbolt.com',
            'privateKey' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private.key'),
        ]);
        // login
        $this->logInAs($user);

        $tagId = $tag->get('id');
        $this->putJson("/tags/{$tagId}.json?api-version=v2", [
            'metadata' => $metadataToUpdate,
            'metadata_key_id' => $user->gpgkey->id,
            'metadata_key_type' => 'shared_key',
            'is_shared' => false,
        ]);

        $this->assertBadRequestError('Unable to save the tag');
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayHasKey('metadata_key_id', $response);
        $this->assertArrayHasKey('metadata', $response);
        $this->assertArrayHasKey('metadata_key_exists', $response['metadata_key_id']);
        $this->assertArrayHasKey('isValidEncryptedMetadata', $response['metadata']);
    }

    /**
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testMetadataTagsUpdateController_Error_InvalidEncryptedMetadata(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'name' => 'old']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, [
            'passphrase' => 'ada@passbolt.com',
            'privateKey' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private.key'),
        ]);
        $tag = TagFactory::make()
            ->isPersonalFor($resource, $user)
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $user->gpgkey->id])
            ->persist();
        // data to update
        $newMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'name' => 'new']);
        $metadataToUpdate = $this->encryptForMetadataKey($newMetadata);
        // login
        $this->logInAs($user);

        $tagId = $tag->get('id');
        $this->putJson("/tags/{$tagId}.json?api-version=v2", [
            'metadata' => $metadataToUpdate,
            'metadata_key_id' => $user->gpgkey->id,
            'metadata_key_type' => 'user_key',
            'is_shared' => false,
        ]);

        $this->assertBadRequestError('Unable to save the tag');
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayHasKey('metadata', $response);
        $this->assertArrayHasKey('isValidEncryptedMetadata', $response['metadata']);
    }
}
