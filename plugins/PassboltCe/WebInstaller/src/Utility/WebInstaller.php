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
 * @since         2.5.0
 */
namespace Passbolt\WebInstaller\Utility;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\Role;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Session;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Migrations\Migrations;
use Passbolt\SmtpSettings\Service\SmtpSettingsSetService;
use Passbolt\WebInstaller\Form\DatabaseConfigurationForm;
use Passbolt\WebInstaller\Service\WebInstallerChangeConfigFolderPermissionService;

class WebInstaller
{
    protected ?Session $session = null;

    /**
     * @var array|mixed
     */
    protected $settings = [];

    /**
     * WebInstaller constructor.
     *
     * @param \Cake\Http\Session|null $session The session to initialize the web installer on.
     */
    public function __construct(?Session $session)
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
     *
     * @return bool
     */
    public function isInitialized(): bool
    {
        return $this->getSettings('initialized') ?? false;
    }

    /**
     * Get a setting.
     *
     * @param string $key The setting value
     * @return mixed The value fetched from the settings, or null.
     */
    public function getSettings(string $key)
    {
        return Hash::get($this->settings, $key);
    }

    /**
     * Set a setting.
     *
     * @param string $key The setting key.
     * @param mixed $value The setting value.
     * @return void
     */
    public function setSettings(string $key, $value): void
    {
        $this->settings[$key] = $value;
    }

    /**
     * Store the settings in session.
     *
     * @return void
     */
    public function saveSettings(): void
    {
        $this->session->write('webinstaller', $this->settings);
    }

    /**
     * Flush the settings from the session.
     *
     * @return void
     */
    public function flushSettings(): void
    {
        $this->session->write('webinstaller', []);
    }

    /**
     * Delete temporary files.
     *
     * @throws \Exception
     * @return void
     */
    public function deleteTmpFiles(): void
    {
        if (file_exists(DatabaseConfigurationForm::CONFIG_FILE_PATH)) {
            if (!is_writable(DatabaseConfigurationForm::CONFIG_FILE_PATH)) {
                Log::write(
                    'error',
                    sprintf(
                        'Could not delete temporary database configuration file %s',
                        DatabaseConfigurationForm::CONFIG_FILE_PATH
                    )
                );

                return;
            }

            unlink(DatabaseConfigurationForm::CONFIG_FILE_PATH);
        }
    }

    /**
     * Set a setting and store the settings in session.
     *
     * @param string $key The setting key.
     * @param mixed $value The setting value.
     * @return void
     */
    public function setSettingsAndSave(string $key, $value): void
    {
        $this->setSettings($key, $value);
        $this->saveSettings();
    }

    /**
     * Install passbolt.
     *
     * @param \Passbolt\WebInstaller\Service\WebInstallerChangeConfigFolderPermissionService $configFolderPermissionService service to change the permissions
     * @throws \Exception
     * @return void
     */
    public function install(WebInstallerChangeConfigFolderPermissionService $configFolderPermissionService): void
    {
        $this->initDatabaseConnection();
        $this->importGpgKey();
        $this->writePassboltConfigFile();
        $this->installDatabase();
        $this->createFirstUser();
        $this->saveSmtpSettingsInDb();
        $this->saveSettings();
        $this->deleteTmpFiles();
        $configFolderPermissionService->changeConfigFolderPermission();
        $this->flushSettings();
    }

    /**
     * Initialize the database connection.
     *
     * @return void
     */
    public function initDatabaseConnection(): void
    {
        $databaseSettings = $this->getSettings('database');
        DatabaseConfiguration::setDefaultConfig($databaseSettings);
    }

    /**
     * Import the server OpenPGP key
     *
     * @return void
     */
    public function importGpgKey(): void
    {
        $gpgSettings = $this->getSettings('gpg');
        $gpg = OpenPGPBackendFactory::get();
        $gpg->importKeyIntoKeyring($gpgSettings['private_key_armored']);
        file_put_contents(Configure::read('passbolt.gpg.serverKey.public'), $gpgSettings['public_key_armored']);
        file_put_contents(Configure::read('passbolt.gpg.serverKey.private'), $gpgSettings['private_key_armored']);
        Configure::write('passbolt.gpg.serverKey.fingerprint', $gpgSettings['fingerprint']);
        $gpgSettings += [
            'fingerprint' => Configure::read('passbolt.gpg.serverKey.fingerprint'),
            'public' => Configure::read('passbolt.gpg.serverKey.public'),
            'private' => Configure::read('passbolt.gpg.serverKey.private'),
        ];
        $this->setSettings('gpg', $gpgSettings);
    }

    /**
     * Write passbolt configuration file.
     *
     * @param string $fileName config/passbolt.php
     * @return void
     */
    public function writePassboltConfigFile(string $fileName = CONFIG . 'passbolt.php'): void
    {
        $passboltConfig = new PassboltConfiguration();
        $contents = $passboltConfig->render($this->settings);
        file_put_contents($fileName, $contents);
    }

    /**
     * Install database.
     *
     * @throws \Exception The database cannot be installed
     * @return void
     */
    public function installDatabase(): void
    {
        $migrations = new Migrations(['connection' => ConnectionManager::get('default')->configName()]);
        $migrated = $migrations->migrate();
        if (!$migrated) {
            throw new \Exception(__('The database cannot be installed'));
        }
    }

    /**
     * Create the first user.
     *
     * @throws \App\Error\Exception\CustomValidationException There was a problem creating the first user
     * @throws \App\Error\Exception\CustomValidationException There was a problem creating the first user register token
     * @return void
     */
    public function createFirstUser(): void
    {
        $userData = $this->getSettings('first_user');
        if (empty($userData)) {
            return;
        }

        /** @var \App\Model\Table\UsersTable $Users */
        $Users = TableRegistry::getTableLocator()->get('Users');

        /** @var \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens */
        $AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');

        $userData['deleted'] = false;
        $userData['role_id'] = $Users->Roles->getIdByName(Role::ADMIN);

        $user = $Users->buildEntity($userData);
        $Users->save($user, ['checkRules' => true, 'atomic' => false]);
        $errors = $user->getErrors();
        if (!empty($errors)) {
            $msg = __('There was a problem creating the first user');
            throw new CustomValidationException($msg, $errors, $Users);
        }

        $token = $AuthenticationTokens->generate($user->id, AuthenticationToken::TYPE_REGISTER);
        $errors = $token->getErrors();
        if (!empty($errors)) {
            $msg = __('There was a problem creating the registration token');
            throw new CustomValidationException($msg, $errors, $AuthenticationTokens);
        }

        $this->setSettings('user', [
            'user_id' => $user->id,
            'token' => $token->token,
        ]);
    }

    /**
     * @return void
     */
    public function saveSmtpSettingsInDb(): void
    {
        $smtpSettings = $this->getSettings('email');
        if (empty($smtpSettings)) {
            return;
        }

        $userId = $this->getSettings('user.user_id');
        if (is_null($userId)) {
            /** @var \App\Model\Table\UsersTable $Users */
            $Users = TableRegistry::getTableLocator()->get('Users');
            $admin = $Users->findFirstAdmin();
            $userId = $admin->get('id');
        }
        $uac = new UserAccessControl(Role::ADMIN, $userId);
        $service = new SmtpSettingsSetService($uac);
        $service->saveSettings($smtpSettings);
    }
}
