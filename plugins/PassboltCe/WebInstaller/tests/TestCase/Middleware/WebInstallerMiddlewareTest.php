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
namespace Passbolt\WebInstaller\Test\TestCase\Middleware;

use Cake\Core\Configure;
use Passbolt\WebInstaller\Test\Lib\WebInstallerIntegrationTestCase;

class WebInstallerMiddlewareTest extends WebInstallerIntegrationTestCase
{
    public function testWebInstallerMiddleware_testConfiguredRedirect()
    {
        $backUpConfigured = Configure::read('passbolt.webInstaller.configured');
        Configure::write('passbolt.webInstaller.configured', true);
        $this->get('/users');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/auth/login');
        Configure::write('passbolt.webInstaller.configured', $backUpConfigured);
    }

    public function testWebInstallerMiddleware_testConfiguredForbidden()
    {
        $backUpConfigured = Configure::read('passbolt.webInstaller.configured');
        Configure::write('passbolt.webInstaller.configured', true);
        // We load the plugin here manually in order to ensure the routes to be defined.
        $this->loadPlugins(['Passbolt/WebInstaller' => ['bootstrap' => true, 'routes' => true]]);
        $this->get('/install');
        $this->assertResponseCode(403);
        Configure::write('passbolt.webInstaller.configured', $backUpConfigured);
    }

    public function testWebInstallerMiddleware_testMockNotConfiguredStartPage()
    {
        $this->mockPassboltIsNotconfigured();
        $this->get('/install');
        $data = $this->_getBodyAsString();
        $this->assertResponseOk();
        $this->assertStringContainsString('<div id="container" class="page setup start', $data);
    }

    public function testWebInstallerMiddleware_testNotConfiguredRedirect()
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
}
