<?php
/**
 * Comment Model
 *
 * Copyright 2012, Passbolt
 * Passbolt(tm), the simple password management solution 
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright		 Copyright 2012, Passbolt.com
 * @package			 app.Model.comment
 * @since				 version 2.12.11
 * @license			 http://www.passbolt.com/license
 */
class Comment extends AppModel {

	public $actsAs = array('Trackable');

/**
 * Get the validation rules upon context
 * @param string context
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case='default') {
		$default = array(
			'id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'allowEmpty' => true,
					'required' => false,
					'message'	=> __('UUID must be in correct format')
				)
			),
			'parent_id' => array(
				'exist' => array(
					'rule' => array('parentExists', null),
					'allowEmpty' => true,
					'message' => __('The parent provided does not exist')
				),
				'uuid' => array(
					'rule' => 'uuid',
					'allowEmpty' => true,
					'required' => false,
					'message'	=> __('UUID must be in correct format')
				)
			),
			'foreign_id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('UUID of the foreign key must be in correct format')
				),
				'exist' => array(
					'rule' => array('foreignExists', null),
					'message' => __('The resource provided does not exist')
				),
			),
			'foreign_model' => array(
				'inlist' => array(
					'required' => true,
					'allowEmpty' => false,
					'rule' => array('inList', array('Resource')),
					'message' => __('Please enter a valid model name')
				)
			),
			'content' => array(
				'alphaNumeric' => array(
					'rule' => '/^([\pL\s\.\!\,0-9]){1,256}$/u',
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
		}
		return $rules;
	}

/**
 * Check if a resource with same id exists
 * @param check
 */
	public function foreignExists($check) {
		if ($this->data['Comment']['foreign_model'] == null) {
			return false;
		}
		if ($check['foreign_id'] == null) {
			return false;
		}
		$m = $this->data['Comment']['foreign_model'];
		$model = Common::getModel($m);
		$exists = $model->find('count', array(
				'conditions' => array('id' => $check['foreign_id']),
				 'recursive' => -1
		));
		return $exists > 0;
	}

}
