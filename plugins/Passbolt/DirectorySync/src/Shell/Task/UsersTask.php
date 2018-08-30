<?php
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

use App\Error\Exception\ValidationException;
use App\Shell\AppShell;
use Cake\Routing\Router;
use Passbolt\DirectorySync\Actions\UserSyncAction;
use Passbolt\DirectorySync\Utility\SyncAction;

class UsersTask extends SyncTask
{
    /**
     * Initializes the Shell
     * acts as constructor for subclasses
     * allows configuration of tasks prior to shell execution
     *
     * @return void
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#Cake\Console\ConsoleOptionParser::initialize
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Users');
        $this->loadModel('Roles');
        $this->loadModel('AuthenticationTokens');
    }

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

        $parser->setDescription(__('Sync users'))
            ->addOption('dry-run', [
                'help' => 'Don\'t save the changes',
                'default' => 'false',
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
        try {
            $this->model = 'Users';
            $dryRun = $this->param('dry-run');
            $action = new UserSyncAction();
            $action->setDryRun($dryRun);
            $reports = $action->execute();
            $this->_displayReports($reports);
        } catch (\Exception $exception) {
            $this->abort($exception->getMessage());

            return false;
        }

        return true;
    }
}
