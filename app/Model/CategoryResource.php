<?php
/**
 * Category Resource Model
 *
 * @copyright		 Copyright 2012, Passbolt.com
 * @package			 app.Model.CategoryResource
 * @since				 version 2.12.7
 * @license			 http://www.passbolt.com/license
 */

App::uses('Category', 'Model');
App::uses('Resource', 'Model');

class CategoryResource extends AppModel {

	public $useTable = "categories_resources";

	public $belongsTo = array(
		'Category', 'Resource'
	);

	public $actsAs = array('Trackable');

/**
 * Get the validation rules upon context
 * @param string context
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case='default') {
		$default = array(
			'category_id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('UUID must be in correct format')
				),
				'exist' => array(
					'rule' => array('categoryExists', null),
					'message' => __('The category type provided does not exist')
				)
			),
			'resource_id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('UUID must be in correct format')
				),
				'exist' => array(
					'rule' => array('resourceExists', null),
					'message' => __('The category type provided does not exist')
				)
			)
		);
		switch ($case) {
			default:
			case 'default' :
				$rules = $default;
		}
		return $rules;
	}

/**
 * Check if a category with same id exists
 * @param check
 */
	public function categoryExists($check) {
		if ($check['parent_id'] == null) {
			return false;
		} else {
			$exists = $this->Category->find('count', array(
				'conditions' => array('Category.id' => $check['category_id']),
				 'recursive' => -1
			));
			return $exists > 0;
		}
	}

/**
 * Check if a category with same id exists
 * @param check
 */
	public function resourceExists($check) {
		if ($check['parent_id'] == null) {
			return false;
		} else {
			$exists = $this->Resource->find('count', array(
				'conditions' => array('Resource.id' => $check['resource_id']),
				 'recursive' => -1
			));
			return $exists > 0;
		}
	}
}
