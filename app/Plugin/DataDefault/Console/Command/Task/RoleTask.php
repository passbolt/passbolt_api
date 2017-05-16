<?php
/**
 * Insert Role Task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataDefault.Console.Command.Task.RoleTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Role', 'Model');

class RoleTask extends ModelTask {

	public $model = 'Role';

	protected function getData() {
		$rs[] = array('Role' => array(
			'id' => Common::uuid('role.id.anonymous'),
			'name' => 'guest',
			'description' => 'Non logged in user',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25'
		));
		$rs[] = array('Role' => array(
			'id' => Common::uuid('role.id.user'),
			'name' => 'user',
			'description' => 'Logged in user',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
		));
		$rs[] = array('Role' => array(
			'id' => Common::uuid('role.id.admin'),
			'name' => 'admin',
			'description' => 'Organization administrator',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
		));
		$rs[] = array('Role' => array(
			'id' => Common::uuid('role.id.root'),
			'name' => 'root',
			'description' => 'Super Administrator',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
		));
		return $rs;
	}
}
