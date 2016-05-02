<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Model', 'Model');
App::uses('AppValidation', 'Model/Utility');

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
 * @link http://api20.cakephp.org/class/app-model#method-AppModel__construct
 * @access public
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->setValidationRules();
	}

/**
 * Set the validation rules upon context
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
 * Get the validation rules upon context
 *
 * @param string $case (optional) The target validation case if any.
 * @return array validation rules
 * @access public
 */
	public static function getValidationRules($case = null) {
		return [];
	}

/**
 * Return the find options (felds and conditions) for a given context
 *
 * @param string $case The target case.
 * @param string $role
 * @param array $data
 * @return array
 */
	public static function getFindOptions($case, $role = null, $data = null) {
		return array_merge(static::getFindConditions($case, $role, $data), static::getFindFields($case, $role));
	}

/**
 * Return the list of fields to use for a find for given context
 *
 * @param string $case context ex: login, activation
 * @param string $role
 * @return array $condition
 * @access public
 */
	public static function getFindFields($case = null, $role = null) {
		return ['fields' => []];
	}

/**
 * Return the list of field to use for a find for given context for an embedded model
 *
 * @param string $case context ex: login, activation
 * @param string $role
 * @return array $condition
 * @access public
 */
	public static function getEmbeddedFindFields($case = null, $role = null) {
		return self::getFindFields($case, $role);
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
	public static function getFindConditions($case = null, $role = null, $data = null) {
		return ['conditions' => []];
	}

/**
 * Check if a record with provided parent_id exists
 *
 * @param check
 * @return boolean
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
 * Get path of a target instance in a nested data array
 *
 * @param string $needle
 * @param array $data stack
 * @param string $key the key which hold the needle value
 * @param array $path
 * @param boolean $found
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
 * Validation rule : Check if an instance of a given model exists
 *
 * @param string $check The data to check
 * @param string $key The key to find the uuid
 * @param string $modelName The model name the instance belong to
 * @return boolean
 */
	public function validateExists($check, $key, $modelName) {
		$model = ClassRegistry::init($modelName);

		return $model->exists($check[$key]);
	}

/**
 * beforeSave() callback.
 *
 * @param array $options
 *
 * @return bool|void
 */
	public function beforeSave($options = []) {
		// Set atomic to false.
		// We usually control transactions at a upper level, and for group of operations.
		$options['atomic'] = false;

		parent::beforeSave($options);
	}

/**
 * Check if a user with same id exists
 *
 * @param $check
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

/**
 * Validate an IP address
 *
 * @param array $check ['ip']
 * @return bool true if a valid IP address
 */
	public function validIpRange($check) {
		if ($check['ip'] == null) {
			return false;
		}
		$ipRegexp = '([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])';
		$ipwildcardRegexp = '^([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.(\*?|[01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.(\*?|[01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.(\*?|[01]?\\d\\d?|2[0-4]\\d|25[0-5])$';
		$ipRangeRegexp = '^' . $ipRegexp . '-' . $ipRegexp . '$';
		$ipMaskRegexp = '^' . $ipRegexp . '\/[0-9]{1,2}$';
		if (preg_match('/' . $ipwildcardRegexp . '/', $check['ip'])) {
			return true;
		} elseif (preg_match('/' . $ipRangeRegexp . '/', $check['ip'])) {
			return true;
		} elseif (preg_match('/' . $ipMaskRegexp . '/', $check['ip'])) {
			return true;
		}

		return false;
	}

}
