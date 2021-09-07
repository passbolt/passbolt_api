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
namespace App\Command;

use App\Model\Entity\Role;
use App\Utility\Application\FeaturePluginAwareTrait;
use App\Utility\Healthchecks;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Passbolt\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtAbstractService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtKeyPairService;
use PassboltTestData\Command\InsertCommand;

class InstallCommand extends PassboltCommand
{
    use DatabaseAwareCommandTrait;
    use FeaturePluginAwareTrait;

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser
            ->setDescription(__('Installation shell for the passbolt application.'))
            ->addOption('quick', [
                'help' => 'Use a database dump if any to speed things up.',
                'boolean' => true,
                'default' => false,
            ])
            ->addOption('backup', [
                'help' => 'Make a database dump to speed things up for next quick install.',
                'boolean' => true,
                'default' => false,
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
                'boolean' => true,
            ])
            ->addOption('no-admin', [
                'help' => 'Don\'t register an admin account during the installation',
                'default' => 'false',
                'boolean' => true,
            ])
            ->addOption('admin-username', [
                'help' => __('Admin\' username (email). Will be requested in interactive mode.'),
            ])
            ->addOption('admin-first-name', [
                'help' => __('Admin\' first name. Will be requested in interactive mode.'),
            ])
            ->addOption('admin-last-name', [
                'help' => __('Admin\' last name. Will be requested in interactive mode.'),
            ]);

        $this->addDatasourceOption($parser);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        // Root user is not allowed to execute this command.
        // This command needs to be executed with the same user as the webserver.
        if (!$this->assertNotRoot($io)) {
            return $this->errorCode();
        }

        // Create a JWT key pair
        if ($this->isFeaturePluginEnabled('JwtAuthentication')) {
            $jwtService = new JwtKeyPairService();
            try {
                $jwtService->createKeyPair();
                $jwtService->validateKeyPair();
            } catch (InvalidJwtKeyPairException $e) {
                $io->abort($e->getMessage());
            }
        }

        // Quick mode - exit on success
        if ($args->getOption('quick')) {
            return $this->quickInstall($args, $io);
        }

        // Normal mode
        if (!$this->healthchecks($args, $io)) {
            return $this->errorCode();
        }
        if (!$this->schemaCleanup($args, $io)) {
            return $this->errorCode();
        }
        if (!$this->schema($args, $io)) {
            return $this->errorCode();
        }
        if (!$this->dataImport($args, $io)) {
            return $this->errorCode();
        }
        if ($this->keyringInit($args, $io) === $this->errorCode()) {
            return $this->errorCode();
        }
        if (!$this->userRegistration($args, $io)) {
            return $this->errorCode();
        }

        // Quick mode - backup for next time
        if (!$this->quickBackup($args, $io)) {
            return $this->errorCode();
        }

        // Winning!
        $io->nl();
        $this->success(__('Passbolt installation success! Enjoy! ☮'), $io);
        $io->nl();

