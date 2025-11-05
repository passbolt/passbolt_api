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
use Cake\Core\Configure;
use Passbolt\Folders\FoldersPlugin;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Metadata\Model\Dto\MetadataFolderDto;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Folders\Controller\Folders\FoldersIndexController
 */
class MetadataFoldersIndexControllerTest extends AppIntegrationTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(FoldersPlugin::class);
    }

    public function testMetadataFoldersIndexController_Success_PersonalV5AndV4Mix()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        // v5 folder
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'Social media']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        $v5Folder = FolderFactory::make()
            ->withPermissionsFor([$user])
            ->withFoldersRelationsFor([$user])
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $user->gpgkey->id])
            ->persist();
        // v4 folder
        $v4Folder = FolderFactory::make(['name' => 'marketing'])
            ->withPermissionsFor([$user])
            ->withFoldersRelationsFor([$user])
            ->persist();
        // folder of another user so shouldn't be returned in response
        $betty = UserFactory::make()->user()->active()->persist();
        FolderFactory::make(['name' => 'marketing'])
            ->withPermissionsFor([$betty])
            ->withFoldersRelationsFor([$betty])
            ->persist();
        $this->logInAs($user);

        $this->getJson('/folders.json?api-version=2');

        $this->assertSuccess();
        // Assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(2, $response);
        foreach ($response as $folder) {
            if ($folder['id'] === $v5Folder->get('id')) {
                // assert v5 fields
                $this->assertArrayNotHasKey('name', $folder);
                $this->assertArrayHasKey('metadata', $folder);
                $this->assertArrayHasKey('metadata_key_id', $folder);
                $this->assertArrayHasKey('metadata_key_type', $folder);
                $this->assertSame('user_key', $folder['metadata_key_type']);
                $this->assertSame($user->gpgkey->id, $folder['metadata_key_id']);
                $this->assertSame($metadata, $folder['metadata']);
                $this->assertNull($folder['folder_parent_id']);
            } elseif ($folder['id'] === $v4Folder->get('id')) {
                // assert v4 fields
                $this->assertArrayHasKey('name', $folder);
                $this->assertArrayNotHasKey('metadata', $folder);
                $this->assertArrayNotHasKey('metadata_key_id', $folder);
                $this->assertArrayNotHasKey('metadata_key_type', $folder);
                $this->assertSame('marketing', $folder['name']);
                $this->assertNull($folder['folder_parent_id']);
            }
        }
    }

    public function testMetadataFoldersIndexController_Success_Shared()
    {
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
        // create metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'engineering']);
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        /** @var \Passbolt\Folders\Model\Entity\Folder $parentFolder */
        $parentFolder = FolderFactory::make(['name' => 'sales'])
            ->withPermissionsFor([$ada, $betty])
            ->withFoldersRelationsFor([$ada, $betty])
            ->persist();
        $v5Folder = FolderFactory::make()
            ->withPermissionsFor([$ada, $betty])
            ->withFoldersRelationsFor([$ada, $betty], $parentFolder)
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $metadataKey->id], true)
            ->persist();
        $this->logInAs($betty);

        $this->getJson('/folders.json?api-version=2');

        $this->assertSuccess();
        // Assert controller response
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(2, $response);
        foreach ($response as $folder) {
            if ($folder['id'] === $v5Folder->get('id')) {
                // assert v5 fields
                $this->assertArrayNotHasKey('name', $folder);
                $this->assertArrayHasKey('metadata', $folder);
                $this->assertArrayHasKey('metadata_key_id', $folder);
                $this->assertArrayHasKey('metadata_key_type', $folder);
                $this->assertSame('shared_key', $folder['metadata_key_type']);
                $this->assertSame($metadataKey->id, $folder['metadata_key_id']);
                $this->assertSame($metadata, $folder['metadata']);
                $this->assertSame($parentFolder->get('id'), $folder['folder_parent_id']);
            } elseif ($folder['id'] === $parentFolder->get('id')) {
                // assert v4 fields
                $this->assertArrayHasKey('name', $folder);
                $this->assertArrayNotHasKey('metadata', $folder);
                $this->assertArrayNotHasKey('metadata_key_id', $folder);
                $this->assertArrayNotHasKey('metadata_key_type', $folder);
                $this->assertSame('sales', $folder['name']);
                $this->assertNull($folder['folder_parent_id']);
            }
        }
    }

    public function testMetadataFoldersIndexController_Success_FilterHasId()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        // v5 folder
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'Social media']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        $folder = FolderFactory::make()
            ->withPermissionsFor([$user])
            ->withFoldersRelationsFor([$user])
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $user->gpgkey->id])
            ->persist();
        FolderFactory::make(['name' => 'marketing'])
            ->withPermissionsFor([$user])
            ->withFoldersRelationsFor([$user])
            ->persist();
        $this->logInAs($user);

        $this->getJson("/folders.json?api-version=2&filter[has-id]={$folder->get('id')}");

        $this->assertSuccess();
        // Assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(1, $response);
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $result = $response[0];
        // assert v5 fields
        $this->assertArrayNotHasKey('name', $result);
        $this->assertArrayHasKey('metadata', $result);
        $this->assertArrayHasKey('metadata_key_id', $result);
        $this->assertArrayHasKey('metadata_key_type', $result);
        $this->assertSame('user_key', $result['metadata_key_type']);
        $this->assertSame($user->gpgkey->id, $result['metadata_key_id']);
        $this->assertSame($metadata, $result['metadata']);
        $this->assertNull($result['folder_parent_id']);
    }

    public function testMetadataFoldersIndexController_Success_FilterSearch()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        // v5 folder (not searchable)
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'Social media']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        FolderFactory::make()
            ->withPermissionsFor([$user])
            ->withFoldersRelationsFor([$user])
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $user->gpgkey->id])
            ->persist();
        $folder = FolderFactory::make(['name' => 'marketing'])
            ->withPermissionsFor([$user])
            ->withFoldersRelationsFor([$user])
            ->persist();
        $this->logInAs($user);

        $this->getJson('/folders.json?api-version=2&filter[search]=mar');

        $this->assertSuccess();
        // Assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(1, $response);
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $result = $response[0];
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayNotHasKey('metadata', $result);
        $this->assertArrayNotHasKey('metadata_key_id', $result);
        $this->assertArrayNotHasKey('metadata_key_type', $result);
        $this->assertSame($folder->get('id'), $result['id']);
        $this->assertSame('marketing', $result['name']);
        $this->assertNull($result['folder_parent_id']);
    }

    public function testMetadataFoldersIndexController_Success_FilterHasParent()
    {
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        // create metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'Social media']);
        $metadata = $this->encryptForUser($clearTextMetadata, $ada, $this->getAdaNoPassphraseKeyInfo());
        /** @var \Passbolt\Folders\Model\Entity\Folder $parentFolder */
        $parentFolder = FolderFactory::make(['name' => 'sales'])
            ->withPermissionsFor([$ada])
            ->withFoldersRelationsFor([$ada])
            ->persist();
        $childFolder = FolderFactory::make()
            ->withPermissionsFor([$ada])
            ->withFoldersRelationsFor([$ada], $parentFolder)
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $metadataKey->id], true)
            ->persist();
        FolderFactory::make(['name' => 'client'])
            ->withPermissionsFor([$ada])
            ->withFoldersRelationsFor([$ada])
            ->persist();
        $this->logInAs($ada);

        $this->getJson("/folders.json?api-version=2&filter[has-parent]={$parentFolder->get('id')}");

        $this->assertSuccess();
        // Assert controller response
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(1, $response);
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $result = $response[0];
        $this->assertArrayNotHasKey('name', $result);
        $this->assertArrayHasKey('metadata', $result);
        $this->assertArrayHasKey('metadata_key_id', $result);
        $this->assertArrayHasKey('metadata_key_type', $result);
        $this->assertSame($childFolder->get('id'), $result['id']);
        $this->assertSame('shared_key', $result['metadata_key_type']);
        $this->assertSame($metadataKey->id, $result['metadata_key_id']);
        $this->assertSame($metadata, $result['metadata']);
        $this->assertSame($parentFolder->get('id'), $result['folder_parent_id']);
    }

    public function testMetadataFoldersIndexController_Metadata_Disabled_Success(): void
    {
        Configure::write('passbolt.v5.enabled', false);

        $user = $this->logInAsUser();
        FolderFactory::make(3)->withPermissionsFor([$user])->persist();
        FolderFactory::make(3)->withPermissionsFor([$user])->v5Fields([
            'metadata' => 'foo',
        ])->persist();

        $this->getJson('/folders.json?sort=Folders.modified');
        $this->assertSuccess();
        $response = (array)json_decode(json_encode($this->_responseJsonBody), true);
        $this->assertCount(3, $response);
        $this->assertSame([0, 1, 2], array_keys($response));
        $folderV4 = array_pop($response);
        $this->assertArrayHasAttributes(MetadataFolderDto::V4_META_PROPS, $folderV4);
    }
}
