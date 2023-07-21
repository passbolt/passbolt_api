<?php
declare(strict_types=1);

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

use Cake\Core\Exception\CakeException;
use Cake\Routing\Router;
use Passbolt\WebInstaller\Form\OptionsConfigurationForm;

class OptionController extends WebInstallerController
{
    /**
     * Initialize.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->stepInfo['previous'] = '/install/gpg_key';
        $this->stepInfo['template'] = 'Pages/options';
        $this->stepInfo['next'] = $this->getNext();
    }

    /**
     * Index
     *
     * @return void
     */
    public function index(): void
    {
        if ($this->request->is('post')) {
            $this->indexPost();

            return;
        }

        $fullBaseUrl = trim(Router::url('/', true), '/');
        $this->request = $this->request->withData('full_base_url', $fullBaseUrl);
        $optionSettings = $this->webInstaller->getSettings('options');
        if (!empty($optionSettings)) {
            foreach ($optionSettings as $key => $optionSetting) {
                $this->request = $this->request->withData($key, $optionSetting);
            }
        }

        // Set SSL force dropdown option to true if webinstaller is launched over https
        $forceSsl = $this->request->getUri()->getScheme() === 'https' ? '1' : '0';
        $this->request = $this->request->withData('force_ssl', $forceSsl);

        $this->set('formExecuteResult', null);
        $this->render($this->stepInfo['template']);
    }

    /**
     * Index post
     *
     * @return void
     */
    protected function indexPost(): void
    {
        try {
            $data = $this->getAndValidateData();
        } catch (CakeException $e) {
            $this->_error($e->getMessage());

            return;
        }

        $this->webInstaller->setSettingsAndSave('options', $data);
        $this->goToNextStep();
    }

    /**
     * Validate data.
     *
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
            throw new CakeException(__('The data entered are not correct'));
        }

        return $data;
    }

    /**
     * Define the next step
     *
     * @return string
     */
    protected function getNext(): string
    {
        if (!$this->webInstaller->getSettings('hasSmtpSettings')) {
            return 'install/email';
        } elseif (!$this->webInstaller->getSettings('hasAdmin')) {
            return '/install/account_creation';
        } else {
            return 'install/installation';
        }
    }
}
