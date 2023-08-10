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

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Passbolt\DirectorySync\Actions\AllSyncAction;

class AllCommand extends DirectorySyncCommand
{
    use SyncCommandTrait;

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription(__('Directory Sync'))
            ->addOption('dry-run', [
                'help' => 'Don\'t save the changes',
                'default' => 'true',
                'boolean' => true,
            ])
            ->addOption('persist', [
                'help' => 'Persist data, otherwise it won\'t save the changes',
                'default' => false,
                'boolean' => true,
            ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);
        $reports = [];

        try {
            $this->model = 'Users';
            $dryRun = $args->getOption('dry-run') || !$args->getOption('persist');
            $allSyncAction = new AllSyncAction();
            $reports = $allSyncAction->execute($dryRun);
        } catch (\Exception $exception) {
            $this->error($exception->getMessage(), $io);

            return $this->errorCode();
        }

        $this->displayReports($reports['users'], 'Users', $io);
        $this->displayReports($reports['groups'], 'Groups', $io);

        $this->warnPersist($args, $io);

        return $this->successCode();
    }
}
