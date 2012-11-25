<?php
/**
 * Tag Resource Model
 *
 * @copyright		 Copyright 2012, Passbolt.com
 * @package			 app.Model.TagResource
 * @since				 version 2.12.11
 * @license			 http://www.passbolt.com/license
 */

App::uses('Tag', 'Model');
App::uses('Resource', 'Model');

class TagResource extends AppModel {

	public $useTable = "tags_resources";

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
			'resource_id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('UUID must be in correct format')
				),
				'exist' => array(
					'rule' => array('resourceExists', null),
					'message' => __('The resource provided does not exist')
				),
				'uniqueCombi' => array(
					'rule' => array('uniqueCombi', null),
					'message' => __('The TagResource entered is a duplicate')
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
 * Check if a Tag / Resource association don't already exist
 * @param check
 */
	public function uniqueCombi($check = null) {
		$tr = $this->data['TagResource'];
		$combi = array(
			'TagResource.Tag_id' => $tr['tag_id'],
			'TagResource.resource_id' => $tr['resource_id']
		);
		$result = $this->Resource->find('count', $combi);
		return $result == 0;
	}

}