        return $this->successCode();
    }

    /**
     * Handle the user registration
     * Dispatch the task to register_user with admin option
     *
     * @param \Cake\Console\Arguments $args Arguments
     * @param \Cake\Console\ConsoleIo $io ConsoleIo
     * @return bool operation result
     */
    protected function userRegistration(Arguments $args, ConsoleIo $io): bool
    {
        if (!$args->getOption('no-admin')) {
            $io->nl();
            $io->out(__('Registering the admin user'));
            $io->hr();

            $options = [
                '--role', Role::ADMIN,
                '--interactive',
                '--interactive-loop', (string)RegisterUserCommand::DEFAULT_INTERACTIVE_LOOP,
                '--username', $args->getOption('admin-username'),
                '--first-name', $args->getOption('admin-first-name'),
                '--last-name', $args->getOption('admin-last-name'),
            ];

            $result = $this->executeCommand(
                RegisterUserCommand::class,
                $this->formatOptions($args, $options),
                $io
            );

            return $result === self::CODE_SUCCESS;
        }

        return true;
    }

    /**
     * Handle import of data if parameter is set
     * Dispatch to plugin PassboltTestData.data task
     *
     * @param \Cake\Console\Arguments $args Arguments
     * @param \Cake\Console\ConsoleIo $io ConsoleIo
     * @return bool status
     */
    protected function dataImport(Arguments $args, ConsoleIo $io): bool
    {
        if ($args->getOption('data')) {
            $io->nl();
            $io->out(__('Installing additional data'));
            $io->hr();
            $options = $this->formatOptions($args);
            $options['scenario'] = $args->getOption('data');
            $result = $this->executeCommand(InsertCommand::class, $options, $io);

            return $result === $this->successCode();
        }

        return true;
    }

    /**
     * Try to perform a quick install steps
     * Dispatch to mysql_import job
     *
     * @param \Cake\Console\Arguments $args Arguments
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return int|null
     */
    protected function quickInstall(Arguments $args, ConsoleIo $io): ?int
    {
        // No healthcheck
        // No admin user install
        // etc.
        // Import sql backup

        $this->keyringInit($args, $io);
        $code = $this->executeCommand(MysqlImportCommand::class, $this->formatOptions($args), $io);
        if ($code === $this->successCode()) {
            $this->success(__('Passbolt installation success! Enjoy! ☮'), $io);
        }

        return $code;
    }

    /**
     * Prepare a backup for next quick install
     *
     * @param \Cake\Console\Arguments $args Arguments
     * @param \Cake\Console\ConsoleIo $io ConsoleIo
     * @return bool true if shell exited with success code
     */
    protected function quickBackup(Arguments $args, ConsoleIo $io): bool
    {
        if ($args->getOption('backup')) {
            $io->nl();
            $io->out(__('Backup data for next quick reinstall.'));
            $io->hr();
            $this->keyringInit($args, $io);
            $options = $this->formatOptions($args, ['--clear-previous']);

            return $this->executeCommand(MysqlExportCommand::class, $options, $io) === self::CODE_SUCCESS;
        }

        return true;
    }

    /**
     * Dispatch drop_tables task
     *
     * @param \Cake\Console\Arguments $args Arguments
     * @param \Cake\Console\ConsoleIo $io ConsoleIo
     * @return bool true if shell exited with success code
     */
    protected function schemaCleanup(Arguments $args, ConsoleIo $io): bool
    {
        $io->nl();
        $io->out(__('Cleaning up existing tables if any.'));
        $io->hr();
        $options = $this->formatOptions($args, ['-d', $args->getOption('datasource')]);

        return $this->executeCommand(DropTablesCommand::class, $options, $io) === $this->successCode();
    }

    /**
     * Run the migrations
     *
     * @param \Cake\Console\Arguments $args Arguments
     * @param \Cake\Console\ConsoleIo $io ConsoleIo
     * @return bool true if shell exited with success code
     */
    protected function schema(Arguments $args, ConsoleIo $io): bool
    {
        $io->nl();
        $io->out(__('Install the schema and default data.'));
        $io->hr();

        return $this->runMigrationsMigrateCommand($args, $io) === $this->successCode();
    }

    /**
     * Import the server key in the keyring
     * Dispatch to keyring init task
     *
     * @param \Cake\Console\Arguments $args Arguments
     * @param \Cake\Console\ConsoleIo $io Console IO
     * @return int|null true if shell exited with success code
     */
    protected function keyringInit(Arguments $args, ConsoleIo $io): ?int
    {
        $io->nl();
        $io->out(__('Import the server private key in the keyring'));
        $io->hr();

        return $this->executeCommand(KeyringInitCommand::class, $this->formatOptions($args), $io);
    }

    /**
     * Installation healthchecks
     *
     * @param \Cake\Console\Arguments $args Arguments.
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return bool status success
     */
    protected function healthchecks(Arguments $args, ConsoleIo $io): bool
    {
        $io->nl();
        $io->out(__('Running baseline checks, please wait...'));
        try {
            // Make sure the baseline config files are present
            $checks = Healthchecks::configFiles();
            if (!$checks['configFile']['app']) {
                throw new Exception(__('The application config file is missing in {0}.', CONFIG));
            }

            // Check application url config
            $checks = Healthchecks::core();
            if (!$checks['core']['fullBaseUrl'] && !$checks['core']['validFullBaseUrl']) {
                $msg = __('The fullBaseUrl is not set or not valid. {0}', $checks['core']['info']['fullBaseUrl']);
                throw new Exception($msg);
            }

            // Check that a GPG configuration id is provided
            $checks = Healthchecks::gpg();
            if (!$checks['gpg']['gpgKey'] || !$checks['gpg']['gpgKeyPublic'] || !$checks['gpg']['gpgKeyPrivate']) {
                throw new Exception(__('The GnuPG config for the server is not available or incomplete'));
            }
            // Check if keyring is present and writable
            if (!$checks['gpg']['gpgHome']) {
                throw new Exception(__('The OpenPGP keyring location is not set.'));
            }
            if (!$checks['gpg']['gpgHomeWritable']) {
                throw new Exception(__('The OpenPGP keyring location is not writable.'));
            }

            // In production don't accept default GPG server key
            if (!Configure::read('debug')) {
                if (!$checks['gpg']['gpgKeyNotDefault']) {
                    $msg = __('Default GnuPG server key cannot be used in production.');
                    $msg .= ' ' . __('Please change the values of passbolt.gpg.server in config/passbolt.php.');
                    $msg .= ' ' . __('If you do not have yet a server key, please generate one.');
                    $msg .= ' ' . __('Take a look at the install documentation for more information.');
                    throw new Exception($msg);
                }
            }

            // Check that there is a public and private key found at the given path
            if (!$checks['gpg']['gpgKeyPublicReadable']) {
                $msg = 'No public key found at the given path {0}';
                throw new Exception(__($msg, Configure::read('GPG.serverKey.public')));
            }
            if (!$checks['gpg']['gpgKeyPrivateReadable']) {
                $msg = 'No private key found at the given path {0}';
                throw new Exception(__($msg, Configure::read('GPG.serverKey.private')));
            }

            // Check that the public and private key match the fingerprint
            if (!$checks['gpg']['gpgKeyPrivateFingerprint'] || !$checks['gpg']['gpgKeyPublicFingerprint']) {
                $msg = __('The server key fingerprint does not match the fingerprint mentioned in config/passbolt.php');
                throw new Exception($msg);
            }
            if (!$checks['gpg']['gpgKeyPublicEmail']) {
                throw new Exception(__('The server public key should have an email id.'));
            }
        } catch (Exception $e) {
            $this->error($e->getMessage(), $io);
            $this->error(__('Please run ./bin/cake passbolt healthcheck for more information and help.'), $io);

            return false;
        }

        // Database checks
        $checks = Healthchecks::database($args->getOption('datasource'));
        if (!$checks['database']['connect'] || !$checks['database']['supportedBackend']) {
            $this->error(__('There are some issues with the database configuration.'), $io);
            $this->error(__('Please run ./bin/cake passbolt healthcheck for more information and help.'), $io);

            return false;
        }
        if ($checks['database']['tablesCount']) {
            if (!$args->getOption('force')) {
                $msg = __('Some tables are already present in the database.') . ' ';
                $msg .= __('A new installation would override existing data.');
                $this->error($msg, $io);
                $this->error(__('Please use --force to proceed anyway.'), $io);

                return false;
            }
        }

        // JWT checks
        if ($this->isFeaturePluginEnabled('JwtAuthentication')) {
            $checks = Healthchecks::jwt();
            if ($checks['jwt']['keyPairValid'] !== true) {
                $fixCmd = (new JwtKeyPairService())->getCreateJwtKeysCommand();

                $this->error('The JWT key pair is not valid, or cannot be found.', $io);
                $this->error('Please run ' . $fixCmd . ' to create a valid pair.', $io);

                return false;
            }

            if ($checks['jwt']['jwtWritable'] !== true) {
                $folder = JwtAbstractService::JWT_CONFIG_DIR;
                $fixCmd = "sudo chmod 775 $(find $folder -type d)";
                $this->error("The directory {$folder} is not writable.", $io);
                $this->error('You can try ' . $fixCmd, $io);

                return false;
            }
        }

        $this->success(__('Critical healthchecks are OK'), $io);

        return true;
    }
}
