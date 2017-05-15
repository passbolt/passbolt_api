<?php
/**
 * Insert Avatar Task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataUnitTests.Console.Command.Task.AvatarTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Profile', 'Model');
App::uses('User', ' Model');
App::uses('Avatar', 'Model');

class AvatarTask extends ModelTask {

	public $model = 'Avatar';

/**
 * Execute
 */
	public function execute() {
		$User = $this->_getModel('User');
		$this->beforeInsert($User);

		// Retrieve the users.
		$data = array();
		$o = $User->getFindOptions('User::index', 'admin', $data);
		$users = $User->find('all', $o);

		// For all users, if an image has been defined insert it as profile avatar.
		$i = 0;
		foreach ($users as $user) {

			// Check if an image exists for him.
			$path = dirname(__FILE__) . DS . 'img' . DS . 'avatar' . DS;
			$matches = array();
			preg_match('/^(.*)@(.*)$/', $user['User']['username'], $matches);
			$fileName = $matches[1] . '.png';

			if (file_exists($path . $fileName)) {
				$data = array(
					'Avatar' => array(
						'file' => array (
							'tmp_name' => $path . $fileName
						)
					)
				);
				if(!$User->Profile->Avatar->upload($user['Profile']['id'], $data)){
					$this->out('Avatar ' . $path . $fileName . ' has not been uploaded');
				}
				$i++;
			}
		}
		$this->out('Data for model ' . $this->model . ' inserted (' . $i . ')');
	}
}
