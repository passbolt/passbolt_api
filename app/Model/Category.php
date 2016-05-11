<?php
/**
 * Category Controller
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('CategoryType', 'Model');
App::uses('Resource', 'Model');
App::uses('User', 'Model');
App::uses('PermissionType', 'Model');

class Category extends AppModel {

/**
 * Model behaviors
 *
 * @link http://api20.cakephp.org/class/model#
 */
	public $actsAs = [
		'Containable',
		'Trackable',
		'Permissionable' => [
			'priority' => 1
		],
		'Tree'
	];

/**
 * Details of has many relationships
 *
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $hasMany = [
		'CategoryResource'
	];

/**
 * Details of belongs relationships
 *
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $belongsTo = [
		'CategoryType' => [
			'className' => 'CategoryType'
		]
	];

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->Behaviors->setPriority(['Permissionable' => 1]);
	}

/**
 * Get the validation rules upon context
 *
 * @param string case (optional) The target validation case if any.
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case = 'default') {
		$default = [
			'id' => [
				'uuid' => [
					'rule' => 'uuid',
					'required' => 'update',
					'message' => __('Id must be in correct format')
				]
			],
			'name' => [
				'alphaNumeric' => [
					'rule' => "/^[\p{L}\d ,.:;!?\-_\(\[\)\]'&\/]*$/u",
					'required' => 'create',
					'message' => __('Name should only contain alphabets, numbers and the special characters : , . - _ ( ) [ ] \'')
				],
				'size' => [
					'rule' => ['between', 3, 64],
					'message' => __('Name should be between %s and %s characters long'),
				]
			],
			'parent_id' => [
				'exist' => [
					'rule' => ['parentExists', null],
					'allowEmpty' => true,
					'shared' => false,
					'message' => __('The parent provided does not exist')
				],
				'uuid' => [
					'rule' => 'uuid',
					'allowEmpty' => true,
					'required' => false,
					'message' => __('UUID must be in correct format')
				]
			],
			'position' => [
				'number' => [
					'rule' => 'numeric',
					'message' => __('The position must be a number')
				]
			],
			'category_type_id' => [
				'exist' => [
					'rule' => ['categoryTypeExists', null],
					'allowEmpty' => true,
					'message' => __('The category type provided does not exist')
				],
				'uuid' => [
					'rule' => 'uuid',
					'allowEmpty' => true,
					'required' => false,
					'message' => __('The category type id must be in correct format')
				]
			]
		];

		switch ($case) {
			default:
			case 'default':
				$rules = $default;
				break;
		}

		return $rules;
	}

/**
 * In the event of ambiguous results returned (multiple top level results, with different parent_ids)
 * top level results with different parent_ids to the first result will be dropped
 *
 * @param string $state
 * @param mixed $query
 * @param array $results
 * @return array Threaded results
 */
	protected function _findThreaded($state, $query, $results = []) {
		if ($state === 'before') {
			return $query;
		} elseif ($state === 'after') {
			// ATTENTION
			// results has to be ordered following Category.lft
			$n = count($results);
			// build parent hierarchy even if some nodes are missing. Based on left and right
			for ($i = $n - 1; $i >= 0; $i--) {
				for ($j = $i - 1; $j >= 0; $j--) {
					if ($results[$i]['Category']['lft'] > $results[$j]['Category']['lft'] && $results[$i]['Category']['rght'] < $results[$j]['Category']['rght']) {
						$results[$j]['children'][] = $results[$i];
						unset($results[$i]);
						break;
					}
				}
			}

			return $results;
		}
	}

/**
 * Get the subcategories of a given category
 *
 * @param {Category} $category The target category
 * @return array
 */
	public function getSubCategories($category = null) {
		$returnValue = [];
		if (!is_null($category)) {
			$returnValue = $this->find('all', [
				'conditions' => [
					'Category.lft >=' => $category['Category']['lft'],
					'Category.rght <=' => $category['Category']['rght']
				],
				'order' => ['Category.lft' => 'ASC']
			]);
		}

		return $returnValue;
	}

/**
 * Check if a category type with same id exists
 *
 * @param check
 */
	public function categoryTypeExists($check) {
		if ($check['category_type_id'] == null) {
			return true;
		} else {
			$exists = $this->CategoryType->find('count', [
				'conditions' => ['CategoryType.id' => $check['category_type_id']],
				'recursive' => -1
			]);

			return $exists > 0;
		}
	}

