<?php
/**
 * Group  model
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Model.Group
 * @since        version 2.12.7
 */
App::uses('User', 'Model');

class Group extends AppModel {

	public $actsAs = array('Trackable');

	public $hasMany = array(
		'GroupUser'
	);

/**
 * Get the validation rules upon context
 * @param string context
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case='default') {
		$default = array(
			'id' => array(
				'uuid' => array(
					'rule'		 => 'uuid',
					'message'	=> __('UUID must be in correct format')
				)
			),
			'name' => array(
				'alphaNumeric' => array(
					'rule'		 => '/^[a-zA-Z0-9]{1,64}$/i',
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('Alphanumeric only')
				)
			)
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
 * Return the conditions to be used for a given context
 *
 * @param $context string{guest or id}
 * @param $data used in find conditions (such as Group.id)
 * @return $condition array
 * @access public
 */
	public static function getFindConditions($case = 'view',  $role = Role::ANONYMOUS, &$data = null) {
		$conditions = array();
		switch ($case) {
			case 'add':
			case 'edit':
			case 'view':
				$conditions = array(
					'conditions' => array(
						'Group.deleted' => 0,
						'Group.id' => $data['Group.id']
					)
				);
			break;
			case 'index':
			default:
				$conditions = array(
					'conditions' => array()
				);
		}
		//var_dump($conditions);
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
			case 'index':
				$fields = array(
					'fields' => array(
						'id', 'name', 'created', 'modified'
					)
				);
			break;
			case 'delete':
				$fields = array('fields' => array('deleted'));
			break;
			case 'save':
				$fields = array('fields' => array('name', 'created', 'modified', 'created_by', 'modified_by', 'deleted'));
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
