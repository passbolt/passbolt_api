<?php
/**
 * Secret model
 *
 * @copyright     Copyright 2012 Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Model.Secret
 * @since         version 2.12.7
 */
App::uses('User', 'Model');
App::uses('Resource', 'Model');

class Secret extends AppModel {

	public $actsAs = array('Containable', 'Trackable');

	public $belongsTo = array(
		'Resource',
		'User',
	);

/**
 * Get the validation rules upon context
 * @param string context
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case='default') {
		$default = array(
			'user_id' => array(
				'uuid' => array(
					'rule'		 => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('UUID must be in correct format')
				),
				'exist' => array(
					'rule' => array('userExists', null),
					'message' => __('The resource provided does not exist')
				),
			),
			'resource_id' => array(
				'uuid' => array(
					'rule'		 => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('UUID must be in correct format')
				),
				'exist' => array(
					'rule' => array('resourceExists', null),
					'message' => __('The resource provided does not exist')
				),
			),
			'data' => array(
				'isNotEmpty' => array(
					'rule' => 'notEmpty',
					'required' => true,
					'message'	=> __('data must be provided')
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
 * Check if a resource with same id exists
 * @param check
 */
	public function resourceExists($check) {
		if ($check['resource_id'] == null) {
			return false;
		} else {
			$exists = $this->Resource->find('count', array(
				'conditions' => array('Resource.id' => $check['resource_id']),
				 'recursive' => -1
			));
			return $exists > 0;
		}
	}

/**
 * Check if a user with same id exists
 * @param check
 */
	public function userExists($check) {
		if ($check['user_id'] == null) {
			return false;
		} else {
			$exists = $this->User->find('count', array(
				'conditions' => array('User.id' => $check['user_id']),
				 'recursive' => -1
			));
			return $exists > 0;
		}
	}

/**
 * Return the conditions to be used for a given context
 *
 * @param $context string{guest or id}
 * @param $data used in find conditions (such as User.id)
 * @return $condition array
 * @access public
 */
	public static function getFindConditions($case = 'view', $role = Role::USER, &$data = null) {
		$conditions = array();
		switch ($case) {
			case 'add':
			case 'edit':
			case 'view':
				$conditions = array(
					'conditions' => array(
					)
				);
			break;
			default:
				$conditions = array(
					'conditions' => array()
				);
		}
		return $conditions;
	}

/**
 * Return the list of field to fetch for given context
 * @param string $case context ex: login, activation
 * @return $condition array
 */
	public static function getFindFields($case = 'view', $role = Role::USER) {
		switch($case){
			case 'view':
				$fields = array('fields' => array('id', 'user_id', 'resource_id', 'data', 'created', 'modified', 'created_by', 'modified_by'));
			break;
			case 'save':
				$fields = array('fields' => array('user_id', 'resource_id', 'data', 'created', 'modified', 'created_by', 'modified_by'));
			break;
			default:
				$fields = array(
					'fields' => array()
				);
			break;
		}
		return $fields;
	}
}
