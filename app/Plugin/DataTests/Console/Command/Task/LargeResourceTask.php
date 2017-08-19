<?php
/**
 * Insert Resource Task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataTests.Console.Command.Task.ResourceTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('ResourceTask', 'DataTests.Console/Command/Task');
App::uses('User', 'Model');

class LargeResourceTask extends ResourceTask {

	public static $count = 50;

/**
 * Get data
 *
 * @return array
 */
	protected function getData() {
		$r = [];

		// Add to the data a large batch of resources. Batch used for the performance tests.
		for($i=0; $i<self::$count; $i++) {
			$r[] = array('Resource' => array(
				'id' => Common::uuid('resource.id.password_' . $i),
				'name' => 'Password ' . $i,
				'username' => '',
				'expiry_date' => null,
				'uri'  => '',
				'description' => '',
				'deleted' => 0,
				'created_by' => Common::uuid('user.id.admin'),
				'modified_by' => Common::uuid('user.id.admin'),
				'created' => date('Y-m-d H:i:s', strtotime('-100 days')),
				'modified' => date('Y-m-d H:i:s', strtotime('-99 days')),
			));
		}

		return $r;
	}
}
