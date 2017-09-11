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
		$this->out();
    }

/**
 * Some of the passbolt commands shouldn't be executed as root.
 * By instance it's the case of the Healtcheck command that needs to be executed with the same user as your web server.
 */
	public function rootNotAllowed() {
		if (PROCESS_USER == 'root') {
			$this->out('<error>Passbolt commands cannot be executed as root.</error>');
			$this->out('');
			$this->out('The command should be executed with the same user as your web server.');
			$this->out('By instance : su -s /bin/bash -c "' . APP . 'Console/cake COMMAND" HTTP_USER');
			$this->out('HTTP_USER can differ regarding your environment : www-data, nginx, http');
			exit(1);
		}
	}

}
