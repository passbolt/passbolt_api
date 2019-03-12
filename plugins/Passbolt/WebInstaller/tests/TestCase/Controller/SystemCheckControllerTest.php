<?php
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

use App\Utility\Healthchecks;
use Cake\Core\Configure;
use Passbolt\WebInstaller\Test\Lib\WebInstallerIntegrationTestCase;

class SystemCheckControllerTest extends WebInstallerIntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->skipTestIfNotWebInstallerFriendly();
        $this->mockPassboltIsNotconfigured();
        $this->initWebInstallerSession();
    }

    public function testWebInstallerSystemCheckViewSuccess()
    {
        $this->markTestSkipped('creates an issue with healthcheck on a webserverless environment.');
//        $this->get('/install/system_check');
//        $data = ($this->_getBodyAsString());
//        $this->assertResponseOk();
//        $this->assertContains('2. Database', $data);
//        $this->assertContains('Nice one! Your environment is ready for passbolt.', $data);
//        $this->assertContains('Environment is configured correctly.', $data);
//        $this->assertContains('GPG is configured correctly.', $data);
//        $this->assertContains('install/database" class="button primary next big">Start configuration', $data);
    }

    public function testWebInstallerSystemCheckViewSuccess_LicensePluginEnabled()
    {
        $this->markTestSkipped('creates an issue with healthcheck on a webserverless environment.');
//        Configure::write('passbolt.plugins.license', ['version' => '2.0.0']);
//        $this->get('/install/system_check');
//        $data = ($this->_getBodyAsString());
//        $this->assertResponseOk();
//        $this->assertContains('2. Subscription key', $data);
//        $this->assertContains('install/license_key" class="button primary next big">Start configuration', $data);
    }
}
