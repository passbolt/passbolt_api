<?php
/**
 * Secret model
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('User', 'Model');
App::uses('Resource', 'Model');

/**
 * @SWG\Definition(
 * @SWG\Xml(name="Secret"),
 * @SWG\Property(
 *     property="id",
 *     type="string",
 *     description="Secret UUID, the primary identifier",
 *     example="d1acbfc1-78d8-3e11-ad8b-7ab1eb0332d3"
 *   ),
 * @SWG\Property(
 *     property="user_id",
 *     type="string",
 *     description="User UUID",
 *     example="d1acbfc1-78d8-3e11-ad8b-7ab1eb0332d3"
 *   ),
 * @SWG\Property(
 *     property="resource_id",
 *     type="string",
 *     description="Resource UUID",
 *     example="d1acbfc1-78d8-3e11-ad8b-7ab1eb0332d3"
 *   ),
 * @SWG\Property(
 *     property="data",
 *     type="string",
 *     description="Encrypted secret, GPG ASCII Armored format",
 *     example="d1acbfc1-78d8-3e11-ad8b-7ab1eb0332d3"
 *   ),
 * @SWG\Property(
 *     property="created",
 *     type="string",
 *     description="Creation date",
 *     example="﻿2016-04-26 17:01:01"
 *   ),
 * @SWG\Property(
 *     property="modified",
 *     type="string",
 *     description="Last modification date",
 *     example="﻿2016-04-26 17:01:01"
 *   ),
 * @SWG\Property(
 *     property="created_by",
 *     type="string",
 *     description="UUID of the user who created the secret",
 *     example="d1acbfc1-78d8-3e11-ad8b-7ab1eb0332d3"
 *   ),
 * @SWG\Property(
 *     property="modified_by",
 *     type="string",
 *     description="UUID of the user who last modified the secret",
 *     example="d1acbfc1-78d8-3e11-ad8b-7ab1eb0332d3"
 *   )
 * )
 */
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
 * @param null|string $case (optional) The target validation case if any.
 * @return array validation rules
 */
	public static function getValidationRules($case = null) {
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
				'uniqueRelationship' => [
					'rule' => ['uniqueRelationship'],
					'on' => 'create',
					'message' => __('The Secret entered is a duplicate')
				]
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
		return $default;
	}

/**
 * Check if a resource with same id exists
 * Custom validation rule
 *
 * @param array $check with resource_id set
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
 * Custom validation rule
 *
 * @param array $check with data key set
 * @return bool
 */
	public function checkGpgMessageIsValid($check) {
		if (empty($check['data']) || $check['data'] == null) {
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
 * Check if a Secret for the given resource_id and user_id already exists.
 * Custom Validation Rule
 *
 * @return bool
 */
	public function uniqueRelationship() {
		$secret = $this->data['Secret'];
		$combination = [
			'Secret.resource_id' => $secret['resource_id'],
			'Secret.user_id' => $secret['user_id'],
		];
		return $this->isUnique($combination, false);
	}


/**
 * Return the find conditions to be used for a given context.
 *
 * @param null|string $case The target case.
 * @param null|string $role The user role.
 * @param null|array $data (optional) Optional data to build the find conditions.
 * @return array
 */
	public static function getFindConditions($case = null, $role = null, &$data = null) {
		$conditions = [
			'conditions' => []
		];
		return $conditions;
	}

/**
 * Return the list of fields to be returned by a find operation in given context
 *
 * @param string $case context ex: login, activation
 * @param string|null $role optional user role if needed to build the options
 * @return array $fields
 * @access public
 */
	public static function getFindFields($case = 'view', $role = null, $data = null) {
		switch ($case) {
			case 'view':
			case 'save':
			case 'update':
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
			default:
				$fields = [
					'fields' => []
				];
				break;
		}

		return $fields;
	}
}
