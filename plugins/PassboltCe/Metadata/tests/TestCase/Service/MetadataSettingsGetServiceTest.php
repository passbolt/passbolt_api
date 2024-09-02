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

use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Lib\AppTestCase;
use Passbolt\Metadata\Model\Dto\MetadataSettingsDto;
use Passbolt\Metadata\Service\MetadataSettingsGetService;
use Passbolt\Metadata\Test\Factory\MetadataSettingsFactory;

class MetadataSettingsGetServiceTest extends AppTestCase
{
    public function testMetadataSettingsGetService_getSettings_NotEntryReturnsDefault(): void
    {
        $sut = new MetadataSettingsGetService();
        $this->assertEquals(MetadataSettingsFactory::getDefaultDataV4(), $sut->getSettings()->toArray());
    }

    public function testMetadataSettingsGetService_getSettings_NotDefault(): void
    {
        $data = MetadataSettingsFactory::getDefaultDataV4();
        $data[MetadataSettingsDto::DEFAULT_COMMENT_TYPE] = 'v5';
        $data[MetadataSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS] = false;
        $data[MetadataSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS] = true;
        MetadataSettingsFactory::make()->value(json_encode($data))->persist();
        $sut = new MetadataSettingsGetService();
        $this->assertEquals($data, $sut->getSettings()->toArray());
    }

    public function testMetadataSettingsGetService_getSettings_BrokenSettingsReturnsDefault(): void
    {
        $this->assertEquals(0, MetadataSettingsFactory::count());
        $data = MetadataSettingsFactory::getDefaultDataV4();
        $data[MetadataSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS] = '🔥';
        MetadataSettingsFactory::make()->value(json_encode($data))->persist();
        $sut = new MetadataSettingsGetService();
        $this->assertEquals(MetadataSettingsFactory::getDefaultDataV4(), $sut->getSettings()->toArray());
    }

    public function testMetadataSettingsGetService_getSettings_BrokenJsonSettingsReturnsDefault(): void
    {
        OrganizationSettingFactory::make()
            ->setPropertyAndValue(MetadataSettingsGetService::ORG_SETTING_PROPERTY, '🔥')
            ->persist();
        $sut = new MetadataSettingsGetService();
        $this->assertEquals(MetadataSettingsFactory::getDefaultDataV4(), $sut->getSettings()->toArray());
    }
}
