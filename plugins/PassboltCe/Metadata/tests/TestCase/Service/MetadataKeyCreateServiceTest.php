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
use Passbolt\Metadata\Model\Dto\MetadataKeyDto;
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
        $keyInfo = $this->getMakiKeyInfo();
        $gpgkey = GpgkeyFactory::make(['armored_key' => $keyInfo['armored_key'], 'fingerprint' => $keyInfo['fingerprint']]);
        $user = UserFactory::make()
            ->with('Gpgkeys', $gpgkey)
            ->admin()
            ->active()
            ->persist();
        $uac = $this->makeUac($user);
        $dummyKey = MetadataKeyFactory::getDummyKeyInfo();

        $dto = MetadataKeyDto::fromArray([
            'armored_key' => $dummyKey['armored_key'],
            'fingerprint' => $dummyKey['fingerprint'], // any random valid fingerprint
            'metadata_private_keys' => [
                [
                    'user_id' => null, // server key
                    'data' => $this->getEncryptedMessageForMakiAndServerKey(),
                ],
                [
                    'user_id' => $uac->getId(),
                    'data' => $this->getEncryptedMessageForMakiAndServerKey(),
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

    public function testMetadataKeyCreateService_Error_Validation(): void
    {
        $user = UserFactory::make()->admin()->active()->persist();
        $uac = $this->makeUac($user);

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('The metadata key could not be saved');

        $dto = MetadataKeyDto::fromArray([
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
        ]);
        $this->service->create($uac, $dto);
    }

    public function testMetadataKeyCreateService_Error_UserDeleted(): void
    {
        $keyInfo = $this->getMakiKeyInfo();
        $gpgkey = GpgkeyFactory::make(['armored_key' => $keyInfo['armored_key'], 'fingerprint' => $keyInfo['fingerprint']]);
        $user = UserFactory::make()
            ->with('Gpgkeys', $gpgkey)
            ->admin()
            ->deleted()
            ->persist();
        $uac = $this->makeUac($user);
        $dummyKey = MetadataKeyFactory::getDummyKeyInfo();

        $dto = MetadataKeyDto::fromArray([
            'armored_key' => $dummyKey['armored_key'],
            'fingerprint' => $dummyKey['fingerprint'],
            'metadata_private_keys' => [
                [
                    'user_id' => null, // server key
                    'data' => $this->getEncryptedMessageForMakiAndServerKey(),
                ],
                [
                    'user_id' => $uac->getId(),
                    'data' => $this->getEncryptedMessageForMakiAndServerKey(),
                ],
            ],
        ]);

        $this->expectException(CustomValidationException::class);

        $this->service->create($uac, $dto);
    }
}
