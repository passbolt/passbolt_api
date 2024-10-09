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
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Tags\TagsPlugin;
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
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        // prepare metadata
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'name' => 'tag-3']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, [
            'passphrase' => 'ada@passbolt.com',
            'privateKey' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private.key'),
        ]);
        TagFactory::make(['slug' => 'tag_2'])->isPersonalFor($resource, $user)->persist();
        TagFactory::make(['slug' => 'unused'])->persist(); // unused tag, used to make sure it's deleted afterwards

        $this->logInAs($user);
        $data = [
            'tags' => [
                'tag1', // new
                'tag_2', // existing (update)
                [
                    'metadata' => $metadata,
                    'metadata_key_id' => $user->gpgkey->id,
                    'metadata_key_type' => 'user_key',
                    'is_shared' => false,
                ],
            ],
        ];
        $this->postJson("/tags/{$resource->id}.json?api-version=2", $data);

        $this->assertSuccess();
        // assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(3, $response);
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
            }

            // is shared will be present for both
            $this->assertArrayHasKey('is_shared', $item);
        }
        // assert tags are saved properly
        $tags = TagFactory::find()->toArray();
        $this->assertCount(3, $tags);
    }

    public function testMetadataResourcesTagsAddController_Error_InvalidData(): void
    {
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
            ],
        ];
        $this->postJson("/tags/{$resource->id}.json?api-version=2", $data);

        $this->assertError(400);
        // assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(4, $response[0]);
        $this->assertArrayHasKey('_required', $response[0]['metadata']);
        $this->assertArrayHasKey('_required', $response[0]['metadata_key_id']);
        $this->assertArrayHasKey('_required', $response[0]['metadata_key_type']);
        $this->assertArrayHasKey('_required', $response[0]['is_shared']);
        $this->assertCount(4, $response[1]);
        $this->assertArrayHasKey('ascii', $response[1]['metadata']);
        $this->assertArrayHasKey('_required', $response[1]['metadata_key_id']);
        $this->assertArrayHasKey('_required', $response[1]['metadata_key_type']);
        $this->assertArrayHasKey('_required', $response[1]['is_shared']);
        $this->assertCount(3, $response[2]);
        $this->assertArrayHasKey('ascii', $response[2]['metadata']);
        $this->assertArrayHasKey('uuid', $response[2]['metadata_key_id']);
        $this->assertArrayHasKey('boolean', $response[2]['is_shared']);
    }

    public function testMetadataResourcesTagsAddController_Error_V4AndV5BothFieldsAreSent(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        // prepare metadata
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'name' => 'tag-3']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, [
            'passphrase' => 'ada@passbolt.com',
            'privateKey' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private.key'),
        ]);
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
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_TAG_METADATA', 'name' => 'tag-3']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, [
            'passphrase' => 'ada@passbolt.com',
            'privateKey' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private.key'),
        ]);

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
}
