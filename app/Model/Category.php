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

/**
 * Category constructor
 *
 * @param bool|false $id  The id to start the model on.
 * @param null $table The table to use for this model.
 * @param null $ds The connection name this model is connected to.
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->Behaviors->setPriority(['Permissionable' => 1]);
	}

/**
 * Get the validation rules for a given context
 *
 * @param string $case (optional) The target validation case if any.
 * @return array validation rules
 * @access public
 */
	public static function getValidationRules($case = null) {
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
		return $default;
	}

/**
 * Find threaded redefinition
 * Warning: results has to be ordered following Category.lft
 * In the event of ambiguous results returned (multiple top level results, with different parent_ids)
 * top level results with different parent_ids to the first result will be dropped
 *
 * @param string $state Either "before" or "after".
 * @param array $query Query.
 * @param array $results Results.
 * @return array Threaded results
 */
	protected function _findThreaded($state, $query, $results = []) {
		if ($state === 'before') {
			return $query;
		} elseif ($state === 'after') {
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
 * Custom validation rule
 *
 * @param array $check with category_type_id key id set
 * @return bool
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
 * @param array $category the category to check
 * @param array $parent parent category to check
 * @return true if element is a child, false otherwise
 */
	public function isChild($category, $parent) {
		return ($category['Category']['rght'] < $parent['Category']['rght']);
	}

/**
 * Check if an element is a leaf (no more children)
 *
 * @param array $category the category to check
 * @return true if the category is a leaf. false otherwise.
 */
	public function isLeaf($category) {
		return ($category['Category']['lft'] + 1 == $category['Category']['rght']);
	}

/**
 * move an element from a position to another in the tree
 * can be moved among its siblings, can also change parent
 *
 * @param string $id uuid of the category
 * @param int $position the position from 1 to n
 * @param string $parentId the parent uuid (if we wish to change it)
 * @return bool true or false
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
 * @return array $fields
 * @access public
 */
	public static function getFindFields($case = 'get', $role = null) {
		switch ($case) {
			case 'view':
			case 'getChildren':
			case 'addResult':
			case 'index' :
			case 'getWithChildren' :
				$fields = [
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
				$fields = [
					'fields' => ['Category.id', 'Category.name', 'Category.parent_id'],
					'contain' => ['Resource' => Resource::getFindFields('view')]
				];
				break;
			case 'rename':
				$fields = ['fields' => ['name']];
				break;
			case 'add':
			case 'edit':
				$fields = ['fields' => ['name', 'parent_id', 'category_type_id']];
				break;
			default:
				$fields = ['fields' => []];
				break;
		}

		return $fields;
	}

/**
 * Return the find conditions to be used for a given context
 *
 * @param string $case (optional) The target validation case if any.
 * @param string $role (optional) Role name
 * @param array $data Used in find conditions (such as User.id)
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
