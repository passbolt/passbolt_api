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

use Passbolt\License\Utility\License;
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
            $data = $this->request->getData();
            $licenseKeyForm = new LicenseKeyForm();
            $dataIsValid = $licenseKeyForm->execute($data);
            $this->set('licenseKeyForm', $licenseKeyForm);

            if (!$dataIsValid) {
                $errors = $licenseKeyForm->errors();
                if (isset($errors['license_key'])) {
                    return $this->_error(array_pop($errors['license_key']));
                }

                return $this->_error(__('The data entered are not correct'));
            }

            return $this->_checkLicense($data['license_key']);
        }

        $this->render($this->stepInfo['template']);
    }

    /**
     * Check that the license provided is valid.
     * @param string $licenseStr The license
     * @return mixed
     */
    protected function _checkLicense(string $licenseStr = '')
    {
        $license = new License($licenseStr);
        try {
            $license->validate();
        } catch (\Exception $e) {
            return $this->_error($e->getMessage());
        }

        return $this->_success();
    }
}
