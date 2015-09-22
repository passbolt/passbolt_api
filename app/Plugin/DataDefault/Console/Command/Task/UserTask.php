<?php
/**
 * Insert User Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataDefault.Console.Command.Task.UserTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('User', 'Model');

class UserTask extends ModelTask {

	public $model = 'User';

	public static function getAlias() {
		$User = ClassRegistry::init('User');
		$aliases = array (
			// anonymous user
			'ano' => $User->findByUsername('anonymous@passbolt.com'),

			// admin
			'adm' => $User->findByUsername('admin@passbolt.com'),

		);
		foreach ($aliases as $name=>$obj){
			$aliases[$name] = $obj['User']['id'];
		}
		return $aliases;
	}
	
	protected function getData() {
		// anonymous user
		$us[] = array('User' => array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'anonymous@passbolt.com',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'password' => 'password',
			'active' => 1
		));

		// one user per default roles
		$us[] = array('User' => array(
			'id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'username' => 'admin@passbolt.com',
			'role_id' => '142c1188-c5cd-11e1-a0c5-080027796c4c',
			'password' => 'password',
			'active' => 1
		));

		return $us;
	}
}
