<?php
/**
 * Tag Resource Model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('Tag', 'Model');
App::uses('Resource', 'Model');

class ItemTag extends AppModel {

/**
 * Details of use table
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html
 */
	public $useTable = 'items_tags';

/**
 * Model behaviors
 * @link http://api20.cakephp.org/class/model#
 */
	public $actsAs = array('Trackable');

/**
 * Details of belongs to relationships
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $belongsTo = array(
		'Resource' => array(
			'foreignId' => 'foreign_id',
		),
		'Tag' => array(
			'foreignId' => 'tag_id',
		)
	);

/**
 * Get the validation rules upon context
 *
 * @param string $case (optional) The target validation case if any.
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case = 'default') {
		$default = array(
			'id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => false,
					'allowEmpty' => true,
					'message' => __('UUID must be in correct format')
				)
			),
			'tag_id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message' => __('UUID must be in correct format')
				),
				'exist' => array(
					'rule' => array('tagExists', null),
					'message' => __('The Tag provided does not exist')
				)
			),
			'foreign_model' => array(
				'alphaNumeric' => array(
					'rule' => '/^.{2,36}$/i',
					'required' => true,
					'allowEmpty' => false,
					'message' => __('Alphanumeric only')
				),
				'inList' => array(
					'required' => true,
					'allowEmpty' => false,
					'rule' => 'validateForeignModel',
					'message' => __('Please enter a valid model name')
				)
			),
			'foreign_id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message' => __('UUID must be in correct format')
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
 *
 * @param $check
 * @return bool
 */
	public function tagExists($check) {
		if ($check['tag_id'] == null) {
			return false;
		} else {
			$exists = $this->Tag->find('count', array('conditions' => array('Tag.id' => $check['tag_id'])));
			return $exists > 0;
		}
	}

/**
 * Check if an item with same id exists
 *
 * @param $check
 * @return bool
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
 *
 * @param $check
 * @return bool
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

/**
 * Check if the given foreign model is allowed
 *
 * @param string foreignModel The foreign model key to test
 * @return boolean
 */
	public function isValidForeignModel($foreignModel) {
		return in_array($foreignModel, Configure::read('ItemTag.foreignModels'));
	}

/**
 * Validation Rule : Check if the given foreign model is allowed
 *
 * @param array check the data to test
 * @return boolean
 */
	public function validateForeignModel($check) {
		return $this->isValidForeignModel($check['foreign_model']);
	}

/**
 * Return the conditions to be used for a given context
 *
 * @param string case (optional) The target case if any.
 * @param string role
 * @param array data Used in find conditions (such as User.id)
 * @return array
 */
	public static function getFindConditions($case = 'view', $role = Role::USER, $data = null) {
		$conditions = array();
		switch ($case) {
			case 'ItemTag.viewByForeignModel':
				$conditions = array(
					'conditions' => array(
						'ItemTag.foreign_id' => $data['ItemTag']['foreign_id']
						// @todo maybe check here if user has right to access the foreign instance, in this case we need the model to make a join with the convient permission view table
					),
					'order' => array('ItemTag.created desc')
				);
				break;
			case 'ItemTag.view':
				$conditions = array(
					'conditions' => array(
						'ItemTag.id' => $data['ItemTag']['id']
						// @todo maybe check here if user has right to access the foreign instance, in this case we need the model to make a join with the convient permission view table
					)
				);
				break;
			default:
				$conditions = array('conditions' => array());
		}

		return $conditions;
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
			case 'ItemTag.view':
			case 'ItemTag.viewByForeignModel':
				$returnValue = array(
					'fields' => array(
						'ItemTag.id',
						'ItemTag.tag_id',
						'ItemTag.foreign_model',
						'ItemTag.foreign_id',
						'ItemTag.created',
						'ItemTag.created_by',
					),
					'contain' => array(
						'Tag' => Tag::getFindFields('Tag.view', User::get('Role.name'))
					)
				);
				break;
			case 'ItemTag.add':
				$returnValue = array(
					'fields' => array(
						'foreign_model',
						'foreign_id',
						'tag_id',
						'created',
						'modified',
						'created_by',
						'modified_by',
					)
				);
				break;
			case 'ItemTag.edit':
				$returnValue = array('fields' => array('content'));
				break;
		}
		return $returnValue;
	}

}
