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
 * @since         2.0.0
 */
namespace Passbolt\WebInstaller\Controller;

use App\Utility\Healthchecks;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Passbolt\WebInstaller\Utility\WebInstallerHealthchecks;

class SystemCheckController extends WebInstallerController
{
    /**
     * Index
     * @return void
     */
    public function index()
    {
        $checks = Healthchecks::environment();
        $gpgChecks = Healthchecks::gpg();
        $webInstallerCheck = WebInstallerHealthchecks::all();
        $checks = array_merge($checks, $gpgChecks, $webInstallerCheck);
        $checks['ssl'] = ['is' => $this->request->is('ssl')];
        $checks['system_ok'] = $this->_healthcheckIsOk($checks);

        if (Configure::read('passbolt.plugins.license')) {
            $nextStepUrl = Router::url('install/license_key', true);
        } else {
            $nextStepUrl = Router::url('install/database', true);
        }

        $this->webInstaller->setSettingsAndSave('initialized', true);
        $this->set('data', $checks);
        $this->set('nextStepUrl', $nextStepUrl);
        $this->render('Pages/system_check');
    }

    /**
     * Check if healthcheck values are good enough to continue installation.
     * @param array $checks checks
     * @return bool
     */
    protected function _healthcheckIsOk($checks)
    {
        $envCheckResults = array_values($checks['environment']);
        $webInstallerChecksResults = array_values($checks['webInstaller']);
        $gpgKeys = ['lib', 'gpgHome', 'gpgHomeWritable'];
        $gpgChecks = [];
        foreach ($gpgKeys as $gpgKey) {
            $gpgChecks[$gpgKey] = $checks['gpg'][$gpgKey];
        }
        $gpgCheckResults = array_values($gpgChecks);
        $allChecks = array_merge($envCheckResults, $gpgCheckResults, $webInstallerChecksResults);
        sort($allChecks);

        return $allChecks[0] ? true : false;
    }
}
