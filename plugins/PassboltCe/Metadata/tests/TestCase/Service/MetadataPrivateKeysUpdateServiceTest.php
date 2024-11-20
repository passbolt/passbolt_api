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

use App\Error\Exception\ValidationException;
use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Passbolt\Metadata\Service\MetadataPrivateKeysUpdateService;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Metadata\Service\MetadataPrivateKeysUpdateService
 */
class MetadataPrivateKeysUpdateServiceTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    public function testMetadataPrivateKeysUpdateService_Success(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $key = MetadataKeyFactory::make()->withUserPrivateKey($user->gpgkey)->withServerPrivateKey()->persist();

        $gpg = OpenPGPBackendFactory::get();
        $adaPrivateKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key');
        $gpg->setEncryptKey($adaPrivateKey);
        $gpg->setSignKey($adaPrivateKey, '');
        $msg = $gpg->encryptSign(json_encode($this->getValidPrivateKeyCleartext()));

        $sut = new MetadataPrivateKeysUpdateService();
        $uac = new UserAccessControl(Role::USER, $user->id);
        $updated = $sut->update($uac, $key->metadata_private_keys[0]->id, ['data' => $msg]);

        $this->assertEquals($user->id, $updated->modified_by);
        $this->assertEquals($msg, $updated->data);
        $this->assertNotEquals($updated->created, $updated->modified);
    }

    public function testMetadataPrivateKeysUpdateService_Error_DataNotSet(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $sut = new MetadataPrivateKeysUpdateService();
        $uac = new UserAccessControl(Role::USER, $user->id);

        $this->expectException(BadRequestException::class);
        $sut->update($uac, UuidFactory::uuid(), []);
    }

    public function testMetadataPrivateKeysUpdateService_Error_DataNotString(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $sut = new MetadataPrivateKeysUpdateService();
        $uac = new UserAccessControl(Role::USER, $user->id);

        $this->expectException(BadRequestException::class);
        $sut->update($uac, UuidFactory::uuid(), ['data' => []]);
    }

    public function testMetadataPrivateKeysUpdateService_Error_KeyIdNotValid(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $sut = new MetadataPrivateKeysUpdateService();
        $uac = new UserAccessControl(Role::USER, $user->id);

        $this->expectException(BadRequestException::class);
        $sut->update($uac, '🔥', ['data' => '🔥']);
    }

    public function testMetadataPrivateKeysUpdateService_Error_KeyNotFound(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $sut = new MetadataPrivateKeysUpdateService();
        $uac = new UserAccessControl(Role::USER, $user->id);

        $data = ['data' => $this->getEncryptedMetadataSessionKeyForMaki()];
        $this->expectException(NotFoundException::class);
        $sut->update($uac, UuidFactory::uuid(), $data);
    }

    public function testMetadataPrivateKeysUpdateService_Error_KeyAlreadyUpdated(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $key = MetadataKeyFactory::make()->withUserPrivateKey($user->gpgkey)->withServerPrivateKey()->persist();

        $gpg = OpenPGPBackendFactory::get();
        $adaPrivateKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key');
        $gpg->setEncryptKey($adaPrivateKey);
        $gpg->setSignKey($adaPrivateKey, '');
        $msg = $gpg->encryptSign(json_encode($this->getValidPrivateKeyCleartext()));

        $sut = new MetadataPrivateKeysUpdateService();
        $uac = new UserAccessControl(Role::USER, $user->id);
        $sut->update($uac, $key->metadata_private_keys[0]->id, ['data' => $msg]);

        $this->expectException(BadRequestException::class);
        $sut->update($uac, $key->metadata_private_keys[0]->id, ['data' => $msg]);
    }

    public function testMetadataPrivateKeysUpdateService_Error_ValidationFailed(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $key = MetadataKeyFactory::make()->withUserPrivateKey($user->gpgkey)->withServerPrivateKey()->persist();

        $sut = new MetadataPrivateKeysUpdateService();
        $uac = new UserAccessControl(Role::USER, $user->id);
        $data = ['data' => 'not a valid gpg message'];
        try {
            $sut->update($uac, $key->metadata_private_keys[0]->id, $data);
            $this->fail();
        } catch (ValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertNotEmpty($errors['data']['isValidOpenPGPMessage']);
        }
    }

    public function testMetadataPrivateKeysUpdateService_Error_BuildRulesFailed(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $key = MetadataKeyFactory::make()->withUserPrivateKey($user->gpgkey)->withServerPrivateKey()->persist();

        $sut = new MetadataPrivateKeysUpdateService();
        $uac = new UserAccessControl(Role::USER, $user->id);
        $data = ['data' => $this->getEncryptedMetadataSessionKeyForMaki()];
        try {
            $sut->update($uac, $key->metadata_private_keys[0]->id, $data);
            $this->fail();
        } catch (ValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertNotEmpty($errors['data']['isValidEncryptedMetadataPrivateKey']);
        }
    }
}
