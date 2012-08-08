<?php
/**
 * Resource model
 *
 * @copyright     Copyright 2012 Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Model.Resource
 * @since         version 2.12.7
 */
class Resource extends AppModel {
	public $actsAs = array('Containable');
	
	public $hasAndBelongsToMany = array(
  'Category' =>
   	array(
     'className'              => 'Category',
     'joinTable'              => 'categories_resources',
     'foreignKey'             => 'resource_id',
     'associationForeignKey'  => 'category_id',
     'unique'                 => false,
     'conditions'             => '',
     'fields'                 => '',
     'order'                  => '',
     'limit'                  => '',
     'offset'                 => '',
     'finderQuery'            => '',
     'deleteQuery'            => '',
     'insertQuery'            => ''
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
						'required' => true,
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
				case 'default' :
					$rules = $default;
			}
			return $rules;
	}

	/**
	 * Validates if a date is in future
	 * @param array $check the parameters
	 * @return bool true if the date is in future, false otherwise
	 */
	function isInFuture($check) {
		  $now = time();
    $expiryDate = strtotime($check['expiry_date']);
    $interval = $expiryDate - $now;
    return ($interval > 0) ;
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
			Resource::getFindConditions($case,&$data),
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
			case 'view':
				$conditions = array(
					'conditions' => array('Resource.deleted'=>0)
				);
			break;
			case 'viewByCategory':
				$conditions = array(
					'conditions' => array('CategoryResource.category_id'=>$data['CategoryResource.category_id'], 'Resource.deleted'=>0)
				);
			break;
			default:
				$conditions = array(
					'conditions' => array('CategoryResource.category_id'=>$data['CategoryResource.category_id'])
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
			case 'add':
				$fields = array(
					'fields' => array(
						'Resource.id', 'Resource.name', 'Resource.username', 'Resource.expiry_date', 'Resource.uri', 'Resource.description', 'CategoryResource.category_id'
					)
				);
			break;
			default:
				$fields = array('fields' => array());
			break;
		}
		return $fields;
	}
	
	
}
