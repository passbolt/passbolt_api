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
use App\Test\Lib\AppTestCase;
use Passbolt\Metadata\Service\MetadataSettingsAssertService;
use Passbolt\Metadata\Test\Factory\MetadataSettingsFactory;

class MetadataSettingsAssertServiceTest extends AppTestCase
{
    public function testMetadataSettingsAssertService_Success(): void
    {
        $data = MetadataSettingsFactory::getDefaultData();
        $sut = new MetadataSettingsAssertService();
        $dto = $sut->assert($data);
        $this->assertEquals($data, $dto->toArray());
    }

    public function testMetadataSettingsAssertService_ErrorFormat(): void
    {
        $sut = new MetadataSettingsAssertService();
        $this->expectException(FormValidationException::class);
        $dto = $sut->assert([]);
    }

    /**
     * Admin select a default version but all resource types are deleted for this version
     *
     * @return void
     */
    public function testMetadataSettingsAssertService_ErrorRules_DefaultResourceTypesAreDeleted(): void
    {
        $this->markTestIncomplete();
    }

    /**
     * Error: v5 default selected but metadata key does not exist or is not available to all
     *
     * @return void
     */
    public function testMetadataSettingsAssertService_ErrorRules_DefaultCryptoNoBueno(): void
    {
        $this->markTestIncomplete();
    }
}