/**
 * Check if an element is a child of a parent (not necessarily an immediate child. can be several levels below)
 * Useful when parsing an array of results
 *
 * @param $elt , the element to check
 * @param $parent , the parent
 * @return true if element is a child, false otherwise
 */
	public function isChild($elt, $parent) {
		return ($elt['Category']['rght'] < $parent['Category']['rght']);
	}

/**
 * Check if an element is a leaf (no more children)
 *
 * @param $category , the category
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
 *
 * @param $objectType , the type of object given, whether a default cakePHP object or a Json converted one : 'default' or 'json'
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
 * move an element from a position to another in the tree
 * can be moved among its sieblings, can also change parent
 *
 * @param uuid $id the if of the category
 * @param integer $position the position from 1 to n
 * @param uuid $parentId the parent Id (if we wish to change it)
 * @return bool true or false
 *
 */
	public function move($id, $position, $parentId = null) {
		// First, manage the parent
		$category = $this->findById($id);
		if (!$category) {
			return false;
		}
		$parentId = ($parentId == null ? $category['Category']['parent_id'] : $parentId);
		if ($category['Category']['parent_id'] != $parentId) {
			$category['Category']['parent_id'] = $parentId;
			$category = $this->save($category);
			if (!$category) {
				return false;
			}
		}
		// then, manage the position
		$nbChildren = $this->childCount($parentId, true);
		// if the position is first one or last one
		if ($position == 1) {
			$result = $this->moveUp($id, true);
		} elseif ($position >= $nbChildren) {
			$result = $this->moveDown($id, true);
		} else {
			$currentPosition = $this->getPosition($id);
			$steps = $currentPosition - $position;
			if ($steps > 0) {
				$result = $this->moveUp($id, $steps);
			} else {
				$result = $this->moveDown($id, -($steps));
			}
		}

		return $result;
	}

/**
 * get the current position of a category among its sieblings, starting from 1
 *
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
		$children = $this->children($parent['Category']['id'], true, null, ['Category.lft' => 'ASC']);
		$currentPosition = 0;
		foreach ($children as $child) {
			$currentPosition++;
			if ($child['Category']['id'] == $id) {
				break;
			}
		}

		return $currentPosition;
	}

/**
 * Return the list of fields to be returned by a find operation in given context
 *
 * @param string $case context ex: login, activation
 * @param string $role optional user role if needed to build the options
 * @return array $condition
 * @access public
 */
	public static function getFindFields($case = 'get', $role = null) {
		switch ($case) {
			case 'view':
			case 'getChildren':
			case 'addResult':
			case 'index' :
			case 'getWithChildren' :
				$returnValue = [
					'fields' => [
						'id',
						'name',
						'parent_id',
						'category_type_id',
						'lft',
						'rght'
					],
					'contain' => ['CategoryType']
				];
				break;
			case 'Resource.viewByCategory':
				$returnValue = [
					'fields' => ['Category.id', 'Category.name', 'Category.parent_id'],
					'contain' => ['Resource' => Resource::getFindFields('view')]
				];
				break;
			case 'rename':
				$returnValue = ['fields' => ['name']];
				break;
			case 'add':
			case 'edit':
				$returnValue = ['fields' => ['name', 'parent_id', 'category_type_id']];
				break;
			default:
				$returnValue = ['fields' => []];
				break;
		}

		return $returnValue;
	}

/**
 * Return the find conditions to be used for a given context
 *
 * @param string case (optional) The target validation case if any.
 * @param string role
 * @param array data Used in find conditions (such as User.id)
 * @return array
 */
	public static function getFindConditions($case = 'get', $role = null, $data = null) {
		switch ($case) {
			case 'getWithChildren':
				$returnValue = [
					'conditions' => [
						'Category.lft >=' => $data['Category']['lft'],
						'Category.rght <=' => $data['Category']['rght']
					],
					'order' => 'Category.lft ASC'
				];
				break;
			case 'getChildren':
				$returnValue = [
					'conditions' => [
						'Category.lft >' => $data['Category']['lft'],
						'Category.rght <' => $data['Category']['rght']
					],
					'order' => 'Category.lft ASC'
				];
				break;
			case 'Resource.viewByCategory':
			case 'view':
			case 'addResult':
				$returnValue = ['conditions' => ['Category.id' => $data['Category']['id']]];
				break;
			case 'index':
				$returnValue = [
					'conditions' => ['Category.parent_id' => null],
					'order' => 'Category.lft ASC'
				];
				break;
			default:
				$returnValue = ['conditions' => []];
		}

		return $returnValue;
	}
}
