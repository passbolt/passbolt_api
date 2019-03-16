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
        if ($this->request->is('post')) {
            return $this->indexPost();
        }

        $this->set('formExecuteResult', null);
        $this->render($this->stepInfo['template']);
    }

    /**
     * Index post
     * @return mixed
     */
    protected function indexPost()
    {
        $data = $this->request->getData();
        try {
            $this->validateData($data);
        } catch (Exception $e) {
            return $this->_error($e->getMessage());
        }

        $this->webInstaller->setSettingsAndSave('license', $data);
        $this->goToNextStep();
    }

    /**
     * Validate data.
     * @param array $data request data
     * @throws Exception The license is not valid
     * @return void
     */
    protected function validateData($data)
    {
        $form = new LicenseKeyForm();
        $confIsValid = $form->execute($data);
        $this->set('formExecuteResult', $form);
        if (!$confIsValid) {
            throw new Exception(__('The license is not valid.'));
        }
    }
}
