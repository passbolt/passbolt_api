<?php
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
namespace App\Shell\Task;

use App\Shell\AppShell;
use App\Utility\Healthchecks;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;

class MigrateTask extends AppShell
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
            ->setDescription(__('Migration shell for the passbolt application.'))
            ->addOption('backup', [
                'help' => 'Make a database backup to be used in case something goes wrong.',
                'boolean' => true,
                'default' => false
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
        // Root user is not allowed to execute this command.
        // This command needs to be executed with the same user as the webserver.
        if (!$this->assertNotRoot()) {
            return false;
        }

        // Backup
        if (!$this->_backup()) {
            return false;
        } else {
        }

        // Migration task
        $this->out(' ' . __('Running migration scripts.'));
        $this->hr();
        $cmd = $this->_formatCmd('migrations migrate --no-lock');
        $result = ($this->dispatchShell($cmd) === self::CODE_SUCCESS);

        // Clean cache
        $cmd = $this->_formatCmd('cache clear_all');
        $this->dispatchShell($cmd);

        return $result;
    }

    /**
     * Prepare a backup for next quick install
     *
     * @return bool true if shell exited with success code
     */
    protected function _backup()
    {
        if ($this->param('backup')) {
            $cmd = $this->_formatCmd('passbolt mysql_export');
            $success = ($this->dispatchShell($cmd) === self::CODE_SUCCESS);
            if ($success) {
                $this->hr();
            }

            return $success;
        }

        return true;
    }
}
