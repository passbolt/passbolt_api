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

use App\Test\Factory\ResourceFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Test\Lib\Model\ResourcesModelTrait;
use Passbolt\Folders\FoldersPlugin;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Metadata\Service\MetadataKey\MetadataKeyAssertUsageService;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;

/**
 * @covers \Passbolt\Metadata\Service\MetadataKey\MetadataKeyDeleteService
 */
class MetadataKeyAssertUsageServiceTest extends AppTestCaseV5
{
    use ResourcesModelTrait;

    public function testMetadataKeyAssertUsageService_Success_Nothing(): void
    {
        $key = MetadataKeyFactory::make()->persist();
        $sut = new MetadataKeyAssertUsageService();
        $this->assertFalse($sut->isKeyInUse($key->get('id')));
    }

    public function testMetadataKeyAssertUsageService_Success_UsedByResources(): void
    {
        $key = MetadataKeyFactory::make()->persist();
        $id = $key->get('id');
        ResourceFactory::make()->v5Fields(true)->setField('metadata_key_id', $id)->persist();
        $sut = new MetadataKeyAssertUsageService();

        $this->assertTrue($sut->isKeyInUse($id));
        $this->assertTrue($sut->isUsedByResources($id));
        $this->assertFalse($sut->isUsedByFolders($id));
        $this->assertFalse($sut->isUsedByTags($id));
    }

    public function testMetadataKeyAssertUsageService_Success_UsedByFolders(): void
    {
        $this->enableFeaturePlugin(FoldersPlugin::class);
        $key = MetadataKeyFactory::make()->persist();
        $id = $key->get('id');
        ResourceFactory::make()->v5Fields(true)->setField('metadata_key_id', $id)->persist();
        $folderData = ['metadata' => '-----BEGIN PGP MESSAGE-----', 'metadata_key_id' => $id];
        FolderFactory::make()->v5Fields($folderData, true)->persist();
        $sut = new MetadataKeyAssertUsageService();

        $this->assertTrue($sut->isKeyInUse($id));
        $this->assertTrue($sut->isUsedByResources($id));
        $this->assertTrue($sut->isUsedByFolders($id));
        $this->assertFalse($sut->isUsedByTags($id));
    }

    public function testMetadataKeyAssertUsageService_Success_UsedByTags(): void
    {
        $this->markTestIncomplete();
    }

    public function testMetadataKeyAssertUsageService_Error_NotUuid(): void
    {
        $sut = new MetadataKeyAssertUsageService();
        $this->expectException(\InvalidArgumentException::class);
        $sut->isKeyInUse('🔥');
    }
}
