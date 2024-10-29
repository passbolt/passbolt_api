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

use App\Service\Healthcheck\Environment\TimeSyncHealthcheck;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use Cake\TestSuite\TestCase;

class TimeSyncHealthcheckTest extends TestCase
{
    public function testHealthcheckTimeSyncIsPassed_Success(): void
    {
        $service = $this->getMockBuilder(TimeSyncHealthcheck::class)
            ->onlyMethods(['runCommand'])
            ->getMock();
        $value = <<<TXT
System clock synchronized: yes
              NTP service: active
TXT;

        $service->expects($this->once())->method('runCommand')->willReturn($value);

        $service->check();
        $this->assertTrue($service->isPassed());
    }

    public function testHealthcheckTimeSyncIsPassed_Fail(): void
    {
        $service = $this->getMockBuilder(TimeSyncHealthcheck::class)
            ->onlyMethods(['runCommand'])
            ->getMock();
        $value = <<<TXT
System clock synchronized: no
              NTP service: inactive
TXT;

        $service->expects($this->once())->method('runCommand')->willReturn($value);

        $service->check();
        $this->assertFalse($service->isPassed());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $service->level());
        $expected = __('System clock is not synchronized and NTP service is inactive.');
        $this->assertSame($expected, $service->getFailureMessage());
    }

    public function testHealthcheckTimeSyncIsPassed_ClockFail(): void
    {
        $service = $this->getMockBuilder(TimeSyncHealthcheck::class)
            ->onlyMethods(['runCommand'])
            ->getMock();
        $value = <<<TXT
System clock synchronized: no
              NTP service: active
TXT;

        $service->expects($this->once())->method('runCommand')->willReturn($value);

        $service->check();
        $this->assertFalse($service->isPassed());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $service->level());
        $expected = __('NTP service is active but the system clock is not synchronized.');
        $this->assertSame($expected, $service->getFailureMessage());
    }

    public function testHealthcheckTimeSyncIsPassed_NtpFail(): void
    {
        $service = $this->getMockBuilder(TimeSyncHealthcheck::class)
            ->onlyMethods(['runCommand'])
            ->getMock();
        $value = <<<TXT
System clock synchronized: yes
              NTP service: inactive
TXT;

        $service->expects($this->once())->method('runCommand')->willReturn($value);

        $service->check();
        $this->assertFalse($service->isPassed());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_WARNING, $service->level());
        $expected = __('System clock is synchronized but NTP service is inactive.');
        $this->assertSame($expected, $service->getFailureMessage());
    }

    public function testHealthcheckTimeSyncIsPassed_NotDetectedFail(): void
    {
        $service = $this->getMockBuilder(TimeSyncHealthcheck::class)
            ->onlyMethods(['runCommand'])
            ->getMock();
        $service->expects($this->once())->method('runCommand')->willReturn(null);

        $service->check();
        $this->assertFalse($service->isPassed());
        $expected = __('System clock and NTP service information cannot be found.');
        $this->assertSame($expected, $service->getFailureMessage());
    }
}
