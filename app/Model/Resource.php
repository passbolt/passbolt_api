<?php
/**
 * Resource model
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 *                2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
/**
 * @SWG\Definition(
 * @SWG\Xml(name="Resource"),
 * @SWG\Property(
 *     property="id",
 *     type="string",
 *     description="UUID of the resource"
 *   ),
 * @SWG\Property(
 *     property="name",
 *     type="string",
 *     description="Name of the resource"
 *   ),
 * @SWG\Property(
 *     property="username",
 *     type="string",
 *     description="Username of the resource"
 *   ),
 * @SWG\Property(
 *     property="expiry_date",
 *     type="string",
 *     description="Expiry date of the resource"
 *   ),
 * @SWG\Property(
 *     property="uri",
 *     type="string",
 *     description="URI of the resource"
 *   ),
 * @SWG\Property(
 *     property="description",
 *     type="string",
 *     description="Description of the resource"
 *   ),
 * @SWG\Property(
 *     property="deleted",
 *     type="boolean",
 *     description="Flag to mark the resource as deleted"
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
 *     description="Id of the user who created the resource"
 *   ),
 * @SWG\Property(
 *     property="modified_by",
 *     type="string",
 *     description="Id of the last user who updated the resource"
 *   )
 * )
 */
class Resource extends AppModel {

/**
 * Model behaviors
 *
 * @link http://api20.cakephp.org/class/model#
 */
	public $actsAs = [
		'Containable',
		'Trackable',
		'Favoritable',
		'Permissionable' => ['priority' => 1]
	];

/**
 * Details of belongs to relationships
 *
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $belongsTo = [
		'Creator' => [
			'className' => 'User',
			'foreignKey' => 'created_by'
		],
		'Modifier' => [
			'className' => 'User',
			'foreignKey' => 'modified_by'
		]
	];

/**
 * Details of has many relationships
 *
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $hasMany = [
		'Secret',
	];

/**
 * Resource constructor
 *
 * @param bool|int|string|array $id Set this ID for this model on startup,
 * can also be an array of options, see above.
 * @param string $table Name of database table to use.
 * @param string $ds DataSource connection name.
 * @link http://api20.cakephp.org/class/app-model#method-AppModel__construct
 * @access public
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->Behaviors->setPriority(['Permissionable' => 1]);
	}

/**
 * Get the validation rules for a given context
 *
 * @param string $case (optional) The target validation case if any.
 * @return array validation rules
 * @access public
 */
	public static function getValidationRules($case = null) {
		$default = [
			'id' => [
				'uuid' => [
					'rule' => 'uuid',
					'required' => 'update',
					'message' => __('Id must be in correct format'),
				]
			],
			'name' => [
				'required' => [
					'allowEmpty' => false,
					'rule' => ['notBlank'],
					'message' => __('A name is required')
				],
				'alphaNumericAndSpecial' => [
					'rule' => "/^[\p{L}\d ,.\-_\(\[\)\]']*$/u",
					'required' => 'create',
					'allowEmpty' => false,
					'message' => __('Name should only contain alphabets, numbers and the special characters : , . - _ ( ) [ ] \''),
				],
				'size' => [
					'rule' => ['lengthBetween', 3, 64],
					'message' => __('Name should be between %s and %s characters long', 3, 64),
				]
			],
			'username' => [
				'alphaNumeric' => [
					'allowEmpty' => true,
					'required' => false,
					'rule' => '/^[a-zA-Z0-9\-_@.]*$/',
					'message' => __('Username should only contain alphabets, numbers only and the special characters : - _ . @'),
				],
				'size' => [
					'rule' => ['lengthBetween', 3, 64],
					'message' => __('Username should be between %s and %s characters long', 3, 64),
				]
			],
			'expiry_date' => [
				'date' => [
					'required' => false,
					'allowEmpty' => true,
					'rule' => [
						'date',
						'ymd'
					],
					'message' => __('Please indicate a valid date')
				],
				'infuture' => [
					'rule' => ['isInFuture'],
					'message' => __('The date should be in the future.')
				],
			],
			'uri' => [
				'url' => [
					'rule' => "/^[\p{L}\d ,.:;?@!\-_\(\[\)\]'\"\/]*$/u",
					'message' => __('URI should only contain alphabets, numbers and the special characters : , . : ; ? ! @ - _ ( ) [ ] \' " /.'),
					'allowEmpty' => true,
				],
				'size' => [
					'rule' => ['lengthBetween', 3, 255],
					'message' => __('URI should be between %s and %s characters long', 3, 255),
				]
			],
			'description' => [
				'alphaNumericAndSpecial' => [
					'rule' => "/^[\p{L}\d ,.:;?@!\-_\(\[\)\]'\"\/]*$/u",
					'required' => false,
					'allowEmpty' => true,
					'message' => __('Description should only contain alphabets, numbers and the special characters : , . : ; ? ! @ - _ ( ) [ ] \' " /')
				],
				'maxLength' => [
					'rule' => ['lengthBetween', 0, 10000],
					'message' => __('Description maximum length is 10k characters')
				]
			],
		];
		return $default;
	}

/**
 * Return the find conditions to be used for a given context.
 *
 * @param null|string $case The target case.
 * @param null|string $role The user role.
 * @param null|array $data (optional) Optional data to build the find conditions.
 * @return array
 */
	public static function getFindConditions($case = 'view', $role = Role::USER, &$data = null) {
		$conditions = ['conditions' => []];

		switch ($case) {
			case 'Resource::exists':
			case 'Resource::view':
			case 'Group::edit':
				$conditions = [
					'conditions' => [
						'Resource.deleted' => 0,
						'Resource.id' => $data['Resource.id']
					]
				];
				break;

			case 'Resource::index':
				$conditions = ['conditions' => [
					'Resource.deleted' => 0,
				]];
				if (isset($data['filter']['keywords'][0])) {
					$keywords = explode(' ', trim($data['filter']['keywords'][0]));
					foreach ($keywords as $keyword) {
						$conditions['conditions']['AND'][] = ['Resource.name LIKE' => '%' . $keyword . '%'];
					}
				}
				if (isset($data['filter']['is-favorite'])) {
					$conditions['conditions']['AND'][] = 'Favorite.id IS NOT NULL';
					if (!isset($data['contain']) || !in_array('Favorite', $data['contain'])) $data['contain'][] = 'Favorite';
				}
				if (isset($data['filter']['is-owned-by-me'])) {
					$conditions['conditions']['AND'][] = ['Resource.created_by' => User::get('User.id')];
					if (!isset($data['contain']) || !in_array('Creator', $data['contain'])) $data['contain'][] = 'Creator';
				}
				if (isset($data['filter']['is-shared-with-me'])) {
					$conditions['conditions']['AND'][] = ['Resource.created_by <>' => User::get('User.id')];
					if (!isset($data['contain']) || !in_array('Modifier', $data['contain'])) $data['contain'][] = 'Modifier';
				}
				if (isset($data['has-resource_id'])) {
					$conditions['conditions']['AND']['Resource.id'] = $data['has-resource_id'];
				}
				break;

			default:
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
			case 'Resource::exists':
				$fields = [
					'fields' => [
						'Resource.id',
						'Resource.deleted',
					],
					// avoid the behavior hooks, especially the permissionable behavior
					'callbacks' => false
				];
				break;

			case 'Resource::view':
			case 'Resource::index':
				$fields = [
					'fields' => [
						'DISTINCT Resource.id',
						'Resource.name',
						'Resource.username',
						'Resource.expiry_date',
						'Resource.uri',
						'Resource.description',
						'Resource.created',
						'Resource.modified',
					],
				];

				// If contain requested, add the query contain.
				if (isset($data['contain'])) {
					// If contain Favorite.
					if (in_array('Favorite', $data['contain'])) {
						$fields['contain']['Favorite'] = [
							'fields' => [
								'Favorite.id',
								'Favorite.user_id',
								'Favorite.created',
							]
						];
					}
					// If contain Secret.
					if (in_array('Secret', $data['contain'])) {
						$fields['contain']['Secret'] = [
							'fields' => [
								'Secret.id',
								'Secret.user_id',
								'Secret.data',
								'Secret.created',
								'Secret.modified',
							],
							'conditions' => [
								'Secret.user_id' => User::get('id')
							],
						];
					}
					// If contain Creator.
					if (in_array('Creator', $data['contain'])) {
						$fields['contain']['Creator'] = [
							'fields' => [
								'Creator.id',
								'Creator.username',
							]
						];
					}
					// If contain Modifier.
					if (in_array('Modifier', $data['contain'])) {
						$fields['contain']['Modifier'] = [
							'fields' => [
								'Modifier.id',
								'Modifier.username',
							]
						];
					}
				}
				break;

			case 'Resource::delete':
				$fields = ['fields' => ['deleted']];
				break;

			case 'Resource::edit':
				$fields = [
					'fields' => [
						'name',
						'username',
						'expiry_date',
						'uri',
						'description',
					]
				];
				break;

			case 'Group::edit':
				$fields = [
					'fields' => [
						'DISTINCT Resource.id',
						'Resource.name',
					],
					'contain' => [
						'Secret' => [
							'fields' => [
								'Secret.id',
								'Secret.user_id',
								'Secret.data',
							],
							// We get only the secret for the current user.
							'conditions' => [
								'Secret.user_id' => User::get('id')
							],
						],
					]
				];
				break;

			case 'Resource::save':
				$fields = [
					'fields' => [
						'name',
						'username',
						'expiry_date',
						'uri',
						'description',
						'created',
						'modified',
						'created_by',
						'modified_by',
						'deleted'
					]
				];
				break;

			default:
				break;
		}

		return $fields;
	}

/**
 * Return the list of contain instructions allowed, with their default values.
 *
 * @param string $case
 * @param null $role
 * @return array
 */
	public static function getFindContain($case = 'view', $role = null) {
		$contain = [];
		switch ($case) {
			case 'Resource::view':
			case 'Resource::index':
				$contain = [
					'Creator' => 1,
					'Favorite' => 1,
					'Modifier' => 1,
					'Secret' => 1,
				];
				break;
		}
		return $contain;
	}

/**
 * Return the list of order instructions allowed for each case, with their default value
 *
 * @param null $case
 * @param null $role
 * @return array
 */
	public static function getFindAllowedOrder($case = null, $role = null) {
		return [
			'Resource.name',
			'Resource.username',
			'Resource.expiry_date',
			'Resource.uri',
			'Resource.description',
			'Resource.created',
			'Resource.modified',
		];
	}

/**
 * Validates if a date is in future
 *
 * @param array $check the parameters
 * @return bool true if the date is in future, false otherwise
 */
	public function isInFuture($check) {
		$now = time();
		$expiryDate = strtotime($check['expiry_date']);
		$interval = $expiryDate - $now;

		return ($interval > 0);
	}

/**
 * Save a list of secrets corresponding to a resource.
 *
 * @param string $resourceId uuid
 * @param array $secrets secret data
 * @throws Exception
 * @throws ValidationException
 * @return true if success
 */
	public function saveSecrets($resourceId, $secrets) {
		// Validate the secrets provided.
		// Make sure there is a secret per user with whom it's shared, nothing more, nothing less.
		// Get list of current permissions for the given ACO.
		$permsUsers = $this->findAuthorizedUsers($resourceId);
		$permsUsers = Hash::extract($permsUsers, '{n}.User.id');

		// Get the list of users corresponding to the secrets, without duplicates.
		$secretUsers = Hash::extract($secrets, '{n}.user_id');
		if (empty($secretUsers)) {
			throw new Exception(__('The list of secrets provided is invalid'));
		}

		// Check if the size of expected secrets is the same as provided one.
		$dataSecretUsers = array_unique($secretUsers);
		$sameSize = (count($permsUsers) === count($dataSecretUsers));

		// Check difference between the users expected and the users provided.
		// Check if the users expected are the same as the users provided.
		$missingUsers = array_diff($permsUsers, $dataSecretUsers);
		$sameUsers = empty($missingUsers);

		// Check errors and return error message if any.
		if (!$sameSize || !$sameUsers) {
			throw new Exception(__('The list of secrets provided is invalid'));
		}

		// End of base user list check. We proceed.
		// Begin transaction.
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		// Delete all the previous secrets.
		$this->Secret->deleteAll(['Secret.resource_id' => $resourceId], false);

		// Get the list of fields allowed in update operation
		// and validate all the given secrets.
		$fields = $this->Secret->getFindFields('update', User::get('Role.name'));
		foreach ($secrets as $i => &$secret) {
			// Force the resource id if empty.
			if (empty($secret['resource_id'])) {
				$secret['resource_id'] = $resourceId;
			}

			// Validate the data.
			$this->Secret->set($secret);
			if (!$this->Secret->validates(['fieldList' => $fields['fields']])) {
				$dataSource->rollback();
				throw new ValidationException(
					__('Could not validate secret model'),
					$this->Secret->validationErrors
				);
			}
		}

		// Save the secrets.
		if (!$this->Secret->saveMany($secrets, ['fieldList' => $fields['fields'], 'atomic' => false])) {
			$dataSource->rollback();
			throw new Exception(__('Could not save the secrets'));
		}

		// Commit transaction.
		$dataSource->commit();
		return true;
	}

/**
 * Returns true if a record with particular ID has been soft deleted.
 *
 * If $id is not passed it calls `Model::getID()` to obtain the current record ID,
 * if the resource has been soft deleted, is considered has a resource which doesn't
 * exist.
 *
 * @param int|string $id ID of record to check for existence
 * @return bool True if such a record exists
 */
	public function isSoftDeleted($id = null) {
		if ($id === null) {
			$id = $this->getID();
		}

		if ($id === false) {
			return false;
		}

		$data = ['Resource.id' => $id];
		$o = $this->getFindOptions('Resource::exists', User::get('Role.name'), $data);
		return !(bool)$this->find('count', $o);
	}

/**
 * Soft delete a resource.
 *
 * @param string $id Id of the resource to soft delete
 * @return void
 * @throws Exception
 */
	public function softDelete($id) {
		$Permission = ClassRegistry::init('Permission');

		// Begin transaction
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		// Mark the resource as deleted
		$data['Resource'] = [
			'id' => $id,
			'deleted' => 1
		];
		$fields = $this->getFindFields('Resource::delete', User::get('Role.name'));
		if (!$this->save($data, true, $fields['fields'])) {
			$dataSource->rollback();
			throw new Exception(__('Unable to soft delete the resource'));
		}

		// Revoke the user's permissions
		$deleteOptions = ['Permission.aro_foreign_key' => $id];
		if (!$Permission->deleteAll($deleteOptions, false)) {
			$dataSource->rollback();
			throw new Exception(__('Unable to delete de resource\'s permissions'));
		}

		// Everything fine, we commit.
		$dataSource->commit();
	}
}
