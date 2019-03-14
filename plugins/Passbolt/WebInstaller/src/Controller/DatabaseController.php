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

use App\Model\Entity\Role;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;
use Passbolt\WebInstaller\Form\DatabaseConfigurationForm;
use Passbolt\WebInstaller\Utility\DatabaseConfiguration;

class DatabaseController extends WebInstallerController
{
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

        $databaseSettings = $this->webInstaller->getSettings('database');
        if (!empty($databaseSettings)) {
            foreach ($databaseSettings as $key => $databaseSetting) {
                $this->request = $this->request->withData($key, $databaseSetting);
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
        $data = $this->request->getData();
        try {
            $this->validateData($data);
            $this->testConnection($data);
            DatabaseConfiguration::setDefaultConfig($data);
            $hasAdmin = $this->hasAdmin();
        } catch (Exception $e) {
            return $this->_error($e->getMessage());
        }

        $this->webInstaller->setSettings('database', $data);
        $this->webInstaller->setSettings('hasAdmin', $hasAdmin);
        $this->webInstaller->saveSettings();

        $this->goToNextStep();
    }

    /**
     * Check if the database has already administrator.
     * @throws Exception If the database schema does not validate
     * @return bool
     */
    protected function hasAdmin()
    {
        $tables = DatabaseConfiguration::getTables();
        if (!count($tables)) {
            return false;
        }

        DatabaseConfiguration::validateSchema();

        $this->loadModel('Users');
        $nbAdmins = $this->Users->find()
            ->where(['role_id' => $this->Users->Roles->getIdByName(Role::ADMIN)])
            ->count();

        return $nbAdmins > 0;
    }

    /**
     * Test the connection to the database
     * @param array $data The database configuration to test
     * @throws Exception A connection could not be established with the provided data
     * @return void
     */
    protected function testConnection($data)
    {
        $config = DatabaseConfiguration::buildConfig($data);
        $this->webInstaller->setSettings('database', $config);
        $this->webInstaller->initDatabaseConnection();
        if (!DatabaseConfiguration::testConnection()) {
            throw new Exception(__('A connection could not be established with the credentials provided. Please verify the settings.'));
        }
    }

    /**
     * Validate data.
     * @param array $data request data
     * @throws Exception The data does not validate
     * @return void
     */
    protected function validateData($data)
    {
        $form = new DatabaseConfigurationForm();
        $this->set('formExecuteResult', $form);
        if (!$form->execute($data)) {
            throw new Exception(__('The data entered are not correct'));
        }
    }
}
