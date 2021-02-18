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
namespace Passbolt\DirectorySync\Shell\Task;

use Passbolt\DirectorySync\Actions\AllSyncAction;

class AllTask extends SyncTask
{
    /**
     * Gets the option parser instance and configures it.
     *
     * By overriding this method you can configure the ConsoleOptionParser before returning it.
     *
     * @throws \Exception
     * @return \Cake\Console\ConsoleOptionParser
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->setDescription(__('Directory Sync'))
            ->addOption('dry-run', [
                'help' => 'Don\'t save the changes',
                'default' => 'true',
                'boolean' => true,
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
        $reports = [];

        try {
            $this->model = 'Users';
            $dryRun = $this->param('dry-run');
            $allSyncAction = new AllSyncAction();
            $reports = $allSyncAction->execute($dryRun);
        } catch (\Exception $exception) {
            $this->abort($exception->getMessage());

            return false;
        }

        $this->model = 'Users';
        $this->_displayReports($reports['users']);

        $this->model = 'Groups';
        $this->_displayReports($reports['groups']);

        return true;
    }
}
