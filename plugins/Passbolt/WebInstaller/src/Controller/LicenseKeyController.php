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

use Passbolt\WebInstaller\Form\LicenseKeyForm;

class LicenseKeyController extends WebInstallerController
{
    /**
     * Initialize.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->stepInfo['previous'] = 'install';
        $this->stepInfo['next'] = 'install/database';
        $this->stepInfo['template'] = 'Pages/license_key';
    }

    /**
     * Index
     * @return mixed
     */
    public function index()
    {
        if (!empty($this->request->getData())) {
            $licenseKeyForm = new LicenseKeyForm();
            $dataIsValid = $licenseKeyForm->execute($this->request->getData());
            $this->set('licenseKeyForm', $licenseKeyForm);

            if (!$dataIsValid) {
                return $this->_error(__('The data entered are not correct'));
            }

            return $this->_checkLicense();
        }

        $this->render($this->stepInfo['template']);
    }

    /**
     * Check that the license provided is valid.
     * @return void
     */
    protected function _checkLicense()
    {
        // TODO: check license and manage errors.
        $this->_success();
    }
}
