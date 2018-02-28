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

use Cake\Controller\Controller;

class LicenseKeyController extends Controller
{
    /**
     * Index
     */
    function index() {
        if(!empty($this->request->getData())) {
            return $this->_checkLicense();
        }

        $this->set(['data' => '']);
        $this->render('Pages/license_key');
    }

    /**
     * Check that the license provided is valid.
     */
    private function _checkLicense() {
        // TODO: check license and manage errors.
        $this->redirect('install/database');
    }
}