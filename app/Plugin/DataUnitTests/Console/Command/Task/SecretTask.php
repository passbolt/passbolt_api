<?php
/**
 * Insert Secret Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataExtras.Console.Command.Task.SecretTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Secret', 'Model');
App::uses('Resource', 'Model');
App::uses('User', 'Model');
App::uses('Gpgkey', 'Model');

class SecretTask extends ModelTask {

	public $model = 'Secret';

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
				$this->out('<error>Unable to insert ' . $item[$this->model]['data'] . '</error>');
			}
		}
	}

	/**
	 * Get Dummy Secret Data.
	 *
	 * The passwords are always returned in the same order, useful for cross checking.
	 *
	 * This secret was encrypted with the dummy public key, also located in the repository.
	 *
	 * @return string
	 */
	protected function getDummyPassword() {
		static $i = 0;
		$passwords = array(
			"testpassword",
			"123456",
			"qwerty",
			"111111",
			"iloveyou",
			"adbobe123",
			"admin",
			"letmein",
			"monkey",
			"adobe",
			"sunshine",
			"princess",
			"azerty",
			"trustno1",
			"iamgod",
			"love",
			"god",
			"business",
			"passbolt",
			"enova",
			"kevisthebest",
		);
		$password = $passwords[$i];
		$i++;
		if ($i > sizeof($passwords) - 1) {
			$i = 0;
		}
		return $passwords[$i];
	}

	/**
	 * Encrypt a password with the user public key.
	 * @param $password
	 * @param $userId
	 * @return string $encrypted encrypted password
	 */
	protected function encryptPassword($password, $userId) {
		$GpgkeyTask = $this->Tasks->load('Data.Gpgkey');
		$gpgkeyPath = $GpgkeyTask->getGpgkeyPath($userId);
		$Gpgkey = Common::getModel('Gpgkey');
		$key = $Gpgkey->find("first", array('conditions' => array(
				'Gpgkey.user_id' => $userId,
				'Gpgkey.deleted' => 0
			)));

		$res = gnupg_init();
		gnupg_seterrormode($res,GNUPG_ERROR_WARNING);
		gnupg_import($res, $key['Gpgkey']['key']);
		gnupg_addencryptkey($res , $key['Gpgkey']['fingerprint']);

		$encrypted = gnupg_encrypt ($res , $password);

		return $encrypted;
	}

	/**
	 * Get all Secret data.
	 * @return array
	 */
	protected function getData() {
		$Resource = Common::getModel('Resource');
		$User = Common::getModel('User');
		$rs = $Resource->find('all');
		$us = $User->find('all');

		// Insertion for all users who can access to available resources.
		// We insert dummy data, same secret for everyone.
		$s = [];
		foreach($rs as $r) {
			$password = $this->getDummyPassword();
			foreach ($us as $u) {
				$isAuthorized = $Resource->isAuthorized($r['Resource']['id'], PermissionType::READ, $u['User']['id']);
				if ($isAuthorized) {
					$passwordEncrypted = $this->encryptPassword($password, $u['User']['id']);
					$s[] = array('Secret'=>array(
						'id' => Common::uuid(),
						'user_id' => $u['User']['id'],
						'resource_id' => $r['Resource']['id'],
						'data' => $passwordEncrypted,
						'created' => '2012-12-24 03:34:40',
						'modified' => '2012-12-24 03:34:40',
						'created_by' => $u['User']['id'],
						'modified_by' => $u['User']['id']
					));
				}
			}
		}
		return $s;
	}
}