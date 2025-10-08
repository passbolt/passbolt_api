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

namespace Passbolt\Metadata\Test\TestCase\Controller\Folders;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Utility\Hash;
use Passbolt\Folders\FoldersPlugin;
use Passbolt\Folders\Service\Folders\FoldersUpdateService;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\FoldersRelationFactory;
use Passbolt\Folders\Test\Factory\PermissionFactory;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Model\Dto\MetadataFolderDto;
use Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto;
use Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Service\MetadataTypesSettingsGetService;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Folders\Controller\Folders\FoldersUpdateController
 */
class MetadataFoldersUpdateControllerTest extends AppIntegrationTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(FoldersPlugin::class);
        // enable event tracking
        EventManager::instance()->setEventList(new EventList());
    }

    public function testMetadataFoldersUpdateController_Success_Personal()
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'marketing updated']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withFoldersRelationsFor([$user])->withPermissionsFor([$user])->persist();
        $this->logInAs($user);

        $data = [
            'metadata' => $metadata,
            'metadata_key_id' => $user->gpgkey->id,
            'metadata_key_type' => 'user_key',
        ];
        $this->postJson("/folders/{$folder->id}.json?api-version=2", $data);

        $this->assertSuccess();
        // Assert controller response
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayNotHasKey('name', $response);
        $this->assertNotNull($response['metadata']);
        $this->assertSame($user->gpgkey->id, $response['metadata_key_id']);
        $this->assertSame('user_key', $response['metadata_key_type']);
        $this->assertEquals($user->id, $response['modified_by']);
        // Assert folder data is saved in database
        $folders = FolderFactory::count();
        $this->assertSame(1, $folders);
        $this->assertSame(1, PermissionFactory::count());
        $this->assertSame(1, FoldersRelationFactory::count());
        // Assert event data
        $this->assertEventFiredWith(
            FoldersUpdateService::FOLDERS_UPDATE_FOLDER_EVENT,
            'isV5',
            true
        );
    }

    public function testMetadataFoldersUpdateController_Success_Personal_KeyIdNull()
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'marketing updated']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withFoldersRelationsFor([$user])->withPermissionsFor([$user])->persist();
        $this->logInAs($user);

        $data = [
            'metadata' => $metadata,
            'metadata_key_id' => null,
            'metadata_key_type' => 'user_key',
        ];
        $this->postJson("/folders/{$folder->id}.json?api-version=2", $data);

        $this->assertSuccess();
        // Assert controller response
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayNotHasKey('name', $response);
        $this->assertNotNull($response['metadata']);
        $this->assertSame($user->gpgkey->id, $response['metadata_key_id']);
        $this->assertSame('user_key', $response['metadata_key_type']);
        $this->assertEquals($user->id, $response['modified_by']);
        // Assert folder data is saved in database
        $folders = FolderFactory::count();
        $this->assertSame(1, $folders);
        $this->assertSame(1, PermissionFactory::count());
        $this->assertSame(1, FoldersRelationFactory::count());
        // Assert event data
        $this->assertEventFiredWith(
            FoldersUpdateService::FOLDERS_UPDATE_FOLDER_EVENT,
            'isV5',
            true
        );
    }

    public function testMetadataFoldersUpdateController_Success_Shared()
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\User $betty */
        $betty = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'updated']);
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        // create metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withFoldersRelationsFor([$ada, $betty])->withPermissionsFor([$ada, $betty])->persist();
        $this->logInAs($betty);

        $data = [
            'metadata' => $metadata,
            'metadata_key_id' => $metadataKey->id,
            'metadata_key_type' => 'shared_key',
        ];
        $this->postJson("/folders/{$folder->id}.json?api-version=2", $data);

        $this->assertSuccess();
        // Assert controller response
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayNotHasKey('name', $response);
        $this->assertNotNull($response['metadata']);
        $this->assertSame($metadataKey->id, $response['metadata_key_id']);
        $this->assertSame('shared_key', $response['metadata_key_type']);
        $this->assertEquals($betty->id, $response['modified_by']);
        // Assert folder data is saved in database
        $this->assertSame(1, FolderFactory::count());
        $this->assertSame(2, PermissionFactory::count());
        $this->assertSame(2, FoldersRelationFactory::count());
    }

    /**
     * Data provider for testMetadataFoldersUpdateController_Error_Validations()
     *
     * @return array[]
     */
    public static function invalidFolderDataProvider(): array
    {
        return [
            [
                'data' => [
                    'metadata' => '',
                    'metadata_key_id' => 'foo-bar',
                    'metadata_key_type' => 12345,
                ],
                'expectedErrors' => ['metadata._empty', 'metadata_key_id.uuid', 'metadata_key_type.inList'],
            ],
            [
                'data' => [
                    'metadata' => 'abcd',
                    'metadata_key_id' => UuidFactory::uuid(),
                    'metadata_key_type' => '🔥',
                ],
                'expectedErrors' => ['metadata.isMetadataParsable', 'metadata_key_type.inList'],
            ],
        ];
    }

    /**
     * @dataProvider invalidFolderDataProvider
     * @return void
     */
    public function testMetadataFoldersUpdateController_Error_Validations(array $data, array $expectedErrorPaths)
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        $user = $this->logInAsAdmin();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withFoldersRelationsFor([$user])->withPermissionsFor([$user])->persist();

        $this->postJson("/folders/{$folder->id}.json?api-version=2", $data);

        $this->assertError(400, 'Could not validate folder data');
        $response = $this->getResponseBodyAsArray();
        foreach ($expectedErrorPaths as $expectedErrorPath) {
            $this->assertTrue(Hash::check($response, $expectedErrorPath));
        }
    }

    public function testMetadataFoldersUpdateController_Error_V5AndV4BothFieldsAreSent()
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'marketing updated']);
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        // create metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withFoldersRelationsFor([$ada])->withPermissionsFor([$ada])->persist();
        $this->logInAs($ada);

        $data = [
            'name' => 'marketing',
            'metadata' => $metadata,
            'metadata_key_id' => $metadataKey->id,
            'metadata_key_type' => 'shared_key',
        ];
        $this->postJson("/folders/{$folder->id}.json?api-version=2", $data);

        $this->assertError(400, 'V4 related fields are not supported for V5');
    }

    public function testMetadataFoldersUpdateController_Error_MetadataEncryptedForCorrectKeySharedKey()
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'marketing']);
        $metadata = $this->encryptForUser($clearTextMetadata, $ada, $this->getAdaNoPassphraseKeyInfo());
        // create metadata key
        MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withFoldersRelationsFor([$ada])->withPermissionsFor([$ada])->persist();
        $this->logInAs($ada);

        $data = [
            'metadata' => $metadata, // encrypted for wrong key
            'metadata_key_id' => $ada->gpgkey->id, // should be metadata key or type should be user_key
            'metadata_key_type' => 'shared_key',
        ];
        $this->postJson("/folders/{$folder->id}.json?api-version=2", $data);

        $this->assertError(400, 'Could not validate folder data');
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(2, $response);
        $this->assertTrue(Hash::check($response, 'metadata_key_id.metadata_key_exists'));
        $this->assertTrue(Hash::check($response, 'metadata.isValidEncryptedMetadata'));
    }

    public function testMetadataFoldersUpdateController_Error_MetadataEncryptedForCorrectKeyUserKey()
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'marketing']);
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        // create metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withFoldersRelationsFor([$ada])->withPermissionsFor([$ada])->persist();
        $this->logInAs($ada);

        $data = [
            'metadata' => $metadata, // encrypted for wrong key
            'metadata_key_id' => $metadataKey->id, // should be user gpg key or type should be shared_key
            'metadata_key_type' => 'user_key',
        ];
        $this->postJson("/folders/{$folder->id}.json?api-version=2", $data);

        $this->assertError(400, 'Could not validate folder data');
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(2, $response);
        $this->assertTrue(Hash::check($response, 'metadata_key_id.metadata_key_exists'));
        $this->assertTrue(Hash::check($response, 'metadata.isValidEncryptedMetadata'));
    }

    public function testMetadataFoldersUpdateController_Error_InsufficientPermission()
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\User $betty */
        $betty = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'marketing']);
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        // create metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withFoldersRelationsFor([$ada])->withPermissionsFor([$ada])->persist();
        $this->logInAs($betty);

        $data = [
            'metadata' => $metadata,
            'metadata_key_id' => $metadataKey->id,
            'metadata_key_type' => 'shared_key',
        ];
        $this->postJson("/folders/{$folder->id}.json?api-version=2", $data);

        $this->assertNotFoundError('The folder does not exist');
    }

    public function testMetadataFoldersUpdateController_Success_MetadataPluginDisabled()
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        Configure::write('passbolt.v5.enabled', false);
        $this->disableFeaturePlugin(MetadataPlugin::class);
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'marketing']);
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        // create metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withFoldersRelationsFor([$ada])->withPermissionsFor([$ada])->persist();
        $this->logInAs($ada);

        $data = [
            'name' => 'sales',
            'metadata' => $metadata,
            'metadata_key_id' => $metadataKey->id,
            'metadata_key_type' => 'shared_key',
        ];
        $this->postJson("/folders/{$folder->id}.json?api-version=2", $data);

        $this->assertSuccess();
        $folders = FolderFactory::find()->toArray();
        $this->assertCount(1, $folders);
        $this->assertSame(1, FoldersRelationFactory::count());
        $this->assertSame(1, PermissionFactory::count());
        // assert db values
        $this->assertSame('sales', $folders[0]['name']);
        $this->assertNull($folders[0]['metadata']);
        $this->assertNull($folders[0]['metadata_key_id']);
        $this->assertNull($folders[0]['metadata_key_type']);
        $this->assertSame($folder->get('created_by'), $folders[0]['created_by']);
        $this->assertSame($ada->get('id'), $folders[0]['modified_by']);
    }

    public function testMetadataFoldersUpdateController_Error_AllowCreationOfV5FoldersDisabled()
    {
        $this->markTestSkipped('Not handled for edit');
        // Allow only V4 format
        MetadataTypesSettingsFactory::make()->v4()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'marketing']);
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        // create metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withFoldersRelationsFor([$ada])->withPermissionsFor([$ada])->persist();
        $this->logInAs($ada);

        $this->postJson("/folders/{$folder->id}.json?api-version=2", [
            'metadata' => $metadata,
            'metadata_key_id' => $metadataKey->id,
            'metadata_key_type' => 'shared_key',
        ]);

        // `\` here is to pass regex in the assertion method
        $this->assertBadRequestError('Folder creation\/modification with encrypted metadata not allowed');
    }

    public function testMetadataFoldersUpdateController_Error_AllowCreationOfV4FoldersDisabled()
    {
        $this->markTestSkipped('Not handled for edit');
        // Allow only V6 format
        MetadataTypesSettingsFactory::make()->v6()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withFoldersRelationsFor([$ada])->withPermissionsFor([$ada])->persist();
        $this->logInAs($ada);

        $this->postJson("/folders/{$folder->id}.json?api-version=2", ['name' => 'sales']);

        // `\` here is to pass regex in the assertion method
        $this->assertBadRequestError('Folder creation\/modification with cleartext metadata not allowed');
    }

    public function testMetadataFoldersUpdateController_Error_UserKey_NotAllowedInSettings()
    {
        $data = MetadataKeysSettingsFactory::getDefaultData();
        $data[MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS] = false;
        MetadataKeysSettingsFactory::make()->value($data)->persist();
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'marketing updated']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withFoldersRelationsFor([$user])->withPermissionsFor([$user])->persist();
        $this->logInAs($user);

        $data = [
            'metadata' => $metadata,
            'metadata_key_id' => $user->gpgkey->id,
            'metadata_key_type' => 'user_key',
        ];
        $this->postJson("/folders/{$folder->id}.json", $data);
        $this->assertError(400);
        $this->assertResponseContains('metadata_key_type');
        $this->assertResponseContains('isMetadataKeyTypeAllowedBySettings');
    }

    public function testMetadataFoldersUpdateController_Error_SharedKeyExpired()
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\User $betty */
        $betty = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'updated']);
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        // create metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()
            ->expired()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withFoldersRelationsFor([$ada, $betty])->withPermissionsFor([$ada, $betty])->persist();
        $this->logInAs($betty);

        $data = [
            'metadata' => $metadata,
            'metadata_key_id' => $metadataKey->id,
            'metadata_key_type' => 'shared_key',
        ];
        $this->postJson("/folders/{$folder->id}.json?api-version=2", $data);

        $this->assertError(400);
        $this->assertResponseContains('metadata_key_id');
        $this->assertResponseContains('isMetadataKeyNotExpired');
    }

    public function testMetadataFoldersUpdateController_Error_V4ToV5UpgradeNotAllowed()
    {
        $settings = MetadataTypesSettingsGetService::defaultV4Settings(); // by default it's false in v4
        $settings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS] = true;
        MetadataTypesSettingsFactory::make()->value($settings)->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withFoldersRelationsFor([$ada])->withPermissionsFor([$ada])->persist();
        $dto = MetadataFolderDto::fromArray($folder->toArray());
        $metadataArray = $dto->getClearTextMetadata();
        $metadata = $this->encryptForUser(json_encode($metadataArray), $ada, $this->getAdaNoPassphraseKeyInfo());
        $this->logInAs($ada);

        $data = [
            'metadata' => $metadata,
            'metadata_key_id' => $ada->gpgkey->id,
            'metadata_key_type' => MetadataKey::TYPE_USER_KEY,
        ];
        $this->postJson("/folders/{$folder->id}.json?api-version=2", $data);

        $this->assertError(400);
        $errors = $this->getResponseBodyAsArray();
        $this->assertArrayHasKey('v4_to_v5_upgrade_allowed', $errors['metadata']);
    }

    public function testMetadataFoldersUpdateController_Success_V4ToV5UpgradeSettingAllowed()
    {
        $settings = MetadataTypesSettingsGetService::defaultV4Settings();
        $settings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS] = true;
        $settings[MetadataTypesSettingsDto::ALLOW_V4_V5_UPGRADE] = true;
        MetadataTypesSettingsFactory::make()->value($settings)->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withFoldersRelationsFor([$ada])->withPermissionsFor([$ada])->persist();
        $dto = MetadataFolderDto::fromArray($folder->toArray());
        $metadataArray = $dto->getClearTextMetadata();
        $metadata = $this->encryptForUser(json_encode($metadataArray), $ada, $this->getAdaNoPassphraseKeyInfo());
        $this->logInAs($ada);

        $data = [
            'metadata' => $metadata,
            'metadata_key_id' => $ada->gpgkey->id,
            'metadata_key_type' => MetadataKey::TYPE_USER_KEY,
        ];
        $this->postJson("/folders/{$folder->id}.json?api-version=2", $data);

        $this->assertSuccess();
        /** @var \Passbolt\Folders\Model\Entity\Folder $updatedFolder */
        $updatedFolder = FolderFactory::get($folder->id);
        $this->assertEquals($metadata, $updatedFolder->metadata);
        $this->assertEquals($ada->gpgkey->id, $updatedFolder->metadata_key_id);
        $this->assertEquals(MetadataKey::TYPE_USER_KEY, $updatedFolder->metadata_key_type);
        $this->assertNull($updatedFolder->name);
    }
}
