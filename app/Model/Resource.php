<?php
/**
 * Resource model
 *
 * @copyright     Copyright 2012 Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Model.Resource
 * @since         version 2.12.7
 */

App::uses('User', 'Model');

class Resource extends AppModel {

	public $actsAs = array('Containable', 'Trackable');

	public $hasMany = array(
		'CategoryResource'
	);

	public $hasOne = array(
		'Secret'
	);

	public $hasAndBelongsToMany = array(
		'Category' => array (
			'className' => 'Category'
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
						'rule'		 => '/^.{2,64}$/i',
						'required' => true,
						'allowEmpty' => false,
						'message'	=> __('Alphanumeric only')
					)
				),
				'username' => array(
					'alphaNumeric' => array(
						'rule'		 => '/^.{2,64}$/i',
						'required' => true,
						'message'	=> __('Alphanumeric only')
					)
				),
				'expiry_date' => array(
					'date' => array(
						'required' => false,
						'allowEmpty' => true,
						'rule' => array('date', 'ymd'),
						'message' => __('Please indicate a valid date')
					),
					'infuture' => array(
						'rule' => array('isInFuture'),
						'message' => __('The date should be in the future.')
					),
				),
				'uri' => array(
					'alphaNumeric' => array(
						'rule'		 => '/^.{2,64}$/i',
						'required' => false,
						'allowEmpty' => true,
							'message'	=> __('Alphanumeric only')
					)
				),
				'description' => array(
					'alphaNumeric' => array(
					'rule'		 => '/^[^<>]*$/i',
					'required' => false,
					'allowEmpty' => true,
					'message'	=> __('Text only. No HTML allowed')
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
 * Return the find options to be used
 *
 * @param string context
 * @return array
 * @access public
 */
	public static function getFindOptions($case,&$data = null) {
		return array_merge(
			Resource::getFindConditions($case, &$data),
			Resource::getFindFields($case)
		);
	}

/**
 * Return the conditions to be used for a given context
 *
 * @param $context string{guest or id}
 * @param $data used in find conditions (such as User.id)
 * @return $condition array
 * @access public
 */
	public static function getFindConditions($case = 'view', &$data = null) {
		$conditions = array();
		switch ($case) {
			case 'add':
			case 'edit':
			case 'view':
				$conditions = array(
					'conditions' => array(
						'Resource.deleted' => 0,
						'Resource.id' => $data['Resource.id']
					)
				);
			break;
			case 'viewByCategory':
				$conditions = array(
					'conditions' => array(
						'CategoryResource.category_id' => $data['CategoryResource.category_id'],
						'Resource.deleted' => 0
					),
					'order' => array(
						'Resource.name ASC'
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
	public static function getFindFields($case = 'view') {
		switch($case){
			case 'view':
			case 'viewByCategory':
				$fields = array(
					'fields' => array(
						'Resource.id', 'Resource.name', 'Resource.username', 'Resource.expiry_date', 'Resource.uri', 'Resource.description', 'Resource.modified', 'Secret.data', 'created', 'modified'
					),
					'contain' => array(
						'CategoryResource',
						'Category',
						'Secret'
					)
				);
			break;
			case 'delete':
				$fields = array('fields' => array('deleted'));
			break;
			case 'save':
				$fields = array('fields' => array('name', 'username', 'expiry_date', 'uri', 'description', 'created', 'modified', 'created_by', 'modified_by', 'deleted'));
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
