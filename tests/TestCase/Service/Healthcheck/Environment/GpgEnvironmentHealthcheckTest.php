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
namespace App\Test\TestCase\Service\Healthcheck\Environment;

use App\Service\Healthcheck\Environment\GpgEnvironmentHealthcheck;
use Cake\TestSuite\TestCase;

/**
 * @covers \App\Service\Healthcheck\Environment\GpgEnvironmentHealthcheck
 */
class GpgEnvironmentHealthcheckTest extends TestCase
{
    public function testHealthcheckGpgCheck_Success(): void
    {
        $service = new GpgEnvironmentHealthcheck();
        $service->check();
        $this->assertTrue($service->isPassed());
    }

    public function testHealthcheckGpgCheck_Fail(): void
    {
        $service = $this->getMockBuilder(GpgEnvironmentHealthcheck::class)->onlyMethods(['runCommand'])->getMock();
        $service->expects($this->exactly(2))->method('runCommand')->willReturnOnConsecutiveCalls(null, null);
        $service->check();
        $this->assertFalse($service->isPassed());
    }
}
