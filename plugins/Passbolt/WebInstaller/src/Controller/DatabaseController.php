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

use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Passbolt\WebInstaller\Form\DatabaseConfigurationForm;

class DatabaseController extends WebInstallerController
{
    const MY_CONFIG_KEY = 'database';

    // Database configuration form.
    protected $databaseConfigurationForm = null;

    /**
     * Initialize.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        if (Configure::read('passbolt.plugins.license')) {
            $this->stepInfo['previous'] = 'install/license_key';
        } else {
            $this->stepInfo['previous'] = 'install';
        }
        $this->stepInfo['next'] = 'install/gpg_key';
        $this->stepInfo['template'] = 'Pages/database';

        $this->databaseConfigurationForm = new DatabaseConfigurationForm();
    }

    /**
     * Index
     * @return mixed
     */
    public function index()
    {
        $data = $this->request->getData();
        if (!empty($data)) {
            try {
                $this->_validateData($data);
            } catch (Exception $e) {
                return $this->_error($e->getMessage());
            }

            try {
                $this->databaseConfigurationForm->testConnection($data);
            } catch (Exception $e) {
                return $this->_error($e->getMessage());
            }

            // Depending on the database content, check if this is a new passbolt instance,
            // or if we are reconfiguring an existing one (there already tables and  users in the db).
            try {
                $nbAdmins = $this->databaseConfigurationForm->checkDbHasAdmin($data);
            } catch (Exception $e) {
                return $this->_error($e->getMessage());
            }

            // Save in session whether the database has existing admins.
            $this->request->getSession()->write(self::CONFIG_KEY . '.hasExistingAdmin', $nbAdmins > 0 ? true : false);

            $this->_saveConfiguration(self::MY_CONFIG_KEY, $data);

            return $this->_success();
        }

        $this->_loadSavedConfiguration(self::MY_CONFIG_KEY);

        $this->render($this->stepInfo['template']);
    }

    /**
     * Validate data.
     * @param array $data request data
     * @return mixed
     */
    protected function _validateData($data)
    {
        $confIsValid = $this->databaseConfigurationForm->execute($data);
        $this->set('databaseConfigurationForm', $this->databaseConfigurationForm);

        if (!$confIsValid) {
            throw new Exception(__('The data entered are not correct'));
        }
    }
}
