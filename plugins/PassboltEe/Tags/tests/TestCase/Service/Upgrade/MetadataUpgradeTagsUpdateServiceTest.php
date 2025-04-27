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
 * @since         5.1.0
 */

namespace Passbolt\Tags\Test\TestCase\Service\Upgrade;

use App\Error\Exception\CustomValidationException;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Exception;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Tags\Model\Dto\MetadataTagDto;
use Passbolt\Tags\Service\Upgrade\MetadataUpgradeTagsUpdateService;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;

/**
 * @covers \Passbolt\Tags\Service\Upgrade\MetadataUpgradeTagsUpdateService
 */
class MetadataUpgradeTagsUpdateServiceTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    private ?MetadataUpgradeTagsUpdateService $service = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new MetadataUpgradeTagsUpdateService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testMetadataUpgradeTagsUpdateService_Success(): void
    {
        // create metadata keys
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($activeMetadataKey)->persist();

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())->persist();

        /** @var \Passbolt\Tags\Model\Entity\ResourcesTag $resourcesTags1 */
        $resourcesTags1 = ResourcesTagFactory::make()->with('Tags')->with('Users', $user)->persist();
        /** @var \Passbolt\Tags\Model\Entity\ResourcesTag $resourcesTags2 */
        $resourcesTags2 = ResourcesTagFactory::make()->with('Tags', TagFactory::make()->isShared())->persist();

        $tagPersonal = $resourcesTags1->tag;
        $tagShared = $resourcesTags2->tag;
        $userKeyId = $user->gpgkey->id;

        $uac = $this->mockAdminAccessControl();
        $metadataForT1 = $this->encryptForUser($tagPersonal->slug, $user, $this->getAdaNoPassphraseKeyInfo());
        $tagDto = MetadataTagDto::fromArray($tagShared->toArray());
        $clearTextMetadata = json_encode($tagDto->getClearTextMetadata());
        $metadataForT2 = $this->encryptForMetadataKey($clearTextMetadata);
        $data = [
            [
                'id' => $tagPersonal->get('id'),
                'metadata_key_id' => $userKeyId,
                'metadata_key_type' => MetadataKey::TYPE_USER_KEY,
                'metadata' => $metadataForT1,
            ],
            [
                'id' => $tagShared->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataForT2,
            ],
        ];
        $this->service->updateMany($uac, $data);

        $updatedTag1 = TagFactory::get($tagPersonal->get('id'));
        $this->assertSame($userKeyId, $updatedTag1->get('metadata_key_id'));
        $this->assertSame($metadataForT1, $updatedTag1->get('metadata'));
        $this->assertNull($updatedTag1->get('slug'));
        $updatedTag2 = TagFactory::get($tagShared->get('id'));
        $this->assertSame($activeMetadataKey->get('id'), $updatedTag2->get('metadata_key_id'));
        $this->assertSame($metadataForT2, $updatedTag2->get('metadata'));
        $this->assertNull($updatedTag2->get('slug'));
    }

    public function testMetadataUpgradeTagsUpdateService_Error_EmptyData(): void
    {
        $uac = $this->mockAdminAccessControl();
        $this->expectException(BadRequestException::class);
        $this->service->updateMany($uac, []);
    }

    public function testMetadataUpgradeTagsUpdateService_Error_DataIsNotAnArray(): void
    {
        $uac = $this->mockAdminAccessControl();
        $this->expectException(BadRequestException::class);
        $this->service->updateMany($uac, ['foo', 'bar', 'baz']);
    }

    public function MetadataUpgradeResourcesUpdateServiceInvalidMetadataEncryptedMessage(): array
    {
        return [
            ['🔥'],
            ['foo-bar'],
        ];
    }

    /**
     * @dataProvider MetadataUpgradeResourcesUpdateServiceInvalidMetadataEncryptedMessage
     * @param mixed $metadata Invalid metadata.
     * @return void
     */
    public function testMetadataUpgradeTagsUpdateService_Error_InvalidMetadataEncryptedMessage($metadata): void
    {
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $tag = TagFactory::make()->persist();
        $uac = $this->mockAdminAccessControl();
        $data = [
            [
                'id' => $tag->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadata,
            ],
        ];

        try {
            $this->service->updateMany($uac, $data);
        } catch (CustomValidationException $e) {
            $errors = $e->getErrors();
            $this->assertCount(1, $errors);
            $this->assertArrayHasKey('isMetadataParsable', $errors[0]['metadata']);
        }
    }

    public function testMetadataUpgradeTagsUpdateService_Error_TagNotFound(): void
    {
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $uac = $this->mockAdminAccessControl();
        $data = [
            [
                'id' => UuidFactory::uuid(),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => 'foo',
            ],
        ];

        $this->expectException(NotFoundException::class);
        $this->service->updateMany($uac, $data);
    }

    public function testMetadataUpgradeTagsUpdateService_Error_MetadataKeyIsExpired(): void
    {
        $uac = $this->mockAdminAccessControl();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->persist();
        $tag = TagFactory::make()->persist();

        try {
            $data = [
                [
                    'id' => $tag->get('id'),
                    'metadata_key_id' => $expiredMetadataKey->get('id'),
                    'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                    'metadata' => $this->encryptForMetadataKey(json_encode([])),
                ],
            ];
            $this->service->updateMany($uac, $data);
        } catch (Exception $e) {
            $this->assertInstanceOf(CustomValidationException::class, $e);
            $errors = $e->getErrors();
            $this->assertArrayHasKey('isMetadataKeyNotExpired', $errors[0]['metadata_key_id']);
        }
    }

    public function testMetadataUpgradeTagsUpdateService_Error_MetadataKeyIsDeleted(): void
    {
        $uac = $this->mockAdminAccessControl();
        // create metadata keys
        $deletedMetadataKey = MetadataKeyFactory::make()->deleted()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($deletedMetadataKey)->persist();
        $tag = TagFactory::make()->persist();

        try {
            $data = [
                [
                    'id' => $tag->get('id'),
                    'metadata_key_id' => $deletedMetadataKey->get('id'),
                    'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                    'metadata' => $this->encryptForMetadataKey(json_encode([])),
                ],
            ];
            $this->service->updateMany($uac, $data);
        } catch (Exception $e) {
            $this->assertInstanceOf(CustomValidationException::class, $e);
            $errors = $e->getErrors();
            $this->assertSame(
                ['metadata_key_exists' => 'The metadata key does not exist.'],
                $errors[0]['metadata_key_id']
            );
        }
    }

    public function testMetadataUpgradeTagsUpdateService_Tag_Already_v5(): void
    {
        // create metadata keys
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($activeMetadataKey)->persist();
        $tag = TagFactory::make()->v5Fields(['metadata' => 'FOO'])->persist();

        $uac = $this->mockAdminAccessControl();
        $metadataForF1 = $this->encryptForMetadataKey(json_encode([]));
        $data = [
            [
                'id' => $tag->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataForF1,
            ],

        ];
        try {
            $this->service->updateMany($uac, $data);
        } catch (NotFoundException $exception) {
            $this->assertSame("Entity {$tag->get('id')} not found.", $exception->getMessage());
        }
    }

    public function testMetadataUpgradeTagsUpdateService_v4_Fields_Set_To_Null(): void
    {
        // create metadata keys
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($activeMetadataKey)->persist();
        $tag = TagFactory::make([
            'slug' => 'foo',
        ])->isShared()->persist();

        $uac = $this->mockAdminAccessControl();
        $metadataForT1 = $this->encryptForMetadataKey(json_encode([]));
        $data = [
            [
                'id' => $tag->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataForT1,
            ],
        ];
        $this->service->updateMany($uac, $data);
        $tagUpdated = TagFactory::firstOrFail();
        $this->assertNull($tagUpdated->slug);
    }
}
