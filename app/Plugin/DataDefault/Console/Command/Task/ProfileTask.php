<?php
/**
 * Insert Comment Task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataUnitTests.Console.Command.Task.ProfileTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Profile', 'Model');

class ProfileTask extends ModelTask {

	public $model = 'Profile';

	protected function getData() {
		// Anonymous user / default for non logged-in user
		$c[] = array('Profile' => array(
			'id' => Common::uuid('profile.id.anonymous'),
			'user_id' => Common::uuid('user.id.anonymous'),
			'gender' => 'm',
			'date_of_birth' => '1970-01-01',
			'title' => 'Mr',
			'first_name' => 'Anonymous',
			'last_name' => 'User'
		));

		return $c;
	}
}
