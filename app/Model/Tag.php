<?php

/**
 * Tag Model
 *
 * Copyright 2012, Passbolt
 * Passbolt(tm), the simple password management solution
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright         Copyright 2012, Passbolt.com
 * @package             app.Model.role
 * @since                 version 2.12.11
 * @license             http://www.passbolt.com/license
 */
class Tag extends AppModel {

	public $actsAs = array('Trackable');

	public $hasAndBelongsToMany = array(
		'Resource' => array(
			'className' => 'Resource'
		)
	);

/**
 * Get the validation rules upon context
 *
 * @param string context
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case = 'default') {
		$default = array(
			'id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'message' => __('UUID must be in correct format')
				)
			),
			'name' => array(
				'alphaNumeric' => array(
					'required' => 'create',
					'allowEmpty' => false,
					'rule' => "/^[\p{L}\d '\"-]*$/u",
					'message' => __('Name should only contain alphabets, numbers, spaces and the special characters \' " -')
				),
				'size' => array(
					'rule' => array('between', 3, 64),
					'message' => __('Name should be between %s and %s characters long'),
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
 * @param string case (optional) The target case if any.
 * @param string role
 * @param array data Used in find conditions (such as User.id)
 * @return array
 */
	public static function getFindConditions($case = 'Tag.view', $role = Role::USER, $data = null) {
		$returnValue = array();
		switch ($case) {
			case 'ItemTag.viewByForeignModel':
				$returnValue = array(
					'conditions' => array(
						'Tag.id' => $data['Tag']['id']
					)
				);
				break;
		}

		return $returnValue;
	}

/**
 * Return the list of field to fetch for given context
 *
 * @param string $case context ex: login, activation
 * @return $condition array
 */
	public static function getFindFields($case = 'view', $role = Role::USER) {
		$returnValue = array('fields' => array());
		switch ($case) {
			case 'ItemTag.viewByForeignModel':
				$returnValue = array(
					'fields' => array(
						'Tag.id',
						'Tag.name',
						'Tag.created',
						'Tag.modified',
						'Tag.created_by',
						'Tag.modified_by'
					)
				);
				break;
		}
		return $returnValue;
	}

}
