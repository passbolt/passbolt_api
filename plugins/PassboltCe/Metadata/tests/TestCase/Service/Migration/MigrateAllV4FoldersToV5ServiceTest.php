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

namespace Passbolt\Metadata\Test\TestCase\Service\Migration;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use Cake\Http\Exception\BadRequestException;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Metadata\Service\Migration\MigrateAllV4FoldersToV5Service;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Metadata\Test\Utility\MigrateFoldersTestTrait;

/**
 * @covers \Passbolt\Metadata\Service\Migration\MigrateAllV4FoldersToV5Service
 */
class MigrateAllV4FoldersToV5ServiceTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;
    use MigrateFoldersTestTrait;

    /**
     * @var MigrateAllV4FoldersToV5Service|null
     */
    private ?MigrateAllV4FoldersToV5Service $service = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new MigrateAllV4FoldersToV5Service();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testMigrateAllV4FoldersToV5Service_Success_PersonalFolder(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        // Folder 1
        $adaKeyInfo = [
            'armored_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key'),
            'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
        ];
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make($adaKeyInfo))
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withFoldersRelationsFor([$ada])->withPermissionsFor([$ada])->persist();

        $result = $this->service->migrate();

        $this->assertSame([], $result['errors']);
        /** @var \Passbolt\Folders\Model\Entity\Folder $updatedFolder */
        $updatedFolder = FolderFactory::get($folder->id);
        $this->assertionsForPersonalFolder($updatedFolder, $folder, $ada->gpgkey, [
            'private_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key'),
            'passphrase' => '',
        ]);
    }

    public function testMigrateAllV4FoldersToV5Service_Success_SharedFolder(): void
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
        // create metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withFoldersRelationsFor([$ada, $betty])->withPermissionsFor([$ada, $betty])->persist();

        $result = $this->service->migrate();

        $this->assertSame([], $result['errors']);
        /** @var \Passbolt\Folders\Model\Entity\Folder $updatedFolder */
        $updatedFolder = FolderFactory::get($folder->id);
        $this->assertionsForSharedFolder($updatedFolder, $folder, $metadataKey);
    }

    public function testMigrateAllV4FoldersToV5Service_Success_MultipleFolders(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $makiKeyInfo = $this->getUserKeyInfo();
        /** @var \App\Model\Entity\User $maki */
        $maki = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withKeyInfo($makiKeyInfo))
            ->user()
            ->active()
            ->persist();
        // create metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        // Personal folder of Ada
        $folder1 = FolderFactory::make()->withFoldersRelationsFor([$ada])->withPermissionsFor([$ada])->persist();
        // Personal folder of Maki
        $folder2 = FolderFactory::make()->withFoldersRelationsFor([$maki])->withPermissionsFor([$maki])->persist();
        // Shared folder main (parent)
        $folder3 = FolderFactory::make()->withFoldersRelationsFor([$ada, $maki])->withPermissionsFor([$ada, $maki])->persist();
        // Shared child folder (level-2)
        $folder4 = FolderFactory::make()->withFoldersRelationsFor([$ada, $maki], $folder3)->withPermissionsFor([$ada, $maki])->persist();
        // Personal child folder of Maki (level-3)
        $folder5 = FolderFactory::make()->withFoldersRelationsFor([$maki], $folder4)->withPermissionsFor([$maki])->persist();

        $result = $this->service->migrate();

        $this->assertSame([], $result['errors']);
        $expected = [
            $folder1->get('id'),
            $folder2->get('id'),
            $folder3->get('id'),
            $folder4->get('id'),
            $folder5->get('id'),
        ];
        sort($expected);
        sort($result['migrated']);
        $this->assertArrayEqualsCanonicalizing($expected, $result['migrated']);
        // Personal folder of Ada
        /** @var \Passbolt\Folders\Model\Entity\Folder $updatedFolder1 */
        $updatedFolder1 = FolderFactory::get($folder1->get('id'));
        $this->assertionsForPersonalFolder($updatedFolder1, $folder1, $ada->gpgkey, $this->getAdaNoPassphraseKeyInfo());
        // Personal folder of Betty
        /** @var \Passbolt\Folders\Model\Entity\Folder $updatedFolder2 */
        $updatedFolder2 = FolderFactory::get($folder2->get('id'));
        $this->assertionsForPersonalFolder($updatedFolder2, $folder2, $maki->gpgkey, $makiKeyInfo);
        // Shared folder main (parent)
        /** @var \Passbolt\Folders\Model\Entity\Folder $updatedFolder3 */
        $updatedFolder3 = FolderFactory::get($folder3->get('id'));
        $this->assertionsForSharedFolder($updatedFolder3, $folder3, $metadataKey);
        // Shared child folder (level-2)
        /** @var \Passbolt\Folders\Model\Entity\Folder $updatedFolder4 */
        $updatedFolder4 = FolderFactory::get($folder4->get('id'));
        $this->assertionsForSharedFolder($updatedFolder4, $folder4, $metadataKey);
        // Personal child folder of Maki (level-3)
        /** @var \Passbolt\Folders\Model\Entity\Folder $updatedFolder5 */
        $updatedFolder5 = FolderFactory::get($folder5->get('id'));
        $this->assertionsForPersonalFolder($updatedFolder5, $folder5, $maki->gpgkey, $makiKeyInfo);
    }

    public function testMigrateAllV4FoldersToV5Service_Error_NoActiveMetadataKey(): void
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
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        FolderFactory::make()->withFoldersRelationsFor([$ada, $betty])->withPermissionsFor([$ada, $betty])->persist();

        $result = $this->service->migrate();

        $this->assertFalse($result['success']);
        $this->assertCount(1, $result['errors']);
        $this->assertStringContainsString('Record not found in table "metadata_keys"', $result['errors'][0]['error_message']);
    }

    public function testMigrateAllV4FoldersToV5Service_Error_FolderIsAlreadyV5(): void
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
        // Create a shared V5 folder
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'marketing']);
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);
        FolderFactory::make()
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $metadataKey->id], true)
            ->withFoldersRelationsFor([$ada, $betty])
            ->withPermissionsFor([$ada, $betty])
            ->persist();

        $result = $this->service->migrate();

        $this->assertFalse($result['success']);
        $this->assertCount(1, $result['errors']);
    }

    public function testMigrateAllV4FoldersToV5Service_Error_AllowCreationOfV5FoldersDisabled(): void
    {
        MetadataTypesSettingsFactory::make()->v4()->persist(); // disable v5 creation
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
        FolderFactory::make()->withFoldersRelationsFor([$ada, $betty])->withPermissionsFor([$ada, $betty])->persist();

        try {
            $this->service->migrate();
        } catch (\Exception $e) {
            $this->assertInstanceOf(BadRequestException::class, $e);
            $this->assertStringContainsString('Folder creation/modification with encrypted metadata not allowed', $e->getMessage());
        }
    }
}
