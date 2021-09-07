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
 * @since         3.1.0
 */
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;

/**
 * Passbolt command.
 */
class PassboltCommand extends Command
{
    /**
     * @var bool
     */
    public static $isUserRoot = false;

    /**
     * The Passbolt welcome banner should be shown only once.
     * This is a memory cell to that aim.
     *
     * @var bool
     */
    public static $welcomeBannerWasAlreadyShown = false;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();

        CommandBootstrap::init();

        if (!isset(self::$isUserRoot)) {
            self::$isUserRoot = (PROCESS_USER === 'root');
        }
    }

    /**
     * All commands are extend the present command.
     * There execution in the console should called by "passbolt this-command".
     * This method prepends the name of the command if required.
     *
     * @return string
     */
    public static function defaultName(): string
    {
        $consoleName = parent::defaultName();
        if (strtolower($consoleName) !== 'passbolt') {
            $consoleName = 'passbolt ' . $consoleName;
        }

        return $consoleName;
    }

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription(
            __('The Passbolt CLI offers an access to the passbolt API directly from the console.')
        );

        $parser->addArgument('cleanup', [
            'help' => __d('cake_console', 'Identify and fix database relational integrity issues.'),
        ]);

        $parser->addArgument('drop_tables', [
            'help' => __d('cake_console', 'Drop all the tables. Dangerous but useful for a full reinstall.'),
        ]);

        $parser->addArgument('healthcheck', [
            'help' => __d('cake_console', 'Run a healthcheck for this passbolt instance.'),
        ]);

        $parser->addArgument('install', [
            'help' => __d('cake_console', 'Installation shell for the passbolt application.'),
        ]);

        $parser->addArgument('keyring_init', [
            'help' => __d('cake_console', 'Init the GnuPG keyring.'),
        ]);

        if (Configure::read('passbolt.plugins.license')) {
            $parser->addArgument('license_check', [
                'help' => __d('cake_console', 'Check the license.'),
            ]);
        }

        $parser->addArgument('migrate', [
            'help' => __d('cake_console', 'Run database migrations.'),
        ]);

        $parser->addArgument('mysql_export', [
            'help' => __d('cake_console', 'Utility to export mysql database backups.'),
        ]);

        $parser->addArgument('mysql_import', [
            'help' => __d('cake_console', 'Utility to import mysql database backups.'),
        ]);

        $parser->addArgument('register_user', [
            'help' => __d('cake_console', 'Register a new user.'),
        ]);

        $parser->addArgument('send_test_email', [
            'help' => __d('cake_console', 'Try to send a test email and display debug information.'),
        ]);

        $parser->addArgument('datacheck', [
            'help' => __d('cake_console', 'Revalidate the data of the passbolt installation.'),
        ]);

        $parser->addArgument('show_logs_path', [
            'help' => __d('cake_console', 'Show application logs.'),
        ]);

        $parser->addArgument('version', [
            'help' => __d('cake_console', 'Provide version number'),
        ]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        if ($this->skipPassboltWelcome($args)) {
            return $this->successCode();
        }

        $io->out();
        $io->out('     ____                  __          ____  ');
        $io->out('    / __ \____  _____ ____/ /_  ____  / / /_ ');
        $io->out('   / /_/ / __ `/ ___/ ___/ __ \/ __ \/ / __/ ');
        $io->out('  / ____/ /_/ (__  |__  ) /_/ / /_/ / / /    ');
        $io->out(' /_/    \__,_/____/____/_.___/\____/_/\__/   ');
        $io->out();
        $io->out(' Open source password manager for teams');
        $io->hr();

        self::$welcomeBannerWasAlreadyShown = true;

        $this->showHelp($args, $io);

        return $this->successCode();
    }

    /**
     * Appends a series of options to the options provided.
     *
     * @param \Cake\Console\Arguments $args Arguments.
     * @param array $options Options to append.
     * @return array
     */
    protected function formatOptions(Arguments $args, array $options = []): array
    {
        if ($args->getOption('quiet') && !in_array('-q', $options)) {
            $options[] = '-q';
        }

        return $options;
    }

    /**
     * Display an error message
     *
     * @param string $msg message
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function error(string $msg, ConsoleIo $io): void
    {
        $io->out('<error>' . $msg . '</error>');
    }

    /**
     * Display a success message
     *
     * @param string $msg message
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function success(string $msg, ConsoleIo $io): void
    {
        $io->out('<success>' . $msg . '</success>');
    }

    /**
     * Some of the passbolt commands shouldn't be executed as root.
     * By instance it's the case of the healthcheck command that needs to be executed with the same user as your web server.
     *
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return bool true if user is root
     */
    protected function assertNotRoot(ConsoleIo $io): bool
    {
        if (self::$isUserRoot) {
            $io->out();
            $this->error('Passbolt commands cannot be executed as root.', $io);
            $io->out();
            $io->out('The command should be executed with the same user as your web server. By instance:');
            $io->out('su -s /bin/bash -c "' . ROOT . '/bin/cake COMMAND" HTTP_USER');
            $io->out('where HTTP_USER match your web server user: www-data, nginx, http');
            $io->out();

            return false;
        }

        return true;
    }

    /**
     * Return error code
     *
     * @return int
     */
    protected function errorCode(): int
    {
        return self::CODE_ERROR;
    }

    /**
     * Return success code
     *
     * @return int
     */
    protected function successCode(): int
    {
        return self::CODE_SUCCESS;
    }

    /**
     * Skip the Passbolt welcome message.
     *
     * @param \Cake\Console\Arguments $args Arguments.
     * @return bool
     */
    protected function skipPassboltWelcome(Arguments $args): bool
    {
        if ($args->getOption('quiet') || self::$welcomeBannerWasAlreadyShown) {
            return true;
        }

        return in_array(static::class, [
             ShowLogsPathCommand::class,
        ]);
    }

    /**
     * Display help options if "bin/cake passbolt" is called.
     *
     * @param \Cake\Console\Arguments $args Arguments.
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    private function showHelp(Arguments $args, ConsoleIo $io): void
    {
        $command = static::class;
        if (!$args->getOption('help') && ($command === PassboltCommand::class)) {
            $this->displayHelp($this->getOptionParser(), $args, $io);
        }
    }

    /**
     * Abort command if not in debug mode.
     *
     * @param \Cake\Console\ConsoleIo $io Console IO
     * @return void
     */
    protected function abortIfNotInDebugMode(ConsoleIo $io): void
    {
        if (Configure::read('debug') !== true) {
            $io->error('This command is available in debug mode only.');
            $this->abort();
        }
    }
}
