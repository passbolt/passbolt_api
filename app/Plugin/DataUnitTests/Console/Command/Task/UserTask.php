<?php
/**
 * Insert User Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataExtras.Console.Command.Task.UserTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('User', 'Model');

class UserTask extends ModelTask {

	public $model = 'User';

	public static function getAlias() {
		$aliases = array (
			// anonymous user
			'ano' => Common::uuid('user.id.anonymous'),

			// one user per default roles
			'gue' => Common::uuid('user.id.guest'),
			'usr' => Common::uuid('user.id.user'),
			'adm' => Common::uuid('user.id.admin'),
			'roo' => Common::uuid('user.id.root'),

			// famous scientists
			'ada' => Common::uuid('user.id.ada'),
            'bet' => Common::uuid('user.id.betty'),
            'car' => Common::uuid('user.id.carol'),
            'dam' => Common::uuid('user.id.dame'),
            'edi' => Common::uuid('user.id.edith'),
            'fra' => Common::uuid('user.id.frances'),
            'gra' => Common::uuid('user.id.grace'),
            'hed' => Common::uuid('user.id.hedy'),
            'ire' => Common::uuid('user.id.irene'),
            'jea' => Common::uuid('user.id.jean'),
            'kay' => Common::uuid('user.id.kathleen'),
            'lyn' => Common::uuid('user.id.lynne'),
            'mar' => Common::uuid('user.id.marlyn'),

		);
		return $aliases;
	}
	
	protected function getData() {
		// anonymous user
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.anonymous'),
			'username' => 'anonymous@passbolt.com',
			'role_id' => Common::uuid('role.id.anonymous'),
			'password' => 'password',
			'active' => 1
		));

		// one user per default roles
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.guest'),
			'username' => 'guest@passbolt.com',
			'role_id' => Common::uuid('role.id.anonymous'),
			'password' => 'password',
			'active' => 1
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.user'),
			'username' => 'user@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'password' => 'password',
			'active' => 1
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.admin'),
			'username' => 'admin@passbolt.com',
			'role_id' => Common::uuid('role.id.admin'),
			'password' => 'password',
			'active' => 1
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.root'),
			'username' => 'root@passbolt.com',
			'role_id' => Common::uuid('role.id.root'),
			'password' => 'password',
			'active' => 1
		));

		// famous scientists
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.ada'),
			'username' => 'ada@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'password' => 'password',
			'active' => 1
		));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.betty'),
            'username' => 'betty@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.carol'),
            'username' => 'carol@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.dame'),
            'username' => 'dame@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.edith'),
            'username' => 'edith@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.frances'),
            'username' => 'frances@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.grace'),
            'username' => 'grace@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.hedy'),
            'username' => 'hedy@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.irene'),
            'username' => 'irene@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.jean'),
            'username' => 'jean@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.kathleen'),
            'username' => 'kathleen@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.lynne'),
            'username' => 'lynne@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.marlyn'),
            'username' => 'marlyn@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
		return $us;
	}
}
