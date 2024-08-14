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
use App\Service\Command\ProcessUserService;
use App\Service\Healthcheck\ConfigFiles\AppConfigFileHealthcheck;
use App\Service\Healthcheck\Core\ValidFullBaseUrlCoreHealthcheck;
use App\Service\Healthcheck\Database\ConnectDatabaseHealthcheck;
use App\Service\Healthcheck\Database\TablesCountDatabaseHealthcheck;
use App\Service\Healthcheck\Gpg\FingerprintMatchGpgHealthcheck;
use App\Service\Healthcheck\Gpg\HomeVariableDefinedGpgHealthcheck;
use App\Service\Healthcheck\Gpg\HomeVariableWritableGpgHealthcheck;
use App\Service\Healthcheck\Gpg\KeyNotDefaultGpgHealthcheck;
use App\Service\Healthcheck\Gpg\PrivateKeyReadableAndParsableGpgHealthcheck;
use App\Service\Healthcheck\Gpg\PublicKeyEmailGpgHealthcheck;
use App\Service\Healthcheck\Gpg\PublicKeyReadableAndParsableGpgHealthcheck;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckWithOptionsInterface;
use App\Service\Subscriptions\SubscriptionCheckInCommandServiceInterface;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\Core\Exception\CakeException;
use Passbolt\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException;
use Passbolt\JwtAuthentication\JwtAuthenticationPlugin;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtKeyPairService;
use PassboltTestData\Command\InsertCommand;

class InstallCommand extends PassboltCommand
{
    use DatabaseAwareCommandTrait;
    use FeaturePluginAwareTrait;

    private HealthcheckServiceCollector $healthcheckServiceCollector;

    protected ProcessUserService $processUserService;

    protected SubscriptionCheckInCommandServiceInterface $subscriptionCheckInCommandService;

    /**
     * The client passed in the constructor might be null when run using the selenium tests
     *
     * @param \App\Service\Command\ProcessUserService $processUserService Process user service.
     * @param \App\Service\Subscriptions\SubscriptionCheckInCommandServiceInterface $subscriptionCheckInCommandService Service checking the subscription validity.
     * @param \App\Service\Healthcheck\HealthcheckServiceCollector $healthcheckServiceCollector Health check service collector.
     */
    public function __construct(
        ProcessUserService $processUserService,
        SubscriptionCheckInCommandServiceInterface $subscriptionCheckInCommandService,
        HealthcheckServiceCollector $healthcheckServiceCollector
    ) {
        parent::__construct();

        $this->processUserService = $processUserService;
        $this->subscriptionCheckInCommandService = $subscriptionCheckInCommandService;
        $this->healthcheckServiceCollector = $healthcheckServiceCollector;
    }

    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Installation shell for the passbolt application.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser
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
        $this->assertCurrentProcessUser($io, $this->processUserService);

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
        $this->subscriptionCheckInCommandService->check($this, $args, $io);

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

            $registerUserCommand = new RegisterUserCommand($this->processUserService);
            $result = $this->executeCommand(
                $registerUserCommand,
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

            return $this->executeCommand(SqlExportCommand::class, $options, $io) === self::CODE_SUCCESS;
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

        $keyringInitCommand = new KeyringInitCommand($this->processUserService);

        return $this->executeCommand($keyringInitCommand, $this->formatOptions($args), $io);
    }

    /**
     * @return \App\Service\Healthcheck\HealthcheckServiceInterface[]
     */
    protected function getInstallCheckHealthcheckServices(): array
    {
        $domainsIncluded = [];
        if ($this->isFeaturePluginEnabled(JwtAuthenticationPlugin::class)) {
            $domainsIncluded = [
                HealthcheckServiceCollector::DOMAIN_JWT,
            ];
        }

        $servicesIncluded = [
            AppConfigFileHealthcheck::class,
            ValidFullBaseUrlCoreHealthcheck::class,
            PublicKeyReadableAndParsableGpgHealthcheck::class,
            PrivateKeyReadableAndParsableGpgHealthcheck::class,
            HomeVariableDefinedGpgHealthcheck::class,
            HomeVariableWritableGpgHealthcheck::class,
            FingerprintMatchGpgHealthcheck::class,
            PublicKeyEmailGpgHealthcheck::class,
        ];

        // In production don't accept default GPG server key
        if (!Configure::read('debug')) {
            $servicesIncluded[] = KeyNotDefaultGpgHealthcheck::class;
        }
        $servicesIncluded[] = ConnectDatabaseHealthcheck::class;

        return $this->healthcheckServiceCollector->getServicesFiltered($domainsIncluded, $servicesIncluded);
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
        $healthcheckServices = $this->getInstallCheckHealthcheckServices();
        try {
            foreach ($healthcheckServices as $healthcheckService) {
                if ($healthcheckService instanceof HealthcheckWithOptionsInterface) {
                    $healthcheckService->setOptions($args->getOptions());
                }

                $healthcheckService->check();
                if (!$healthcheckService->isPassed()) {
                    throw new CakeException($healthcheckService->getFailureMessage());
                }
            }
        } catch (CakeException $e) {
            $this->error($e->getMessage(), $io);
            $this->error(__('Please run ./bin/cake passbolt healthcheck for more information and help.'), $io);

            return false;
        }

        // If force is false, and database is populated, warn
        if (!$args->getOption('force')) {
            /** @var \App\Service\Healthcheck\Database\TablesCountDatabaseHealthcheck $tableCountCheck */
            $tableCountCheck = $this->healthcheckServiceCollector
                ->getService(TablesCountDatabaseHealthcheck::class);
            $tableCountCheck->check();
            if ($tableCountCheck->isPassed()) {
                $msg = __('Some tables are already present in the database.') . ' ';
                $msg .= __('A new installation would override existing data.');
                $this->error($msg, $io);
                $this->error(__('Please use --force to proceed anyway.'), $io);

                return false;
            }
        }

        $this->success(__('Critical healthchecks are OK'), $io);

        return true;
    }
}
