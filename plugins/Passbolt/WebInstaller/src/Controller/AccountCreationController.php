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

use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Passbolt\WebInstaller\Form\AccountCreationForm;

class AccountCreationController extends WebInstallerController
{
    /**
     * Initialize.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->stepInfo['next'] = 'install/installation';
        $this->stepInfo['template'] = 'Pages/account_creation';
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

        $this->set('formExecuteResult', null);
        $this->render('Pages/account_creation');
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

        $this->webInstaller->setSettingsAndSave('first_user', $data);
        $this->goToNextStep();
    }

    /**
     * Get and validate the posted data.
     * @throws Exception If the user is not valid
     * @return array
     */
    protected function getAndValidateData()
    {
        $data = $this->request->getData();
        $accountCreationForm = new AccountCreationForm();
        $isValid = $accountCreationForm->execute($data);
        $this->set('formExecuteResult', $accountCreationForm);

        if (!$isValid) {
            throw new Exception(__('The data entered are not correct'));
        }

        return [
            'username' => $data['username'],
            'profile' => [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
            ]
        ];
    }
}
