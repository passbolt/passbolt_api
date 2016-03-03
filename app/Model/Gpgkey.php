<?php
/**
 * Gpg Key Model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

if (!class_exists('\Passbolt\Gpg')) {
	App::import('Model/Utility', 'Gpg');
}

class Gpgkey extends AppModel {

	public $name = 'Gpgkey';

	public $useTable = 'gpgkeys';

	public $belongsTo = [
		'User',
	];

/**
 * Get the validation rules upon context
 *
 * @param string case (optional) The target validation case if any.
 * @return array CakePHP validation rules
 */
	public static function getValidationRules($case = 'default') {
		$default = [
			'id' => [
				'uuid' => [
					'rule' => 'uuid',
					'required' => 'update',
					'message' => __('Uuid must be provided and in correct format'),
				]
			],
			'user_id' => [
				'uuid' => [
					'rule' => 'uuid',
					'required' => 'create',
					'allowEmpty' => false,
					'message' => __('Id must be in correct format'),
				],
				'exist' => [
					'rule' => ['userExists', null],
					'message' => __('The user id provided does not exist')
				],
			],
			'key' => [
				'importable' => [
					'rule' => ['checkKeyIsImportable', null],
					'required' => true,
					'allowEmpty' => false,
					'message' => __('The key provided is not in the right format, and couldn\'t be imported'),
				],
			],
			'bits' => [
				'rule' => 'numeric',
				'required' => false,
				'allowEmpty' => true,
				'message' => __('The number of bits should be specified in a numeric format'),
			],
			'uid' => [
				'format' => [
					'rule' => ['checkUid', null],
					'required' => false,
					'message' => __('The uid uses incorrect characters'),
				],
			],
			'key_id' => [
				'format' => [
					'rule' => '/^[A-Z0-9]{8}$/',
					'required' => false,
					'message' => __('The key id has an incorrect format'),
				],
			],
			'fingerprint' => [
				'format' => [
					'rule' => '/^[A-Z0-9]{40}$/',
					'required' => 'create',
					'message' => __('The fingerprint has an incorrect format'),
					'allowEmpty' => false,
				],
			],
			'type' => [
				'format' => [
					'rule' => ['checkTypeExist', null],
					'message' => __('The type uses an incorrect format'),
					'allowEmpty' => true,
				],
			],
			'expires' => [
				'is_date' => [
					'rule' => '/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/i',
					'message' => __('The expiring date has an incorrect format'),
					'allowEmpty' => true,
				],
				'is_in_future' => [
					'rule' => ['checkExpireIsInFuture', null],
					'message' => __('The key should expire in future.'),
				],
			],
			'key_created' => [
				'is_date' => [
					'rule' => '/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/i',
					'message' => __('The key creation date has an incorrect format'),
					'allowEmpty' => false,
				],
				'is_in_past' => [
					'rule' => ['checkCreatedIsInPast', null],
					'message' => __('The key should have been created in the past.'),
				],
			],

		];
		switch ($case) {
			default:
			case 'default':
				$rules = $default;
				break;
		}

		return $rules;
	}

/**
 * Check if a key can be imported
 *
 * @param $check
 * @return bool
 */
	public function checkKeyIsImportable($check) {
		if ($check['key'] == null) {
			return false;
		}
		$gpg = new Passbolt\Gpg();
		try {
			$info = $gpg->getKeyInfo($check['key']);
		} catch (Exception $e) {
			return false;
		}

		return is_array($info);
	}

/**
 * Check if a key can be imported
 *
 * @param $check
 * @return bool
 */
	public function checkExpireIsInFuture($check) {
		if ($check['expires'] == null) {
			return false;
		} else {
			$expire = strtotime($check['expires']);
			$now = time();
			$inFuture = $now < $expire;

			return $inFuture;
		}
	}

/**
 * Check if a key can be imported
 *
 * @param $check
 * @return bool
 */
	public function checkCreatedIsInPast($check) {
		if ($check['key_created'] == null) {
			return false;
		} else {
			$created = strtotime($check['key_created']);
			$now = gmdate('U');
			$inPast = $now > $created;

			return $inPast;
		}
	}

/**
 * Check uid (according to gpg rfc).
 *
 * @param $check
 * @return bool
 */
	public function checkUid($check) {
		if ($check['uid'] == null) {
			return false;
		} else {
			$valid = false;
			try {
				$userIdPacket = new OpenPGP_UserIDPacket($check['uid']);
			} catch (Exception $e) {
				return false;
			}
			$valid = ($check['uid'] == sprintf('%s', $userIdPacket));

			return $valid;
		}
	}

