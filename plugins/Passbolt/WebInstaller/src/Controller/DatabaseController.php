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
use Cake\Core\Exception\Exception;
use Cake\Controller\Component\FlashComponent;
use Passbolt\WebInstaller\Form\DatabaseConfigurationForm;

class DatabaseController extends Controller
{
    var $components = ['Flash'];

    const CONF_KEY = 'Passbolt.Config.database';

    /**
     * Index
     */
    function index() {
        if(!empty($this->request->getData())) {
            $dbConf = new DatabaseConfigurationForm();
            $confIsValid = $dbConf->execute($this->request->getData());
            $this->set('databaseConfiguration', $dbConf);

            if (!$confIsValid) {
                return $this->_error(__('The data entered are not correct'));
            }

            try {
                $dbConf->testConnection($this->request->getData());
            }
            catch(Exception $e) {
                return $this->_error($e->getMessage());
            }

            $session = $this->request->getSession();

            // Depending on the database content, check if this is a new passbolt instance,
            // or if we are reconfiguring an existing one (already users in the db).
            $nbAdmins = $dbConf->checkDbHasAdmin($this->request->getData());
            $session->write('Passbolt.Config.isNewInstance', $nbAdmins > 0 ? false : true);

            // Database is valid, store information in the session.
            $session->write(CONF_KEY, $this->request->getData());
            return $this->_success();
        }

        $this->render('Pages/database');
    }

    protected function _error($message) {
        $this->Flash->error($message);
        $this->render('Pages/database');
    }

    protected function _success() {
        $this->redirect('install/gpg_key');
    }
}