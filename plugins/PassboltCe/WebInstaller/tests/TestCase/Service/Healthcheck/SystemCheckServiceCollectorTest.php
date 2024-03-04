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
 * @since         4.3.0
 */

namespace Passbolt\WebInstaller\Test\TestCase\Service\Healthcheck;

use App\Service\Healthcheck\Application\JsProdApplicationHealthcheck;
use App\Service\Healthcheck\Application\LatestVersionApplicationHealthcheck;
use App\Service\Healthcheck\Environment\ImageHealthcheck;
use App\Service\Healthcheck\Environment\IntlHealthcheck;
use App\Service\Healthcheck\Environment\LogFolderWritableHealthcheck;
use App\Service\Healthcheck\Environment\MbstringHealthcheck;
use App\Service\Healthcheck\Environment\NextMinPhpVersionHealthcheck;
use App\Service\Healthcheck\Environment\PcreHealthcheck;
use App\Service\Healthcheck\Environment\PhpVersionHealthcheck;
use App\Service\Healthcheck\Environment\TmpFolderWritableHealthcheck;
use App\Service\Healthcheck\Gpg\HomeVariableDefinedGpgHealthcheck;
use App\Service\Healthcheck\Gpg\HomeVariableWritableGpgHealthcheck;
use App\Service\Healthcheck\Gpg\KeyNotDefaultGpgHealthcheck;
use App\Service\Healthcheck\Gpg\PhpGpgModuleInstalledGpgHealthcheck;
use App\Service\Healthcheck\Gpg\PrivateKeyFingerprintMatchGpgHealthcheck;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Test\Lib\AppTestCase;
use Cake\Http\ServerRequest;
use Passbolt\WebInstaller\Service\Healthcheck\IsSslWebInstallerHealthcheck;
use Passbolt\WebInstaller\Service\Healthcheck\PassboltConfigWritableWebInstallerHealthcheck;
use Passbolt\WebInstaller\Service\Healthcheck\PrivateKeyWritableWebInstallerHealthcheck;
use Passbolt\WebInstaller\Service\Healthcheck\PublicKeyWritableWebInstallerHealthcheck;
use Passbolt\WebInstaller\Service\Healthcheck\SystemCheckServiceCollector;

/**
 * @covers \Passbolt\WebInstaller\Service\Healthcheck\SystemCheckServiceCollector
 */
class SystemCheckServiceCollectorTest extends AppTestCase
{
    public function testSystemCheckServiceCollector_GetServices()
    {
        $healthcheckServiceCollector = new HealthcheckServiceCollector();
        // Add Env health checks
        $envHealthchecks = [
            PhpVersionHealthcheck::class,
            ImageHealthcheck::class,
            IntlHealthcheck::class,
            LogFolderWritableHealthcheck::class,
            MbstringHealthcheck::class,
            NextMinPhpVersionHealthcheck::class, // This should be skipped as a result, so make sure to add this
            PcreHealthcheck::class,
            TmpFolderWritableHealthcheck::class,
        ];
        foreach ($envHealthchecks as $envHealthcheck) {
            $healthcheckServiceCollector->addService(new $envHealthcheck());
        }
        // Add GPG health checks
        $gpgHealthchecks = [
            PhpGpgModuleInstalledGpgHealthcheck::class,
            HomeVariableDefinedGpgHealthcheck::class,
            HomeVariableWritableGpgHealthcheck::class,
            // Below services should be skipped
            KeyNotDefaultGpgHealthcheck::class,
            PrivateKeyFingerprintMatchGpgHealthcheck::class,
        ];
        foreach ($gpgHealthchecks as $gpgHealthcheck) {
            $healthcheckServiceCollector->addService(new $gpgHealthcheck());
        }
        // Add some more services that should be skipped
        $noRequiredServices = [
            JsProdApplicationHealthcheck::class,
            LatestVersionApplicationHealthcheck::class,
        ];
        foreach ($noRequiredServices as $noRequiredService) {
            $healthcheckServiceCollector->addService(new $noRequiredService());
        }
        // Create mock for request object required for IsSslWebInstallerHealthcheck
        $request = $this->getMockBuilder(ServerRequest::class)->getMock();
        $request->method('is')->willReturn(true);

        $systemCheckServiceCollector = new SystemCheckServiceCollector(
            $healthcheckServiceCollector,
            new PhpGpgModuleInstalledGpgHealthcheck(),
            new HomeVariableDefinedGpgHealthcheck(),
            new HomeVariableWritableGpgHealthcheck(),
            new PassboltConfigWritableWebInstallerHealthcheck(),
            new PublicKeyWritableWebInstallerHealthcheck(),
            new PrivateKeyWritableWebInstallerHealthcheck(),
            new IsSslWebInstallerHealthcheck($request)
        );
        $services = $systemCheckServiceCollector->getServices();

        /**
         * What we need is following:
         * - All env domain checks except NextMinPhpVersionHealthcheck = 7
         * - Only 3(PhpGpgModuleInstalledGpgHealthcheck, HomeVariableDefinedGpgHealthcheck, gpgHomeWritable) GPG services = 3
         * - Web installer specific services = 4
         *
         * Total: 14
         */
        $this->assertCount(14, $services);
    }
}
