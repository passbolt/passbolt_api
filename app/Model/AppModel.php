<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Model', 'Model');

class AppModel extends Model {

/**
 * Model behaviors
 *
 * @link http://api20.cakephp.org/class/model#
 *
 * @var array
 */
	public $actsAs = ['Containable'];

/**
 * Never fetch any recursive data from associated models
 * Use containable for any assocs
 *
 * @var int
 */
	public $recursive = -1;

/**
 * Constructor
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
		$this->setValidationRules();
	}

/**
 * Set the validation rules for a given context
 *
 * @param string $case (optional) The target validation case if any.
 * @return bool true if success
 * @access public
 */
	public function setValidationRules($case = 'default') {
		$this->validate = $this->getValidationRules($case);
		return true;
	}

/**
 * Get the validation rules for a given context
 *
 * @param string $case (optional) The target validation case if any.
 * @return array validation rules
 * @access public
 */
	public static function getValidationRules($case = null) {
		return [];
	}

/**
 * Return the find options (felds, conditions and order) for a given context
 *
 * @param string $case The target case.
 * @param string $role optional user role filter if needed to build the options
 * @param array $data optional data to be used if needed to build the options
 * @return array
 */
	public static function getFindOptions($case, $role = null, $data = array()) {
		$findOptions = [];

		// Retrieve the find conditions
		$findConditions = static::getFindConditions($case, $role, $data);
		$findOptions = array_merge($findOptions, $findConditions);

		// Prepare contain instructions.
		$data['contain'] = static::mergeFindContain(
			static::getFindContain($case, $role),
			isset($data['contain']) ? $data['contain'] : []
		);

		// Retrieve the find fields.
		$findFields = static::getFindFields($case, $role, $data);
		$findOptions = array_merge($findOptions, $findFields);

		// Retrieve the find orders.
		$findOrders = static::getFindOrder(
			static::getFindAllowedOrder($case, $role),
			isset($data['order']) ? $data['order'] : [],
			isset($findOptions['contain']) ? array_keys($findOptions['contain']) : []
		);
		$findOptions = array_merge($findOptions, $findOrders);

		return $findOptions;
	}

/**
 * Return the list of fields to be returned by a find operation in given context
 *
 * @param string $case context ex: login, activation
 * @param string $role optional user role if needed to build the options
 * @return array $fields
 * @access public
 */
	public static function getFindFields($case = null, $role = null, $data = null) {
		return ['fields' => []];
	}

/**
 * Prepare contain instructions for a find operation.
 *
 * Will basically compute the defaults (usually returned by Model::getFindContain) against the
 * requested contains (usually provided in the query).
 *
 * @param array defaultContain
 *  array of data, with a contain key listing the contain objects requested
 *  in the form:
 *  array[
 *    'users' => 1,
 *    'resources' => 0,
 *  ]
 *
 * @param array requestedContain
 *   same as above
 *
 * @return array
 *   the computed result.
 *   let's imagine the operation:
 *   $defaultContain = array[
 *    'users' => 1,
 *    'resources' => 0,
 *  ];
 *  $requestedContain = [
 *    'resources' => 1,
 *    'whatever' => 1,
 *  ]
 *  the result would be:
 *  [
 *    'users' => 1,
 *    'resources' => 1,
 *  ];
 *
 */
	public static function mergeFindContain($defaultContain, $requestedContain) {
		$finalContain = [];
		// Check default contain values. Only retain the one that have been explicitly requested in data, or that
		// are equal to 1 by default.
		foreach($defaultContain as $key => $value) {
			if ((isset($requestedContain[$key]) && $requestedContain[$key] == 1)
				|| (!isset($requestedContain[$key]) && $defaultContain[$key] == 1)) {
				$finalContain[] = $key;
			}
		}
		return $finalContain;
	}

/**
 * Return the list of contain instructions allowed for each case, with their default value
 *
 * @param null $case
 * @param null $role
 * @return array
 */
	public static function getFindContain($case = null, $role = null) {
		return [];
	}

/**
 * Return the find conditions to be used for a given context.
 * Use it if you want to activate a User session by instance.
 *
 * @param null|string $case The target case.
 * @param null|string $role The user role.
 * @param null|array $data (optional) Optional data to build the find conditions.
 * @return array
 */
	public static function getFindConditions($case = null, $role = null, &$data = null) {
		return ['conditions' => []];
	}

/**
 * Return the list of order instructions allowed for each case.
 *
 * @param null $case
 * @param null $role
 * @return array
 */
	public static function getFindAllowedOrder($case = null, $role = null) {
		return [];
	}

/**
 * Get order instructions for a find operation.
 *
 * Will basically ensure that the requested orders are allowed, and valid.
 *
 * @param array $allowedOrder
 *  array of allowed orders
 *  array[
 * 	  'User.username',
 *    'User.modified',
 *    'Profile.last_name',
 *    'Profile.first_name'
 *  ]
 *
 * @param array requestedOrders
 *   array of requested orders
 *  array[
 * 	  'User.active DESC',
 *    'User.modified ASC'
 *  ]
 *
 * @param array contain
 *   array of requested contain
 *  array[
 * 	  'User',
 * 	  'Profile'
 *  ]
 *
 * @return array
 *   the computed result.
 *   let's imagine the operation:
 *
 *   $allowedOrder = array[
 * 	  'User.username',
 *    'User.modified',
 *    'Profile.last_name',
 *    'Profile.first_name'
 *  ]
 *  $requestedOrder = [
 * 	  'User.active DESC',
 *    'Profile.last_name ASC'
 *  ]
 *  $contain = []
 *
 *  the result would be:
 *  [
 * 	  'User.modified DESC'
 *    // Profile.last_name has been dropped as the request doesn't contain the Profile model
 *  ];
 *
 */
	public static function getFindOrder($allowedOrder = array(), $requestOrders = array(), $contain = array()) {
		$finalOrder['order'] = [];

		foreach ($requestOrders as $requestOrder) {
			/*
			 * Does the requested order match expected find orders.
			 * The regex extracts the data as following
			 * [
			 *   1: MODEL_NAME.FIELD_NAME
			 *   2: MODEL_NAME
			 *   3: FIELD_NAME
			 *   4: ASC OR DESC
			 * ]
			 */
			$matches = [];
			preg_match('/^((\w*)?\.?([\w-_]*))\s*(asc|desc)?$/i', trim($requestOrder), $matches);

			// If the request order is valid and the field is an allowed field
			if (isset($matches[1]) && in_array($matches[1], $allowedOrder)) {
				// If the field model is part of a requested model. Check that the request model
				// is either the current model or a model requested in contain.
				if ($matches[2] === get_called_class() || in_array($matches[2], $contain)) {
					$finalOrder['order'][] = $requestOrder;
				}
			}
		}

		return $finalOrder;
	}

/**
 * Get path of a target instance in a nested data array
 *
 * @param string $needle what are are looking for
 * @param array $data stack
 * @param string $key the key which hold the needle value
 * @param array &$path the path of the found needle of last recursive call
 * @param bool &$found result of last recursive call
 * @return array the path of the found needle or false
 */
	public function inNestedArray($needle, $data, $key = 'id', &$path = [], &$found = false) {
		// if data is an array of nested array
		if (!isset($data[$this->alias])) {
			foreach ($data as $nestedData) {
				$this->inNestedArray($needle, $nestedData, $key, $path, $found);
				if ($found) {
					break;
				}
			}
		} else {
			// the needle is found
			if ($data[$this->alias][$key] == $needle) {
				$found = true;
				array_unshift($path, $data[$this->alias]['id']);
				return $path;
			}
			// search in the children
			if (!empty($data['children'])) {
				foreach ($data['children'] as $child) {
					$this->inNestedArray($needle, $child, $key, $path, $found);
					if ($found) {
						array_unshift($path, $data[$this->alias]['id']);
						break;
					}
				}
			}
		}
		return $path;
	}

/**
 * beforeSave() callback.
 *
 * @param array $options passed from Model::save().
 * @return bool
 */
	public function beforeSave($options = []) {
		// Set atomic to false: we control database transactions manually.
		$options['atomic'] = false;
		return parent::beforeSave($options);
	}

/****************************************************************************************
 *  COMMON VALIDATION RULES
 ****************************************************************************************/

/**
 * Check if a record with provided parent_id exists
 *
 * @param array $check record to check
 * @return bool
 */
	public function parentExists($check) {
		if ($check['parent_id'] == null) {
			return true;
		} else {
			$exists = $this->find('count', ['conditions' => ['id' => $check['parent_id']], 'recursive' => -1]);
			return $exists > 0;
		}
	}

/**
 * Validation rule : Check if an instance of a given model exists
 *
 * @param string $check The data to check
 * @param string $key The key to find the uuid
 * @param string $modelName The model name the instance belong to
 * @return bool
 */
	public function validateExists($check, $key, $modelName) {
		$model = ClassRegistry::init($modelName);
		return $model->exists($check[$key]);
	}

/**
 * Check if a user with same id exists
 *
 * @param array $check ['user_id' => UUID]
 * @return bool
 */
	public function userExists($check) {
		if ($check['user_id'] == null) {
			return false;
		} else {
			if (!isset($this->User)) {
				$this->User = Common::getModel('User');
			}
			$exists = $this->User->find('count', [
				'conditions' => ['User.id' => $check['user_id']]
			]);

			return $exists > 0;
		}
	}

}
