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
use Cake\Utility\Hash;
use Passbolt\Folders\FoldersPlugin;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Folders\Controller\Folders\FoldersIndexController
 */
class MetadataFoldersViewControllerTest extends AppIntegrationTestCaseV5
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

    public function testMetadataFoldersViewController_Success_ContainChildrenFolders()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        // v5 folder
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'Social media']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, [
            'passphrase' => '',
            'privateKey' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key'),
        ]);
        $v5Folder = FolderFactory::make()
            ->withPermissionsFor([$user])
            ->withFoldersRelationsFor([$user])
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $user->gpgkey->id])
            ->persist();
        // v4 folder
        $v4Folder = FolderFactory::make(['name' => 'marketing'])
            ->withPermissionsFor([$user])
            ->withFoldersRelationsFor([$user], $v5Folder)
            ->persist();
        $this->logInAs($user);

        $this->getJson("/folders/{$v5Folder->get('id')}.json?contain[children_folders]=1&api-version=2");

        $this->assertSuccess();
        // Assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayHasAttributes([
            'id',
            'metadata',
            'metadata_key_id',
            'metadata_key_type',
            'created',
            'modified',
            'created_by',
            'modified_by',
            'personal',
            'folder_parent_id',
            'children_folders',
        ], $response);
        $this->assertSame($v5Folder->get('id'), $response['id']);
        $this->assertArrayNotHasKey('name', $response);
        $this->assertSame($metadata, $response['metadata']);
        $this->assertSame($user->gpgkey->id, $response['metadata_key_id']);
        $this->assertSame('user_key', $response['metadata_key_type']);
        // children assertions
        $this->assertCount(1, $response['children_folders']);
        $this->assertSame($v4Folder->get('id'), $response['children_folders'][0]['id']);
        $this->assertSame('marketing', $response['children_folders'][0]['name']);
        $this->assertSame($v5Folder->get('id'), $response['children_folders'][0]['folder_parent_id']);
    }

    public function testMetadataFoldersViewController_Success_ContainChildrenResources()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        // v5 folder
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'Social media']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, [
            'passphrase' => '',
            'privateKey' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key'),
        ]);
        $v5Folder = FolderFactory::make()
            ->withPermissionsFor([$user])
            ->withFoldersRelationsFor([$user])
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $user->gpgkey->id])
            ->persist();
        /** @var \App\Model\Entity\Resource[] $resources */
        $resources = ResourceFactory::make(2)->withFoldersRelationsFor([$user], $v5Folder)->persist();
        $this->logInAs($user);

        $this->getJson("/folders/{$v5Folder->get('id')}.json?contain[children_resources]=1&api-version=2");

        $this->assertSuccess();
        // Assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayHasAttributes([
            'id',
            'metadata',
            'metadata_key_id',
            'metadata_key_type',
            'created',
            'modified',
            'created_by',
            'modified_by',
            'personal',
            'folder_parent_id',
            'children_resources',
        ], $response);
        $this->assertSame($v5Folder->get('id'), $response['id']);
        $this->assertArrayNotHasKey('name', $response);
        $this->assertSame($metadata, $response['metadata']);
        $this->assertSame($user->gpgkey->id, $response['metadata_key_id']);
        $this->assertSame('user_key', $response['metadata_key_type']);
        // assert child resources
        $this->assertCount(2, $response['children_resources']);
        $childrenResourceIds = Hash::extract($response['children_resources'], '{n}.id');
        $this->assertContains($resources[0]->get('id'), $childrenResourceIds);
        $this->assertContains($resources[1]->get('id'), $childrenResourceIds);
    }

    public function testMetadataFoldersViewController_Success_ContainPermissionsUserProfile()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        // v5 folder
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'Social media']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, [
            'passphrase' => '',
            'privateKey' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key'),
        ]);
        $v5Folder = FolderFactory::make()
            ->withPermissionsFor([$user])
            ->withFoldersRelationsFor([$user])
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $user->gpgkey->id])
            ->persist();
        $this->logInAs($user);

        $this->getJson("/folders/{$v5Folder->get('id')}.json?contain[permissions.user.profile]=1&api-version=2");

        $this->assertSuccess();
        // Assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayHasAttributes([
            'id',
            'metadata',
            'metadata_key_id',
            'metadata_key_type',
            'created',
            'modified',
            'created_by',
            'modified_by',
            'personal',
            'folder_parent_id',
            'permissions',
        ], $response);
        $this->assertSame($v5Folder->get('id'), $response['id']);
        $this->assertArrayNotHasKey('name', $response);
        $this->assertSame($metadata, $response['metadata']);
        $this->assertSame($user->gpgkey->id, $response['metadata_key_id']);
        $this->assertSame('user_key', $response['metadata_key_type']);
        // assert user profile is present
        $this->assertCount(1, $response['permissions']);
        $this->assertArrayHasKey('user', $response['permissions'][0]);
        $this->assertArrayHasKey('profile', $response['permissions'][0]['user']);
        $this->assertSame($user->get('id'), $response['permissions'][0]['user']['profile']['user_id']);
    }
}
