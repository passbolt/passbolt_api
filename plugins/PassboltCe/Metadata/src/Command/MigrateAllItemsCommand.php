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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Command;

use App\Command\PassboltCommand;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Passbolt\Metadata\Service\Migration\MigrateAllV4ItemsToV5Service;

class MigrateAllItemsCommand extends PassboltCommand
{
    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Migrate V4 resources, folders, etc. to V5.');
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        if (!Configure::read('debug') || !Configure::read('passbolt.selenium.active')) {
            $io->out('This command is to be used for testing and development purpose only.');
            $io->out('Please enable DEBUG and PASSBOLT_SELENIUM_ACTIVE flags.');

            return $this->errorCode();
        }

        $result = (new MigrateAllV4ItemsToV5Service())->migrate($io);

        if (!$result['success']) {
            $msg = __('No items were migrated due to errors.');
            if (!empty($result['migrated'])) {
                $msg = __('There were few errors while migrating some items.');
            }

            $io->error($msg);
            $io->error(__('See errors below:'));
            $this->displayErrors($result['errors'], $io);

            $this->displayMigratedItems($result['migrated'], $io);

            return $this->errorCode();
        }

        $io->success(__('All items successfully migrated.'));
        // Display success summary
        $this->displayMigratedItems($result['migrated'], $io);

        return $this->successCode();
    }

    /**
     * Displays migrated items in the table format.
     *
     * @param array $migratedItems Migrated items.
     * @param \Cake\Console\ConsoleIo $io IO object.
     * @return void
     */
    private function displayMigratedItems(array $migratedItems, ConsoleIo $io): void
    {
        if ($migratedItems === []) {
            return;
        }

        $io->success(__('See migrated items summary below:'));

        $summary = [];

        // header
        $summary[] = [__('Entity'), __('Number of rows updated')];
        $total = 0;
        foreach ($migratedItems as $item) {
            $count = count($item['ids']);
            $summary[] = [$item['entity'], $count];
            $total += $count;
        }
        $summary[] = [__('Total'), $total];

        $io->helper('Table')->output($summary);
    }

    /**
     * Displays validation exception errors into table format.
     *
     * @param array $errors Errors to display.
     * @param \Cake\Console\ConsoleIo $io IO object.
     * @return void
     */
    private function displayErrors(array $errors, ConsoleIo $io): void
    {
        $errorSummary = [];

        // header
        $errorSummary[] = [__('Entity'), __('Error message')];
        foreach ($errors as $error) {
            $errorSummary[] = [$error['entity'], $error['error_message']];
        }

        $io->helper('Table')->output($errorSummary);
    }
}
