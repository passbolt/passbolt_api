<?php

/**
 * Resource model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class Resource extends AppModel {
/**
 * Model behaviors
 * @link http://api20.cakephp.org/class/model#
 */
	public $actsAs = array(
		'SuperJoin',
		'Containable',
		'Trackable',
		'Favoritable',
		'Permissionable' => array('priority' => 1)
	);


/**
 * Details of belongs to relationships
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $belongsTo = array(
		'Creator' => array(
			'className' => 'User',
			'foreignKey' => 'created_by'
		),
		'Modifier' => array(
			'className' => 'User',
			'foreignKey' => 'modified_by'
		)
	);

/**
 * Details of has many relationships
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $hasMany = array(
		'CategoryResource',
		'Secret',
	);

/**
 * Details of has and belongs to many relationships
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $hasAndBelongsToMany = array('Category' => array('className' => 'Category'));

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->Behaviors->setPriority(array('Permissionable' => 1));
	}

/**
 * Get the validation rules upon context
 *
 * @param string case (optional) The target validation case if any.
 * @return array CakePHP validation rules
 */
	public static function getValidationRules($case = 'default') {
		$default = array(
				'id' => array(
					'uuid' => array(
						'rule' => 'uuid',
						'required' => 'update',
						'message' => __('Id must be in correct format'),
					)
				),
				'name' => array(
					'required' => array(
						'allowEmpty' => false,
						'rule'       => array('notEmpty'),
						'message'    => __('A name is required')
					),
					'alphaNumericAndSpecial' => array(
						'rule' => "/^[\p{L}\d ,.\-_\(\[\)\]']*$/u",
						'required' => 'create',
						'allowEmpty' => false,
						'message' => __('Name should only contain alphabets, numbers and the special characters : , . - _ ( ) [ ] \''),
					),
					'size' => array(
						'rule' => array('lengthBetween', 3, 64),
						'message' => __('Name should be between %s and %s characters long', 3, 64),
					)
				),
				'username' => array(
					'required' => array(
						'allowEmpty' => false,
						'rule'       => array('notEmpty'),
						'message'    => __('A username is required')
					),
					'alphaNumeric' => array(
						'rule' => '/^[a-zA-Z0-9\-_]*$/',
						'required' => 'create',
						'message' => __('Username should only contain alphabets, numbers only and the special characters : - _'),
					),
					'size' => array(
						'rule' => array('lengthBetween', 3, 64),
						'message' => __('Username should be between %s and %s characters long', 3, 64),
					)
				),
			'expiry_date' => array(
				'date' => array(
					'required' => false,
					'allowEmpty' => true,
					'rule' => array(
						'date',
						'ymd'
					),
					'message' => __('Please indicate a valid date')
				),
				'infuture' => array(
					'rule' => array('isInFuture'),
					'message' => __('The date should be in the future.')
				),
			),
			'uri' => array(
				'url' => array(
					'rule' => AppValidation::getValidationAlphaNumericAndSpecialRegex(),
					'message' => __('URI should only contain alphabets, numbers and the special characters : , . : ; ? ! @ - _ ( ) [ ] \' " /.'),
					'allowEmpty' => true,
				),
				'size' => array(
					'rule' => array('lengthBetween', 3, 255),
					'message' => __('URI should be between %s and %s characters long', 3, 255),
				)
			),
			'description' => array(
				'alphaNumericAndSpecial' => array(
					'rule' => AppValidation::getValidationAlphaNumericAndSpecialRegex(),
					'required' => false,
					'allowEmpty' => true,
					'message' => __('Description should only contain alphabets, numbers and the special characters : , . : ; ? ! @ - _ ( ) [ ] \' " /')
				),
				'size' => array(
					'rule' => array('lengthBetween', 3, 255),
					'message' => __('Description should be between %s and %s characters long', 3, 255),
				)
			),
		);
		switch ($case) {
			default:
			case 'default':
				$rules = $default;
				break;
		}
		return $rules;
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
		$conditions = array();

		switch ($case) {
			case 'add':
			case 'edit':
			case 'view':
				$conditions = array('conditions' => array(
					'Resource.deleted' => 0,
					'Resource.id' => $data['Resource.id']
				));
				break;

			case 'index':
			case 'viewByCategory':
				$conditions = array('conditions' => array('Resource.deleted' => 0));
				if (isset($data['foreignModels']['Category.id'])) {
					$conditions['conditions']['Category.id'] = $data['foreignModels']['Category.id'];
				}
				if (isset($data['keywords'])) {
					$keywords = explode(' ', $data['keywords']);
					foreach ($keywords as $keyword) {
						$conditions['conditions']["AND"][] = array('Resource.name LIKE' => '%' . $keyword . '%');
					}
				}
				if (isset($data['case'])) {
					switch ($data['case']) {
						case 'favorite':
							$conditions['conditions']["AND"][] = array('Favorite.id IS NOT NULL');
							break;

						case 'own':
							$conditions['conditions']["AND"][] = array('Resource.created_by' => User::get('User.id'));
							break;

						case 'shared':
							$conditions['conditions']["AND"][] = array('Resource.created_by <>' => User::get('User.id'));
							break;

					}
				}
				if (isset($data['order'])) {
					switch ($data['order']) {
						case 'modified':
							$conditions['order'] = array('Resource.modified DESC');
							break;

						case 'expiry_date':
							$conditions['order'] = array('Resource.expiry_date DESC');
							break;

					}
				} else {
					// By default order by created date
					$conditions['order'] = array('Resource.modified DESC');
				}
				break;

			default:
				$conditions = array('conditions' => array());
		}

		return $conditions;
	}

