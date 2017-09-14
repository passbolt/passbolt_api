<?php
/**
 * Insert Large GroupUser Task
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('User', 'Model');
App::uses('Group', 'Model');
App::uses('GroupUser', 'Model');
App::uses('LargeGroupTask', 'DataTests.Console/Command/Task');
App::uses('LargeUserTask', 'DataTests.Console/Command/Task');

class LargeGroupUserTask extends ModelTask {

	public $model = 'GroupUser';

	protected function getData() {
		$usersCount = LargeUserTask::$count;

		LargeGroupTask::foreachGroupLevel(function($groupLevel, $level, $levelItems) use (&$gu, &$usersCount){
			$batchOfUsers = ceil($usersCount / $levelItems);

			// Admin is group manager of the Root group
			$gu[] = array('GroupUser' => array(
				'id' => Common::uuid('group_user.id.root-admin'),
				'group_id' => Common::uuid('group.id.root'),
				'user_id' => Common::uuid('user.id.admin'),
				'is_admin' => 1,
				'created_by' => Common::uuid('user.id.admin'),
				'modified_by' => Common::uuid('user.id.admin')
			));

			// Add Groups Users dummies.
			// Group manager of all groups.
			// Users are split in the groups.
			for ($i=0; $i<$levelItems; $i++) {
				$groupAlias = $groupLevel . '_' . $i;
				$groupId = Common::uuid('group.id.' . $groupAlias);

				$gu[] = array('GroupUser' => array(
					'id' => Common::uuid('group_user.id.' . $groupAlias . '-admin'),
					'group_id' => $groupId,
					'user_id' => Common::uuid('user.id.admin'),
					'is_admin' => 1,
					'created_by' => Common::uuid('user.id.admin'),
					'modified_by' => Common::uuid('user.id.admin')
				));

				$startingUser = ($batchOfUsers * $i);
				$endingUser = ($batchOfUsers * $i) + $batchOfUsers;
				$endingUser = $endingUser < $usersCount ? $endingUser : $usersCount;
				for ($j=$startingUser; $j<$endingUser; $j++) {
					$userAlias = 'user_' . $j;
					$userId = Common::uuid('user.id.' . $userAlias);

					$gu[] = array('GroupUser' => array(
						'id' => Common::uuid('group_user.id.' . $groupAlias . '-' . $userAlias),
						'group_id' => $groupId,
						'user_id' => $userId,
						'is_admin' => 0,
						'created_by' => Common::uuid('user.id.admin'),
						'modified_by' => Common::uuid('user.id.admin')
					));
				}
			}
		});

		return $gu;
	}
}
