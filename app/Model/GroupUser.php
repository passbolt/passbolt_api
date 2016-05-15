<?php
/**
 * GroupUser Model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
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
	public static function getValidationRules($case = null) {
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
				'uniqueRelationship' => [
					'rule' => ['uniqueRelationship'],
					'message' => __('The GroupUser entered is a duplicate')
				]
			]
		];
		return $default;
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
 * Return the list of fields to be returned by a find operation in given context
 *
 * @param string $case context ex: login, activation
 * @param string $role optional user role if needed to build the options
 * @return array $fields
 * @access public
 */
	public static function getFindFields($case = 'view', $role = null) {
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
 * Check if a Group User association with same id exists
 * Custom Validation Rule
 *
 * @return bool
 */
	public function uniqueRelationship() {
		$groupUser = $this->data['GroupUser'];
		$combination = [
			'GroupUser.group_id' => $groupUser['group_id'],
			'GroupUser.user_id' => $groupUser['user_id']
		];
		return $this->isUnique($combination, false);
	}
}
