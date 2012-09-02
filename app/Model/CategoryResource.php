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

	public var $useTable = "categories_resources";

	public $belongsTo = array(
		'Category', 'Resource'
	);

/**
 * Get the validation rules upon context
 * @param string context
 * @return array cakephp validation rules
 */
	public static function getValidationRules($context='default') {
		$rules = array(
			'category_id' => array(
				'exist' => array(
					'rule' => array('categoryTypeExists', null),
					'allowEmpty' => true,
					'message' => __('The category type provided does not exist')
				),
				'uuid' => array(
					'rule' => 'uuid',
					'allowEmpty' => true,
					'required' => false,
					'message'	=> __('UUID must be in correct format')
				)
			),
			'resource_id' => array(
				'exist' => array(
					'rule' => array('categoryTypeExists', null),
					'allowEmpty' => true,
					'message' => __('The category type provided does not exist')
				),
				'uuid' => array(
					'rule' => 'uuid',
					'allowEmpty' => true,
					'required' => false,
					'message'	=> __('UUID must be in correct format')
				)
			)
		);
	}

/**
 * Check if a category with same id exists
 * @param check
 */
	public function categoryExists($check) {
		if ($check['parent_id'] == null) {
			return false;
		} else {
			$categoryM = new Category();
			$exists = $this->find('count', array(
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
			$resourceM = new Resource();
			$exists = $resourceM->find('count', array(
				'conditions' => array('Resource.id' => $check['resource_id']),
				 'recursive' => -1
			));
			return $exists > 0;
		}
	}
}
