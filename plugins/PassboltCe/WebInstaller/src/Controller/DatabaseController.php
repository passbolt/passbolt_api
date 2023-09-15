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

use App\Model\Entity\Role;
use App\Utility\UuidFactory;
use Cake\Core\Exception\CakeException;
use Passbolt\WebInstaller\Form\DatabaseConfigurationForm;
use Passbolt\WebInstaller\Utility\DatabaseConfiguration;

/**
 * Class DatabaseController
 *
 * @package Passbolt\WebInstaller\Controller
 */
class DatabaseController extends WebInstallerController
{
    /**
     * Default password to use in the UI in case the config is provided through a .ini config file.
     */
    private string $defaultPassword;

    /**
     * Ini config file content (if ini file provided).
     */
    private array $configFile = [];

    /**
     * Default config to use  when a ini config file is provided.
     */
    private array $configFileDefault = [
        'type' => 'mysql',
        'host' => '127.0.0.1',
    ];

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->stepInfo['previous'] = '/install';
        $this->stepInfo['next'] = '/install/gpg_key';
        $this->stepInfo['template'] = 'Pages/database';

        if (
            file_exists(DatabaseConfigurationForm::CONFIG_FILE_PATH)
            && is_readable(DatabaseConfigurationForm::CONFIG_FILE_PATH)
        ) {
            $iniFileContent = parse_ini_file(DatabaseConfigurationForm::CONFIG_FILE_PATH);
            if (!$iniFileContent) {
                return;
            }
            $this->configFile = $iniFileContent;
            $this->stepInfo['defaultConfig'] = array_merge($this->configFileDefault, $this->configFile);
            $this->defaultPassword = UuidFactory::uuid('__default_password__');
        }
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

        // Load default config file values if present.
        if (isset($this->stepInfo['defaultConfig']) && !empty($this->stepInfo['defaultConfig'])) {
            foreach ($this->stepInfo['defaultConfig'] as $key => $databaseSetting) {
                // We override the default password with a default value that will be replace while saving.
                // This is to avoid a sensitive information leakage through the webinstaller.
                if ($key === 'password') {
                    $databaseSetting = $this->defaultPassword;
                }
                $this->request = $this->request->withData($key, $databaseSetting);
            }
        }

        // Then, load previously saved database values if present (will override default values).
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
     *
     * @return void
     */
    protected function indexPost(): void
    {
        $data = $this->request->getData();

        if (!empty($this->configFile) && $data['password'] === $this->defaultPassword) {
            $data['password'] = $this->configFile['password'];
        }

        try {
            $data = $this->validateData($data);
            $this->testConnection($data);
            DatabaseConfiguration::setDefaultConfig($data);
            $hasAdmin = $this->hasAdmin();
        } catch (CakeException $e) {
            $this->_error($e->getMessage());

            return;
        }

        $this->webInstaller->setSettings('database', $data);
        $this->webInstaller->setSettings('hasAdmin', $hasAdmin);
        $this->webInstaller->saveSettings();

        $this->goToNextStep();
    }

    /**
     * Check if the database has already administrator.
     *
     * @throws \Cake\Core\Exception\CakeException If the database schema does not validate
     * @return bool
     */
    protected function hasAdmin(): bool
    {
        $tables = DatabaseConfiguration::getTables();
        if (!count($tables)) {
            return false;
        }

        DatabaseConfiguration::validateSchema();

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');

        $nbAdmins = $usersTable
            ->find()
            ->where(['role_id' => $usersTable->Roles->getIdByName(Role::ADMIN)])
            ->count();

        return $nbAdmins > 0;
    }

    /**
     * Test the connection to the database
     *
     * @param array $data The database configuration to test
     * @throws \Cake\Core\Exception\CakeException A connection could not be established with the provided data
     * @return void
     */
    protected function testConnection(array $data): void
    {
        $config = DatabaseConfiguration::buildConfig($data);
        $this->webInstaller->setSettings('database', $config);
        $this->webInstaller->initDatabaseConnection();
        if (!DatabaseConfiguration::testConnection()) {
            $msg = __('A connection could not be established with the credentials provided.') . ' ';
            $msg .= __('Please verify the settings.');
            throw new CakeException($msg);
        }
    }

    /**
     * Validate data.
     *
     * @param array $data request data
     * @throws \Cake\Core\Exception\CakeException The data does not validate
     * @return array
     */
    protected function validateData(array $data): array
    {
        $form = new DatabaseConfigurationForm();
        $this->set('formExecuteResult', $form);
        if (!$form->execute($data)) {
            throw new CakeException(__('The data entered are not correct'));
        }
        /** @var array $data */
        $data = $form->getData();

        return $data;
    }
}
