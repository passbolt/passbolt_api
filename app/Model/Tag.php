<?php
/**
 * Tag Model
 *
 * Copyright 2012, Passbolt
 * Passbolt(tm), the simple password management solution 
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright		 Copyright 2012, Passbolt.com
 * @package			 app.Model.role
 * @since				 version 2.12.11
 * @license			 http://www.passbolt.com/license
 */
class Tag extends AppModel {

	public $actsAs = array('Trackable');

	public $hasAndBelongsToMany = array(
		'Resource' => array (
			'className' => 'Resource'
		)
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
					'rule'		 => '/^.{2,36}$/i',
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

}