/**
 * Check type exists (according to gpg rfc).
 *
 * @param $check
 * @return bool
 */
	public function checkTypeExist($check) {
		if ($check['type'] == null) {
			return false;
		} else {
			$supported = array_search($check['type'], OpenPGP_PublicKeyPacket::$algorithms);

			return $supported;
		}
	}

/**
 * Analyze key before validating it, and extract key information.
 *
 * @param array $options
 *
 * @return bool
 */
	public function beforeValidate($options = []) {
		if (!empty($this->data['Gpgkey']['key']) &&
			empty($this->data['Gpgkey']['fingerprint'])
		) {
			$data = $this->buildGpgkeyDataFromKey($this->data['Gpgkey']['key']);
			if ($data !== false) {
				$this->data['Gpgkey'] = array_merge($this->data['Gpgkey'], $data['Gpgkey']);
			}
		}

		return true;
	}

/**
 * Analyze key before saving it, and extract key information.
 *
 * @param array $options
 *
 * @return bool
 */
	public function beforeSave($options = []) {
		if (!empty($this->data['Gpgkey']['key']) &&
			empty($this->data['Gpgkey']['fingerprint'])
		) {
			$data = $this->buildGpgkeyDataFromKey($this->data['Gpgkey']['key']);
			if ($data !== false) {
				$this->data['Gpgkey'] = array_merge($this->data['Gpgkey'], $data['Gpgkey']);
			}
		}

		return true;
	}

/**
 * Build data array from a key.
 *
 * @param $key
 *
 * @return mixed
 */
	public function buildGpgkeyDataFromKey($key) {
		try {
			$gpg = new Passbolt\Gpg();
			$info = $gpg->getKeyInfo($key);
		} catch (Exception $e) {
			return false;
		}

		if ($info) {
			$data['Gpgkey'] = array_merge(
				[
					'key' => $key
				],
				$info
			);
			if (!empty($data['Gpgkey']['expires'])) {
				$data['Gpgkey']['expires'] = gmdate('Y-m-d H:i:s', $data['Gpgkey']['expires']);
			}
			if (!empty($data['Gpgkey']['key_created'])) {
				$data['Gpgkey']['key_created'] = gmdate('Y-m-d H:i:s', $data['Gpgkey']['key_created']);
			}
		} else {
			$data['Gpgkey']['key'] = $key;
		}

		return $data;
	}

/**
 * Return the find conditions to be used for a given context.
 *
 * @param null|string $case The target case.
 * @param null|string $role The user role.
 * @param null|array $data (optional) Optional data to build the find conditions.
 * @return array
 */
	public static function getFindConditions($case = 'view', $role = Role::ANONYMOUS, $data = null) {
		switch ($case) {
			case 'index':
				$conditions = ['Gpgkey.deleted' => 0];
				if (isset($data['modified_after'])) {
					$conditions['Gpgkey.modified >='] = $data['modified_after'];
				}
				$conditions = ['conditions' => $conditions];
				break;
			case 'view':
				$conditions = ['conditions' => ['Gpgkey.deleted' => 0, 'Gpgkey.user_id' => $data['Gpgkey.user_id']]];
				break;
			default:
				$conditions = ['conditions' => []];
				break;
		}

		return $conditions;
	}

/**
 * Return the list of field to fetch for given context.
 *
 * @param string $case context ex: login, activation
 * @return array
 */
	public static function getFindFields($case = 'view', $role = Role::USER) {
		switch ($case) {
			case 'view':
			case 'index':
				$fields = [
					'fields' => [
						'user_id',
						'key',
						'bits',
						'uid',
						'key_id',
						'fingerprint',
						'type',
						'expires',
						'modified'
					]
				];
				break;
			case 'delete':
				$fields = [
					'fields' => [
						'deleted'
					]
				];
				break;
			case 'save':
				$fields = [
					'fields' => [
						'user_id',
						'key',
						'bits',
						'uid',
						'key_id',
						'fingerprint',
						'type',
						'expires',
						'parent_id',
						'key_created'
					]
				];
				break;
		}

		return $fields;
	}

	static public function isValidFingerprint($fingerprint) {
		// we expect a SHA1 fingerprint
		$pattern = '/[A-Fa-f0-9]{40}/';

		return preg_match($pattern, $fingerprint);
	}
}
