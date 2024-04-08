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
use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Service\Healthcheck\Ssl\IsRequestHttpsSslHealthcheck;
use Cake\Collection\Collection;
use Cake\Collection\CollectionInterface;
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
        $isSystemOk = true;
        $isRequestHttps = false;
        foreach ($systemCheckHealthcheckServices as $healthcheckService) {
            $result = $healthcheckService->check();

            $resultCollection = $resultCollection->appendItem($result);
            if ($healthcheckService instanceof NextMinPhpVersionHealthcheck) {
                continue;
            }
            if ($healthcheckService instanceof IsRequestHttpsSslHealthcheck) {
                $isRequestHttps = $result->isPassed();
                continue;
            }
            $isSystemOk = $isSystemOk && $result->isPassed();
        }

        $isNextMinPhpVersionPassed = $this->isNextMinPhpVersionPassed($resultCollection);
        $environmentChecks = $resultCollection->filter(function (HealthcheckServiceInterface $result) {
            return $result->domain() === HealthcheckServiceCollector::DOMAIN_ENVIRONMENT;
        });
        $gpgChecks = $resultCollection->filter(function (HealthcheckServiceInterface $result) {
            return $result->domain() === HealthcheckServiceCollector::DOMAIN_GPG;
        });

        $nextStepUrl = Router::url('/install/database', true);
        $this->webInstaller->setSettingsAndSave('initialized', true);
        $this->set(compact(
            'environmentChecks',
            'gpgChecks',
            'isNextMinPhpVersionPassed',
            'isSystemOk',
            'isRequestHttps',
            'nextStepUrl'
        ));
        $this->render('Pages/system_check');
    }

    /**
     * Filter all the healthcheck services to extract only the ones relevant here
     *
     * @param \App\Service\Healthcheck\HealthcheckServiceCollector $healthcheckServiceCollector healthcheck service collector
     * @return \App\Service\Healthcheck\HealthcheckServiceInterface[]
     */
    private function getSystemCheckHealthcheckServices(HealthcheckServiceCollector $healthcheckServiceCollector): array
    {
        $domainsIncluded = [
            HealthcheckServiceCollector::DOMAIN_ENVIRONMENT,
        ];
        $servicesIncluded = [
            PhpGpgModuleInstalledGpgHealthcheck::class,
            HomeVariableDefinedGpgHealthcheck::class,
            HomeVariableWritableGpgHealthcheck::class,
            IsRequestHttpsSslHealthcheck::class,
        ];

        return $healthcheckServiceCollector->getServicesFiltered($domainsIncluded, $servicesIncluded);
    }

    /**
     * @param \Cake\Collection\CollectionInterface $resultCollection Result collection.
     * @return bool
     */
    private function isNextMinPhpVersionPassed(CollectionInterface $resultCollection): bool
    {
        $result = false;
        /** @var \App\Service\Healthcheck\HealthcheckServiceInterface $healthcheckResult */
        foreach ($resultCollection as $healthcheckResult) {
            if ($healthcheckResult instanceof NextMinPhpVersionHealthcheck) {
                $result = $healthcheckResult->isPassed();

                break;
            }
        }

        return $result;
    }
}
