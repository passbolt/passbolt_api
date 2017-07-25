<?php
/**
 * Insert User Task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataDefault.Console.Command.Task.UserTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('User', 'Model');

class UserTask extends ModelTask {

	public $model = 'User';

	public static function getAlias() {

		$aliases = array (
			'adm' => Common::uuid('user.id.admin'),
			'ano' => Common::uuid('user.id.anonymous'),
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
			'active' => 1,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));

		return $us;
	}

	protected function getValidationFields($item) {
		return [
			'id',
			'username',
			'active',
			'created_by'
		];
	}
}
