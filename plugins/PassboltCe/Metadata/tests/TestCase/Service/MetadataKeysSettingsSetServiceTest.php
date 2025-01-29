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

use App\Error\Exception\FormValidationException;
use App\Model\Entity\Role;
use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\ForbiddenException;
use Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto;
use Passbolt\Metadata\Service\MetadataKeysSettingsSetService;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * \Passbolt\Metadata\Service\MetadataKeysSettingsSetService
 */
class MetadataKeysSettingsSetServiceTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    public function testMetadataKeysSettingsSetService_Success_Create(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $data = [
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => true,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => true,
        ];
        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));
        $sut = new MetadataKeysSettingsSetService();
        $dto = $sut->saveSettings($uac, $data);
        $this->assertEquals($data, $dto->toArray());
    }

    public function testMetadataKeysSettingsSetService_Success_ZeroKnowledgeDisabled(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $data = [
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => true,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => false,
        ];
        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));
        $sut = new MetadataKeysSettingsSetService();
        $dto = $sut->saveSettings($uac, $data);
        $this->assertEquals($data, $dto->toArray());
    }

    public function testMetadataKeysSettingsSetService_Success_EditSimple(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $data = [
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => false,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => true,
        ];
        MetadataKeysSettingsFactory::make()->value(json_encode($data))->persist();
        $this->assertEquals(1, OrganizationSettingFactory::count());

        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));
        $sut = new MetadataKeysSettingsSetService();
        $data = [
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => true,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => true,
        ];
        $dto = $sut->saveSettings($uac, $data);
        $this->assertEquals($data, $dto->toArray());
        $this->assertEquals(1, OrganizationSettingFactory::count());
    }

    public function testMetadataKeysSettingsSetService_Success_EditZeroKnowledgeOnOff(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->withValidGpgKey()->persist();
        $key = MetadataKeyFactory::make()->withUserPrivateKey($user->gpgkey)->persist();

        $data = [
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => true,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => false,
            'metadata_private_keys' => [[
                'metadata_key_id' => $key->get('id'),
                'user_id' => null, // server key
                'data' => $this->getEncryptedMetadataPrivateKeyForServerKey(),
            ]],
        ];
        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));
        $sut = new MetadataKeysSettingsSetService();

        $dto = $sut->saveSettings($uac, $data);

        $this->assertEquals([
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => true,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => false,
        ], $dto->toArray());
        $this->assertEquals(1, OrganizationSettingFactory::count());
    }

    public function testMetadataKeysSettingsSetService_Success_EditZeroKnowledgeOffOn(): void
    {
        $this->markTestIncomplete();
    }

    public function testMetadataKeysSettingsSetService_Error_NotAdmin(): void
    {
        $user = UserFactory::make()->user()->persist();
        $data = MetadataKeysSettingsFactory::getDefaultData();
        $uac = new UserAccessControl(Role::USER, $user->get('id'));
        $sut = new MetadataKeysSettingsSetService();
        $this->expectException(ForbiddenException::class);
        $sut->saveSettings($uac, $data);
    }

    public function testMetadataKeysSettingsSetService_Error_Invalid(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $data = MetadataKeysSettingsFactory::getDefaultData();
        $data[MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE] = 'zero-trust';
        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));
        $sut = new MetadataKeysSettingsSetService();
        $this->expectException(FormValidationException::class);
        $sut->saveSettings($uac, $data);
    }

    public function testMetadataKeysSettingsSetService_Error_ZeroKnowledgeButNoKey(): void
    {
        $this->markTestIncomplete();
    }

    public function testMetadataKeysSettingsSetService_Error_ZeroKnowledgeInvalidKey(): void
    {
        $this->markTestIncomplete();
    }
}
