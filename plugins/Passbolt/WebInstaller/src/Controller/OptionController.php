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
use Cake\Routing\Router;
use Passbolt\WebInstaller\Form\OptionsConfigurationForm;

class OptionController extends WebInstallerController
{
    const MY_CONFIG_KEY = 'options';

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
        $data = $this->request->getData();
        if (empty($data)) {
            // Set default values.
            $this->request->data['full_base_url'] = trim(Router::url('/', true), '/');
            $this->set(['force_ssl' => $this->request->is('ssl') === true ? 1 : 0]);
        } else {
            // Remove trailing slash in case it exists in full_base_url.
            $data['full_base_url'] = trim($data['full_base_url'], '/');

            try {
                $this->_validateData($data);
            } catch (Exception $e) {
                return $this->_error($e->getMessage());
            }

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
        // Validate data.
        $optionsConfigurationForm = new OptionsConfigurationForm();
        $confIsValid = $optionsConfigurationForm->execute($data);
        $this->set('optionsConfigurationForm', $optionsConfigurationForm);

        if (!$confIsValid) {
            throw new Exception(__('The data entered are not correct'));
        }
    }
}
