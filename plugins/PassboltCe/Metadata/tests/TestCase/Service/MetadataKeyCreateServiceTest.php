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

namespace Passbolt\Metadata\Test\TestCase\Service;

use App\Error\Exception\CustomValidationException;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;
use Passbolt\Metadata\Model\Dto\MetadataKeyCreateDto;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Service\MetadataKeyCreateService;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Metadata\Service\MetadataKeyCreateService
 */
class MetadataKeyCreateServiceTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    /**
     * @var MetadataKeyCreateService|null
     */
    private ?MetadataKeyCreateService $service = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new MetadataKeyCreateService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testMetadataKeyCreateService_Success(): void
    {
        $keyInfo = $this->getUserKeyInfo();
        $gpgkey = GpgkeyFactory::make(['armored_key' => $keyInfo['armored_key'], 'fingerprint' => $keyInfo['fingerprint']]);
        $user = UserFactory::make()
            ->with('Gpgkeys', $gpgkey)
            ->admin()
            ->active()
            ->persist();
        $uac = $this->makeUac($user);
        $dummyKey = $this->getMetadataKeyInfo();

        $dto = MetadataKeyCreateDto::fromArray([
            'armored_key' => $dummyKey['public_key'],
            'fingerprint' => $dummyKey['fingerprint'],
            'metadata_private_keys' => [
                [
                    'user_id' => null, // server key
                    'data' => $this->getEncryptedMetadataPrivateKeyForServerKey(),
                ],
                [
                    'user_id' => $uac->getId(),
                    'data' => $this->getEncryptedMetadataPrivateKeyForUser(),
                ],
            ],
        ]);
        $result = $this->service->create($uac, $dto);

        $this->assertInstanceOf(MetadataKey::class, $result);
        $metadataKeys = MetadataKeyFactory::find()->all()->toArray();
        $this->assertCount(1, $metadataKeys);
        $metadataPrivateKeys = MetadataPrivateKeyFactory::find()->all()->toArray();
        $this->assertCount(2, $metadataPrivateKeys);
    }

    public static function invalidMetadataKeyDataProvider(): array
    {
        $dummyKey = self::getMetadataKeyInfo();
        $makiKey = self::getUserKeyInfo();
        $expiredKey = self::getExpiredKeyInfo();
        $msgForServer = self::getEncryptedMetadataPrivateKeyForServerKey();
        $invalidAlgKey = self::getInvalidAlgKeyInfo();

        return [
            [
                'data (invalid types)' => [
                    'armored_key' => 'bar-foo',
                    'fingerprint' => '🔥🔥🔥',
                    'metadata_private_keys' => [
                        [
                            'user_id' => 'foo-bar',
                            'data' => 'some data',
                        ],
                        [
                            'user_id' => 1230,
                            'data' => '*()_+(!#$%',
                        ],
                    ],
                ],
                'expected errors paths' => [
                    'armored_key.isParsableArmoredPublicKey',
                    'fingerprint.alphaNumeric',
                    'metadata_private_keys.{n}.user_id.uuid',
                    'metadata_private_keys.{n}.data.isValidOpenPGPMessage',
                ],
            ],
            [
                'data (expired armored key)' => [
                    'armored_key' => $expiredKey['armored_key'],
                    'fingerprint' => $expiredKey['fingerprint'],
                    'metadata_private_keys' => [
                        [
                            'user_id' => UuidFactory::uuid(),
                            'data' => self::getDummyPrivateKeyOpenPGPMessage(),
                        ],
                        [
                            'user_id' => null,
                            'data' => self::getDummyPrivateKeyOpenPGPMessage(),
                        ],
                    ],
                ],
                'expected errors paths' => ['armored_key.isPublicKeyValidStrict'],
            ],
            [
                'data (more than one user_id null)' => [
                    'armored_key' => $dummyKey['public_key'],
                    'fingerprint' => $dummyKey['fingerprint'],
                    'metadata_private_keys' => [
                        [
                            'user_id' => null,
                            'data' => $msgForServer,
                        ],
                        [
                            'user_id' => null,
                            'data' => $msgForServer,
                        ],
                    ],
                ],
                'expected errors paths' => ['metadata_private_keys.{n}.user_id._isUnique'],
            ],
            [
                'data (more than one invalid uuid in user_id)' => [
                    'armored_key' => $dummyKey['public_key'],
                    'fingerprint' => $dummyKey['fingerprint'],
                    'metadata_private_keys' => [
                        [
                            'user_id' => 'foo-bar',
                            'data' => self::getDummyPrivateKeyOpenPGPMessage(),
                        ],
                        [
                            'user_id' => '🔥🔥🔥',
                            'data' => self::getDummyPrivateKeyOpenPGPMessage(),
                        ],
                        [
                            'user_id' => 12345,
                            'data' => self::getDummyPrivateKeyOpenPGPMessage(),
                        ],
                    ],
                ],
                'expected errors paths' => ['metadata_private_keys.{n}.user_id.uuid'],
            ],
            [
                'data (data is not encrypted with the server key if user_id if set to null)' => [
                    'armored_key' => $dummyKey['public_key'],
                    'fingerprint' => $dummyKey['fingerprint'],
                    'metadata_private_keys' => [
                        [
                            'user_id' => null,
                            'data' => self::getDummyPrivateKeyOpenPGPMessage(),
                        ],
                    ],
                ],
                'expected errors paths' => ['metadata_private_keys.{n}.data.isValidEncryptedMetadataPrivateKey'],
            ],
            [
                'data (fingerprint not matching public key)' => [
                    'armored_key' => $makiKey['armored_key'],
                    'fingerprint' => $dummyKey['fingerprint'],
                    'metadata_private_keys' => [
                        [
                            'user_id' => null,
                            'data' => $msgForServer,
                        ],
                    ],
                ],
                'expected errors paths' => [
                    'fingerprint.isMatchingKeyFingerprint',
                ],
            ],
            [
                'data (valid algorithm for public key)' => [
                    'armored_key' => $invalidAlgKey['armored_key'],
                    'fingerprint' => $invalidAlgKey['fingerprint'],
                    'metadata_private_keys' => [
                        [
                            'user_id' => null,
                            'data' => $msgForServer,
                        ],
                    ],
                ],
                'expected errors paths' => [
                    'armored_key.isPublicKeyValidStrict',
                ],
            ],
        ];
    }

    /**
     * @dataProvider invalidMetadataKeyDataProvider
     */
    public function testMetadataKeyCreateService_Error_Validation(array $data, array $expectedErrors): void
    {
        $user = UserFactory::make()->admin()->active()->persist();
        $uac = $this->makeUac($user);

        try {
            $this->service->create($uac, MetadataKeyCreateDto::fromArray($data));
        } catch (CustomValidationException $e) {
            // Use assertions (instead of expectException) in catch to assert errors thrown
            $this->assertStringContainsString('The metadata key could not be saved', $e->getMessage());

            $errors = $e->getErrors();
            foreach ($expectedErrors as $expectedErrorPath) {
                $this->assertTrue(Hash::check($errors, $expectedErrorPath));
            }
        }
    }

    public function testMetadataKeyCreateService_Error_UserDeleted(): void
    {
        $keyInfo = $this->getUserKeyInfo();
        $gpgkey = GpgkeyFactory::make(['armored_key' => $keyInfo['armored_key'], 'fingerprint' => $keyInfo['fingerprint']]);
        $user = UserFactory::make()
            ->with('Gpgkeys', $gpgkey)
            ->admin()
            ->deleted()
            ->persist();
        $uac = $this->makeUac($user);
        $dummyKey = $this->getMetadataKeyInfo();

        $dto = MetadataKeyCreateDto::fromArray([
            'armored_key' => $dummyKey['public_key'],
            'fingerprint' => $dummyKey['fingerprint'],
            'metadata_private_keys' => [
                [
                    'user_id' => null, // server key
                    'data' => $this->getEncryptedMetadataPrivateKeyForUser(),
                ],
                [
                    'user_id' => $uac->getId(),
                    'data' => $this->getEncryptedMetadataPrivateKeyForUser(),
                ],
            ],
        ]);

        $this->expectException(CustomValidationException::class);

        $this->service->create($uac, $dto);
    }

    public function testMetadataKeyCreateService_Error_MoreThanOnePrivateKeysPerUser(): void
    {
        $keyInfo = $this->getUserKeyInfo();
        $gpgkey = GpgkeyFactory::make(['armored_key' => $keyInfo['armored_key'], 'fingerprint' => $keyInfo['fingerprint']]);
        $user = UserFactory::make()
            ->with('Gpgkeys', $gpgkey)
            ->admin()
            ->active()
            ->persist();
        $uac = $this->makeUac($user);
        $dummyKey = $this->getMetadataKeyInfo();

        $dto = MetadataKeyCreateDto::fromArray([
            'armored_key' => $dummyKey['public_key'],
            'fingerprint' => $dummyKey['fingerprint'],
            'metadata_private_keys' => [
                [
                    'user_id' => null, // server key
                    'data' => $this->getEncryptedMetadataPrivateKeyForServerKey(),
                ],
                [
                    'user_id' => $uac->getId(),
                    'data' => $this->getEncryptedMetadataPrivateKeyForUser(),
                ],
                [
                    // Same user, different encrypted message
                    'user_id' => $uac->getId(),
                    'data' => $this->getEncryptedMetadataPrivateKeyFoUserDifferent(),
                ],
            ],
        ]);

        try {
            $this->service->create($uac, $dto);
        } catch (CustomValidationException $e) {
            $errors = $e->getErrors();

            $this->assertTrue(Hash::check($errors, 'metadata_private_keys.{n}.user_id._isUnique'));
        }
    }

    public function testMetadataKeyCreateService_Error_TwoActiveKeyAlreadyPresent(): void
    {
        $keyInfo = $this->getUserKeyInfo();
        $gpgkey = GpgkeyFactory::make(['armored_key' => $keyInfo['armored_key'], 'fingerprint' => $keyInfo['fingerprint']]);
        $user = UserFactory::make()
            ->with('Gpgkeys', $gpgkey)
            ->admin()
            ->active()
            ->persist();
        $uac = $this->makeUac($user);
        $dummyKey = [
            'fingerprint' => 'A754860C3ADE5AB04599025ED3F1FE4BE61D7009',
            'armored_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'betty_public.key'),
        ];
        // already exists
        MetadataKeyFactory::make(2)->withCreatorAndModifier()->persist();
        MetadataKeyFactory::make()->withCreatorAndModifier()->deleted()->persist();
        MetadataKeyFactory::make()->withCreatorAndModifier()->expired()->persist();

        $dto = MetadataKeyCreateDto::fromArray([
            'armored_key' => $dummyKey['armored_key'],
            'fingerprint' => $dummyKey['fingerprint'],
            'metadata_private_keys' => [
                [
                    'user_id' => null, // server key
                    'data' => $this->getEncryptedMetadataPrivateKeyForServerKey(),
                ],
                [
                    'user_id' => $uac->getId(),
                    'data' => $this->getEncryptedMetadataPrivateKeyForUser(),
                ],
            ],
        ]);

        try {
            $this->service->create($uac, $dto);
        } catch (CustomValidationException $e) {
            $errors = $e->getErrors();

            $this->assertTrue(Hash::check($errors, 'fingerprint.maxNoOfActiveKeys'));
        }
    }
}
