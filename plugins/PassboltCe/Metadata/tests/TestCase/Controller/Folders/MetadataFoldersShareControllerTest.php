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

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Passbolt\Folders\FoldersPlugin;
use Passbolt\Folders\Service\Folders\FoldersShareService;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\PermissionFactory;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Folders\Controller\Folders\FoldersShareController
 */
class MetadataFoldersShareControllerTest extends AppIntegrationTestCaseV5
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

    public function testMetadataFoldersShareController_Success_ShareWithAUser()
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
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'Social media']);
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        // v5 folder
        $folder = FolderFactory::make()
            ->withPermissionsFor([$ada])
            ->withFoldersRelationsFor([$ada])
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $metadataKey->id], true)
            ->persist();
        $this->logInAs($ada);

        $data = [
            'permissions' => [
                ['aro' => PermissionsTable::USER_ARO, 'aro_foreign_key' => $betty->id, 'type' => Permission::READ],
            ],
        ];
        $folderId = $folder->get('id');
        $this->postJson("/share/folder/{$folderId}.json?api-version=2", $data);

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
        ], $response);
        $this->assertSame($folderId, $response['id']);
        $this->assertArrayNotHasKey('name', $response);
        $permissions = PermissionFactory::find()->where(['aro' => PermissionsTable::USER_ARO, 'aro_foreign_key' => $betty->id])->toArray();
        $this->assertSame(1, count($permissions));
        $this->assertSame(PermissionsTable::FOLDER_ACO, $permissions[0]['aco']);
        $this->assertSame($folderId, $permissions[0]['aco_foreign_key']);
        // assert event
        $this->assertEventFiredWith(
            FoldersShareService::FOLDERS_SHARE_FOLDER_EVENT,
            'isV5',
            true
        );
    }

    public function testMetadataFoldersShareController_Success_V4MetadataFieldsAreNotPresent()
    {
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\User $betty */
        $betty = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\User $jane */
        $jane = UserFactory::make()->user()->active()->persist();
        // v4 folder
        $folder = FolderFactory::make(['name' => 'marketing'])
            ->withPermissionsFor([$ada])
            ->withFoldersRelationsFor([$ada])
            ->persist();
        $this->logInAs($ada);

        $data = [
            'permissions' => [
                ['aro' => PermissionsTable::USER_ARO, 'aro_foreign_key' => $betty->id, 'type' => Permission::READ],
                ['aro' => PermissionsTable::USER_ARO, 'aro_foreign_key' => $jane->id, 'type' => Permission::UPDATE],
            ],
        ];
        $folderId = $folder->get('id');
        $this->postJson("/share/folder/{$folderId}.json?api-version=2", $data);

        $this->assertSuccess();
        // Assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayHasAttributes([
            'id',
            'name',
            'created',
            'modified',
            'created_by',
            'modified_by',
            'personal',
            'folder_parent_id',
        ], $response);
        $this->assertSame($folderId, $response['id']);
        $this->assertArrayNotHasKey('metadata', $response);
        $this->assertArrayNotHasKey('metadata_key_id', $response);
        $this->assertArrayNotHasKey('metadata_key_type', $response);
        $this->assertSame(3, PermissionFactory::count());
    }

    public function testMetadataFoldersShareController_Error_TryToShareFolderWithUserKeyMetadataType()
    {
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'marketing']);
        $metadata = $this->encryptForUser($clearTextMetadata, $ada, $this->getAdaNoPassphraseKeyInfo());
        /** @var \App\Model\Entity\User $betty */
        $betty = UserFactory::make()->user()->active()->persist();
        // v5 folder
        $folder = FolderFactory::make()
            ->withPermissionsFor([$ada])
            ->withFoldersRelationsFor([$ada])
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $ada->gpgkey->id])
            ->persist();
        $this->logInAs($ada);

        $data = [
            'permissions' => [
                ['aro' => PermissionsTable::USER_ARO, 'aro_foreign_key' => $betty->id, 'type' => Permission::READ],
            ],
        ];
        $folderId = $folder->get('id');
        $this->postJson("/share/folder/{$folderId}.json?api-version=2", $data);

        $this->assertBadRequestError('Folder can not be shared');
    }
}
