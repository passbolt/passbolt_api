<?php
/**
 * Group  model
 *
 * @copyright (c) 2017 - present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('User', 'Model');
App::uses('GroupUser', 'Model');

class Group extends AppModel {

	public $name = 'Group';

	public $actsAs = [
		'Trackable',
		'SuperJoin',
		'Containable',
	];

	public $hasMany = [
		'GroupUser' => [
			'className' => 'GroupUser',
		]
	];

	public $hasAndBelongsToMany = [
		'User' => [
			'className' => 'User',

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
					'message' => __('UUID must be in correct format')
				]
			],
			'name' => [
				'alphaNumeric' => [
					'rule' => '/^[a-zA-Z0-9\-_ ]{1,64}$/i',
					'required' => true,
					'allowEmpty' => false,
					'message' => __('Alphanumeric only')
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
 * Return the find conditions to be used for a given context.
 *
 * @param null|string $case The target case.
 * @param null|string $role The user role.
 * @param null|array $data (optional) Optional data to build the find conditions.
 * @return array
 */
	public static function getFindConditions($case = 'Group::view', $role = null, $data = null) {
		$conditions = [];

		switch ($case) {
			case 'Group::add':
			case 'Group::edit':
			case 'Group::view':
				$conditions = ['conditions' => ['Group.deleted' => 0, 'Group.id' => $data['Group.id']]];
				break;
			case 'Group::index':
				$conditions = ['conditions' => [ 'Group.deleted' => 0 ]];
				if(isset($data['filter']['has-users']) || isset($data['filter']['has-managers'])) {
					$users = isset($data['filter']['has-users']) ?
						$data['filter']['has-users'] : $data['filter']['has-managers'];
					$conditions['conditions'][] = ['GroupsUser.user_id IN' => $users];
				}
				if(isset($data['filter']['has-managers'])) {
					$conditions['conditions'][] = ['GroupsUser.is_admin' => 1];
				}

				if (isset($data['filter']['keywords'])) {
					$keywords = explode(' ', $data['filter']['keywords']);
					foreach ($keywords as $keyword) {
						$conditions['conditions']["AND"][] = ['Group.name LIKE' => '%' . $keyword . '%'];
					}
				}
				$conditions['order'] = 'Group.name ASC';
				break;
			case 'Share::searchUsers':
				// Use conditions already defined for the index case
				$conditions = Group::getFindConditions('Group::index', $role, $data);

				// Only return users who don't have a direct permission defined for the given aco instance
				$conditions['joins'][] = [
					'table' => 'groups',
					'alias' => 'GroupToGrant',
					'type' => 'inner',
					'conditions' => [
						'Group.id = GroupToGrant.id
						AND GroupToGrant.id NOT IN (
							SELECT Permission.aro_foreign_key
							FROM permissions Permission
							WHERE Permission.aco = "' . $data['aco'] . '"
								AND Permission.aco_foreign_key = "' . $data['aco_foreign_key'] . '"
								AND Permission.aro_foreign_key = GroupToGrant.id
						)',
					],
				];
				break;
			default:
				$conditions = ['conditions' => []];
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
	public static function getFindFields($case = 'Group::view', $role = null, $data = null) {
		switch ($case) {
			case 'Group::view':
			case 'Group::index':
			case 'Share::searchUsers':
				$fields = [
					'fields' => [
						'DISTINCT Group.id',
						'Group.name',
						'Group.created',
						'Group.modified',
					],
					'contain' => [],
				];
				// If filter has-users or has-managers is set, then contain[user] is done by default.
				if (isset($data['filter']) &&
					(isset($data['filter']['has-users']) || isset($data['filter']['has-managers']))) {
					$data['contain'][] = 'user';
				}

				if (isset($data['contain'])) {
					if (in_array('user', $data['contain'])) {
						$fields['superjoin'] = ['User'];
						$fields['contain'] = [
							'User' => User::getFindFields($case, $role, $data),
							'GroupUser' => GroupUser::getFindFields('view', $role, $data)
						];
					}
				}
				break;
			case 'Group::delete':
				$fields = ['fields' => ['deleted']];
				break;
			case 'Group::add':
				$fields = ['fields' => ['name', 'created_by', 'modified_by']];
				break;
			default:
				$fields = ['fields' => []];
				break;
		}
		return $fields;
	}

/**
 * Return the list of contain instructions allowed, with their default values.
 *
 * @param string $case
 * @param null $role
 * @return array
 */
	public static function getFindContain($case = 'view', $role = null) {
		$contain = [];
		switch ($case) {
			case 'Group::view':
			case 'Group::index':
				$contain = [
					'user' => 1,
					'resource' => 0,
				];
				break;
			case 'Share::searchUsers':
				$contain = [
					'user' => 0,
					'resource' => 0,
				];
		}
		return $contain;
	}

/**
 * Return the list of filter instructions allowed.
 * @param string $case
 * @param null $role
 * @return array
 */
	public static function getFindFilter($case = 'view', $role = null) {
		$filter = [];
		switch ($case) {
			case 'Group::index':
				$filter = [
					'has-users',
					'has-managers',
					'has-resources',
				];
				break;
		}
		return $filter;
	}

/**
 * Filter a list of groups and remove the groups that don't contain all the members defined by $userIds.
 *
 * @param $groups
 *   list of groups, with the users and groupUsers provided.
 * @param $userIds
 *   list of user ids
 *
 * @return array
 *   list of only the groups that contain at least the members defined by userIds
 */
	public static function filterGroupWithAllUsers($groups, $userIds) {
		foreach($groups as $key => $group) {
			$groupMemberIds = Hash::extract($group['GroupUser'], '{n}.user_id');
			foreach($userIds as $userId) {
				if (!in_array($userId, $groupMemberIds)) {
					unset($groups[$key]);
					break;
				}
			}
		}
		return $groups;
	}
}
