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
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use Cake\Console\ConsoleIo;
use Cake\Console\TestSuite\StubConsoleInput;
use Cake\Console\TestSuite\StubConsoleOutput;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\PermissionFactory;
use Passbolt\Metadata\Service\Migration\MigrateAllV4ItemsToV5Service;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataSettingsFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Metadata\Test\Utility\MigrateFoldersTestTrait;
use Passbolt\Metadata\Test\Utility\MigrateResourcesTestTrait;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * @covers \Passbolt\Metadata\Service\Migration\MigrateAllV4ItemsToV5Service
 */
class MigrateAllV4ItemsToV5ServiceTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;
    use MigrateFoldersTestTrait;
    use MigrateResourcesTestTrait;

    /**
     * @var MigrateAllV4ItemsToV5Service|null
     */
    private ?MigrateAllV4ItemsToV5Service $service = null;

    private ?ConsoleIo $io = null;

    private ?StubConsoleOutput $out = null;
    private ?StubConsoleOutput $err = null;
    private ?StubConsoleInput $in = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new MigrateAllV4ItemsToV5Service();
        // Stub console I/O
        $this->out = new StubConsoleOutput();
        $this->err = new StubConsoleOutput();
        $this->in = new StubConsoleInput([]);
        $this->io = new ConsoleIo($this->out, $this->err, $this->in);
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->out);
        unset($this->err);
        unset($this->in);
        unset($this->io);
        unset($this->service);

        parent::tearDown();
    }

    public function testMigrateAllV4ItemsToV5Service_Success(): void
    {
        MetadataSettingsFactory::make()->v5()->persist();
        // Folder
        $adaKeyInfo = [
            'armored_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key'),
            'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
        ];
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make($adaKeyInfo))
            ->persist();
        // Resource
        $v4ResourceType = ResourceTypeFactory::make()->passwordAndDescription()->persist();
        ResourceTypeFactory::make()->v5Default()->persist();
        $resource = ResourceFactory::make()
            ->with('ResourceTypes', $v4ResourceType)
            ->withCreatorAndPermission($ada)
            ->persist();
        $sharedResource = ResourceFactory::make()
            ->with('ResourceTypes', $v4ResourceType)
            ->withCreatorAndPermission(UserFactory::make()->persist())
            ->persist();
        PermissionFactory::make()->acoResource($sharedResource)->typeRead()->withAroUser()->persist();
        PermissionFactory::make()->acoResource($sharedResource)->typeUpdate()->withAroUser()->persist();
        // metadata key
        $metadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withFoldersRelationsFor([$ada])->withPermissionsFor([$ada])->persist();

        $result = $this->service->migrate($this->io);

        $this->assertSame([], $result['errors']);
        $this->assertCount(2, $result['migrated']);
        $this->assertSame('resources', $result['migrated'][0]['entity']);
        $this->assertCount(2, $result['migrated'][0]['ids']);
        $this->assertSame('folders', $result['migrated'][1]['entity']);
        $this->assertCount(1, $result['migrated'][1]['ids']);
        // assert data updated into the db
        $updatedResource = ResourceFactory::get($resource->get('id'));
        $this->assertionsForPersonalResource($updatedResource, $resource, $ada->gpgkey, [
            'private_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private.key'),
            'passphrase' => 'ada@passbolt.com',
        ]);
        $updatedResource = ResourceFactory::get($sharedResource->get('id'));
        $this->assertionsForSharedResource($updatedResource, $sharedResource, $metadataKey);
        /** @var \Passbolt\Folders\Model\Entity\Folder $updatedFolder */
        $updatedFolder = FolderFactory::get($folder->id);
        $this->assertionsForPersonalFolder($updatedFolder, $folder, $ada->gpgkey, [
            'private_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private.key'),
            'passphrase' => 'ada@passbolt.com',
        ]);
    }

    public function testMigrateAllV4ItemsToV5Service_Error_NoActiveMetadataKey(): void
    {
        MetadataSettingsFactory::make()->v5()->persist();
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

        $result = $this->service->migrate($this->io);

        $this->assertFalse($result['success']);
        $this->assertCount(2, $result['errors']);
        $resourceError = $result['errors'][0]['error_message'];
        $this->assertStringContainsString('No resources to migrate', $resourceError);
        $folderError = $result['errors'][1]['error_message'];
        $this->assertStringContainsString('Record not found in table \"metadata_keys\"', $folderError);
    }

    public function testMigrateAllV4ItemsToV5Service_Error_ItemsAreAlreadyV5(): void
    {
        MetadataSettingsFactory::make()->v5()->persist();
        $v4ResourceType = ResourceTypeFactory::make()->passwordAndDescription()->persist();
        /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $v5ResourceTypePasswordString */
        ResourceTypeFactory::make()->v5Default()->persist();
        // Shared resource.
        /** @var \App\Model\Entity\Resource $resource */
        $sharedResource = ResourceFactory::make()
            ->v5Fields(true)
            ->with('ResourceTypes', $v4ResourceType)
            ->withCreatorAndPermission(UserFactory::make()->persist())
            ->persist();
        PermissionFactory::make()->acoResource($sharedResource)->typeRead()->withAroUser()->persist();
        PermissionFactory::make()->acoResource($sharedResource)->typeUpdate()->withAroUser()->persist();

        $result = $this->service->migrate($this->io);

        $this->assertFalse($result['success']);
        $this->assertCount(2, $result['errors']);
        $resourceError = $result['errors'][0]['error_message'];
        $this->assertStringContainsString(sprintf('Resource ID \"%s\" is already V5', $sharedResource->get('id')), $resourceError);
        $folderError = $result['errors'][1]['error_message'];
        $this->assertStringContainsString('No folders to migrate', $folderError);
    }

    public function testMigrateAllV4ItemsToV5Service_Error_AllV5ItemsCreationsDisabled(): void
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
        FolderFactory::make()->withFoldersRelationsFor([$ada, $betty])->withPermissionsFor([$ada, $betty])->persist();
        // no metadata settings

        $result = $this->service->migrate($this->io);

        $this->assertFalse($result['success']);
        $this->assertCount(2, $result['errors']);
        $this->assertStringContainsString(
            'Resource creation/modification with encrypted metadata not allowed',
            $result['errors'][0]['error_message']
        );
        $this->assertStringContainsString(
            'Folder creation/modification with encrypted metadata not allowed',
            $result['errors'][1]['error_message']
        );
    }
}
