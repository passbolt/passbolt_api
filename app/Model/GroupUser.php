<?php
/**
 * GroupUser Model
 *
 * @copyright         Copyright 2012, Passbolt.com
 * @package             app.Model.GroupUser
 * @since                 version 2.12.7
 * @license             http://www.passbolt.com/license
 */

App::uses('Group', 'Model');
App::uses('User', 'Model');

class GroupUser extends AppModel {

	public $useTable = "groups_users";

	public $belongsTo = [
		'Group',
		'User',
	];

	public $actsAs = ['Trackable'];

/**
 * Get the validation rules upon context
 *
 * @param string $case (optional) The target validation case if any.
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case = 'default') {
		$default = [
			'group_id' => [
				'uuid' => [
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message' => __('UUID must be in correct format')
				],
				'exist' => [
					'rule' => ['validateExists', 'group_id', 'Group'],
					'message' => __('The group provided does not exist')
				]
			],
			'user_id' => [
				'uuid' => [
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message' => __('UUID must be in correct format')
				],
				'exist' => [
					'rule' => ['validateExists', 'user_id', 'User'],
					'message' => __('The user provided does not exist')
				],
				'uniqueCombi' => [
					'rule' => ['uniqueCombi', null],
					'message' => __('The GroupUser entered is a duplicate')
				]
			]
		];
		switch ($case) {
			default:
			case 'default' :
				$rules = $default;
		}

		return $rules;
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
		$conditions = [];
		switch ($case) {
			case 'add':
				$conditions = [];
				break;
			case 'view':
				$conditions = [
					'conditions' => [
						'GroupUser.id' => $data['GroupUser.id']
					]
				];
				break;
			default:
				$conditions = [
					'conditions' => []
				];
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
		switch ($case) {
			case 'view':
			case 'add':
			case 'edit':
				$fields = [
					'fields' => [
						'id',
						'group_id',
						'user_id'
					]
				];
				break;
			case 'delete':
				$fields = [];
				break;
			default:
				$fields = [
					'fields' => []
				];
				break;
		}

		return $fields;
	}

/**
 * Check if a category with same id exists
 *
 * @param check
 */
	public function uniqueCombi($check = false) {
		$cr = $this->data['GroupUser'];
		$combi = [
			'GroupUser.group_id' => $cr['group_id'],
			'GroupUser.user_id' => $cr['user_id']
		];

		return $this->isUnique($combi, false);
	}
}
