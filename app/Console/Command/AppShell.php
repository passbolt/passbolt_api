<?php
/**
 * AppShell file
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Shell', 'Console');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
class AppShell extends Shell {

/**
 * Display the passbolt ascii banner
 *
 * @return void
 */
    protected function _welcome() {
        $this->hr();
        $this->out('     ____                  __          ____  ');
        $this->out('    / __ \____  _____ ____/ /_  ____  / / /_ ');
        $this->out('   / /_/ / __ `/ ___/ ___/ __ \/ __ \/ / __/ ');
        $this->out('  / ____/ /_/ (__  |__  ) /_/ / /_/ / / /    ');
        $this->out(' /_/    \__,_/____/____/_.___/\____/_/\__/   ');
        $this->out('');
        $this->out(' Open source password manager for teams');
        $this->hr();
    }

/**
 * Execute function
 *
 * @return void
 */
	public function execute() {
	}
}
