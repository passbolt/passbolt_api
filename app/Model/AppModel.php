<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Model.AppModel
 * @since        version 2.12.7
 */
App::uses('Model', 'Model');
class AppModel extends Model {

/**
 * Binds to ARO nodes through permissions settings
 *
 * @var array
 */
	public $actsAs = array('Containable');

/**
 * Never fetch any recursive data from associated models
 * Use containable for any assocs
 *
 * @var integer
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
 * @param string context
 * @return bool true if success
 * @access public
 */
	public function setValidationRules($context = 'default') {
		$this->validate = $this->getValidationRules($context);
		return true;
	}

/**
 * Get the validation rules upon context
 *
 * @param string context
 * @return array validation rules
 * @access public
 */
	public static function getValidationRules($case = null) {
		return array();
	}

/**
 * Return the find options (felds and conditions) for a given context
 * @param string context
 * @param array data
 * @return array
 */
	public static function getFindOptions($case, $role=null, &$data = null) {
		return array_merge(
			static::getFindConditions($case, $role, &$data),
			static::getFindFields($case, $role)
		);
	}

/**
 * Return the list of field to use for a find for given context
 *
 * @param string $case context ex: login, activation
 * @return $condition array
 * @access public
 */
	public static function getFindFields($case = null, $role=null) {
		return array('fields' => array());
	}

/**
 * Return the conditions to be used for a given context
 * for example if you want to activate a User session
 *
 * @param $context string{guest or id}
 * @param $data used in find conditions (such as User.id)
 * @return $condition array
 * @access public
 */
	public static function getFindConditions($case = null, $role=null, &$data = null) {
		return array('conditions' => array());
	}

/**
 * Check if a record with provided parent_id exists
 * @param check
 */
	public function parentExists($check) {
		if ($check['parent_id'] == null) {
			return true;
		} else {
			$exists = $this->find('count', array(
				'conditions' => array('id' => $check['parent_id']),
				 'recursive' => -1
			));
			return $exists > 0;
		}
	}

/**
 * Get path of a target instance in a nested data array
 * @param string id needle
 * @param array stack 
 * @return array the path of the found needle or false
 */
	public function inNestedArray ($needle, $data, &$path=array(), &$found=false) {
		// if data is an array of nested array
		if(!isset($data[$this->alias])) {
			foreach($data as $nestedData) {
				$this->inNestedArray($needle, $nestedData, $path, $found);
				if ($found){
					break;					
				}
			}
		} else {
			// the needle is found
			if($data[$this->alias]['id'] == $needle) {
				$found = true;
				return $path;
			}
			// search in the children
			if(!empty($data['children'])) {
				foreach($data['children'] as $child) {
					$this->inNestedArray($needle, $child, $path, $found);
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
 * Check if an instance is existing functions of a given id and a given model name
 * @param string id The uuid of the instance to check
 * @param string modelName The model name the instance belong to
 * @return boolean
 */
	public function isInstanceExisting($id, $modelName) {
		$model = ClassRegistry::init($modelName);
		$instance = $model->findById($id);
		if (!$instance) {
			return false;
		}
		return true;
	}
}
