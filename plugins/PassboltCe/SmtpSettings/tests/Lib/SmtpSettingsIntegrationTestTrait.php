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
 * @since         3.8.0
 */

namespace Passbolt\SmtpSettings\Test\Lib;

use Passbolt\SmtpSettings\Service\SmtpSettingsSendTestEmailService;

/**
 * @covers \Passbolt\SmtpSettings\Service\SmtpSettingsSetService
 */
trait SmtpSettingsIntegrationTestTrait
{
    /**
     * Throwing an exception on email sending is complex as the Debug Smtp Transport will bypass this.
     * This method injects a mock of the SmtpSettingsSendTestEmailService that will generate an exception
     * and will return a trace.
     *
     * @param array $trace
     * @param string $errorMessage
     */
    private function mockSmtpSettingsSendTestEmailServiceFail(array $trace, string $errorMessage = '')
    {
        $this->mockService(SmtpSettingsSendTestEmailService::class, function () use ($trace, $errorMessage) {
            $service = $this->getMockBuilder(SmtpSettingsSendTestEmailService::class)
                ->onlyMethods(['getTrace', 'sendTestEmail'])
                ->getMock();
            $service->method('sendTestEmail')->willThrowException(new \Exception($errorMessage));
            $service->method('getTrace')->willReturn($trace);

            return $service;
        });
    }

    /**
     * Mock the response of the SmtpSettingsSendTestEmailService to simulate a successful sent email
     *
     * @param array $trace
     */
    private function mockSmtpSettingsSendTestEmailServiceSuccessful(array $trace = [])
    {
        $this->mockService(SmtpSettingsSendTestEmailService::class, function () use ($trace) {
            $service = $this->getMockBuilder(SmtpSettingsSendTestEmailService::class)
                ->onlyMethods(['getTrace'])
                ->getMock();
            $service->method('getTrace')->willReturn($trace);

            return $service;
        });
    }
}
