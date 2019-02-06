<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.5.0
 */
namespace Passbolt\WebInstaller\Test\TestCase\Middleware;

use Cake\Core\Configure;
use Passbolt\WebInstaller\Test\Lib\WebInstallerBootstrap;
use Passbolt\WebInstaller\Test\Lib\WebInstallerIntegrationTestCase;

class WebInstallerMiddlewareTest extends WebInstallerIntegrationTestCase
{
    // Override the following phpunit variables in order to isolate the tests.
    // It will allow the tests to work on the same constants, here PASSBOLT_IS_CONFIGURED
    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function testWebInstallerMiddlewareNotConfigured_GoToInstall_Success()
    {
        $this->mockPassboltIsNotconfigured();
        $this->get('/install');
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('<div id="container" class="page setup start', $data);
    }

    public function testWebInstallerMiddlewareNotConfigured_RedirectAllToInstall_Success()
    {
        $this->mockPassboltIsNotconfigured();
        $uris = ['/', 'auth/login', 'resources.json', 'users/recover'];
        foreach ($uris as $uri) {
            $this->get($uri);
            $this->assertResponseCode(302);
            $this->assertRedirectContains('/install');
            $this->_response = null; // Free the memory usage.
        }
    }

    public function testWebInstallerAlreadyConfigured_GoToInstall_Error()
    {
        $this->mockPassboltIsconfigured();
        $this->get('/install');
        $this->assertResponseCode(404);
    }
}
