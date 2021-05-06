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

use Cake\Command\CacheClearallCommand;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * Migrate command.
 */
class MigrateCommand extends PassboltCommand
{
    use DatabaseAwareCommandTrait;

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser
            ->setDescription(__('Migration shell for the Passbolt application.'))
            ->addOption('backup', [
                'help' => 'Make a database backup to be used in case something goes wrong.',
                'boolean' => true,
                'default' => false,
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

        // Backup
        if ($this->backup($args, $io)) {
            $io->hr();
        } else {
            return $this->errorCode();
        }

        // Migration task
        $io->out(' ' . __('Running migration scripts.'));
        $io->hr();
        $result = $this->runMigrationsMigrateCommand($args, $io);

        if ($result === self::CODE_ERROR) {
            return $result;
        }

        // Clean cache
        $result = $this->executeCommand(
            CacheClearallCommand::class,
            $this->formatOptions($args),
            $io
        );

        return $result;
    }

    /**
     * Prepare a backup for next quick install
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return bool true if shell exited with success code
     */
    protected function backup(Arguments $args, ConsoleIo $io): bool
    {
        if ($args->getOption('backup')) {
            $result = $this->executeCommand(MysqlExportCommand::class, $this->formatOptions($args, ['--force']), $io);

            return $result === self::CODE_SUCCESS;
        }

        return true;
    }
}
