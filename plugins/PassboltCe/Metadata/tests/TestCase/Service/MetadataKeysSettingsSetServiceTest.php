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
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\Exception\ForbiddenException;
use Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto;
use Passbolt\Metadata\Service\MetadataKeysSettingsSetService;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\TestCase\Form\MetadataKeysSettingsFormTest;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Metadata\Service\MetadataKeysSettingsSetService
 */
class MetadataKeysSettingsSetServiceTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        // enable event tracking
        EventManager::instance()->setEventList(new EventList());
    }

    public function testMetadataKeysSettingsSetService_Success_CreateZeroKnowledgeMode(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $data = MetadataKeysSettingsFormTest::getDefaultData([
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => true,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => true,
        ]);
        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));
        $sut = new MetadataKeysSettingsSetService();
        $dto = $sut->saveSettings($uac, $data);
        $this->assertEquals($data, $dto->toArray());
    }

    public function testMetadataKeysSettingsSetService_Success_CreateUserFriendlyMode(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $data = MetadataKeysSettingsFormTest::getDefaultData([
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => true,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => false,
        ]);
        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));
        $sut = new MetadataKeysSettingsSetService();
        $dto = $sut->saveSettings($uac, $data);
        $this->assertEquals($data, $dto->toArray());
    }

    public function testMetadataKeysSettingsSetService_Success_UpdateFromUserFriendlyMode_To_UserFriendlyMode(): void
    {
        $user = UserFactory::make()->admin()->persist();
        MetadataKeysSettingsFactory::make()->persist();
        $data = MetadataKeysSettingsFormTest::getDefaultData([
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => true,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => false,
        ]);
        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));
        $sut = new MetadataKeysSettingsSetService();
        $dto = $sut->saveSettings($uac, $data);
        $this->assertEquals($data, $dto->toArray());
    }

    public function testMetadataKeysSettingsSetService_Success_EditAllowUsageOfPersonalKeys(): void
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

    /**
     * Update settings from zero-knowledge to user-friendly mode.
     *
     * @return void
     * @throws \Exception
     */
    public function testMetadataKeysSettingsSetService_Success_EditZeroKnowledgeOnOff(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->withValidGpgKey()->persist();
        $key = MetadataKeyFactory::make()->withUserPrivateKey($user->gpgkey)->persist();
        MetadataKeysSettingsFactory::make()->enableZeroTrustKeySharing()->persist();

        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));
        $sut = new MetadataKeysSettingsSetService();
        $settingsUpdated = MetadataKeysSettingsFormTest::getDefaultData(
            [
                MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => false,
            ],
            [[
                'metadata_key_id' => $key->get('id'),
                'user_id' => null, // server key
                'data' => $this->getEncryptedMetadataPrivateKeyForServerKey(),
            ]],
        );
        $dto = $sut->saveSettings($uac, $settingsUpdated);

        $expectedSettings = [
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => true,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => false,
        ];
        $this->assertEquals($expectedSettings, $dto->toArray());
        /** @var \App\Model\Entity\OrganizationSetting[] $metadataKeysSettings */
        $metadataKeysSettings = MetadataKeysSettingsFactory::find()->all()->toArray();
        $this->assertCount(1, $metadataKeysSettings);
        $this->assertEquals(json_encode($expectedSettings), $metadataKeysSettings[0]->value);
        // assert server private key is created
        $this->assertSame(1, MetadataPrivateKeyFactory::find()->where(['user_id IS' => null, 'metadata_key_id' => $key->get('id')])->count());
        // assert user private key exists (not touched)
        $this->assertSame(1, MetadataPrivateKeyFactory::find()->where(['user_id IS' => $user->id, 'metadata_key_id' => $key->get('id')])->count());
    }

    /**
     * Update settings from user-friendly to zero-knowledge mode.
     *
     * @return void
     * @throws \Exception
     */
    public function testMetadataKeysSettingsSetService_Success_EditZeroKnowledgeOffOn(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->withValidGpgKey()->persist();
        $metadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withUserPrivateKey($user->gpgkey)->withMetadataKey($metadataKey)->persist();
        $metadataKeySettings = json_encode([
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => false,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => false,
        ]);
        MetadataKeysSettingsFactory::make()->value($metadataKeySettings)->persist();

        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));
        $sut = new MetadataKeysSettingsSetService();
        $settingsUpdated = [
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => true,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => true,
        ];
        $dto = $sut->saveSettings($uac, $settingsUpdated);

        $this->assertEquals([
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => true,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => true,
        ], $dto->toArray());
        $this->assertEquals(1, OrganizationSettingFactory::count());
        $expectedMetadataKeysSetting = json_decode(OrganizationSettingFactory::firstOrFail()->get('value'), true);
        $this->assertEqualsCanonicalizing($settingsUpdated, $expectedMetadataKeysSetting);
        $this->assertEventFired(MetadataKeysSettingsSetService::AFTER_METADATA_SETTINGS_SET_SUCCESS_EVENT_NAME);
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

    public function testMetadataKeysSettingsSetService_Error_InvalidSettings(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $data = MetadataKeysSettingsFactory::getDefaultData();
        $data[MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE] = 'zero-trust';
        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));
        $sut = new MetadataKeysSettingsSetService();
        $this->expectException(FormValidationException::class);
        $sut->saveSettings($uac, $data);
    }

    /**
     * Update settings from zero-knowledge to user-friendly mode should throw validation error if no server metadata private keys data provided.
     *
     * @return void
     * @throws \Exception
     */
    public function testMetadataKeysSettingsSetService_Error_EditZeroKnowledgeOnOffWithoutServerPrivateKeysData(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $metadataKeySettings = json_encode([
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => false,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => true,
        ]);
        MetadataKeysSettingsFactory::make()->value($metadataKeySettings)->persist();
        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));
        $sut = new MetadataKeysSettingsSetService();
        $this->expectException(FormValidationException::class);
        $sut->saveSettings($uac, [
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => false,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => false,
        ]);
    }

    /**
     * Gracefully handle case when server private key already present when settings are updated from zero-knowledge to user-friendly mode.
     *
     * @return void
     * @throws \Exception
     */
    public function testMetadataKeysSettingsSetService_Success_EditZeroKnowledgeOnOffServerKeyAlreadyPresent(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->withValidGpgKey()->persist();
        $metadataKeySettings = json_encode([
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => false,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => true,
        ]);
        MetadataKeysSettingsFactory::make()->value($metadataKeySettings)->persist();
        // create metadata keys
        $key = MetadataKeyFactory::make()->withUserPrivateKey($user->gpgkey)->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($key)->serverKey()->persist();

        $uac = new UserAccessControl(Role::ADMIN, $user->get('id'));
        $sut = new MetadataKeysSettingsSetService();
        $settingsUpdated = [
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => false,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => false,
        ];
        $data = MetadataKeysSettingsFormTest::getDefaultData(
            $settingsUpdated,
            [[
                'metadata_key_id' => $key->get('id'),
                'user_id' => null, // server key
                'data' => $this->getEncryptedMetadataPrivateKeyForServerKey(),
            ]],
        );
        $dto = $sut->saveSettings($uac, $data);

        $this->assertEquals($settingsUpdated, $dto->toArray());
        $this->assertEquals(1, OrganizationSettingFactory::count());
        $expectedMetadataKeysSetting = json_decode(OrganizationSettingFactory::firstOrFail()->get('value'), true);
        $this->assertEqualsCanonicalizing($settingsUpdated, $expectedMetadataKeysSetting);
        $this->assertSame(2, MetadataPrivateKeyFactory::find()->all()->count());
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
