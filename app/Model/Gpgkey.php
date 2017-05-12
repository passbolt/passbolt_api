<?php
/**
 * Gpg Key Model
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

if (!class_exists('\Passbolt\Gpg')) {
	App::import('Model/Utility', 'Gpg');
}

/**
 * @SWG\Definition(
 * @SWG\Xml(name="Gpgkey"),
 * @SWG\Property(
 *     property="id",
 *     type="string",
 *     description="UUID serving as the primary identifier in passbolt database"
 *   ),
 * @SWG\Property(
 *     property="uid",
 *     type="string",
 *     description="Name and email of the user of the key",
 *     example="Firstname Lastname <example@passbolt.com>"
 *   ),
 * @SWG\Property(
 *     property="fingerprint",
 *     type="string",
 *     description="Key fingerprint. SHA1 hash, e.g. 40 hexadecimal characters",
 *     example="120F87DDE5A438DE89826D464F8194025FD2D92C"
 *   ),
 * @SWG\Property(
 *     property="key_id",
 *     type="string",
 *     description="Key id, the last 8 characters of the fingerprint",
 *     example="5FD2D92C"
 *   ),
 * @SWG\Property(
 *     property="key_created",
 *     type="string",
 *     description="Date time when the key was created on the client side",
 *     example="﻿2016-04-26 17:01:01"
 *   ),
 * @SWG\Property(
 *     property="expires",
 *     type="string",
 *     description="Date and time when the key will expire",
 *     example="﻿2016-04-26 17:01:01"
 *   ),
 * @SWG\Property(
 *     property="type",
 *     type="string",
 *     description="Algorithm used for encryption or signature, example: RSA."
 *   ),
 * @SWG\Property(
 *     property="key",
 *     type="string",
 *     description="PGP Public key block, ASCII armored format"
 *   ),
 * @SWG\Property(
 *     property="bits",
 *     type="integer",
 *     description="Size of the key, example: 2048"
 *   ),
 * @SWG\Property(
 *     property="created",
 *     type="string",
 *     description="Creation date (as a database entry)",
 *     example="﻿2016-04-26 17:01:01"
 *   ),
 * @SWG\Property(
 *     property="modified",
 *     type="string",
 *     description="Last modification date (as a database entry)",
 *     example="﻿2016-04-26 17:01:01"
 *   ),
 * @SWG\Property(
 *     property="created_by",
 *     type="string",
 *     description="Id of the user who created the key (as a database entry)"
 *   ),
 * @SWG\Property(
 *     property="modified_by",
 *     type="string",
 *     description="Id of the user who last modified the key (as a database entry)"
 *   )
 * )
 */
class Gpgkey extends AppModel {

	public $name = 'Gpgkey';

	public $useTable = 'gpgkeys';

	public $belongsTo = [
		'User',
	];

/**
 * Model behaviors
 *
 * @link http://api20.cakephp.org/class/model#
 */
	public $actsAs = [
		'Containable',
		'Trackable'
	];

/**
 * Get the validation rules upon context
 *
 * @param string $case (optional) the target validation case if any.
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
					'rule' => '/^[A-F0-9]{8}$/',
					'required' => false,
					'message' => __('The key id has an incorrect format'),
				],
			],
			'fingerprint' => [
				'format' => [
					'rule' => '/^[A-F0-9]{40}$/',
					'required' => 'create',
					'message' => __('The fingerprint has an incorrect format'),
					'allowEmpty' => false,
				],
				'unique' => [
					'rule' => ['checkKeyFingerprintIsUnique', null],
					'message' => __('The key fingerprint provided is already in use for another user'),
				]
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
 * Check if a key can be imported from ASCII armored block
 *
 * @param array $check with $check['key'] set
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
 * @param array $check with 'expires' key set
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
 * @param array $check with 'key_created' set
 * @return bool
 */
	public function checkCreatedIsInPast($check) {
		if ($check['key_created'] == null) {
			return false;
		} else {
			// We allow a margin because we had the issue of having keys generated by systems that were not exactly
			// on time.
			// See ticket PASSBOLT-1505.
			$margin = '12 hour';
			$created = strtotime('-' . $margin, strtotime($check['key_created']));
			$now = gmdate('U');
			$inPast = $now > $created;

			return $inPast;
		}
	}

/**
 * Check uid (according to gpg rfc).
 *
 * @param array $check with 'uid' key set
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
 * Check if an active key with the same fingerprint already exist.
 *
 * @param array $check with 'fingerprint' set
 * @return bool
 */
	public function checkKeyFingerprintIsUnique($check) {
		if (!isset($check['fingerprint']) || empty($check['fingerprint'])) {
			return false;
		} else {
			$exist = $this->find('first', [
					'conditions' => [
						'Gpgkey.fingerprint' => $check['fingerprint'],
						'User.deleted' => false,
						'User.active' => true,
					],
					'contain' => [
						'User'
					]
				]);

			return empty($exist);
		}
	}

/**
 * Check type exists (according to gpg rfc).
 *
 * @param array $check with 'type' key set
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
 * @param array $options Options passed from Model::save().
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
 * @param array $options Options passed from Model::save().
 * @return bool
 */
	public function beforeSave($options = []) {
		if (!empty($this->data['Gpgkey']['key']) && empty($this->data['Gpgkey']['fingerprint'])) {
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
 * @param string $key ascii armored key
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
				['key' => $key],
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
	public static function getFindConditions($case = 'view', $role = null, &$data = null) {
		$conditions = ['conditions' => []];

		switch ($case) {
			case 'GpgKey::index':
				$conditions['conditions']['Gpgkey.deleted'] = 0;

				if (isset($data['filter']['modified-after'])) {
					// convert timestamp to mysql condition
					$datetime = date('Y-m-d H:i:s', $data['filter']['modified-after']);
					$conditions['conditions']['Gpgkey.modified >='] = $datetime;
				}
				break;

			case 'GpgKey::view':
				$conditions['conditions'] = [
					'Gpgkey.deleted' => 0,
					'Gpgkey.user_id' => $data['Gpgkey.user_id']
				];
				break;
		}
		return $conditions;
	}

/**
 * Return the list of fields to be returned by a find operation in given context
 *
 * @param string $case context ex: login, activation
 * @param string $role optional user role if needed to build the options
 * @return array $fields
 * @access public
 */
	public static function getFindFields($case = 'view', $role = null, $data = null) {
		$fields = ['fields' => []];

		switch ($case) {
			case 'GpgKey::view':
			case 'GpgKey::index':
				$fields = [
					'fields' => [
						'id',
						'user_id',
						'key',
						'bits',
						'uid',
						'key_id',
						'fingerprint',
						'type',
						'expires',
						'created',
						'modified'
					]
				];
				break;

			case 'GpgKey::save':
				$fields = [
					'fields' => [
						'id',
						'user_id',
						'key',
						'bits',
						'uid',
						'key_id',
						'fingerprint',
						'type',
						'expires',
						'parent_id',
						'key_created',
						'created_by',
						'modified_by',
					]
				];
				break;
		}

		return $fields;
	}

/**
 * Check if a key fingerprint is valid
 *
 * @param string $fingerprint a hex string 40 chars in length
 * @return bool
 */
	static public function isValidFingerprint($fingerprint) {
		// we expect a SHA1 fingerprint
		$pattern = '/^[A-F0-9]{40}$/';

		return (preg_match($pattern, $fingerprint) === 1);
	}
}
