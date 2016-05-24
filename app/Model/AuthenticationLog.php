<?php
/**
 * AuthenticationLog  model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('User', 'Model');

class AuthenticationLog extends AppModel {

	public $belongsTo = [
		'User'
	];

/**
 * create a log of an authentication attempt
 *
 * @param string $username the username passed in the request. We want to store it to know what was tried
 * @param string $ip the provenance ip
 * @param bool $status the status of the authentication.
 * @param string $data optional data that we'd like to store
 * @return bool true if the log was successful, false otherwise
 */
	public function add($username, $ip, $status, $data = null) {
		$u = $this->User->findByUsername($username);
		$log = [
			'user_id' => $u ? $u['User']['id'] : null,
			'username' => $username,
			'ip' => $ip,
			'status' => $status,
			'data' => $data
		];
		$this->set($log);
		if (!$this->validates()) {
			return false;
		}
		$this->create();
		if (!$this->save($log)) {
			return false;
		}

		return true;
	}

/**
 * calculates the number of failed authentication attempts for a particular username, ip, or both together
 *
 * @param string $username the username. Keep it null if not needed
 * @param string $ip optional
 * @param datetime $sinceTimestamp how far we check for failed attempts. null means forever.
 * @return int the count of failed attempts, false if something went wrong.
 */
	public function getFailedAuthenticationCount($username = null, $ip = null, $sinceTimestamp = null) {
		$conditions = [];
		if ($username == null && $ip == null) {
			return false;
		}

		$conditions[] = ['status' => false];
		if ($username) {
			$conditions[] = ["username" => $username];
		}

		if ($ip) {
			$conditions[] = ["ip" => $ip];
		}

		if ($sinceTimestamp) {
			$conditions[] = ["created >=" => date('Y-m-d H:i:s', $sinceTimestamp)];
		}

		return $this->find('count', [
			'conditions' => $conditions,
			'order' => ['created' => 'DESC']
		]);
	}

/**
 * Get the last failed authentication log, for an individual  username, ip, or both combined
 *
 * @param string $username email address
 * @param string $ip address
 * @return array the object AuthenticationLog, null if not found, false if something is wrong with the parameters
 */
	public function getLastFailedAuthenticationLog($username = null, $ip = null) {
		$conditions = [];
		if ($username == null && $ip == null) {
			return false;
		}

		$conditions[] = ['status' => false];
		if ($username) {
			$conditions[] = ["username" => $username];
		}

		if ($ip) {
			$conditions[] = ["ip" => $ip];
		}

		$attempt = $this->find('first', [
			'conditions' => $conditions,
			'order' => ['created' => 'DESC']
		]);

		return !empty($attempt) ? $attempt : null;
	}
}
