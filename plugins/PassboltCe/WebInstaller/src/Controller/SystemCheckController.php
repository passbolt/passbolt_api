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

use Cake\Collection\Collection;
use Cake\Routing\Router;
use Passbolt\WebInstaller\Service\Healthcheck\SystemCheckServiceCollector;

class SystemCheckController extends WebInstallerController
{
    /**
     * Index
     *
     * @param \Passbolt\WebInstaller\Service\Healthcheck\SystemCheckServiceCollector $systemCheckServiceCollector System check service collector.
     * @return void
     */
    public function index(SystemCheckServiceCollector $systemCheckServiceCollector)
    {
        $healthcheckServices = $systemCheckServiceCollector->getServices();

        $resultCollection = new Collection([]);
        foreach ($healthcheckServices as $healthcheckService) {
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
}
