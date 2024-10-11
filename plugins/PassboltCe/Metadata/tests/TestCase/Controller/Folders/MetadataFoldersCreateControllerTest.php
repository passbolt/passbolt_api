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
use Passbolt\Folders\Service\Folders\FoldersCreateService;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\FoldersRelationFactory;
use Passbolt\Folders\Test\Factory\PermissionFactory;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Folders\Controller\Folders\FoldersCreateController
 */
class MetadataFoldersCreateControllerTest extends AppIntegrationTestCaseV5
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

    public function testMetadataFoldersCreateController_Success_Personal()
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'Social media']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        $this->logInAs($user);

        $data = [
            'metadata' => $metadata,
            'metadata_key_id' => $user->gpgkey->id,
            'metadata_key_type' => 'user_key',
        ];
        $this->postJson('/folders.json?api-version=2', $data);

        $this->assertSuccess();
        // Assert controller response
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayNotHasKey('name', $response);
        $this->assertNotNull($response['metadata']);
        $this->assertSame($user->gpgkey->id, $response['metadata_key_id']);
        $this->assertSame('user_key', $response['metadata_key_type']);
        $this->assertEquals($user->id, $response['created_by']);
        $this->assertEquals($user->id, $response['modified_by']);
        // Assert folder data is saved in database
        $folders = FolderFactory::count();
        $this->assertSame(1, $folders);
        $this->assertSame(1, PermissionFactory::count());
        // Assert event data
        $this->assertEventFiredWith(
            FoldersCreateService::FOLDERS_CREATE_FOLDER_EVENT,
            'isV5',
            true
        );
    }

    public function testMetadataFoldersCreateController_Success_PersonalChildFolder()
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'marketing']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        /** @var \Passbolt\Folders\Model\Entity\Folder $parentFolder */
        $parentFolder = FolderFactory::make()->withPermissionsFor([$user])->persist();
        $this->logInAs($user);

        $data = [
            'metadata' => $metadata,
            'metadata_key_id' => $user->gpgkey->id,
            'metadata_key_type' => 'user_key',
            'folder_parent_id' => $parentFolder->id,
        ];
        $this->postJson('/folders.json?api-version=2', $data);

        $this->assertSuccess();
        // Assert controller response
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayNotHasKey('name', $response);
        $this->assertSame($parentFolder->id, $response['folder_parent_id']);
        $this->assertNotNull($response['metadata']);
        $this->assertSame($user->gpgkey->id, $response['metadata_key_id']);
        $this->assertSame('user_key', $response['metadata_key_type']);
        $this->assertEquals($user->id, $response['created_by']);
        $this->assertEquals($user->id, $response['modified_by']);
        // Assert folder data is saved in database
        $this->assertSame(2, FolderFactory::count());
        $this->assertSame(2, PermissionFactory::count());
        $this->assertSame(1, FoldersRelationFactory::count());
    }

    public function testMetadataFoldersCreateController_Success_Shared()
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
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'Social media']);
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        /** @var \Passbolt\Folders\Model\Entity\Folder $parentFolder */
        $parentFolder = FolderFactory::make()
            ->withPermissionsFor([$ada, $betty])
            ->withFoldersRelationsFor([$ada, $betty])
            ->persist();
        // create metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        $this->logInAs($betty);

        $data = [
            'metadata' => $metadata,
            'metadata_key_id' => $metadataKey->id,
            'metadata_key_type' => 'shared_key',
            'folder_parent_id' => $parentFolder->id,
        ];
        $this->postJson('/folders.json?api-version=2', $data);

        $this->assertSuccess();
        // Assert controller response
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayNotHasKey('name', $response);
        $this->assertNotNull($response['metadata']);
        $this->assertSame($metadataKey->id, $response['metadata_key_id']);
        $this->assertSame('shared_key', $response['metadata_key_type']);
        $this->assertEquals($betty->id, $response['created_by']);
        $this->assertEquals($betty->id, $response['modified_by']);
        // Assert folder data is saved in database
        $this->assertSame(2, FolderFactory::count());
        $this->assertSame(3, PermissionFactory::count()); // 2 of parent folder, 1 child folder(just created)
        $this->assertSame(3, FoldersRelationFactory::count());
    }

    /**
     * Data provider for testMetadataFoldersCreateController_Error_Validations()
     *
     * @return array[]
     */
    public function invalidFolderDataProvider(): array
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
    public function testMetadataFoldersCreateController_Error_Validations(array $data, array $expectedErrorPaths)
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        $this->logInAsAdmin();

        $this->postJson('/folders.json?api-version=2', $data);

        $this->assertError(400, 'Could not validate folder data');
        $response = $this->getResponseBodyAsArray();
        foreach ($expectedErrorPaths as $expectedErrorPath) {
            $this->assertTrue(Hash::check($response, $expectedErrorPath));
        }
    }

    public function testMetadataFoldersCreateController_Error_V5AndV4BothFieldsAreSent()
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
        $this->logInAs($ada);

        $data = [
            'name' => 'marketing',
            'metadata' => $metadata,
            'metadata_key_id' => $metadataKey->id,
            'metadata_key_type' => 'shared_key',
            'folder_parent_id' => UuidFactory::uuid(),
        ];
        $this->postJson('/folders.json?api-version=2', $data);

        $this->assertError(400, 'V4 related fields are not supported for V5');
    }

    public function testMetadataFoldersCreateController_Error_MetadataEncryptedForCorrectKeySharedKey()
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
        $this->logInAs($ada);

        $data = [
            'metadata' => $metadata, // encrypted for wrong key
            'metadata_key_id' => $ada->gpgkey->id, // should be metadata key or type should be user_key
            'metadata_key_type' => 'shared_key',
        ];
        $this->postJson('/folders.json?api-version=2', $data);

        $this->assertError(400, 'Could not validate folder data');
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(2, $response);
        $this->assertTrue(Hash::check($response, 'metadata_key_id.metadata_key_exists'));
        $this->assertTrue(Hash::check($response, 'metadata.isValidEncryptedMetadata'));
    }

    public function testMetadataFoldersCreateController_Error_MetadataEncryptedForCorrectKeyUserKey()
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
        $this->logInAs($ada);

        $data = [
            'metadata' => $metadata, // encrypted for wrong key
            'metadata_key_id' => $metadataKey->id, // should be user gpg key or type should be shared_key
            'metadata_key_type' => 'user_key',
        ];
        $this->postJson('/folders.json?api-version=2', $data);

        $this->assertError(400, 'Could not validate folder data');
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(2, $response);
        $this->assertTrue(Hash::check($response, 'metadata_key_id.metadata_key_exists'));
        $this->assertTrue(Hash::check($response, 'metadata.isValidEncryptedMetadata'));
    }

    public function testMetadataFoldersCreateController_Error_ParentFolderDoesNotExist()
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
        $this->logInAs($ada);

        $data = [
            'metadata' => $metadata,
            'metadata_key_id' => $metadataKey->id,
            'metadata_key_type' => 'shared_key',
            'folder_parent_id' => UuidFactory::uuid(),
        ];
        $this->postJson('/folders.json?api-version=2', $data);

        $this->assertError(400, 'Could not validate folder data');
        $response = $this->getResponseBodyAsArray();
        $error = Hash::get($response, 'folder_parent_id');
        $this->assertEquals('The folder parent must exist.', $error['folder_exists']);
    }

    public function testMetadataFoldersCreateController_Error_ParentFolderInsufficientPermission()
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
        /** @var \Passbolt\Folders\Model\Entity\Folder $parentFolder */
        $parentFolder = FolderFactory::make()
            ->withPermissionsFor([$ada])
            ->withFoldersRelationsFor([$ada])
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'marketing']);
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        // create metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        $this->logInAs($betty);

        $data = [
            'metadata' => $metadata,
            'metadata_key_id' => $metadataKey->id,
            'metadata_key_type' => 'shared_key',
            'folder_parent_id' => $parentFolder->id,
        ];
        $this->postJson('/folders.json?api-version=2', $data);

        $this->assertError(400, 'Could not validate folder data');
        $response = $this->getResponseBodyAsArray();
        $error = Hash::get($response, 'folder_parent_id');
        $this->assertEquals('You are not allowed to create content into the parent folder.', $error['has_folder_access']);
    }

    public function testMetadataFoldersCreateController_Success_MetadataPluginDisabled()
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
        $this->logInAs($ada);

        $data = [
            'name' => 'marketing',
            'metadata' => $metadata,
            'metadata_key_id' => $metadataKey->id,
            'metadata_key_type' => 'shared_key',
        ];
        $this->postJson('/folders.json?api-version=2', $data);

        $this->assertSuccess();
        $folders = FolderFactory::find()->toArray();
        $this->assertCount(1, $folders);
        $this->assertSame(1, FoldersRelationFactory::count());
        $this->assertSame(1, PermissionFactory::count());
        // assert db values
        $this->assertSame('marketing', $folders[0]['name']);
        $this->assertNull($folders[0]['metadata']);
        $this->assertNull($folders[0]['metadata_key_id']);
        $this->assertNull($folders[0]['metadata_key_type']);
        $this->assertSame($ada->get('id'), $folders[0]['created_by']);
        $this->assertSame($ada->get('id'), $folders[0]['modified_by']);
    }

    public function testMetadataFoldersCreateController_Error_AllowCreationOfV5FoldersDisabled()
    {
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
        $this->logInAs($ada);

        $this->postJson('/folders.json?api-version=2', [
            'metadata' => $metadata,
            'metadata_key_id' => $metadataKey->id,
            'metadata_key_type' => 'shared_key',
        ]);

        // `\` here is to pass regex in the assertion method
        $this->assertBadRequestError('Folder creation\/modification with encrypted metadata not allowed');
    }
}
