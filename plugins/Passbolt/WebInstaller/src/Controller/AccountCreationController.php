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

use App\Model\Entity\Role;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Passbolt\WebInstaller\Utility\DatabaseConfiguration;

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

        $this->set('user', null);
        $this->render('Pages/account_creation');
    }

    /**
     * Index post
     * @return void|mixed
     */
    protected function indexPost()
    {
        $data = $this->request->getData();
        $this->webInstaller->setSettingsAndSave('first_user', $data);

        try {
            $this->validateData($data);
        } catch (Exception $e) {
            return $this->_error($e->getMessage());
        }

        $this->goToNextStep();
    }

    /**
     * Get and validate the posted data.
     * @param array $data The posted data
     * @throws Exception If the user is not valid
     * @return array
     */
    protected function validateData($data)
    {
        // Set the default database configuration, so the models loaded after can be used on it.
        $this->webInstaller->initDatabaseConnection();
        $this->loadModel('Users');

        $data['role_id'] = UuidFactory::uuid(); // Temporary role id
        $user = $this->Users->buildEntity($data);
        $this->set('user', $user);
        $errors = $user->getErrors();
        if (!empty($errors)) {
            throw new Exception(__('The data entered are not correct'));
        }

        return $data;
    }
}
