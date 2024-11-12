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
use App\Test\Lib\AppTestCaseV5;
use Passbolt\Metadata\Service\MetadataKeysSettingsAssertService;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;

class MetadataKeysSettingsAssertServiceTest extends AppTestCaseV5
{
    public function testMetadataKeysSettingsAssertService_Success(): void
    {
        $data = MetadataKeysSettingsFactory::getDefaultData();
        $sut = new MetadataKeysSettingsAssertService();
        $dto = $sut->assert($data);
        $this->assertEquals($data, $dto->toArray());
    }

    public function testMetadataKeysSettingsAssertService_ErrorFormat(): void
    {
        $sut = new MetadataKeysSettingsAssertService();
        $this->expectException(FormValidationException::class);
        $sut->assert([]);
    }

    public function testMetadataKeysSettingsAssertService_ErrorZeroKnowledgeButNoServerPrivateKeys(): void
    {
        $this->markTestIncomplete();
    }
}
