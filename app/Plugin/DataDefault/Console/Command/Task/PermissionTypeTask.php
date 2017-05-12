<?php
/**
 * Insert Permission Type Task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataDefault.Console.Command.Task.PermissionTypeTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Permission', 'Model');
App::uses('PermissionType', 'Model');

class PermissionTypeTask extends ModelTask {

	public $model = 'PermissionType';

	protected function getData() {
		$pts = array();
		$pts[] = array(
            'id' => '5204e74b-00d0-4d4b-b335-75198cebc04d',
			'serial' => '1',
			'name' => '---r',
			'description' => 'read only',
			'active' => '1'
		);
		$pts[] = array(
            'id' => '5204e74b-055c-4473-8f1b-75198cebc04d',
			'serial' => '7',
			'name' => '-ucr',
			'description' => 'create, update and read',
			'active' => '1'
		);
		$pts[] = array(
            'id' => '5204e74b-9734-4548-ac57-75198cebc04d',
			'serial' => '15',
			'name' => 'aucr',
			'description' => 'all rights',
			'active' => '1'
		);

		return $pts;
	}
}
