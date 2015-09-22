<?php
/**
 * Gpgkey Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataDefault.Console.Command.Task.GpgkeyTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('User', 'Model');

class GpgkeyTask extends ModelTask {

	public $model = 'Gpgkey';

	public function execute() {
		$Model = ClassRegistry::init($this->model);
		$data = $this->getData();
		foreach ($data as $item) {
			$Model->create();
			$Model->set($item);
			if (!$Model->validates()) {
				var_dump($Model->validationErrors);
			}
			$instance = $Model->save();
			if (!$instance) {
				$this->out('<error>Unable to insert ' . $item[$this->model]['name'] . '</error>');
			}
		}
	}

	/**
	 * Get path of the key for the given user.
	 *
	 * @param $userId
	 *
	 * @return string
	 */
	public function getGpgkeyPath($userId) {
		$User = Common::getModel('User');
		$u = $User->findById($userId);
		$prefix = $u['User']['username'];
		$uprefix = explode('@', $prefix);
		$gpgkeyPath = APP . 'Config' . DS . 'gpg' . DS;
		if (file_exists($gpgkeyPath . $uprefix[0] . '_public.key')) {
			$keyFileName = $gpgkeyPath . $uprefix[0] . '_public.key';
		} else {
			$keyFileName = $gpgkeyPath . 'passbolt_dummy_key.asc';
			$msg = 'could not find key' . $gpgkeyPath . $uprefix[0] . '_public.key' . ' for ' . $u['User']['username'] . ' using dummy one.';
			$this->out($msg);
		}
		return $keyFileName;
	}

	/**
	 * Get the public key of a user.
	 *
	 * @param $userId
	 *
	 * @return string
	 */
	public function getUserKey($userId) {
		$key = file_get_contents($this->getGpgkeyPath($userId));
		return $key;
	}

	public function getData() {
		$User = Common::getModel('User');
		$us = $User->find('all');
		$Model = ClassRegistry::init($this->model);

		$k = array();

		foreach($us as $u) {
			$keyRaw = $this->getUserKey($u['User']['id']);
			$info = $Model->info($keyRaw);
			$key = array(
				'Gpgkey'=>array(
					'id' => Common::uuid(),
					'user_id' => $u['User']['id'],
					'key' => $keyRaw,
					'bits' => $info['bits'],
					'uid' => $info['uid'],
					'key_id' => $info['key_id'],
					'fingerprint' => $info['fingerprint'],
					'type' => $info['type'],
					'expires' => !empty($info['expires']) ? date('Y-m-d H:i:s', $info['expires']) : '',
					'key_created' => date('Y-m-d H:i:s', $info['key_created']),
					'created' => date('Y-m-d H:i:s'),
					'modified' => date('Y-m-d H:i:s'),
					'created_by' => $u['User']['id'],
					'modified_by' => $u['User']['id'],
				)
			);
			$k[] = $key;
		}
		return $k;
	}
}