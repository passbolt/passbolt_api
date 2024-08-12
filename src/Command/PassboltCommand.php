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

use App\Service\Command\ProcessUserService;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\BaseCommand;
use Cake\Console\CommandCollection;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\Event\EventDispatcherTrait;

/**
 * Passbolt command.
 */
class PassboltCommand extends Command implements PassboltCommandInterface
{
    use EventDispatcherTrait;

    /**
     * The Passbolt welcome banner should be shown only once.
     * This is a memory cell to that aim.
     *
     * @var bool
     */
    public static $welcomeBannerWasAlreadyShown = false;

    /**
     * List of popular webserver usernames.
     *
     * @var string[]
     */
    public const KNOWN_WEBSERVER_USERS = [
        'www-data',
        'nginx',
        'apache',
        'http',
    ];

    private ?CommandCollection $passboltCommandCollection;

    /**
     * @param \Cake\Console\CommandCollection|null $passboltCommandCollection Collection of commands used to display the list of passbolt commands
     * @see PassboltBuildCommandsListener
     */
    public function __construct(?CommandCollection $passboltCommandCollection = null)
    {
        parent::__construct();
        $this->passboltCommandCollection = $passboltCommandCollection;
    }

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();

        CommandBootstrap::init();
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
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('The Passbolt CLI offers an access to the passbolt API directly from the console.');
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
        $parser->setDescription($this->getCommandDescription());

        if (!isset($this->passboltCommandCollection)) {
            return $parser;
        }

        // Append the help message as options of all the passbolt commands
        foreach ($this->passboltCommandCollection as $name => $commandFQN) {
            if (is_a($commandFQN, PassboltCommandInterface::class, true)) {
                /** @var \App\Command\PassboltCommandInterface $commandFQN */
                $help = $commandFQN::getCommandDescription();
            } elseif (is_a($commandFQN, BaseCommand::class, true)) {
                /** @var \Cake\Console\BaseCommand $command */
                $command = new $commandFQN();
                $parser = $command->getOptionParser();
                $help = $parser->getDescription();
            } else {
                continue;
            }
            $parser->addArgument($name, [
                'help' => $help,
            ]);
        }

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
    public function formatOptions(Arguments $args, array $options = []): array
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
    public function error(string $msg, ConsoleIo $io): void
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
     * Checks if user running the command is valid or not. If not, aborts or shows warning depending on severity.
     *
     * @param \Cake\Console\ConsoleIo $io IO object.
     * @param \App\Service\Command\ProcessUserService $processUserService process user service
     * @return void
     */
    protected function assertCurrentProcessUser(ConsoleIo $io, ProcessUserService $processUserService)
    {
        if (!$this->assertNotRoot($processUserService, $io)) {
            $this->error(__('aborting'), $io);
            $this->abort();
        }

        $isWebserverUser = in_array($processUserService->getName(), self::KNOWN_WEBSERVER_USERS);
        if (!$isWebserverUser) {
            $io->out();
            $io->warning(__('Passbolt commands should only be executed as the web server user.'));
            $io->out();
            $io->info(__('The command should be executed with the same user as your web server. By instance:'));
            $io->info('su -s /bin/bash -c "' . ROOT . '/bin/cake COMMAND" HTTP_USER');
            $io->info(
                __(
                    'where HTTP_USER match your web server user: {0}',
                    implode(', ', self::KNOWN_WEBSERVER_USERS)
                )
            );
            $io->out();
        }
    }

    /**
     * Some of the passbolt commands shouldn't be executed as root.
     * By instance it's the case of the healthcheck command that needs to be executed with the same user as your web server.
     *
     * @param \App\Service\Command\ProcessUserService $processUserService Process user service.
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return bool true if user is root
     */
    protected function assertNotRoot(ProcessUserService $processUserService, ConsoleIo $io): bool
    {
        if ($processUserService->getName() === 'root') {
            $io->out();
            $this->error('Passbolt commands cannot be executed as root.', $io);
            $io->out();
            $io->out('The command should be executed with the same user as your web server. By instance:');
            $io->out('su -s /bin/bash -c "' . ROOT . '/bin/cake COMMAND" HTTP_USER');
            $io->out(
                __(
                    'where HTTP_USER match your web server user: {0}',
                    implode(', ', self::KNOWN_WEBSERVER_USERS)
                )
            );
            $io->out();

            return false;
        }

        return true;
    }

    /**
     * Checks if current process user is known webserver user or not.
     *
     * @return bool Returns `true` if known webserver user, `false` otherwise.
     */
    protected function isWebserverUser(): bool
    {
        return in_array(PROCESS_USER, self::KNOWN_WEBSERVER_USERS);
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

    /**
     * @return \Cake\Console\CommandCollection|null
     */
    public function getPassboltCommandCollection(): ?CommandCollection
    {
        return $this->passboltCommandCollection;
    }
}
