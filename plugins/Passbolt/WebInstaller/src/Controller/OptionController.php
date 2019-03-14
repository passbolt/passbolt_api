<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
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
    /**
     * Initialize.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->stepInfo['previous'] = 'install/email';
        $this->stepInfo['template'] = 'Pages/options';
        $this->stepInfo['next'] = $this->webInstaller->getSettings('hasAdmin') ? 'install/installation' : 'install/account_creation';
    }

    /**
     * Index
     * @return void|mixed
     */
    public function index()
    {
        if ($this->request->is('post')) {
            return $this->indexPost();
        }

        $fullBaseUrl = trim(Router::url('/', true), '/');
        $this->request = $this->request->withData('full_base_url', $fullBaseUrl);
        $optionSettings = $this->webInstaller->getSettings('options');
        if (!empty($optionSettings)) {
            foreach ($optionSettings as $key => $optionSetting) {
                $this->request = $this->request->withData($key, $optionSetting);
            }
        }

        $this->set('formExecuteResult', null);
        $this->render($this->stepInfo['template']);
    }

    /**
     * Index post
     * @return void|mixed
     */
    protected function indexPost()
    {
        try {
            $data = $this->getAndValidateData();
        } catch (Exception $e) {
            return $this->_error($e->getMessage());
        }

        $this->webInstaller->setSettingsAndSave('options', $data);
        $this->goToNextStep();
    }

    /**
     * Validate data.
     * @return array
     */
    protected function getAndValidateData()
    {
        $data = $this->request->getData();
        $data['full_base_url'] = trim($data['full_base_url'], '/');
        $optionsConfigurationForm = new OptionsConfigurationForm();
        $confIsValid = $optionsConfigurationForm->execute($data);
        $this->set('formExecuteResult', $optionsConfigurationForm);

        if (!$confIsValid) {
            throw new Exception(__('The data entered are not correct'));
        }

        return $data;
    }
}
