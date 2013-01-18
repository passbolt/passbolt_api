<?php
/**
 * Permission  model
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Model.Permission
 * @since        version 2.12.11
 */
class Permission extends AppModel {

	public $actsAs = array('Containable', 'Trackable');

	public $belongsTo = array(
		'PermissionType' => array(
			'className' => 'PermissionType',
			'foreign_key' => 'type'
		)
	);

/**
 * Get the validation rules upon context
 * @param string context
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case='default') {
		$default = array(
			'aco' => array(
				'rule' => array('validateAco')
			),
			'aco_foreign_key' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('aco_foreign_key must be an uuid in correct format')
				),
				'aco_foreign_key' => array(
					'rule' => array('validateAcoForeignKey'),
					'message' => __('the aco_foreign_key must be relative to an existing instance of aco model')
				)
			),
			'aro' => array(
				'rule' => array('validateAro')
			),
			'aro_foreign_key' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('aro_foreign_key must be an uuid in correct format')
				),
				'aro_foreign_key' => array(
					'rule' => array('validateAroForeignKey'),
					'message' => __('the aro_foreign_key must be relative to an existing instance of aro model')
				)
			),
			'type' => array(
				'rule' => 'validatePermissionType',
				'required' => true,
				'allowEmpty' => false,
				'message'	=> __('The given permission type is not valid')
			),
		);
		switch ($case) {
			default:
			case 'default' :
				$rules = $default;
		}
		return $rules;
	}
	
	public static function getAllowedAcos() {
		return array('Resource', 'Category');
	}
	
	public static function getAllowedAros() {
		return array('User', 'Group');
	}

/**
 * Validation Rule : Check if the given ACO key is an allowed ACO model
 * @param array check the data to test
 * @return boolean
 */
	public function validateAco($check) {
		return $this->isValidAco($check['aco']);
	}
	
/**
 * Validation Rule : Check if the given ARO key is an allowed ARO model
 * @param array check the data to test
 * @return boolean
 */
	public function validateAro($check) {
		return $this->isValidAro($check['aro']);
	}
	
/**
 * CValidation Rule : heck if the given aco foreign key is relative to an existing instance
 * @param array check the data to test 
 * @return boolean
 */
	public function validateAcoForeignKey($check) {
		return $this->isInstanceExisting($check['aco_foreign_key'], $this->data[$this->alias]['aco']);
	}
	
/**
 * Validation Rule : Check if the given aro foreign key is relative to an existing instance
 * @param array check the data to test
 * @return boolean
 */
	public function validateAroForeignKey($check) {
		return $this->isInstanceExisting($check['aro_foreign_key'], $this->data[$this->alias]['aro']);
	}
	
/**
 * Validation Rule : Check if the given permission type is valid
 * @param array check the data to test
 * @return boolean
 */
	public function validatePermissionType($check) {
		return $this->PermissionType->isValidSerial($check['type']);
	}

/**
 * Check if the given ACO key is an allowed ACO model
 * @param string aco The aco key to test
 * @return boolean
 */
	public function isValidAco($aco) {
		return in_array($aco, Configure::read('Permission.acoModels'));
	}
	
/**
 * Check if the given ARO key is an allowed ACO model
 * @param string aro The aro key to test
 * @return boolean
 */
	public function isValidAro($aro) {
		return in_array($aro, Configure::read('Permission.aroModels'));
	}

}
