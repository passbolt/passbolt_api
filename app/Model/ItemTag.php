<?php
/**
 * Tag Resource Model
 *
 * @copyright		 Copyright 2012, Passbolt.com
 * @package			 app.Model.ResourceTag
 * @since				 version 2.12.11
 * @license			 http://www.passbolt.com/license
 */

App::uses('Tag', 'Model');
App::uses('Resource', 'Model');

class ItemTag extends AppModel {

	public $useTable = "items_tags";

	public $belongsTo = array(
		'Tag', 'Resource'
	);

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
					'required' => false,
					'allowEmpty' => true,
					'message'	=> __('UUID must be in correct format')
				)
			),
			'tag_id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('UUID must be in correct format')
				),
				'exist' => array(
					'rule' => array('tagExists', null),
					'message' => __('The Tag provided does not exist')
				)
			),
			'foreign_model' => array(
				'alphaNumeric' => array(
					'rule'		 => '/^.{2,36}$/i',
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('Alphanumeric only')
				)
			),
			'foreign_id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('UUID must be in correct format')
				),
				'exist' => array(
					'rule' => array('itemExists', null),
					'message' => __('The resource provided does not exist')
				),
				'uniqueCombi' => array(
					'rule' => array('uniqueCombi', null),
					'message' => __('The tag and resource combination entered is a duplicate')
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
 * Check if a Tag with same id exists
 * @param check
 */
	public function tagExists($check) {
		if ($check['tag_id'] == null) {
			return false;
		} else {
			$exists = $this->Tag->find('count', array(
				'conditions' => array('Tag.id' => $check['tag_id'])
			));
			return $exists > 0;
		}
	}

/**
 * Check if an item with same id exists
 * @param check
 */
	public function itemExists($check) {
		$tr = $this->data['ItemTag'];
		if ($check['foreign_id'] == null) {
			return false;
		} else {
			$Item = ClassRegistry::init($tr['foreign_model']);
			$exists = $Item->find('count', array(
				'conditions' => array($tr['foreign_model'] . '.id' => $check['foreign_id']),
				 'recursive' => -1
			));
			return $exists > 0;
		}
	}

/**
 * Check if a Tag / Item association don't already exist
 * @param check
 */
	public function uniqueCombi($check = null) {
		$tr = $this->data['ItemTag'];
		$combi = array(
			'ItemTag.tag_id' => $tr['tag_id'],
			'ItemTag.foreign_model' => $tr['foreign_model'],
			'ItemTag.foreign_id' => $check['foreign_id']
		);
		//pr($combi);
		//pr($this->find('all'));
		$result = $this->find('count', array('conditions' => $combi));
		//var_dump($result);
		return $result == 0;
	}

}
