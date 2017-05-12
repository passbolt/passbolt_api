<?php
/**
 * Gpgkey Task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

require_once(APP . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('User', 'Model');
App::uses('Gpg', 'Model/Utility');

class GpgkeyTask extends ModelTask {

	public $model = 'Gpgkey';

/**
 * Get path of the key for the given user.
 *
 * @param $userId
 * @return string
 */
	public function getGpgkeyPath($userId) {
		$User = $this->_getModel('User');
		$u = $User->findById($userId);
		$prefix = $u['User']['username'];
		$uprefix = explode('@', $prefix);
		$gpgkeyPath = Configure::read('GPG.testKeys.path');
		if (file_exists($gpgkeyPath . $uprefix[0] . '_public.key')) {
			$keyFileName = $gpgkeyPath . $uprefix[0] . '_public.key';
		} else {
			$keyFileName = $gpgkeyPath . 'passbolt_dummy_key.asc';
			$this->out('Warning: could not find key ' . $uprefix[0] . '_public.key' . ' for ' . $u['User']['username']);
			$this->out('         at path ' . $gpgkeyPath);
			$this->out('         using ' . $keyFileName . ' instead.');
		}
		return $keyFileName;
	}

/**
 * Get the public key of a user.
 *
 * @param $userId
 * @return string
 */
	public function getUserKey($userId) {
		$key = file_get_contents($this->getGpgkeyPath($userId));
		return $key;
	}

/**
 * Get the Gpgkey data
 *
 * @return array
 * @throws Exception
 */
	public function getData() {
		$User = $this->_getModel('User');
		$us = $User->find('all', ['conditions' => ['role_id <>' => Common::uuid('role.id.anonymous')]]);
		$Gpg = new \Passbolt\Gpg();
		$k = array();

		foreach($us as $u) {
			$keyRaw = $this->getUserKey($u['User']['id']);
			$info = $Gpg->getKeyInfo($keyRaw);
			$k[] = array(
				'Gpgkey'=> array(
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
		}
		return $k;
	}
}