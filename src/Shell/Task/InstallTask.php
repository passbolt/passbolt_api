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
namespace App\Shell\Task;

use App\Shell\AppShell;
use App\Utility\Healthchecks;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;

class InstallTask extends AppShell
{
    /**
     * Gets the option parser instance and configures it.
     *
     * By overriding this method you can configure the ConsoleOptionParser before returning it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser
            ->setDescription(__('Installation shell for the passbolt application.'))
            ->addOption('quick', [
                'help' => 'Use a database dump if any to speed things up.',
                'boolean' => true,
                'default' => false
            ])
            ->addOption('backup', [
                'help' => 'Make a database dump to speed things up for next quick install.',
                'boolean' => true,
                'default' => false
            ])
            ->addOption('cache', [
                'help' => 'Create a database dump to enable cache option use later on',
                'default' => 'true',
                'short' => 'c',
            ])
            ->addOption('data', [
                'help' => 'Insert some default test data, useful for testing and development purpose.',
                'default' => null,
            ])
            ->addOption('force', [
                'help' => 'Override database if any',
                'default' => 'false',
                'short' => 'f',
                'boolean' => true
            ])
            ->addOption('no-admin', [
                'help' => 'Don\'t register an admin account during the installation',
                'default' => 'false',
                'boolean' => true,
            ])
            ->addOption('admin-username', [
                'help' => __('Admin\' username (email). If interactive mode enabled, and no-admin not set, it will be requested')
            ])
            ->addOption('admin-first-name', [
                'help' => __('Admin\' first name. If interactive mode enabled, and no-admin not set, it will be requested')
            ])
            ->addOption('admin-last-name', [
                'help' => __('Admin\' last name. If interactive mode enabled, and no-admin not set, it will be requested')
            ]);

        return $parser;
    }

    /**
     * Main shell entry point
     *
     * @return bool true if successful
     */
    public function main()
    {
        // Root user is not allowed to execute this command.
        // This command needs to be executed with the same user as the webserver.
        if (!$this->assertNotRoot()) {
            return false;
        }

        // Quick mode - exit on success
        if ($this->_quickInstall()) {
            return true;
        }

        // Normal mode
        if (!$this->_healthchecks()) {
            return false;
        }
        if (!$this->_schemaCleanup()) {
            return false;
        }
        if (!$this->_schema()) {
            return false;
        }
        if (!$this->_dataImport()) {
            return false;
        }
        if (!$this->_keyringInit()) {
            return false;
        }
        if (!$this->_userRegistration()) {
            return false;
        }

        // Quick mode - backup for next time
        if (!$this->_quickBackup()) {
            return false;
        }

        // Winning!
        $this->out('');
        $this->_success(__('Passbolt installation success! Enjoy! ☮'));
        $this->out('');

        return true;
    }

    /**
     * Handle the user registration
     * Dispatch the task to register_user with admin option
     *
     * @return bool operation result
     */
    protected function _userRegistration()
    {
        if ($this->param('no-admin') === false) {
            $username = $this->param('admin-username');
            $firstName = $this->param('admin-first-name');
            $lastName = $this->param('admin-last-name');

            $this->out();
            $this->out(__('Registering the admin user'));
            $this->hr();

            $cmd = 'passbolt register_user -r admin';
            if ($this->interactive) {
                $cmd .= ' -i';
            }
            if (!is_null($username)) {
                $cmd .= ' -u ' . $username;
            }
            if (!is_null($firstName)) {
                $cmd .= ' -f ' . $firstName;
            }
            if (!is_null($lastName)) {
                $cmd .= ' -l ' . $lastName;
            }
            $cmd = $this->_formatCmd($cmd);

            return ($this->dispatchShell($cmd) === self::CODE_SUCCESS);
        }

        return true;
    }

    /**
     * Handle import of data if parameter is set
     * Dispatch to plugin PassboltTestData.data task
     *
     * @return bool status
     */
    protected function _dataImport()
    {
        $data = $this->param('data');
        if (isset($data)) {
            $this->out();
            $this->out(__('Installing additional data'));
            $this->hr();
            $cmd = $this->_formatCmd('passbolt data ' . $data);
            $this->dispatchShell($cmd);
        }

        return true;
    }

    /**
     * Try to perform a quick install steps
     * Dispatch to mysql_import job
     *
     * @return bool
     */
    protected function _quickInstall()
    {
        // No healthcheck
        // No admin user install
        // etc.
        // Import sql backup
        if ($this->param('quick')) {
            $this->_keyringInit();
            $cmd = $this->_formatCmd('passbolt mysql_import');
            $code = $this->dispatchShell($cmd);
            if ($code === self::CODE_SUCCESS) {
                $this->_success(__('Passbolt installation success! Enjoy! ☮'));

                return true;
            }
        }

        return false;
    }

