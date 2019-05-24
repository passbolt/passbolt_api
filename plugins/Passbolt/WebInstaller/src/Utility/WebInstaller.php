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
 * @since         2.5.0
 */
namespace Passbolt\WebInstaller\Utility;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\Role;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Network\Session;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Migrations\Migrations;

class WebInstaller
{
    protected $session = null;
    protected $settings = [];
    public $createdUser = null;
    public $createdUserToken = null;

    /**
     * WebInstaller constructor.
     * @param Session $session The session to initialize the web installer on.
     */
    public function __construct($session = null)
    {
        $this->session = $session;
        if (!is_null($session)) {
            $sessionSettings = $session->read('webinstaller');
            if (!empty($sessionSettings)) {
                $this->settings = $sessionSettings;
            }
        }
    }

    /**
     * Check if the web installer has been initialized already.
     * @return bool
     */
    public function isInitialized()
    {
        return $this->getSettings('initialized');
    }

    /**
     * Get a setting.
     * @param string $key The setting value
     * @return mixed
     */
    public function getSettings($key)
    {
        return Hash::get($this->settings, $key);
    }

    /**
     * Set a setting.
     * @param string $key The setting key.
     * @param string $value The setting value.
     * @return void
     */
    public function setSettings($key, $value)
    {
        $this->settings[$key] = $value;
    }

    /**
     * Store the settings in session.
     * @return void
     */
    public function saveSettings()
    {
        $this->session->write('webinstaller', $this->settings);
    }

    /**
     * Flush the settings from the session.
     * @return void
     */
    public function flushSettings()
    {
        $this->session->write('webinstaller', []);
    }

    /**
     * Set a setting and store the settings in session.
     * @param string $key The setting key.
     * @param string $value The setting value.
     * @return void
     */
    public function setSettingsAndSave($key, $value)
    {
        $this->setSettings($key, $value);
        $this->saveSettings();
    }

    /**
     * Install passbolt.
     * @throws \Exception
     * @return void
     */
    public function install()
    {
        $this->initDatabaseConnection();
        $this->importGpgKey();
        $this->writePassboltConfigFile();
        $this->installDatabase();
        $this->writeLicenseFile();
        $this->createFirstUser();
        $this->saveSettings();
        $this->changeConfigFolderPermission();
        $this->flushSettings();
    }

    /**
     * Initialize the database connection.
     * @return void
     */
    public function initDatabaseConnection()
    {
        $databaseSettings = $this->getSettings('database');
        DatabaseConfiguration::setDefaultConfig($databaseSettings);
    }

    /**
     * Import the server gpg key
     * @return void
     */
    public function importGpgKey()
    {
        $gpgSettings = $this->getSettings('gpg');
        $gpg = OpenPGPBackendFactory::get();
        $gpg->importKeyIntoKeyring($gpgSettings['private_key_armored']);
        file_put_contents(Configure::read('passbolt.gpg.serverKey.public'), $gpgSettings['public_key_armored']);
        file_put_contents(Configure::read('passbolt.gpg.serverKey.private'), $gpgSettings['private_key_armored']);
        $gpgSettings += [
            'fingerprint' => $gpgSettings['fingerprint'],
            'public' => Configure::read('passbolt.gpg.serverKey.public'),
            'private' => Configure::read('passbolt.gpg.serverKey.private')
        ];
        $this->setSettings('gpg', $gpgSettings);
    }

    /**
     * Write passbolt configuration file.
     * @return void
     */
    public function writePassboltConfigFile()
    {
        $passboltConfig = new PassboltConfiguration();
        $contents = $passboltConfig->render($this->settings);
        file_put_contents(CONFIG . 'passbolt.php', $contents);
    }

    /**
     * Write the license file.
     * @return void
     */
    public function writeLicenseFile()
    {
        if (!Configure::read('passbolt.plugins.license')) {
            return;
        }
        $license = $this->getSettings('license');
        file_put_contents(CONFIG . 'license', $license);
    }

    /**
     * Install database.
     * @throws \Exception The database cannot be installed
     * @return void
     */
    public function installDatabase()
    {
        $migrations = new Migrations(['connection' => ConnectionManager::get('default')->configName()]);
        $migrated = $migrations->migrate();
        if (!$migrated) {
            throw new \Exception('The database cannot be installed');
        }
    }

    /**
     * Create the first user.
     * @throws CustomValidationException There was a problem creating the first user
     * @throws CustomValidationException There was a problem creating the first user register token
     * @return void
     */
    public function createFirstUser()
    {
        $userData = $this->getSettings('first_user');
        if (empty($userData)) {
            return;
        }

        $Users = TableRegistry::getTableLocator()->get('Users');
        $userData['deleted'] = false;
        $userData['role_id'] = $Users->Roles->getIdByName(Role::ADMIN);

        $user = $Users->buildEntity($userData);
        $Users->save($user, ['checkRules' => true, 'atomic' => false]);
        $errors = $user->getErrors();
        if (!empty($errors)) {
            throw new CustomValidationException('There was a problem creating the first user', $errors, $Users);
        }

        $token = $Users->AuthenticationTokens->generate($user->id, AuthenticationToken::TYPE_REGISTER);
        $errors = $token->getErrors();
        if (!empty($errors)) {
            throw new CustomValidationException('There was a problem creating the registration token', $errors, $Users->AuthenticationTokens);
        }

        $this->setSettings('user', [
            'user_id' => $user->id,
            'token' => $token->token
        ]);
    }

    /**
     * Change the config folder permissions.
     * @return void
     */
    public function changeConfigFolderPermission()
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(CONFIG),
            \RecursiveIteratorIterator::SELF_FIRST,
            \RecursiveIteratorIterator::CATCH_GET_CHILD // Don't throw an error if one child cannot be opened
        );
        foreach ($iterator as $name => $fileInfo) {
            if ($fileInfo->getFilename() == '..') {
                continue;
            }
            if (is_writable($name)) {
                if (is_dir($name)) {
                    chmod($name, 0550);
                } else {
                    chmod($name, 0440);
                }
            }
        }
    }
}
