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
		'Containable',
	];

	public $hasMany = [
		'GroupUser' => [
			'className' => 'GroupUser',
		],
		'GroupResourcePermission'
	];

	public $belongsTo = [
		'Creator' => [
			'className' => 'User',
			'foreignKey' => 'created_by'
		],
		'Modifier' => [
			'className' => 'User',
			'foreignKey' => 'modified_by'
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
				],
				'unique' => [
					'shared' => false,
					'on' => 'create',
					'rule' => ['checkGroupNameIsUnique', null],
					'message' => __('The group name provided is already used by another group'),
				],
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
					$hasUsers = $data['filter']['has-users'];
					$conditions['joins'][] = array(
						'table' => 'groups',
						'alias' => 'GroupsHasUsers',
						'conditions' => array(
							'Group.id = GroupsHasUsers.id',
							"(SELECT COUNT(*) FROM groups_users SubGroupUser
							  WHERE SubGroupUser.group_id = GroupsHasUsers.id
							  AND SubGroupUser.user_id IN ('" . implode("','", $hasUsers) . "')) = " . count($hasUsers)
						)
					);
					if (!isset($data['contain']) || !in_array('user', $data['contain'])) $data['contain'][] = 'user';
				}
				if (isset($data['filter']['has-managers'])) {
					$hasManagers = $data['filter']['has-managers'];
					$conditions['joins'][] = array(
						'table' => 'groups', 'alias' => 'GroupHasManagers',
						'conditions' => array(
							'Group.id = GroupHasManagers.id',
							"(SELECT COUNT(*) FROM groups_users SubGroupUser
							  WHERE SubGroupUser.group_id = GroupHasManagers.id
							  AND SubGroupUser.is_admin = 1
							  AND SubGroupUser.user_id IN ('" . implode("','", $hasManagers) . "')) = " . count($hasManagers)
						)
					);
					if (!isset($data['contain']) || !in_array('user', $data['contain'])) $data['contain'][] = 'user';
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
						'Group.id',
						'Group.name',
						'Group.created',
						'Group.modified'
					],
					'contain' => [],
				];

				if (isset($data['contain'])) {
					if (in_array('user', $data['contain'])) {
						$fields['contain'] = [
							'GroupUser' => [
								'fields' => [
									'GroupUser.id',
									'GroupUser.group_id',
									'GroupUser.user_id',
									'GroupUser.is_admin',
								],
								'User' => [
									'fields' => [
										'User.id',
										'User.username',
										'User.role_id',
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
												'Avatar.filename',
												'Avatar.mime_type',
												'Avatar.extension',
												'Avatar.path',
											]
										]
									],
									'Gpgkey' => [
										'fields' => [
											'Gpgkey.fingerprint',
										],
									],
								]
							]
						];
					}
					if (in_array('modifier', $data['contain'])) {
						$fields['contain']['Modifier'] = [
							'fields' => [
								'Modifier.id',
								'Modifier.username',
								'Modifier.role_id',
							],
							'Profile' => [
								'fields' => [
									'Profile.id',
									'Profile.first_name',
									'Profile.last_name',
								],
							]
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
				$fields = [
					'fields' => [
						'Group.id',
						'Group.deleted',
					]
				];
				break;
			case 'Group::edit':
				$fields = [
					'fields' => [
						'name',
						'modified',
						'modified_by'
					]
				];
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
					'modifier' => 0
				];
				break;
			case 'Share::searchUsers':
				$contain = [
					'user' => 0,
					'resource' => 0,
					'modifier' => 0
				];
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
		return [
			'Group.name',
		];
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
}
