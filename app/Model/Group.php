<?php
/**
 * Group  model
 *
 * @copyright (c) 2017 - present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('User', 'Model');
App::uses('GroupUser', 'Model');
App::uses('GroupResourcePermission', 'Model');

class Group extends AppModel {

	public $name = 'Group';

	public $actsAs = [
		'Trackable',
		'SuperJoin',
		'Containable'
	];

	public $hasMany = [
		'GroupUser' => [
			'className' => 'GroupUser'
		],
		'GroupResourcePermission'
	];

	public $hasAndBelongsToMany = [
		'User' => [
			'className' => 'User'
		]
	];

/**
 * Get the validation rules upon context
 *
 * @param string $case (optional) The target validation case if any.
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case = 'default') {
		return [
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
				],
				'unique' => [
					'shared' => false,
					'on' => 'create',
					'rule' => ['checkGroupNameIsUnique', null],
					'message' => __('The group name provided is already used by another group'),
				],
			]
		];
	}

/**
 * Return the find conditions to be used for a given context.
 *
 * @param null|string $case The target case.
 * @param null|string $role The user role.
 * @param null|array $data (optional) Optional data to build the find conditions.
 * @return array
 */
	public static function getFindConditions($case = 'Group::view', $role = null, &$data = null) {
		$conditions = [];

		switch ($case) {
			case 'Group::add':
			case 'Group::edit':
			case 'Group::view':
			case 'Group::exists':
				$conditions = ['conditions' => ['Group.deleted' => 0, 'Group.id' => $data['Group.id']]];
				break;

			case 'Group::index':
				$conditions = ['conditions' => ['Group.deleted' => 0]];

				if (isset($data['filter']['keywords'][0])) {
					$keywords = explode(' ', trim($data['filter']['keywords'][0]));
					foreach ($keywords as $keyword) {
						$conditions['conditions']["AND"][] = ['Group.name LIKE' => '%' . $keyword . '%'];
					}
				}
				if (isset($data['filter']['has-users'])) {
					$users = $data['filter']['has-users'];
					$conditions['conditions'][] = ['GroupsUser.user_id IN' => $users];
					if (!isset($data['contain']) || !in_array('Favorite', $data['contain'])) {
						$data['contain'][] = 'user';
					}
				}
				if (isset($data['filter']['has-managers'])) {
					$users = $data['filter']['has-managers'];
					$conditions['conditions'][] = ['GroupsUser.user_id IN' => $users];
					$conditions['conditions'][] = ['GroupsUser.is_admin' => 1];
					if (!isset($data['contain']) || !in_array('Favorite', $data['contain'])) {
						$data['contain'][] = 'user';
					}
				}
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
							WHERE Permission.aco = "' . $data['Permission.aco'] . '"
								AND Permission.aco_foreign_key = "' . $data['Permission.aco_foreign_key'] . '"
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
			case 'Group::exists':
				$fields = ['fields' => ['Group.id', 'Group.deleted']];
				break;
			case 'Group::edit':
				$fields = ['fields' => ['name', 'modified', 'modified_by']];
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
				$contain = ['user' => 1, 'resource' => 0];
				break;
			case 'Share::searchUsers':
				$contain = ['user' => 0, 'resource' => 0];
				break;
		}
		return $contain;
	}

/**
 * Return the list of order instructions allowed for each case, with their default value
 *
 * @param null $case
 * @param null $role
 * @return array
 */
	public static function getFindAllowedOrder($case = null, $role = null) {
		return ['Group.name'];
	}

/**
 * Filter a list of groups and remove the groups that don't contain all the members defined by $userIds.
 *
 * @param $groups list of groups, with the users and groupUsers provided.
 * @param $userIds list of user ids
 * @return array list of only the groups that contain at least the members defined by userIds
 */
	public static function filterGroupWithAllUsers($groups, $userIds) {
		$results = [];
		foreach ($groups as $key => $group) {
			$groupMemberIds = Hash::extract($group['GroupUser'], '{n}.user_id');
			if (count(array_intersect($groupMemberIds, $userIds)) === count($userIds)) {
				array_push($results, $group);
			}
		}
		return $results;
	}

/**
 * Returns true if a record with particular ID has been soft deleted.
 *
 * If $id is not passed it calls `Model::getID()` to obtain the current record ID,
 * if the group has been soft deleted, is considered has a group which doesn't
 * exist.
 *
 * @param int|string $id ID of record to check for existence
 * @return bool True if such a record exists
 */
	public function isSoftDeleted($id = null) {
		if ($id === null) {
			$id = $this->getID();
		}
		if ($id === false) {
			return false;
		}

		$data = ['Group.id' => $id];
		$o = $this->getFindOptions('Group::exists', User::get('Role.name'), $data);
		return !(bool)$this->find('count', $o);
	}

/**
 * Soft delete a group.
 *
 * @param string $id Id of the group to soft delete
 * @return void
 * @throws Exception
 */
	public function softDelete($id) {
		// Begin transaction
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		// Mark the group as deleted
		$data['Group'] = [
			'id' => $id,
			'deleted' => 1
		];
		$fields = $this->getFindFields('Group::delete', User::get('Role.name'));
		if (!$this->save($data, true, $fields['fields'])) {
			$dataSource->rollback();
			throw new Exception(__('Unable to soft delete the group'));
		}

		// Everything fine, we commit.
		$dataSource->commit();
	}

/**
 * Check that the group name is unique.
 *
 * @param array $check with 'name' set
 * @return bool
 */
	public function checkGroupNameIsUnique($check) {
		if (!isset($check['name']) || empty($check['name'])) {
			return false;
		} else {
			$exist = $this->find('first', [
				'conditions' => [
					'Group.name' => $check['name'],
					'Group.deleted' => false,
				],
			]);
			return empty($exist);
		}
	}

/**
 * Validate filters
 *
 * @params array $filters
 * - has-users: an array of user uuids
 * - has-manager: an array of user uuids
 * @throws ValidationException if one of the has-managers or has-users values is not a valid uuid
 * @return true if valid
 */
 	public function validateFilters ($filters = null) {
		foreach ($filters as $filter => $values) {
			switch ($filter) {
				case 'has-managers':
				case 'has-users':
					foreach($values as $i => $userId) {
						if(!Common::isUuid($userId)) {
							throw new ValidationException(__('"%s" is not a valid user id for filter %s.', $userId, $filter));
						}
					}
				break;
			}
		}
 		return true;
	}

/**
 * Validate order
 *
 * @param array $order
 * - Group.name: a string that is a valid group name
 * @throws ValidationException if the group name does not validate
 * @return bool true if valid
 */
 	public function validateOrder ($order = null) {
 		if (isset($order) && isset($order['Group.name'])) {
			$validationRules = Group::getValidationRules();
			$valid = (preg_match($order['Group.name'], $validationRules['name']['alphanumeric']['rule']) === 1);
			if(!$valid) {
				throw new ValidationException(__('"%" is not a valid group name.'), $order['Group.name']);
			}
		}
 		return true;
 	}
}
