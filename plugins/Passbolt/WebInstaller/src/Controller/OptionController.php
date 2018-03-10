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

use Cake\Routing\Router;
use Passbolt\WebInstaller\Form\OptionsConfigurationForm;

class OptionController extends WebInstallerController
{
    /**
     * Initialize.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->stepInfo['previous'] = 'install/email';
        $this->stepInfo['next'] = 'install/installation';
        $this->stepInfo['template'] = 'Pages/options';
    }

    /**
     * Index
     * @return mixed
     */
    public function index()
    {
        if (empty($this->request->getData())) {
            // Set default values.
            $this->request->data['full_base_url'] = trim(Router::url('/', true), '/');
            $this->set(['force_ssl' => $this->request->is('ssl') === true ? 1 : 0]);
        } else {
            $this->_validateData($this->request->getData());
            $this->_saveConfiguration($this->request->getData());

            return $this->_success();
        }

	    // Pre-populate form if data already exist in the session.
	    $this->request->data =  $this->request->getSession()->read(self::CONFIG_KEY . '.options');

        $this->render($this->stepInfo['template']);
    }

    /**
     * Validate data.
     * @param array $data request data
     * @return mixed
     */
    protected function _validateData($data)
    {
        $optionsConfigurationForm = new OptionsConfigurationForm();
        $confIsValid = $optionsConfigurationForm->execute($data);
        $this->set('optionsConfigurationForm', $optionsConfigurationForm);

        if (!$confIsValid) {
            return $this->_error(__('The data entered are not correct'));
        }
    }

    /**
     * Save configuration.
     * @param array $data request data
     * @return void
     */
    protected function _saveConfiguration($data)
    {
        $session = $this->request->getSession();
        $session->write(self::CONFIG_KEY . '.options', $data);
    }
}
