<?php
declare(strict_types=1);

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
namespace Passbolt\DirectorySync\Command;

use App\Command\PassboltCommand;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Routing\Router;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

class DirectorySyncCommand extends PassboltCommand
{
    /**
     * @var \Passbolt\DirectorySync\Utility\DirectoryOrgSettings
     */
    public $directoryOrgSettings;

    /**
     * All the plugins commands extend the present command.
     * There execution in the console should called by "directory_sync this-command".
     * This method prepends the name of the command if required.
     *
     * @return string
     */
    public static function defaultName(): string
    {
        $consoleName = str_replace('passbolt ', '', parent::defaultName());
        if (strtolower($consoleName) !== 'directory_sync') {
            $consoleName = 'directory_sync ' . $consoleName;
        }

        return $consoleName;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $this->assertCurrentProcessUser($io);

        $isLdapLoaded = extension_loaded('ldap');
        if (!$isLdapLoaded) {
            $this->error(__('Error: the ldap extension is not installed'), $io);
            $this->abort();
        }

        $this->directoryOrgSettings = DirectoryOrgSettings::get();
        if (!$this->directoryOrgSettings->isEnabled()) {
            $io->err(__('The ldap integration is not configured or it is disabled'));
            $io->info(
                __(
                    'To fix this problem, you need to configure ldap: {0}.',
                    [Router::url('/app/administration/users-directory', true)]
                )
            );
            $this->error(__('aborting'), $io);
            $this->abort();
        }
        $this->warnPersist($args, $io);

        return $this->successCode();
    }

    /**
     * Check persist argument and displays a warning
     *
     * @param \Cake\Console\Arguments $args The command arguments
     * @param \Cake\Console\ConsoleIo $io The console IO
     * @return void
     */
    protected function warnPersist(Arguments $args, ConsoleIo $io)
    {
        if ($args->hasOption('persist') && !$args->getOption('persist') && !$args->getOption('dry-run')) {
            $io->error(__(
                'Warning: check config and pass option --persist to actually modify data. Running in dry-run mode.'
            ), 2);
        }
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription(__('The directory shell offer synchronizations tasks from the CLI.'));

        $parser->addArgument('test', [
            'help' => __d('cake_console', 'Test ldap connection and objects.'),
        ]);
        $parser->addArgument('all', [
            'help' => __d('cake_console', 'Synchronize users and groups.'),
        ]);
        $parser->addArgument('users', [
            'help' => __d('cake_console', 'Synchronize users'),
        ]);
        $parser->addArgument('groups', [
            'help' => __d('cake_console', 'Synchronize groups'),
        ]);
        $parser->addArgument('ignore-list', [
            'help' => __d('cake_console', 'List all the ignored record during the directory synchronization process.'),
        ]);
        $parser->addArgument('ignore-create', [
            'help' => __d('cake_console', 'Start ignoring a record during the directory synchronization process.'),
        ]);
        $parser->addArgument('ignore-delete', [
            'help' => __d('cake_console', 'Stop ignoring a record during the directory synchronization process.'),
        ]);
        $parser->addArgument('debug', [
            'help' => __d('cake_console', 'Debug configuration helper'),
        ]);

        return $parser;
    }
}
