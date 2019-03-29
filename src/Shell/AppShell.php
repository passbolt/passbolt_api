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
namespace App\Shell;

use App\Shell\AppShellBootstrap;
use Cake\Console\Shell;

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 */
class AppShell extends Shell
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
        AppShellBootstrap::init();
    }

    /**
     * Display a banner
     *
     * @return void
     */
    protected function _welcome()
    {
        $this->out();
    }

    /**
     * Some of the passbolt commands shouldn't be executed as root.
     * By instance it's the case of the healthcheck command that needs to be executed with the same user as your web server.
     *
     * @return bool true if user is root
     */
    public function assertNotRoot()
    {
        if (PROCESS_USER === 'root') {
            $this->out('');
            $this->_error('Passbolt commands cannot be executed as root.', false);
            $this->out('');
            $this->out('The command should be executed with the same user as your web server. By instance:');
            $this->out('su -s /bin/bash -c "' . APP . 'Console/cake COMMAND" HTTP_USER');
            $this->out('where HTTP_USER match your web server user: www-data, nginx, http');
            $this->out('');

            return false;
        }

        return true;
    }

    /**
     * Display an error message
     *
     * @param string $msg message
     * @return void
     */
    protected function _error($msg)
    {
        $this->out('<error>' . $msg . '</error>');
    }

    /**
     * Display a success message
     *
     * @param string $msg message
     * @return void
     */
    protected function _success($msg)
    {
        $this->out('<success>' . $msg . '</success>');
    }

    /**
     * Add any global additional options for about to be dispatched tasks
     *
     * @param string $cmd command
     * @return string
     */
    protected function _formatCmd($cmd)
    {
        $quiet = $this->param('quiet');
        if (isset($quiet) && $quiet == 1) {
            $cmd .= ' -q';
        }

        return $cmd;
    }
}