    /**
     * Prepare a backup for next quick install
     *
     * @return bool true if shell exited with success code
     */
    protected function _quickBackup()
    {
        if ($this->param('backup')) {
            $this->out();
            $this->out(__('Backup data for next quick reinstall.'));
            $this->hr();
            $this->_keyringInit();
            $cmd = $this->_formatCmd('passbolt mysql_export --clear-previous');

            return ($this->dispatchShell($cmd) === self::CODE_SUCCESS);
        }

        return true;
    }

    /**
     * Dispatch drop_tables task
     *
     * @return bool true if shell exited with success code
     */
    protected function _schemaCleanup()
    {
        $this->out();
        $this->out(__('Cleaning up existing tables if any.'));
        $this->hr();
        $cmd = $this->_formatCmd('passbolt drop_tables');

        return ($this->dispatchShell($cmd) === self::CODE_SUCCESS);
    }

    /**
     * Run the migrations
     *
     * @return bool true if shell exited with success code
     */
    protected function _schema()
    {
        $this->out();
        $this->out(__('Install the schema and default data.'));
        $this->hr();
        $cmd = $this->_formatCmd('migrations migrate --no-lock');

        return ($this->dispatchShell($cmd) === self::CODE_SUCCESS);
    }

    /**
     * Import the server key in the keyring
     * Dispatch to keyring init task
     *
     * @return bool true if shell exited with success code
     */
    protected function _keyringInit()
    {
        $this->out();
        $this->out(__('Import the server private key in the keyring'));
        $this->hr();
        $cmd = $this->_formatCmd('passbolt keyring_init');

        return ($this->dispatchShell($cmd) === self::CODE_SUCCESS);
    }

    /**
     * Installation healthchecks
     *
     * @return bool status success
     */
    protected function _healthchecks()
    {
        $this->out();
        $this->out(__('Running baseline checks, please wait...'));
        try {
            // Make sure the baseline config files are present
            $checks = Healthchecks::configFiles();
            if (!$checks['configFile']['app']) {
                throw new Exception(__('The application config file is missing in {0}.', CONFIG));
            }

            // Check application url config
            $checks = Healthchecks::core();
            if (!$checks['core']['fullBaseUrl'] && !$checks['core']['validFullBaseUrl']) {
                throw new Exception(__('The fullBaseUrl is not set or not valid. {0}', $checks['core']['info']['fullBaseUrl']));
            }

            // Check that a GPG configuration id is provided
            $checks = Healthchecks::gpg();
            if (!$checks['gpg']['gpgKey'] || !$checks['gpg']['gpgKeyPublic'] || !$checks['gpg']['gpgKeyPrivate']) {
                throw new Exception(__('The GnuPG config for the server is not available or incomplete'));
            }
            // Check if keyring is present and writable
            if (!$checks['gpg']['gpgHome']) {
                throw new Exception(__('The GPG keyring location is not set.'));
            }
            if (!$checks['gpg']['gpgHomeWritable']) {
                throw new Exception(__('The GPG keyring location is not writable.'));
            }

            // In production don't accept default GPG server key
            if (!Configure::read('debug')) {
                if (!$checks['gpg']['gpgKeyNotDefault']) {
                    $msg = __('Default GnuPG server key cannot be used in production.');
                    $msg .= ' ' . __('Please change the values of passbolt.gpg.server in config/passbolt.php with your server key information.');
                    $msg .= ' ' . __('If you do not have yet a server key, please generate one, take a look at the install documentation.');
                    throw new Exception($msg);
                }
            }

            // Check that there is a public and private key found at the given path
            if (!$checks['gpg']['gpgKeyPublicReadable']) {
                throw new Exception(__('No public key found at the given path {0}', Configure::read('GPG.serverKey.public')));
            }
            if (!$checks['gpg']['gpgKeyPrivateReadable']) {
                throw new Exception(__('No private key found at the given path {0}', Configure::read('GPG.serverKey.private')));
            }

            // Check that the public and private key match the fingerprint
            if (!$checks['gpg']['gpgKeyPrivateFingerprint'] || !$checks['gpg']['gpgKeyPublicFingerprint']) {
                throw new Exception(__('The server key fingerprint does not match the fingerprint mentioned in config/passbolt.php'));
            }
            if (!$checks['gpg']['gpgKeyPublicEmail']) {
                throw new Exception(__('The server public key should have an email id.'));
            }
        } catch (Exception $e) {
            $this->_error($e->getMessage());
            $this->_error(__('Please run ./bin/cake passbolt healthcheck for more information and help.'));

            return false;
        }

        // Database checks
        $checks = Healthchecks::database();
        if (!$checks['database']['connect'] || !$checks['database']['supportedBackend']) {
            $this->_error(__('There are some issues with the database configuration.'));
            $this->_error(__('Please run ./bin/cake passbolt healthcheck for more information and help.'));

            return false;
        }
        if ($checks['database']['tablesCount']) {
            if (!$this->param('force')) {
                $this->_error(__('Some tables are already present in the database, a new installation would override existing data.'));
                $this->_error(__('Please use --force to proceed anyway.'));

                return false;
            }
        }

        $this->_success(__('Critical healthchecks are OK'));

        return true;
    }
}
