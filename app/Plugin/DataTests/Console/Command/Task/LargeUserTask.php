<?php
/**
 * Insert User Task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataExtras.Console.Command.Task.UserTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('User', 'Model');

class LargeUserTask extends ModelTask {

	public $model = 'User';

	public static $count = 50;

	protected function getData() {
		// admin
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.admin'),
			'username' => 'admin@passbolt.com',
			'role_id' => Common::uuid('role.id.admin'),
			'active' => 1,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		// anonymous user
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.anonymous'),
			'username' => 'anonymous@passbolt.com',
			'role_id' => Common::uuid('role.id.anonymous'),
			'active' => 1,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		// batch of dummy users
		for ($i = 0; $i < self::$count; $i++) {
			$us[] = array('User' => array(
				'id' => Common::uuid('user.id.user_' . $i),
				'username' => 'user_' . $i . '@passbolt.com',
				'role_id' => Common::uuid('role.id.user'),
				'active' => 1,
				'created_by' => Common::uuid('user.id.admin'),
				'modified_by' => Common::uuid('user.id.admin')
			));
		}

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
