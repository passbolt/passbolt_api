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

use Cake\Core\Exception\Exception;
use Passbolt\WebInstaller\Form\DatabaseConfigurationForm;

class DatabaseController extends WebInstallerController
{
    // Database configuration form.
    protected $databaseConfigurationForm = null;

    /**
     * Initialize.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->stepInfo['previous'] = 'install/license_key';
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
        if (!empty($this->request->getData())) {
            $this->_validateData($this->request->getData());

            try {
                $this->databaseConfigurationForm->testConnection($this->request->getData());
            } catch (Exception $e) {
                return $this->_error($e->getMessage());
            }

            $session = $this->request->getSession();

            // Depending on the database content, check if this is a new passbolt instance,
            // or if we are reconfiguring an existing one (there already tables and  users in the db).
            try {
                $nbAdmins = $this->databaseConfigurationForm->checkDbHasAdmin($this->request->getData());
            } catch (\Exception $e) {
                return $this->_error($e->getMessage());
            }

            $session->write(self::CONFIG_KEY . '.hasExistingAdmin', $nbAdmins > 0 ? true : false);

            // Database is valid, store information in the session.
            $session->write(self::CONFIG_KEY . '.database', $this->request->getData());

            return $this->_success();
        }

        // Pre-populate form if data already exist in the session.
	    $this->request->data =  $this->request->getSession()->read(self::CONFIG_KEY . '.database');

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
            return $this->_error(__('The data entered are not correct'));
        }
    }
}
