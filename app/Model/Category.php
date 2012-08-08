<?php
/**
 * Category Controller
 *
 * @copyright		 Copyright 2012, Passbolt.com
 * @package			 app.Model.Category
 * @since				 version 2.12.7
 * @license			 http://www.passbolt.com/license
 */
App::uses('CategoryType', 'Model');
	
class Category extends AppModel {

/**
 * Model behave as a tree with left, right, parent_id
 */
	public $actsAs = array('Tree');

/**
 * Get the validation rules upon context
 * @param string context
 * @return array cakephp validation rules
 */
	public static function getValidationRules($context='default') {
		$rules = array(
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
					'message'	=> __('Alphanumeric only')
				)
			),
			'parent_id' => array(
				'exist' => array(
					'rule'		=> array('parentExists', null),
					'allowEmpty' => true,
					'message' => __('The parent provided does not exist')
				),
				'uuid' => array(
					'rule'		 => 'uuid',
					'allowEmpty' => true,
					'required' => false,
					'message'	=> __('UUID must be in correct format')
				)
			),
			'position' => array(
				'number' => array(
					'rule'		=> 'numeric',
					'message' => __('The position must be a number')
				)
			),
			'category_type_id' => array(
				'exist' => array(
					'rule'		=> array('categoryTypeExists', null),
					'allowEmpty' => true,
					'message' => __('The category type provided does not exist')
					),
				'uuid' => array(
					'rule'		 => 'uuid',
					'allowEmpty' => true,
					'required' => false,
					'message'	=> __('UUID must be in correct format')
				)
				)
		);
		

		/* a context switch if needed
		switch ($context) {
			default:
				unset($rules['rule']);
			break;
		}*/

		return $rules;
	}

/**
 * Check if a category with same id exists
 * @param check
 */
	public function parentExists($check) {
		if ($check['parent_id'] == null) {
			return true;
		} else {
			$exists = $this->find('count', array(
				'conditions' => array('Category.id' => $check['parent_id']),
				 'recursive' => -1
			));
			return $exists > 0;
		}
	}

/**
 * Check if a category type with same id exists
 * @param check
 */
	public function categoryTypeExists($check) {
		if ($check['category_type_id'] == null) {
			return true;
		} else {
			$categoryTypeModel = new CategoryType();
			$exists = $categoryTypeModel->find('count', array(
				'conditions' => array('CategoryType.id' => $check['category_type_id']),
				 'recursive' => -1
			));
			return $exists > 0;
		}
	}

/**
 * Converts an array of categories into a proper nested tree
 * @param array $categories, the list as it is returned by find() or children()
 * @param array $options the options.
 *				- array fields , the required fields (will remove unnecessary fields at the output)
 *				- bool position, whether or not to add a position field to specify the position of the categories among the sieblings
 * @return : Nested objects representing the category :
 * category{
 *		id : char[36]
 * 		name:string
 *		parent_id:int
 *		position:int
 * 		children:categories array
 * }
 */
	public function results2Tree($categories, $options=null) {
		$stack = array();
		$clones = $categories;

		// define which keys are extra to remove them later
		$extraFields = array();
		if (!empty($options) && isset($options['fields']['fields'])) {
			foreach ($categories[0]['Category'] as $key => $V) {
				if (!in_array("Category.$key", $options['fields']['fields'])) {
					$extraFields[] = $key;
				}
			}
		}

		foreach ($categories as $i => $cat) {
			//$clones[$i] = $clones[$i]['Category']; // remove the 'Category' level
			//unset($clones[$i]['Category']);
			$clones[$i]['Category']['children'] = array(); // add the children row
			// process here the format of the element so we keep only the required fields
			foreach ($extraFields as $extraF) {
				unset($clones[$i]['Category'][$extraF]);
			}

			$categories[$i]['Category']['copyref'] = & $clones[$i];
			$i = count($stack) - 1;
			while ($stack && !$this->isChild($cat, $stack[$i])) {
				// if element is not a child of the stack
				// we remove previous stack from the array
				array_pop($stack);
				$i = count($stack) - 1;
			}

			if (!$this->isTopLevelElement($cat, $categories)) {
				$stack[count($stack) - 1]['Category']['copyref']['Category']['children'][] = &$clones[$i];
			}
			$stack[] = $categories[$i];
		}

		if (empty($clones[0])) {
			return array();
		}
		// Put the final result in $res
		$res = array();
		// Place only the top level elements, and ignore the
		foreach ($clones as $k => $r) {
			if ($this->isTopLevelElement($r, $clones)) {
				$res[] = $r;
			}
		}
		return $res;
	}

/**
 * Check if an element is a child of a parent (not necessarily an immediate child. can be several levels below)
 * Useful when parsing an array of results
 * @param $elt, the element to check
 * @param $parent, the parent
 * @return true if element is a child, false otherwise
 */
	public function isChild($elt, $parent) {
		return ($elt['Category']['rght'] < $parent['Category']['rght']);
	}

/**
 * Check if an element is a leaf (no more children)
 * @param $category, the category
 * @return true if the category is a leaf. false otherwise.
 */
	public function isLeaf($category) {
		if ($category['Category']['lft'] + 1 == $category['Category']['rght']) {
			return true;
		}
		return false;
	}

/**
 * Check if an element is at the top level of the given branch
 * @param $objectType, the type of object given, whether a default cakePHP object or a Json converted one : 'default' or 'json'
 */
	public function isTopLevelElement($category, $categories) {
		$parentId = $category['Category']['parent_id'];
		foreach ($categories as $c) {
			if ($c['Category']['id'] == $parentId) {
				return false;
			}
		}
		return true;
	}

/**
 * get the current position of a category among its sieblings, starting from 1
 * @param uuid $id the id of the category
 * @return the current position starting from 1, false if category doesnt exist
 */
	public function getPosition($id) {
		// check if the category exist
		$category = $this->findById($id);
		if (!$category) {
			return false;
		}
		$parent = $this->findById($category['Category']['parent_id']);
		// calculate the current position
		$children = $this->children(
			$parent['Category']['id'], true, null,
			array('Category.lft' => 'ASC')
		);
		$currentPosition = 0;
		foreach ($children as $child) {
			$currentPosition++;
			if ($child['Category']['id'] == $id) break;
		}
		return $currentPosition;
	}

/**
 * Return the list of field to fetch for given context
 * @param string $case context ex: login, activation
 * @return $condition array
 */
	public static function getFindFields($case = 'get') {
		switch($case){
			case 'get':
			case 'getChildren':
			case 'add':
				$fields = array(
					'fields' => array(
						'Category.id', 'Category.name', 'Category.parent_id'
					)
				);
			break;
			default:
				$fields = array('fields' => array());
			break;
		}
		return $fields;
	}

/**
 * Return the find conditions to be used for a given context
 *
 * @param $context string
 * @param $data that will be used in find conditions
 * @return $condition array
 * @access public
 */
	public static function getFindConditions($case = 'get', &$data = null) {
		switch ($case) {
			case 'getWithChildren':
				$c = array(
					'conditions' => array(
						'Category.lft >=' => $data['Category']['lft'],
						'Category.rght <=' => $data['Category']['rght']
					),
					'order' => 'lft ASC'
				);
			case 'getChildren':
				$c = array(
					'conditions' => array(
						'Category.lft >' => $data['Category']['lft'],
						'Category.rght <' => $data['Category']['rght']
					),
					'order' => 'lft ASC'
				);
			break;
			case 'get':
			default:
				$c = array('conditions' => array());
		}
		return $c;
	}
}
