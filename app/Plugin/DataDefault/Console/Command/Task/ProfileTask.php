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
		$UserTask = $this->Tasks->load('DataDefault.User');
		$users = $UserTask::getAlias();

		// Anonymous user / default for non logged-in user
		$c[] = array('Profile' => array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'user_id' => $users['ano'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-10',
			'title' => 'Mr',
			'first_name' => 'Anonymous',
			'last_name' => 'User'
		));

        // Admin
        $c[] = array('Profile' => array(
            'id' => '528c2dab-ccco-416d-802b-71668cebc04d',
            'user_id' => $users['adm'],
            'gender' => 'm',
            'date_of_birth' => '1980-12-13',
            'title' => 'Mr',
            'first_name' => 'Admin',
            'last_name' => 'User'
        ));

		return $c;
	}
}
