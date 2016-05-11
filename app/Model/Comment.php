<?php
/**
 * Comment Model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

class Comment extends AppModel {

/**
 * Model behaviors
 *
 * @link http://api20.cakephp.org/class/model#
 */
	public $actsAs = ['Trackable'];

/**
 * Details of belongs to relationships
 *
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $belongsTo = [
		'Resource' => [
			'foreignId' => 'aco_foreign_key'
		],
		'Creator' => [
			'className' => 'User',
			'foreignKey' => 'created_by'
		],
		'Modifier' => [
			'className' => 'User',
			'foreignKey' => 'modified_by'
		]
	];

/**
 * Details of has many relationships
 *
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $hasMany = [
		'Comment' => [
			'className' => 'Comment',
			'foreignKey' => 'parent_id',
			'dependent' => true // Allow developpers to delete children comments when a parent is deleted
		]
	];

/**
 * Get the validation rules upon context
 *
 * @param string $case (optional) The target validation case if any.
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case = 'default') {
		$default = [
			'id' => [
				'uuid' => [
					'rule' => 'uuid',
					'allowEmpty' => true,
					'required' => false,
					'message' => __('UUID must be in correct format')
				]
			],
			'parent_id' => [
				'uuid' => [
					'rule' => 'uuid',
					'allowEmpty' => true,
					'required' => false,
					'message' => __('UUID must be in correct format')
				],
				'exist' => [
					'shared' => false,
					'rule' => ['parentExists', null],
					'message' => __('The parent provided does not exist')
				],
			],
			'foreign_id' => [
				'uuid' => [
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message' => __('UUID of the foreign key must be in correct format')
				],
				'exist' => [
					'shared' => false,
					'rule' => ['foreignExists', null],
					'message' => __('The resource provided does not exist')
				],
			],
			'foreign_model' => [
				'inlist' => [
					'shared' => false,
					'required' => true,
					'allowEmpty' => false,
					'rule' => 'validateForeignModel',
					'message' => __('Please enter a valid model name')
				]
			],
			'content' => [
				'alphaNumeric' => [
					'required' => true,
					'allowEmpty' => false,
					'rule' => AppValidation::getValidationAlphaNumericAndSpecialRegex(),
					'message' => __('Content should only contain alphabets, numbers and the special characters : , . - _ ( ) [ ] \' " ? @ !')
				],
				'size' => [
					'rule' => ['lengthBetween', 3, 255],
					'message' => __('Comment should be between %s and %s characters long', 3, 255),
				]
			]
		];
		switch ($case) {
			case 'edit':
				$rules = [
					'id' => $default['id'],
					'content' => $default['content'],
				];
				break;
			default:
			case 'default':
				$rules = $default;
				break;
		}
		return $rules;
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
 * Check if the given foreign model is allowed
 *
 * @param string foreignModel The foreign model key to test
 * @return boolean
 */
	public function isValidForeignModel($foreignModel) {
		return in_array($foreignModel, Configure::read('Comment.foreignModels'));
	}

/**
 * Check if a resource with same id exists
 *
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
		$exists = $model->find('count', [
			'conditions' => ['id' => $check['foreign_id']],
			'recursive' => -1
		]);
		return $exists > 0;
	}

/**
 * Return the find conditions to be used for a given context.
 *
 * @param null|string $case The target case.
 * @param null|string $role The user role.
 * @param null|array $data (optional) Optional data to build the find conditions.
 * @return array
 */
	public static function getFindConditions($case = 'view', $role = Role::USER, $data = null) {
		$returnValue = [];
		switch ($case) {
			case 'viewByForeignModel':
				$returnValue = [
					'conditions' => [
						'Comment.foreign_id' => $data['Comment']['foreign_id']
						// @todo maybe check here if user has right to access the foreign instance, in this case we need the model to make a join with the convient permission view table
					],
					'order' => [
						'Comment.modified desc'
					]
				];
				break;
			case 'view':
				$returnValue = [
					'conditions' => [
						'Comment.id' => $data['Comment']['id']
						// @todo maybe check here if user has right to access the foreign instance, in this case we need the model to make a join with the convient permission view table
					]
				];
				break;
			default:
				$returnValue = [
					'conditions' => []
				];
		}

		return $returnValue;
	}

/**
 * Return the list of fields to be returned by a find operation in given context
 *
 * @param string $case context ex: login, activation
 * @param string $role optional user role if needed to build the options
 * @return array $condition
 * @access public
 */
	public static function getFindFields($case = 'view', $role = null) {
		$fields = ['fields' => []];
		switch ($case) {
			case 'view':
			case 'viewByForeignModel':
				$fields = [
					'fields' => ['id', 'parent_id', 'content', 'created', 'modified', 'created_by', 'modified_by'],
					'contain' => [
						'Creator' => [
							'fields' => [
								'username'
							],
							'Role' => [
								'fields' => [
									'Role.id',
									'Role.name'
								]
							],
							'Profile' => [
								'fields' => [
									'Profile.id',
									'Profile.first_name',
									'Profile.last_name',
								],
								'Avatar' => [
									'fields' => [
										'Avatar.id',
										'Avatar.user_id',
										'Avatar.foreign_key',
										'Avatar.model',
										'Avatar.filename',
										'Avatar.filesize',
										'Avatar.mime_type',
										'Avatar.extension',
										'Avatar.hash',
										'Avatar.path',
										'Avatar.adapter',
										'Avatar.created',
										'Avatar.modified'
									]
								]
							],
						]
					]
				];
				break;
			case 'add':
				$fields = [
					'fields' => ['content', 'parent_id', 'foreign_model', 'foreign_id', 'created_by', 'modified_by']
				];
				break;
			case 'edit':
				$fields = [
					'fields' => ['content']
				];
				break;
		}
		return $fields;
	}

}
