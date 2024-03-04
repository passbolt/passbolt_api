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
 * @since         4.6.0
 */

namespace Passbolt\WebInstaller\Service\Healthcheck;

use App\Service\Healthcheck\Environment\NextMinPhpVersionHealthcheck;
use App\Service\Healthcheck\Gpg\HomeVariableDefinedGpgHealthcheck;
use App\Service\Healthcheck\Gpg\HomeVariableWritableGpgHealthcheck;
use App\Service\Healthcheck\Gpg\PhpGpgModuleInstalledGpgHealthcheck;
use App\Service\Healthcheck\HealthcheckServiceCollector;

class WebInstallerSystemCheckCollector
{
    private HealthcheckServiceCollector $healthcheckServiceCollector;

    private PhpGpgModuleInstalledGpgHealthcheck $phpGpgModuleInstalledGpgHealthcheck;

    private HomeVariableDefinedGpgHealthcheck $homeVariableDefinedGpgHealthcheck;

    private HomeVariableWritableGpgHealthcheck $homeVariableWritableGpgHealthcheck;

    private PassboltConfigWritableWebInstallerHealthcheck $configWritableWebInstallerHealthcheck;

    private PublicKeyWritableWebInstallerHealthcheck $publicKeyWritableWebInstallerHealthcheck;

    private PrivateKeyWritableWebInstallerHealthcheck $privateKeyWritableWebInstallerHealthcheck;

    private IsSslWebInstallerHealthcheck $isSslWebInstallerHealthcheck;

    /**
     * @param \App\Service\Healthcheck\HealthcheckServiceCollector $healthcheckServiceCollector Health check collector.
     * @param \App\Service\Healthcheck\Gpg\PhpGpgModuleInstalledGpgHealthcheck $phpGpgModuleInstalledGpgHealthcheck Health check service.
     * @param \App\Service\Healthcheck\Gpg\HomeVariableDefinedGpgHealthcheck $homeVariableDefinedGpgHealthcheck Health check service.
     * @param \App\Service\Healthcheck\Gpg\HomeVariableWritableGpgHealthcheck $homeVariableWritableGpgHealthcheck Health check service.
     * @param \Passbolt\WebInstaller\Service\Healthcheck\PassboltConfigWritableWebInstallerHealthcheck $configWritableWebInstallerHealthcheck Health check service.
     * @param \Passbolt\WebInstaller\Service\Healthcheck\PublicKeyWritableWebInstallerHealthcheck $publicKeyWritableWebInstallerHealthcheck Health check service.
     * @param \Passbolt\WebInstaller\Service\Healthcheck\PrivateKeyWritableWebInstallerHealthcheck $privateKeyWritableWebInstallerHealthcheck Health check service.
     * @param \Passbolt\WebInstaller\Service\Healthcheck\IsSslWebInstallerHealthcheck $isSslWebInstallerHealthcheck Health check service.
     */
    public function __construct(
        HealthcheckServiceCollector $healthcheckServiceCollector,
        // GPG specific checks
        PhpGpgModuleInstalledGpgHealthcheck $phpGpgModuleInstalledGpgHealthcheck,
        HomeVariableDefinedGpgHealthcheck $homeVariableDefinedGpgHealthcheck,
        HomeVariableWritableGpgHealthcheck $homeVariableWritableGpgHealthcheck,
        // Web installer specific checks
        PassboltConfigWritableWebInstallerHealthcheck $configWritableWebInstallerHealthcheck,
        PublicKeyWritableWebInstallerHealthcheck $publicKeyWritableWebInstallerHealthcheck,
        PrivateKeyWritableWebInstallerHealthcheck $privateKeyWritableWebInstallerHealthcheck,
        IsSslWebInstallerHealthcheck $isSslWebInstallerHealthcheck
    ) {
        $this->healthcheckServiceCollector = $healthcheckServiceCollector;
        $this->phpGpgModuleInstalledGpgHealthcheck = $phpGpgModuleInstalledGpgHealthcheck;
        $this->homeVariableDefinedGpgHealthcheck = $homeVariableDefinedGpgHealthcheck;
        $this->homeVariableWritableGpgHealthcheck = $homeVariableWritableGpgHealthcheck;
        $this->configWritableWebInstallerHealthcheck = $configWritableWebInstallerHealthcheck;
        $this->publicKeyWritableWebInstallerHealthcheck = $publicKeyWritableWebInstallerHealthcheck;
        $this->privateKeyWritableWebInstallerHealthcheck = $privateKeyWritableWebInstallerHealthcheck;
        $this->isSslWebInstallerHealthcheck = $isSslWebInstallerHealthcheck;
    }

    /**
     * Returns all services required for system check controller.
     *
     * @return \App\Service\Healthcheck\HealthcheckServiceInterface[]
     */
    public function getServices(): array
    {
        $services = [];

        /**
         * Extract environment domain services but without NextMinPhpVersionHealthcheck
         */
        foreach ($this->healthcheckServiceCollector->getServices() as $healthcheckService) {
            if (
                $healthcheckService->domain() === HealthcheckServiceCollector::DOMAIN_ENVIRONMENT
                && !$healthcheckService instanceof NextMinPhpVersionHealthcheck
            ) {
                $services[] = $healthcheckService;
            }
        }

        /**
         * Append few GPG services that required
         */
        $services[] = $this->phpGpgModuleInstalledGpgHealthcheck;
        $services[] = $this->homeVariableDefinedGpgHealthcheck;
        $services[] = $this->homeVariableWritableGpgHealthcheck;

        /**
         * Add web installer specific health checks
         */
        $webinstallerSpecificServices = [
            $this->configWritableWebInstallerHealthcheck,
            $this->publicKeyWritableWebInstallerHealthcheck,
            $this->privateKeyWritableWebInstallerHealthcheck,
            $this->isSslWebInstallerHealthcheck,
        ];
        foreach ($webinstallerSpecificServices as $webinstallerSpecificService) {
            $services[] = $webinstallerSpecificService;
        }

        return $services;
    }
}
