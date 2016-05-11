<?php
/**
 * Secret model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('User', 'Model');
App::uses('Resource', 'Model');

class Secret extends AppModel {

	public $actsAs = [
		'Containable',
		'Trackable'
	];

	public $belongsTo = [
		'Resource',
		'User',
	];

/**
 * Get the validation rules upon context
 *
 * @param string case (optional) The target validation case if any.
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case = 'default') {
		$default = [
			'user_id' => [
				'uuid' => [
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message' => __('The secret uid must be provided and in correct format')
				],
				'exist' => [
					'rule' => ['userExists', null],
					'message' => __('The user provided does not exist')
				],
			],
			'resource_id' => [
				'uuid' => [
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message' => __('The resource uid must be in correct format')
				],
				'exist' => [
					'rule' => ['resourceExists', null],
					'message' => __('The resource provided does not exist')
				],
			],
			'data' => [
				'isnotBlank' => [
					'required' => 'create',
					'rule' => 'notBlank',
					'message' => __('The secret must be provided')
				],
				'isGpgFormat' => [
					'rule' => ['checkGpgMessageIsValid', null],
					'message' => __('The message provided is not in the right format'),
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
 * Check if a resource with same id exists
 *
 * @param check
 * @return bool
 */
	public function resourceExists($check) {
		if ($check['resource_id'] == null) {
			return false;
		} else {
			$exists = $this->Resource->find('count', [
				'conditions' => [
					'Resource.id' => $check['resource_id']
				],
				'recursive' => -1
			]);

			return $exists > 0;
		}
	}

/**
 * Check a gpg message is valid
 *
 * @param $check
 * @return bool
 */
	public function checkGpgMessageIsValid($check) {
		if ($check['data'] == null) {
			return false;
		} else {
			$isMarker = preg_match('/-(BEGIN )*([A-Z0-9 ]+)-/', $check['data'], $values);
			if (!$isMarker || !isset($values[2])) {
				return false;
			}
			$marker = $values[2];
			$msgUnarmored = OpenPGP::unarmor($check['data'], $marker);
			if ($msgUnarmored != false) {
				// Message in right format.
				$msg = OpenPGP_Message::parse($msgUnarmored);

				return true;
			}

			return false;
		}
	}

/**
 * Return the find conditions to be used for a given context.
 *
 * @param null|string $case The target case.
 * @param null|string $role The user role.
 * @param null|array $data (optional) Optional data to build the find conditions.
 * @return array
 */
	public static function getFindConditions($case = 'view', $role = Role::USER, $data = null) {
		$conditions = [];

		switch ($case) {
			case 'add':
			case 'edit':
			case 'view':
				$conditions = [
					'conditions' => [
					]
				];
				break;

			default:
				$conditions = [
					'conditions' => []
				];
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
	public static function getFindFields($case = 'view', $role = null) {
		switch ($case) {
			case 'view':
				$fields = [
					'fields' => [
						'id',
						'user_id',
						'resource_id',
						'data',
						'created',
						'modified',
						'created_by',
						'modified_by'
					]
				];
				break;
			case 'save':
				$fields = [
					'fields' => [
						'user_id',
						'resource_id',
						'data',
						'created',
						'modified',
						'created_by',
						'modified_by'
					]
				];
				break;
			case 'update':
				$fields = [
					'fields' => [
						'user_id',
						'resource_id',
						'data',
						'created',
						'modified',
						'created_by',
						'modified_by'
					]
				];
				break;
			default:
				$fields = [
					'fields' => []
				];
				break;
		}

		return $fields;
	}
}
