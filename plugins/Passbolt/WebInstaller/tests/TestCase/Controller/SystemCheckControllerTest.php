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
 * @since         2.5.0
 */
namespace Passbolt\WebInstaller\Test\TestCase\Controller;

use Passbolt\WebInstaller\Test\Lib\WebInstallerIntegrationTestCase;

class SystemCheckControllerTest extends WebInstallerIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->skipTestIfNotWebInstallerFriendly();
        $this->mockPassboltIsNotconfigured();
        $this->initWebInstallerSession();
    }

    /**
     * note: creates an issue with healthcheck on a webserverless environment.
     */
    public function testWebInstallerSystemCheckViewSuccess()
    {
        $this->get('/install/system_check');
        $data = $this->_getBodyAsString();
        $this->assertResponseOk();
        $this->assertStringContainsString('. Database', $data);
        $this->assertStringContainsString('Nice one! Your environment is ready for passbolt.', $data);
        $this->assertStringContainsString('Environment is configured correctly.', $data);
        $this->assertStringContainsString('GPG is configured correctly.', $data);
        $this->assertStringContainsString('Start configuration', $data);
    }

    /**
     * note: creates an issue with healthcheck on a webserverless environment.
     */
    public function testWebInstallerSystemCheckViewSuccess_LicensePluginEnabled()
    {
        $this->get('/install/system_check');
        $data = $this->_getBodyAsString();
        $this->assertResponseOk();
        $this->assertStringContainsString('. Subscription key', $data);
        $this->assertStringContainsString('Start configuration', $data);
    }
}
