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

use App\Service\Healthcheck\Environment\NextMinPhpVersionHealthcheck;
use App\Service\Healthcheck\Environment\PhpVersionHealthcheck;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Passbolt\WebInstaller\Service\Healthcheck\PassboltConfigWritableWebInstallerHealthcheck;
use Passbolt\WebInstaller\Service\Healthcheck\PrivateKeyWritableWebInstallerHealthcheck;
use Passbolt\WebInstaller\Service\Healthcheck\PublicKeyWritableWebInstallerHealthcheck;
use Passbolt\WebInstaller\Test\Lib\WebInstallerIntegrationTestCase;

class SystemCheckControllerTest extends WebInstallerIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->skipTestIfNotWebInstallerFriendly();
        $this->mockPassboltIsNotconfigured();
        $this->initWebInstallerSession();
        $this->mockService(ServerRequest::class, function () {
            return (new ServerRequest())->withEnv('HTTPS', 'on');
        });
        $this->mockService(PhpVersionHealthcheck::class, function () {
            $stub = $this->getMockBuilder(PhpVersionHealthcheck::class)->onlyMethods(['isPassed'])->getMock();
            $stub->method('isPassed')->willReturn(true);

            return $stub;
        });
        $this->mockService(NextMinPhpVersionHealthcheck::class, function () {
            $stub = $this->getMockBuilder(NextMinPhpVersionHealthcheck::class)->onlyMethods(['isPassed'])->getMock();
            $stub->method('isPassed')->willReturn(true);

            return $stub;
        });
    }

    /**
     * note: creates an issue with healthcheck on a webserverless environment.
     */
    public function testWebInstallerSystemCheckViewSuccess()
    {
        $this->get('/install/system_check');
        $this->assertCanStartSetup();

        $this->assertResponseContains('1. System check');
        $this->assertResponseContains('<div class="message success">Environment is configured correctly.</div>');
        $this->assertResponseContains('<div class="message success">GPG is configured correctly.</div>');
        $this->assertResponseContains('<div class="message success">SSL access is enabled.</div>');
    }

    public function testWebInstallerSystemCheck_Ssl_Off()
    {
        $this->mockService(ServerRequest::class, function () {
            return (new ServerRequest())->withEnv('HTTPS', 'off');
        });

        $this->get('/install/system_check');

        $this->assertCanStartSetup();
        $this->assertResponseContains('<div class="message warning">SSL access is not enabled. You can still proceed, but it is highly recommended that you configure your web server to use HTTPS before you continue.</div>');
    }

    public function testWebInstallerSystemCheck_PHP_Version_Too_Low()
    {
        $this->mockFailingHealthcheck(PhpVersionHealthcheck::class);

        $this->get('/install/system_check');

        $this->assertCannotStartSetup();
        $this->assertResponseContains('<div class="message error">PHP version is too');
    }

    public function testWebInstallerSystemCheck_PHP_Below_Next_Version_Warning()
    {
        $this->mockFailingHealthcheck(NextMinPhpVersionHealthcheck::class);

        $this->get('/install/system_check');

        $this->assertCanStartSetup();
        $msg = 'PHP version less than ' . Configure::read(NextMinPhpVersionHealthcheck::PHP_NEXT_MIN_VERSION_CONFIG) . ' will soon be not supported by passbolt, so consider upgrading your operating system or PHP environment.'; // phpcs:ignore
        $this->assertResponseContains('<div class="message warning">' . $msg);
        $this->assertResponseNotContains('Environment is configured correctly.');
        $this->assertResponseContains('GPG is configured correctly.');
    }

    public function testWebInstallerSystemCheck_WebInstallerChecksFailing()
    {
        $servicesToMock = [
            PassboltConfigWritableWebInstallerHealthcheck::class,
            PublicKeyWritableWebInstallerHealthcheck::class,
            PrivateKeyWritableWebInstallerHealthcheck::class,
        ];
        foreach ($servicesToMock as $service) {
            $this->mockFailingHealthcheck($service);
        }

        $this->get('/install/system_check');

        $this->assertResponseContains('<h3>Environment</h3>');
        $this->assertResponseContains('<h3>GPG Configuration</h3>');
        $this->assertResponseContains('<h3>SSL</h3>');
        $this->assertResponseContains('<div class="message error">The server OpenPGP public key file is not writable.</div>');
        $this->assertResponseContains('<div class="message error">The server OpenPGP private key file is not writable.</div>');
        $this->assertResponseContains('<div class="message error">The passbolt config is not writable.</div>');
        $this->assertCannotStartSetup();
    }

    public function testWebInstallerSystemCheck_Gpg_Directory_Undefined()
    {
        Configure::write('passbolt.gpg.backend', 'foo');

        $this->get('/install/system_check');

        $this->assertResponseContains('<h3>Environment</h3>');
        $this->assertResponseContains('<h3>GPG Configuration</h3>');
        $this->assertResponseContains('<h3>SSL</h3>');
        $this->assertResponseContains('<div class="message error">PHP GPG Module is not installed or loaded.</div>');
        $this->assertResponseContains('<div class="message error">The environment variable GNUPGHOME is set to , but the directory does not exist.</div>');
        $this->assertResponseContains('<div class="message error">The directory  containing the keyring is not writable by the webserver user.</div>');
        $this->assertCannotStartSetup();
    }

    private function assertCanStartSetup(): void
    {
        $this->assertResponseOk();
        $this->assertResponseContains('Nice one! Your environment is ready for passbolt.');
        $this->assertResponseContains('Start configuration');
    }

    private function assertCannotStartSetup(): void
    {
        $this->assertResponseOk();
        $this->assertResponseContains('Oops!! Passbolt cannot run yet on your server.');
    }

    private function mockFailingHealthcheck(string $service): void
    {
        $this->mockService($service, function () use ($service) {
            $stub = $this->getMockBuilder($service)->onlyMethods(['isPassed'])->getMock();
            $stub->method('isPassed')->willReturn(false);

            return $stub;
        });
    }
}
