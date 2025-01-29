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
use App\Utility\UuidFactory;
use Cake\I18n\FrozenTime;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Tags\TagsPlugin;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;

/**
 * @covers \Passbolt\Tags\Controller\Tags\ResourcesTagsAddController
 */
class MetadataResourcesTagsAddControllerTest extends AppIntegrationTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->enableFeaturePlugin(TagsPlugin::class);
    }

    public function testMetadataResourcesTagsAddController_Success(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        // prepare metadata
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'slug' => 'tag-3']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        TagFactory::make(['slug' => 'tag_2'])->isPersonalFor($resource, $user)->persist();
        TagFactory::make(['slug' => 'unused'])->persist(); // unused tag, used to make sure it's deleted afterwards

        $this->logInAs($user);
        $data = [
            'tags' => [
                'tag1', // new
                'tag_2', // existing (update)
                [
                    // new
                    'metadata' => $metadata,
                    'metadata_key_id' => $user->gpgkey->id,
                    'metadata_key_type' => MetadataKey::TYPE_USER_KEY,
                    'is_shared' => false,
                ],
                [
                    // metadata key id is null
                    'metadata' => $metadata,
                    'metadata_key_id' => null,
                    'metadata_key_type' => MetadataKey::TYPE_USER_KEY,
                    'is_shared' => false,
                ],
            ],
        ];
        $this->postJson("/tags/{$resource->id}.json?api-version=2", $data);

        $this->assertSuccess();
        // assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(4, $response);
        foreach ($response as $item) {
            if (array_key_exists('slug', $item)) {
                // v4
                $this->assertArrayNotHasKey('metadata', $item);
                $this->assertArrayNotHasKey('metadata_key_id', $item);
                $this->assertArrayNotHasKey('metadata_key_type', $item);
                $this->assertArrayHasKey('slug', $item);
            } else {
                // v5
                $this->assertArrayNotHasKey('slug', $item);
                $this->assertArrayHasKey('metadata', $item);
                $this->assertArrayHasKey('metadata_key_id', $item);
                $this->assertArrayHasKey('metadata_key_type', $item);
                $this->assertSame($user->gpgkey->id, $item['metadata_key_id']);
            }

            // is shared will be present for both
            $this->assertArrayHasKey('is_shared', $item);
        }
        // assert tags are saved properly
        $tags = TagFactory::find()->toArray();
        $this->assertCount(4, $tags);
    }

    public function testMetadataResourcesTagsAddController_Success_ReuseExistingSharedTag(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$ada])->persist();
        // Existing tag created by another user
        $betty = UserFactory::make()->user()->active()->persist();
        $resource2 = ResourceFactory::make()->withPermissionsFor([$betty])->persist();
        // Create a shared tag
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        $myFavTagClearText = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'slug' => 'my-fav']);
        $myFavMetadata = $this->encryptForMetadataKey($myFavTagClearText);
        $myfavTag = TagFactory::make()
            ->isSharedFor($resource2)
            ->v5Fields(['metadata' => $myFavMetadata, 'metadata_key_id' => $metadataKey->id], true)
            ->persist();
        // Create a personal tag that is already linked, to test it's not duplicated
        $marketingClearText = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'slug' => 'marketing']);
        $marketingMetadata = $this->encryptForUser($marketingClearText, $ada, $this->getAdaNoPassphraseKeyInfo());
        $marketingTag = TagFactory::make()
            ->isPersonalFor($resource, $ada)
            ->v5Fields(['metadata' => $marketingMetadata, 'metadata_key_id' => $ada->gpgkey->id])
            ->persist();
        // A V4 tag
        TagFactory::make(['slug' => 'imv4'])->isPersonalFor($resource2, $betty)->persist();
        // Prepare metadata for the request
        $starredTagClearText = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'slug' => 'starred']);
        $starredMetadata = $this->encryptForUser($starredTagClearText, $ada, $this->getAdaNoPassphraseKeyInfo());

        $this->logInAs($ada);
        $data = [
            'tags' => [
                'imv4', // existing tag (reuse)
                [
                    // new
                    'metadata' => $starredMetadata,
                    'metadata_key_id' => $ada->gpgkey->id,
                    'metadata_key_type' => MetadataKey::TYPE_USER_KEY,
                    'is_shared' => false,
                ],
                [
                    // existing tag (reuse)
                    'id' => $myfavTag->get('id'),
                ],
                [
                    'id' => $marketingTag->get('id'),
                ],
            ],
        ];
        $this->postJson("/tags/{$resource->id}.json?api-version=2", $data);

        $this->assertSuccess();
        // assert response
        $this->assertCount(4, $this->getResponseBodyAsArray());
        // assert database entries
        $this->assertCount(4, TagFactory::find()->toArray());
        $resourceTags = ResourcesTagFactory::find()->where(['resource_id' => $resource->id])->toArray();
        $this->assertCount(4, $resourceTags);
        /** @var \Passbolt\Tags\Model\Entity\ResourcesTag $resourceTag */
        foreach ($resourceTags as $resourceTag) {
            if (is_null($resourceTag->user_id)) {
                $this->assertSame($myfavTag->get('id'), $resourceTag->tag_id);
                $this->assertNull($resourceTag->user_id);
            } else {
                $this->assertSame($ada->id, $resourceTag->user_id);
            }
        }
    }

    public function testMetadataResourcesTagsAddController_Success_UnlinkV5TagFromResource(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$ada])->persist();
        // Create two personal tags
        // tag 1
        $myFavTagClearText = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'slug' => 'my-fav']);
        $myFavMetadata = $this->encryptForUser($myFavTagClearText, $ada, $this->getAdaNoPassphraseKeyInfo());
        $tag1 = TagFactory::make()
            ->isPersonalFor($resource, $ada)
            ->v5Fields(['metadata' => $myFavMetadata, 'metadata_key_id' => $ada->gpgkey->id])
            ->persist();
        // tag 2
        $marketingClearText = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'slug' => 'marketing']);
        $marketingMetadata = $this->encryptForUser($marketingClearText, $ada, $this->getAdaNoPassphraseKeyInfo());
        TagFactory::make()
            ->isPersonalFor($resource, $ada)
            ->v5Fields(['metadata' => $marketingMetadata, 'metadata_key_id' => $ada->gpgkey->id])
            ->persist();

        $this->logInAs($ada);
        $data = [
            'tags' => [
                [
                    'id' => $tag1->get('id'),
                ],
                // note: not sending $tag2 id because we want to unlink it from the resource
            ],
        ];
        $this->postJson("/tags/{$resource->id}.json?api-version=2", $data);

        $this->assertSuccess();
        // assert response
        $this->assertCount(1, $this->getResponseBodyAsArray());
        // assert database entries
        $tags = TagFactory::find()->toArray();
        $this->assertCount(1, $tags);
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        $tag = $tags[0];
        $this->assertSame($tag1->get('id'), $tag->get('id'));
        $resourceTags = ResourcesTagFactory::find()->where(['resource_id' => $resource->id])->toArray();
        $this->assertCount(1, $resourceTags);
        /** @var \Passbolt\Tags\Model\Entity\ResourcesTag $resourceTag */
        $resourceTag = $resourceTags[0];
        $this->assertSame($tag1->get('id'), $resourceTag->get('tag_id'));
        $this->assertSame($ada->get('id'), $resourceTag->get('user_id'));
    }

    public function testMetadataResourcesTagsAddController_Error_InvalidData(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();

        $this->logInAs($user);
        $data = [
            'tags' => [
                ['foo' => 'bar'],
                ['metadata' => new \stdClass()],
                [
                    'metadata' => '🔥🔥🔥',
                    'metadata_key_id' => 'invalid value',
                    'metadata_key_type' => 'my_secret_key',
                    'is_shared' => UuidFactory::uuid(),
                ],
                [
                    'id' => 'invalid-uuid',
                ],
                [
                    'id' => UuidFactory::uuid(), // tag does not exists
                ],
            ],
        ];
        $this->postJson("/tags/{$resource->id}.json?api-version=2", $data);

        $this->assertError(400);
        // assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(5, $response);
        // validation field 1
        $this->assertCount(3, $response[0]);
        $this->assertArrayHasKey('_required', $response[0]['metadata']);
        $this->assertArrayHasKey('_required', $response[0]['metadata_key_type']);
        $this->assertArrayHasKey('_required', $response[0]['is_shared']);
        // validation field 2
        $this->assertCount(3, $response[1]);
        $this->assertArrayHasKey('ascii', $response[1]['metadata']);
        $this->assertArrayHasKey('_required', $response[1]['metadata_key_type']);
        $this->assertArrayHasKey('_required', $response[1]['is_shared']);
        // validation field 3
        $this->assertCount(3, $response[2]);
        $this->assertArrayHasKey('ascii', $response[2]['metadata']);
        $this->assertArrayHasKey('uuid', $response[2]['metadata_key_id']);
        $this->assertArrayHasKey('boolean', $response[2]['is_shared']);
        // validation field 4
        $this->assertCount(1, $response[3]);
        $this->assertArrayHasKey('uuid', $response[3]['id']);
        // validation field 5
        $this->assertCount(1, $response[4]);
        $this->assertArrayHasKey('tagExists', $response[4]['id']);
    }

    public function testMetadataResourcesTagsAddController_Error_V4AndV5BothFieldsAreSent(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        // prepare metadata
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'slug' => 'tag-3']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        TagFactory::make(['slug' => 'tag_2'])->isPersonalFor($resource, $user)->persist();
        TagFactory::make(['slug' => 'unused'])->persist(); // unused tag, used to make sure it's deleted afterwards

        $this->logInAs($user);
        $data = [
            'tags' => [
                'tag1', // new
                'tag_2', // existing (update)
                [
                    'slug' => 'tag-3',
                    'metadata' => $metadata,
                    'metadata_key_id' => $user->gpgkey->id,
                    'metadata_key_type' => 'user_key',
                    'is_shared' => false,
                ],
            ],
        ];
        $this->postJson("/tags/{$resource->id}.json?api-version=2", $data);

        $this->assertBadRequestError('V4 related fields are not supported for V5');
    }

    public function testMetadataResourcesTagsAddController_Error_InsufficientResourcePermission(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $user2 = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user2])->persist();
        // prepare metadata
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'slug' => 'tag-3']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());

        $this->logInAs($user);
        $data = [
            'tags' => [
                [
                    'metadata' => $metadata,
                    'metadata_key_id' => $user->gpgkey->id,
                    'metadata_key_type' => 'user_key',
                    'is_shared' => false,
                ],
            ],
        ];
        $this->postJson("/tags/{$resource->id}.json?api-version=2", $data);

        $this->assertNotFoundError('The resource does not exist');
    }

    /**
     * Case: Id is specified and the tag is personal and the tag was not created by the current user, e.g. the user doesn't have right to view that tag
     *
     * @return void
     * @throws \Exception
     */
    public function testMetadataResourcesTagsAddController_Error_InsufficientPersonalTagPermission(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$ada])->persist();
        $myFavTagClearText = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'slug' => 'my-fav']);
        $myFavMetadata = $this->encryptForUser($myFavTagClearText, $ada, $this->getAdaNoPassphraseKeyInfo());
        $tag = TagFactory::make()
            ->isPersonalFor($resource, $ada)
            ->v5Fields(['metadata' => $myFavMetadata, 'metadata_key_id' => $ada->gpgkey->id])
            ->persist();

        $this->logInAsUser(); // Login with different user
        $data = [
            'tags' => [
                [
                    'id' => $tag->get('id'),
                ],
            ],
        ];
        $this->postJson("/tags/{$resource->id}.json?api-version=2", $data);

        $this->assertError(400);
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayHasKey('tagExists', $response[0]['id']);
    }

    public function testMetadataResourcesTagsAddController_Error_TagIsSharedButNotEncryptedWithSharedKey(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$ada])->persist();
        // Create a shared tag
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'slug' => 'my-fav']);
        // note: encrypt metadata using user key instead of shared metadata key
        $metadata = $this->encryptForUser($clearTextMetadata, $ada, $this->getAdaNoPassphraseKeyInfo());

        $this->logInAs($ada);
        $data = [
            'tags' => [
                [
                    'metadata' => $metadata,
                    'metadata_key_id' => $metadataKey->id,
                    'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                    'is_shared' => true,
                ],
            ],
        ];
        $this->postJson("/tags/{$resource->id}.json?api-version=2", $data);

        $this->assertError(400);
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(1, $response['tags']);
        $this->assertArrayHasKey('metadata', $response['tags'][0]);
        $this->assertArrayHasKey('isValidEncryptedMetadata', $response['tags'][0]['metadata']);
    }

    public function testMetadataResourcesTagsAddController_Error_PersonalTagNotEncryptedUsingUserKey(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$ada])->persist();
        // Create a shared tag
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'slug' => 'my-fav']);
        // note: encrypt metadata using metadata key
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);

        $this->logInAs($ada);
        $data = [
            'tags' => [
                [
                    'metadata' => $metadata,
                    'metadata_key_id' => $ada->gpgkey->id,
                    'metadata_key_type' => MetadataKey::TYPE_USER_KEY,
                    'is_shared' => false,
                ],
            ],
        ];
        $this->postJson("/tags/{$resource->id}.json?api-version=2", $data);

        $this->assertError(400);
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(1, $response['tags']);
        $this->assertArrayHasKey('metadata', $response['tags'][0]);
        $this->assertArrayHasKey('isValidEncryptedMetadata', $response['tags'][0]['metadata']);
    }

    public function testMetadataResourcesTagsAddController_Error_AllowCreationOfV5TagIsDisabled(): void
    {
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$ada])->persist();
        // Create a shared tag
        MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'slug' => 'my-fav']);
        // note: encrypt metadata using metadata key
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);

        $this->logInAs($ada);
        $data = [
            'tags' => [
                [
                    'metadata' => $metadata,
                    'metadata_key_id' => $ada->gpgkey->id,
                    'metadata_key_type' => MetadataKey::TYPE_USER_KEY,
                    'is_shared' => false,
                ],
            ],
        ];
        $this->postJson("/tags/{$resource->id}.json?api-version=2", $data);

        $this->assertBadRequestError('Tag creation\/modification with encrypted metadata not allowed');
    }

    public function testMetadataResourcesTagsAddController_Error_AllowCreationOfV4TagIsDisabled(): void
    {
        MetadataTypesSettingsFactory::make()->v6()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$ada])->persist();

        $this->logInAs($ada);
        $this->postJson("/tags/{$resource->id}.json?api-version=2", [
            'tags' => ['test-tag'],
        ]);

        $this->assertBadRequestError('Tag creation\/modification with cleartext metadata not allowed');
    }

    public function testMetadataResourcesTagsAddController_Error_PersonalKeysDisabled(): void
    {
        MetadataKeysSettingsFactory::make()->disableUsageOfPersonalKeys()->persist();
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$ada])->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'slug' => 'my-fav']);
        $metadata = $this->encryptForUser($clearTextMetadata, $ada, $this->getAdaNoPassphraseKeyInfo());

        $this->logInAs($ada);
        $data = [
            'tags' => [
                [
                    'metadata' => $metadata,
                    'metadata_key_id' => $ada->gpgkey->id,
                    'metadata_key_type' => MetadataKey::TYPE_USER_KEY,
                    'is_shared' => false,
                ],
            ],
        ];
        $this->postJson("/tags/{$resource->id}.json?api-version=2", $data);

        $this->assertError(400);
        $this->assertResponseContains('isMetadataKeyTypeAllowedBySettings');
    }

    public function metadataResourcesTagsAddControllerErrorDeletedOrExpiredValuesProvider(): array
    {
        return [
            [
                'input' => ['expired' => FrozenTime::yesterday()],
                'expected response' => 'isMetadataKeyNotExpired',
            ],
            [
                'input' => ['deleted' => FrozenTime::now()],
                'expected response' => 'metadata_key_exists',
            ],
        ];
    }

    /**
     * @param array $fields Fields to set.
     * @param string $expectedResponse Expected response.
     * @return void
     * @dataProvider metadataResourcesTagsAddControllerErrorDeletedOrExpiredValuesProvider
     */
    public function testMetadataResourcesTagsAddController_Error_DeletedOrExpiredKeyNotAllowed(array $fields, string $expectedResponse): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$ada])->persist();
        // Create a shared tag
        $metadataKey = MetadataKeyFactory::make($fields)->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'slug' => 'my-fav']);
        // note: encrypt metadata using user key instead of shared metadata key
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);

        $this->logInAs($ada);
        $data = [
            'tags' => [
                [
                    'metadata' => $metadata,
                    'metadata_key_id' => $metadataKey->get('id'),
                    'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                    'is_shared' => true,
                ],
            ],
        ];
        $this->postJson("/tags/{$resource->id}.json?api-version=2", $data);

        $this->assertError(400);
        $this->assertResponseContains($expectedResponse);
    }
}
