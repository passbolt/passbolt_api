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

namespace Passbolt\Metadata\Test\TestCase\Service\MetadataKey;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\DateTime;
use Passbolt\Metadata\Model\Dto\MetadataKeyUpdateDto;
use Passbolt\Metadata\Service\MetadataKey\MetadataKeyUpdateService;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;

/**
 * @covers \Passbolt\Metadata\Service\MetadataKey\MetadataKeyUpdateService
 */
class MetadataKeyUpdateServiceTest extends AppTestCaseV5
{
    private function getDefaultFixtureData(): array
    {
        return [
            'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
            'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
        ];
    }

    private function getDefaultRequestData(): array
    {
        return [
            'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
            'expired' => DateTime::yesterday(),
        ];
    }

    public function testMetadataKeyUpdateService_Success(): void
    {
        $key = MetadataKeyFactory::make()->patchData($this->getDefaultFixtureData())->persist();
        $user = UserFactory::make()->admin()->persist();
        $data = $this->getDefaultRequestData();
        $dto = MetadataKeyUpdateDto::fromArray($data);
        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));

        (new MetadataKeyUpdateService())->update($uac, $key->get('id'), $dto);

        $updatedKey = MetadataKeyFactory::get($key->get('id'));
        $this->assertEquals($data['armored_key'], $updatedKey->get('armored_key'));
        $this->assertInstanceOf(DateTime::class, $updatedKey->get('expired'));
    }

    public function testMetadataKeyUpdateService_Error_NotAdmin(): void
    {
        $key = MetadataKeyFactory::make()->patchData($this->getDefaultFixtureData())->persist();
        $user = UserFactory::make()->user()->persist();
        $data = $this->getDefaultRequestData();
        $dto = MetadataKeyUpdateDto::fromArray($data);
        $uac = new UserAccessControl(Role::USER, $user->get('id'));

        $this->expectException(ForbiddenException::class);
        (new MetadataKeyUpdateService())->update($uac, $key->get('id'), $dto);
    }

    public function testMetadataKeyUpdateService_Error_IdNotUUID(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $data = $this->getDefaultRequestData();
        $dto = MetadataKeyUpdateDto::fromArray($data);
        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));

        $this->expectException(BadRequestException::class);
        (new MetadataKeyUpdateService())->update($uac, '🔥', $dto);
    }

    public function testMetadataKeyUpdateService_Error_RecordNotFound(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $data = $this->getDefaultRequestData();
        $dto = MetadataKeyUpdateDto::fromArray($data);
        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));

        $this->expectException(NotFoundException::class);
        (new MetadataKeyUpdateService())->update($uac, UuidFactory::uuid(), $dto);
    }

    public function testMetadataKeyUpdateService_Error_FingerprintMismatch(): void
    {
        $key = MetadataKeyFactory::make()->patchData($this->getDefaultFixtureData())->persist();
        $user = UserFactory::make()->admin()->persist();
        $data = $this->getDefaultRequestData();
        $data['fingerprint'] = '67BFFCB7B74AF4C85E81AB26508850525CD78BAF';
        $dto = MetadataKeyUpdateDto::fromArray($data);
        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));

        $this->expectException(NotFoundException::class);
        (new MetadataKeyUpdateService())->update($uac, $key->get('id'), $dto);
    }

    public function testMetadataKeyUpdateService_Error_KeyIsAlreadyExpired(): void
    {
        $key = MetadataKeyFactory::make()->patchData([
            'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
            'expired' => DateTime::yesterday(),
        ])->persist();
        $user = UserFactory::make()->admin()->persist();
        $data = $this->getDefaultRequestData();
        $dto = MetadataKeyUpdateDto::fromArray($data);
        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The metadata key is already marked as expired.');
        (new MetadataKeyUpdateService())->update($uac, $key->get('id'), $dto);
    }

    public function testMetadataKeyUpdateService_Error_KeyIsAlreadyDeleted(): void
    {
        $key = MetadataKeyFactory::make()->patchData([
            'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
            'expired' => DateTime::yesterday(),
            'deleted' => DateTime::yesterday(),
        ])->persist();
        $user = UserFactory::make()->admin()->persist();
        $data = $this->getDefaultRequestData();
        $dto = MetadataKeyUpdateDto::fromArray($data);
        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The metadata key has already been deleted.');
        (new MetadataKeyUpdateService())->update($uac, $key->get('id'), $dto);
    }

    public function testMetadataKeyUpdateService_Error_DataKeyNotRevoked(): void
    {
        $key = MetadataKeyFactory::make()->patchData($this->getDefaultFixtureData())->persist();
        $user = UserFactory::make()->admin()->persist();
        $data = [
            'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
            'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
            'expired' => DateTime::yesterday(),
        ];
        $dto = MetadataKeyUpdateDto::fromArray($data);
        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));

        $this->expectException(CustomValidationException::class);
        (new MetadataKeyUpdateService())->update($uac, $key->get('id'), $dto);
    }

    public function testMetadataKeyUpdateService_Error_FingerprintMismatch2(): void
    {
        $fixture = [
            'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
            'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAF',
        ];
        $data = [
            'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAF',
            'expired' => DateTime::yesterday(),
        ];

        $key = MetadataKeyFactory::make()->patchData($fixture)->persist();
        $user = UserFactory::make()->admin()->persist();
        $dto = MetadataKeyUpdateDto::fromArray($data);
        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));

        $this->expectException(CustomValidationException::class);
        (new MetadataKeyUpdateService())->update($uac, $key->get('id'), $dto);
    }
}
