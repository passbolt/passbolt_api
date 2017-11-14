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
                'default' => 'false',
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
//            ->addOption('delete-avatars', [
//                'help' => 'Delete existing public avatars',
//                'default' => 'true',
//                'short' => 'a',
//            ])
//            ->addOption('send-anonymous-statistics', [
//                'help' => 'Whether or not anonymous usage statistics should be sent to passbolt servers.
//				(Check our privacy policy for more information: https://www.passbolt.com/privacy#statistics).',
//                'default' => '',
//                'choices' => [
//                    'true',
//                    'false',
//                ],
//            ])

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
        $this->assertNotRoot();

        // Assert that the required healtcheck are passing
        $this->out(__('Running baseline checks, please wait...'));
        $this->assertBaselineHealthchecks();
        $this->assertDatabaseChecks();
        $this->out(__('Checks OK'));

        $this->out();
        $this->out(__('Cleaning up existing tables if any'));
        $this->hr();
        $this->dispatchShell('passbolt drop_tables');

        $this->out();
        $this->out(__('Installing data model and baseline data'));
        $this->hr();
        $this->dispatchShell('migrations migrate');

        // Insert additional data
        $data = $this->param('data');
        if (isset($data)) {
            $this->out();
            $this->out(__('Installing additional data'));
            $this->hr();
            $this->dispatchShell('passbolt data ' . $this->param('data'));
        }

        // Create first admin user
        if ($this->param('no-admin') === false) {
            $username = $this->param('admin-username');
            $firstname = $this->param('admin-first-name');
            $lastname = $this->param('admin-last-name');

            $this->out();
            $this->out(__('Registering the admin user'));
            $this->hr();
            $this->_registerAdmin($username, $firstname, $lastname);
        }

        $this->out('');
        $this->out('<success>' . __('Passbolt installation success! Enjoy! ☮') . '</success>');
        $this->out('');

        return true;
    }

    /**
     * Database checks
     */
    public function assertDatabaseChecks()
    {
        // Database checks
        $checks = Healthchecks::database();
        if (!$checks['database']['connect'] || !$checks['database']['supportedBackend']) {
            $this->errorExit([
                __('There are some issues with the database configuration.'),
                __('Please run ./app/Console/cake passbolt healthcheck for more information and help.')
            ]);
        }
        if ($checks['database']['tablesCount']) {
            if (!$this->param('force')) {
                $this->errorExit([
                    __('Some tables are already present in the database, a new installation would override existing data.'),
                    __('Please use --force to proceed anyway.')
                ]);
            }
        }
    }

    /**
     * Installation healthchecks
     */
    public function assertBaselineHealthchecks()
    {
        try {
            // Make sure the baseline config files are present
            $checks = Healthchecks::configFiles();
            foreach ($checks['configFile'] as $file => $enabled) {
                if (!$enabled) {
                    throw new Exception('One config file is missing (' . $file . ').');
                }
            }

            // Check application url config
            $checks = Healthchecks::core();
            if (!$checks['core']['fullBaseUrl'] && !$checks['core']['validFullBaseUrl']) {
                throw new Exception('The fullBaseUrl is not set or not valid. ' . $checks['core']['info']['fullBaseUrl']);
            }

            // Check that a GPG configuration id is provided
            $checks = Healthchecks::gpg();
            if (!$checks['gpg']['gpgKey'] || !$checks['gpg']['gpgKeyPublic'] || !$checks['gpg']['gpgKeyPrivate']) {
                throw new Exception('The GnuPG config for the server is not available or incomplete');
            }
            // Check if keyring is present and writable
            if (!$checks['gpg']['gpgHome'] || !$checks['gpg']['gpgHomeWritable']) {
                throw new Exception("The GPG keyring location is not set or not writable.");
            }

            // In production don't accept default GPG server key
            if (!Configure::read('debug')) {
                if (!$checks['gpg']['gpgKeyNotDefault']) {
                    $msg = "Default GnuPG server key cannot be used in production. ";
                    $msg .= "Please change the values of 'GPG.server' in 'APP/Config/app.php' with your server key information. ";
                    $msg .= "If you don't have yet a server key, please generate one, take a look at the install documentation.";
                    throw new Exception($msg);
                }
            }

            // Check that there is a public and private key found at the given path
            if (!$checks['gpg']['gpgKeyPublicReadable']) {
                throw new Exception("No public key found at the given path " . Configure::read('GPG.serverKey.public'));
            }
            if (!$checks['gpg']['gpgKeyPrivateReadable']) {
                throw new Exception("No private key found at the given path " . Configure::read('GPG.serverKey.private'));
            }

            // Check that the public and private key match the fingerprint
            if (!$checks['gpg']['gpgKeyPrivateFingerprint'] || !$checks['gpg']['gpgKeyPublicFingerprint']) {
                throw new Exception('The server key fingerprint does not match the fingerprint mentioned in app/config.php');
            }
            if (!$checks['gpg']['gpgKeyPublicEmail']) {
                throw new Exception('The server public key should have an email id.');
            }
        } catch (Exception $e) {
            $this->errorExit([
                $e->getMessage(),
                __('Please run ./app/Console/cake passbolt healthcheck for more information and help.')
            ]);
        }
    }

    /**
     * Register the admin user
     *
     * @param string $username admin email
     * @param string $firstName admin first name
     * @param string $lastName admin last name
     * @return void
     */
    protected function _registerAdmin($username = null, $firstName = null, $lastName = null)
    {
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
        $quiet = $this->param('quiet');
        if (isset($quiet) && $quiet == 1) {
            $cmd .= ' -q';
        }
        $this->dispatchShell($cmd);
    }
}
