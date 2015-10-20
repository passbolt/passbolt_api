<?php
/**
 * Insert Comment Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
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
			'date_of_birth' => '1980-12-10',
			'title' => 'Mr',
			'first_name' => 'Anonymous',
			'last_name' => 'User'
		));

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

		return $c;
	}
}
