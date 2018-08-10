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
        $data = $this->request->getData();
        if (!empty($data)) {
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

            return $this->_saveLicense($data['license_key']);
        }

        $this->render($this->stepInfo['template']);
    }

    /**
     * Save the the provided license if valid
     * @param string $licenseStr The license
     * @return mixed
     */
    protected function _saveLicense(string $licenseStr = '')
    {
        $license = new License($licenseStr);
        try {
            $license->validate();
        } catch (\Exception $e) {
            return $this->_error($e->getMessage());
        }
        $session = $this->request->getSession();
        $session->write('Passbolt.License', $licenseStr);

        return $this->_success();
    }
}
