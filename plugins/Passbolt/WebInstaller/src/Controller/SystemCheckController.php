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
 * @since         2.0.0
 */
namespace Passbolt\WebInstaller\Controller;

use App\Utility\Healthchecks;

class SystemCheckController extends WebInstallerController
{
    /**
     * Index
     */
    function index() {
        $checks = Healthchecks::environment();
        $gpgChecks = Healthchecks::gpg();
        $checks = array_merge($checks, $gpgChecks);
        $checks['ssl'] = ['is' => $this->request->is('ssl')];
        $checks['system_ok'] = $this->_healthcheckIsOk($checks) ? true : false;

        $this->set(['data' => $checks]);
        $this->render('Pages/system_check');
    }

    /**
     * Check if healthcheck values are good enough to continue installation.
     * @param $checks
     * @return bool mixed
     */
    private function _healthcheckIsOk($checks) {
        $envCheckResults = array_values($checks['environment']);
        $gpgKeys = ['lib', 'gpgHome', 'gpgHomeWritable'];
        $gpgChecks = [];
        foreach($gpgKeys as $gpgKey) {
            $gpgChecks[$gpgKey] = $checks['gpg'][$gpgKey];
        }
        $gpgCheckResults = array_values($gpgChecks);
        $allChecks = array_merge($envCheckResults, $gpgCheckResults);
        sort($allChecks);

        return $allChecks[0];
    }
}