/**
 * Return the list of field to fetch for given context
 * @param string $case context ex: login, activation
 * @return $condition array
 */
	public static function getFindFields($case = 'view', $role = Role::USER) {
		switch ($case) {
			case 'view':
			case 'index':
			case 'viewByCategory':
				$fields = array(
					'fields' => array(
						'DISTINCT Resource.id',
						'Resource.name',
						'Resource.username',
						'Resource.expiry_date',
						'Resource.uri',
						'Resource.description',
						'Resource.created',
						'Resource.modified',
						'Favorite.id',
						'Favorite.user_id',
						'Favorite.created',
						'Creator.id',
						'Creator.username',
						'Modifier.id',
						'Modifier.username'
					),
					'superjoin' => array('Category'),
					'contain' => array(
						'Category',
						'CategoryResource',
						'Favorite',
						'Secret' => array (
							'fields' => array(
								'Secret.id',
								'Secret.user_id',
								'Secret.data',
								'Secret.created',
								'Secret.modified',
							),
							// We get only the secret for the current user.
							'conditions' => array(
								'Secret.user_id' => User::get('id')
							),
						),
						'Creator',
						'Modifier'
					)
				);
				break;
			case 'delete':
				$fields = array('fields' => array('deleted'));
				break;
			case 'Resource::edit':
				$fields = array(
					'fields' => array(
						'name',
						'username',
						'expiry_date',
						'uri',
						'description',
					)
				);
				break;
			case 'save':
				$fields = array('fields' => array(
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
				));
				break;
			default:
				$fields = array('fields' => array());
				break;
		}
		return $fields;
	}

/**
 * Validates if a date is in future
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
	 * @param $resourceId
	 * @param $secrets
	 *
	 * @throws Exception
	 * @throws ValidationException
	 */
	public function saveSecrets($resourceId, $secrets) {
		// Validate the secrets provided.
		// Make sure there is a secret per user with whom it's shared, nothing more, nothing less.

		// Get list of current permissions for the given ACO.
		$permsUsers = $this->getAuthorizedUsers($resourceId);
		$permsUsers = Hash::extract($permsUsers, '{n}.User.id');

		// Get the list of users corresponding to the secrets, without duplicates.
		$dataSecretUsers = array_unique(
			Hash::extract($secrets, '{n}.user_id')
		);

		// Check difference between the users expected and the users provided.
		$missingUsers = array_diff($permsUsers, $dataSecretUsers);
		// Check if the size of expected secrets is the same as provided one.
		$sameSize = sizeof($permsUsers) == sizeof($dataSecretUsers);

		// Check if the users expected are the same as the users provided.
		$sameUsers = empty($missingUsers);

		// Check errors and return error message if any.
		if (!$sameSize || !$sameUsers) {
			throw new Exception(__('The list of secrets provided is invalid'));
		}

		// Begin transaction.
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		// End of secrets check. We proceed.

		// Delete all the previous secrets.
		$this->Secret->deleteAll(array(
				'Secret.resource_id' => $resourceId
			), false);

		$fields = $this->Secret->getFindFields('update', User::get('Role.name'));
		// Validate the given secrets.
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
	}
}
