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
 * @since         2.0.0
 */
namespace Passbolt\WebInstaller\Controller;

use App\Service\Healthcheck\Environment\NextMinPhpVersionHealthcheck;
use App\Service\Healthcheck\Gpg\HomeVariableDefinedGpgHealthcheck;
use App\Service\Healthcheck\Gpg\HomeVariableWritableGpgHealthcheck;
use App\Service\Healthcheck\Gpg\PhpGpgModuleInstalledGpgHealthcheck;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use Cake\Collection\Collection;
use Cake\Routing\Router;

class SystemCheckController extends WebInstallerController
{
    /**
     * Index
     *
     * @param \App\Service\Healthcheck\HealthcheckServiceCollector $healthcheckServiceCollector healthcheck service collector.
     * @return void
     */
    public function index(HealthcheckServiceCollector $healthcheckServiceCollector)
    {
        $systemCheckHealthcheckServices = $this->getSystemCheckHealthcheckServices($healthcheckServiceCollector);

        $resultCollection = new Collection([]);
        foreach ($systemCheckHealthcheckServices as $healthcheckService) {
            $result = $healthcheckService->check();

            $resultCollection = $resultCollection->appendItem($result);
        }

        $isSystemOk = $resultCollection->every(function ($healthcheckResult) {
            /** @var \App\Service\Healthcheck\HealthcheckServiceInterface $healthcheckResult */
            return $healthcheckResult->isPassed();
        });

        $nextStepUrl = Router::url('/install/database', true);
        $this->webInstaller->setSettingsAndSave('initialized', true);
        $this->set('resultCollection', $resultCollection);
        $this->set('isSystemOk', $isSystemOk);
        $this->set('nextStepUrl', $nextStepUrl);
        $this->render('Pages/system_check');
    }

    /**
     * Filter all the healthcheck services to extract only the ones relevant here
     *
     * @param \App\Service\Healthcheck\HealthcheckServiceCollector $healthcheckServiceCollector healthcheck service collector
     * @return array
     */
    private function getSystemCheckHealthcheckServices(HealthcheckServiceCollector $healthcheckServiceCollector): array
    {
        $domainsIncluded = [
            HealthcheckServiceCollector::DOMAIN_ENVIRONMENT,
            HealthcheckServiceCollector::DOMAIN_WEB_INSTALLER,
        ];
        $servicesIncluded = [
            PhpGpgModuleInstalledGpgHealthcheck::class,
            HomeVariableDefinedGpgHealthcheck::class,
            HomeVariableWritableGpgHealthcheck::class,
        ];

        $services = [];
        /**
         * Extract environment domain services but without NextMinPhpVersionHealthcheck
         */
        foreach ($healthcheckServiceCollector->getServices() as $healthcheckService) {
            // Exclude this service
            if ($healthcheckService instanceof NextMinPhpVersionHealthcheck) {
                continue;
            }
            if (in_array($healthcheckService->domain(), $domainsIncluded)) {
                $services[] = $healthcheckService;
                continue;
            }
            foreach ($servicesIncluded as $serviceIncluded) {
                if ($healthcheckService instanceof $serviceIncluded) {
                    $services[] = $healthcheckService;
                }
            }
        }

        return $services;
    }
}
