<?php
/**
 * Insert Large Comment Task
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Profile', 'Model');
App::uses('ProfileTask', 'DataTests.Console/Command/Task');
App::uses('LargeUserTask', 'DataTests.Console/Command/Task');

class LargeProfileTask extends ProfileTask {

	protected function getData() {
		$usersCount = LargeUserTask::$count;

		// Admin
		$c[] = array('Profile' => array(
			'id' => Common::uuid('profile.id.admin'),
			'user_id' => Common::uuid('user.id.admin'),
			'gender' => 'm',
			'date_of_birth' => '1980-12-13',
			'title' => 'Mr',
			'first_name' => 'Admin',
			'last_name' => 'User'
		));
		// Anonymous user / default for non logged-in user
		$c[] = array('Profile' => array(
			'id' => Common::uuid('profile.id.anonymous'),
			'user_id' => Common::uuid('user.id.anonymous'),
			'gender' => 'm',
			'date_of_birth' => '1980-12-10',
			'title' => 'Mr',
			'first_name' => 'Anonymous',
			'last_name' => 'User'
		));

		// batch of dummy users
		for ($i = 0; $i < $usersCount; $i++) {
			$userId = Common::uuid('user.id.user_' . $i);
			$profileId = Common::uuid('profile.id.profile_' . $i);

			$c[] = array('Profile' => array(
				'id' => $profileId,
				'user_id' => $userId,
				'gender' => 'f',
				'date_of_birth' => '1970-01-01',
				'title' => 'Ms',
				'first_name' => 'First Name',
				'last_name' => 'Last Name'
			));
		}

		return $c;
	}
}
