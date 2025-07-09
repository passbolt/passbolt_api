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
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Exception;
use Passbolt\Metadata\Service\MetadataPrivateKeysCreateService;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Metadata\Service\MetadataPrivateKeysCreateService
 */
class MetadataPrivateKeysCreateServiceTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    public function testMetadataPrivateKeysCreateService_Success(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->persist();

        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $data = [
            'user_id' => $user->id,
            'data' => $this->getValidPrivateKeyData($user->gpgkey->armored_key),
        ];
        $sut = new MetadataPrivateKeysCreateService();
        $created = $sut->create($uac, $key->id, $data);
        $this->assertEquals($data['data'], $created->data);
        $this->assertEquals($admin->id, $created->created_by);
        $this->assertNotEmpty($created->created);
    }

    public function testMetadataPrivateKeysCreateService_Success_ServerKey(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $key = MetadataKeyFactory::make()->withUserPrivateKey($user->gpgkey)->persist();

        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $data = [
            'user_id' => null,
            'data' => $this->getValidPrivateKeyDataForServer(),
        ];

        $sut = new MetadataPrivateKeysCreateService();
        $created = $sut->create($uac, $key->id, $data);
        $this->assertEquals($data['data'], $created->data);
        $this->assertEquals($admin->id, $created->created_by);
        $this->assertNotEmpty($created->created);
    }

    public function testMetadataPrivateKeysCreateService_Error_NotAdmin(): void
    {
        /** @var \App\Model\Entity\User $notAdmin */
        $notAdmin = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();

        $uac = new UserAccessControl(Role::USER, $notAdmin->id);
        $data = [
            'user_id' => $user->id,
            'data' => $this->getValidPrivateKeyData($user->gpgkey->armored_key),
        ];
        $sut = new MetadataPrivateKeysCreateService();
        $this->expectException(ForbiddenException::class);
        $sut->create($uac, UuidFactory::uuid(), $data);
    }

    public function testMetadataPrivateKeysCreateService_Error_UserIdNotUuid(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->persist();

        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $data = [
            'user_id' => '🔥',
            'data' => $this->getValidPrivateKeyData($user->gpgkey->armored_key),
        ];
        $sut = new MetadataPrivateKeysCreateService();
        $this->expectException(BadRequestException::class);
        $sut->create($uac, $key->id, $data);
    }

    public function testMetadataPrivateKeysCreateService_Error_DataNotSet(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->persist();

        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $data = [
            'user_id' => $user->id,
            'data' => null,
        ];
        $sut = new MetadataPrivateKeysCreateService();
        $this->expectException(BadRequestException::class);
        $sut->create($uac, $key->id, $data);
    }

    public function testMetadataPrivateKeysCreateService_Error_DataNotString(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->persist();

        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $data = [
            'user_id' => $user->id,
            'data' => [],
        ];
        $sut = new MetadataPrivateKeysCreateService();
        $this->expectException(BadRequestException::class);
        $sut->create($uac, $key->id, $data);
    }

    public function testMetadataPrivateKeysCreateService_Error_KeyDoesNotExist(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();

        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $data = [
            'user_id' => $user->id,
            'data' => $this->getEncryptedMetadataPrivateKeyForUser(),
        ];
        $sut = new MetadataPrivateKeysCreateService();
        $this->expectException(NotFoundException::class);
        $sut->create($uac, UuidFactory::uuid(), $data);
    }

    public function testMetadataPrivateKeysCreateService_Error_KeyIsDeleted(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->deleted()->persist();

        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $data = [
            'user_id' => $user->id,
            'data' => $this->getValidPrivateKeyData($user->gpgkey->armored_key),
        ];
        $sut = new MetadataPrivateKeysCreateService();
        $this->expectException(NotFoundException::class);
        $sut->create($uac, $key->id, $data);
    }

    public function testMetadataPrivateKeysCreateService_Error_UserAlreadyHaveKey(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $key = MetadataKeyFactory::make()->withServerPrivateKey()
            ->withUserPrivateKey($user->gpgkey)->persist();

        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $data = [
            'user_id' => $user->id,
            'data' => $this->getValidPrivateKeyData($user->gpgkey->armored_key),
        ];
        $sut = new MetadataPrivateKeysCreateService();
        try {
            $sut->create($uac, $key->id, $data);
            $this->fail();
        } catch (Exception $exception) {
            $this->assertTextContains('already shared', $exception->getMessage());
        }
    }

    public function testMetadataPrivateKeysCreateService_Error_ValidationError(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->persist();

        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $data = [
            'user_id' => $user->id,
            'data' => '🔥',
        ];
        $sut = new MetadataPrivateKeysCreateService();
        $this->expectException(ValidationException::class);
        $sut->create($uac, $key->id, $data);
    }

    public function testMetadataPrivateKeysCreateService_Error_BuildRules(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->persist();

        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $data = [
            'user_id' => $user->id,
            'data' => $this->getEncryptedMetadataPrivateKeyFoUserDifferent(),
        ];
        $sut = new MetadataPrivateKeysCreateService();
        $this->expectException(ValidationException::class);
        $sut->create($uac, $key->id, $data);
    }
}
