<?php
/**
 * Insert Group Task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataTests.Console.Command.Task.GroupTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Group', 'Model');

class LargeGroupTask extends ModelTask {

	public $model = "Group";

	public static function getGroupsLevel() {
		return [
			'multiverse', // 1 group
			'universe', // 2 groups
			'galaxy', // 3 groups
			'system', // ...
			'planet',
			'continent',
//			'country',
//			'state',
//			'city',
//			'street',
//			'building',
//			'room',
//			'desk'
		];
	}

	/**
	 * Execute a callback for each group level
	 * @param $callback
	 */
	public static function foreachGroupLevel($callback) {
		$expansionRate = 2;
		$groupsLevel = self::getGroupsLevel();

		for ($level=0; $level<count($groupsLevel); $level++) {
			$levelItems = isset($levelItems) ? $levelItems * $expansionRate: 1;
			call_user_func_array($callback, [$groupsLevel[$level], $level, $levelItems]);
		}
	}

	/**
	 * Execute a callback for each group
	 * @param $callback
	 */
	public static function foreachGroup($callback) {
		self::foreachGroupLevel(function($groupLevel, $level, $levelItems) use (&$callback){
			for ($i=0; $i<$levelItems; $i++) {
				$groupId = Common::uuid('group.id.' . $groupLevel . '_' . $i);
				call_user_func_array($callback, [$groupId, $groupLevel, $i, $level]);
			}
		});
	}

	/**
	 * Get the dummy data
	 * @return mixed
	 */
	protected function getData() {
		// Root group, all passwords should be shared with
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.root'),
			'name' => 'Root',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));

		// Groups dummies.
		self::foreachGroup(function($groupId, $groupLevel, $i, $level) use (&$g){
			$g[] = array('Group' => array(
				'id' => $groupId,
				'name' => ucfirst($groupLevel . '_' . $i),
				'created_by' => Common::uuid('user.id.admin'),
				'modified_by' => Common::uuid('user.id.admin')
			));
		});

		return $g;
	}
}
