<?php
/**
 * GroupUser Model
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
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
					'rule' => ['validateGroup'],
					'message' => __('The group provided does not exist or is deleted')
				],
			],
			'user_id' => [
				'uuid' => [
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message' => __('UUID must be in correct format')
				],
				'exist' => [
					'rule' => ['validateUser'],
					'message' => __('The user provided does not exist or is deleted')
				],
				'uniqueRelationship' => [
					'rule' => ['uniqueRelationship'],
					'on' => 'create',
					'message' => __('The GroupUser entered is a duplicate')
				]
			],
			'is_admin' => [
				'boolean' => [
					'rule' => ['boolean'],
					'required' => false,
					'allowEmpty' => true,
					'message' => __('the field should be a boolean')
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
	public static function getFindConditions($case = 'view', $role = Role::USER, &$data = null) {
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
	public static function getFindFields($case = 'view', $role = null, $data = null) {
		switch ($case) {
			case 'view':
			case 'edit':
			case 'Group::view':
				$fields = [
					'fields' => [
						'id',
						'group_id',
						'user_id',
						'is_admin',
					]
				];
				break;
			case 'add':
			case 'Group::add':
				$fields = [
					'fields' => [
						'group_id',
						'user_id',
						'is_admin',
						'created_by',
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
 * Check that the user exists, is active and not deleted.
 *
 * @param array $check with 'user_id' set
 * @return bool
 */
	public function validateUser($check) {
		if (!isset($check['user_id']) || empty($check['user_id'])) {
			return false;
		} else {
			$exist = $this->User->find('first', [
				'conditions' => [
					'User.id' => $check['user_id'],
					'User.deleted' => false,
					'User.active' => true,
				],
			]);

			return !empty($exist);
		}
	}

/**
 * Check that the group exists and is not deleted.
 *
 * @param array $check with 'group_id' set
 * @return bool
 */
	public function validateGroup($check) {
		if (!isset($check['group_id']) || empty($check['group_id'])) {
			return false;
		} else {
			$exist = $this->Group->find('first', [
				'conditions' => [
					'Group.id' => $check['group_id'],
					'Group.deleted' => false
				],
			]);

			return !empty($exist);
		}
	}

/**
 * Check that the user is active.
 *
 * @param array $check with 'user_id' set
 * @return bool
 */
	public function validateUserActive($check) {
		if (!isset($check['user_id']) || empty($check['user_id'])) {
			return false;
		} else {
			$exist = $this->User->find('first', [
				'conditions' => [
					'User.id' => $check['user_id'],
					'User.active' => true,
				],
			]);

			return empty($exist);
		}
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

/**
 * Count the number of group admins.
 *
 * @param $groupId
 * @return array|null
 */
	public function countGroupAdmins($groupId) {
		$countGroupAdmins = $this->find('count', [
			'conditions' => [
				'group_id' => $groupId,
				'is_admin' => '1',
			]
		]);
		return $countGroupAdmins;

	}

/**
 * Find users who are all member of all the given groups.
 * @param $groupsIds
 * @return array
 */
	public function findUsersIdsMemberOfGroups($groupsIds) {
		$result = $this->find('all', [
			'fields' => [
				'GroupUser.user_id',
				'COUNT(GroupUser.user_id) as count_rows'
			],
			'conditions' => [
				'GroupUser.group_id' => $groupsIds
			],
			'group' => 'GroupUser.user_id HAVING count_rows = ' . count($groupsIds)
		]);
		return Hash::extract($result, '{n}.GroupUser.user_id');
	}

/**
 * Find groups having all the given members.
 * @param $usersIds
 * @return array
 */
	public function findGroupsIdsHavingMembers($usersIds) {
		$result = $this->find('all', [
			'fields' => [
				'GroupUser.group_id',
				'COUNT(GroupUser.group_id) as count_rows'
			],
			'conditions' => [
				'GroupUser.user_id' => $usersIds
			],
			'group' => 'GroupUser.group_id HAVING count_rows = ' . count($usersIds)
		]);
		return Hash::extract($result, '{n}.GroupUser.group_id');
	}

/**
 * Find groups having all the given managers.
 * @param $usersIds
 * @return array
 */
	public function findGroupsIdsHavingManagers($usersIds) {
		$result = $this->find('all', [
			'fields' => [
				'GroupUser.group_id',
				'COUNT(GroupUser.group_id) as count_rows'
			],
			'conditions' => [
				'GroupUser.user_id' => $usersIds,
				'GroupUser.is_admin' => 1
			],
			'group' => 'GroupUser.group_id HAVING count_rows = ' . count($usersIds)
		]);
		return Hash::extract($result, '{n}.GroupUser.group_id');
	}

/**
 * Prepare a bulk update operation.
 *
 * Will validate the various operations to be executed, and return an array of the actions to be done.
 * Validates that each groupUser has a correct uuid, that it exists, that it belongs to the given group.
 *
 * @param $groupId
 * @param array $groupUsers
 *   a list of groupUsers to update (create, update, delete).
 *   - a create entry should contain at least  a user_id
 *   - an update entry should contain the id, and is_admin field
 *   - a delete entry should contain an id, and a 'delete' = '1' field
 *
 * @return array
 *   an array of the operations to be executed.
 *   - create : contains a list of groupUsers to create
 *   - update : contains a list of groupUsers to update, with their id
 *   - delete : contains a list of groupUsers to delete, with their id
 *
 * @throws Exception
 */
	public function prepareBulkUpdate($groupId, $groupUsers) {
		$changes = [
			'count' => 0,
			'create' => [],
			'update' => [],
			'delete' => [],
		];

		// Process changes.
		foreach($groupUsers as $groupUser) {
			$deleteCase = isset($groupUser['GroupUser']['id']) && isset($groupUser['GroupUser']['delete']);
			$updateCase = isset($groupUser['GroupUser']['id']) && isset($groupUser['GroupUser']['is_admin']);
			$createCase = !isset($groupUser['GroupUser']['id']) && isset($groupUser['GroupUser']['user_id']);

			if ($deleteCase || $updateCase) {
				// Validate GroupUser id is a uuid.
				if (!Common::isUuid($groupUser['GroupUser']['id'])) {
					throw new Exception(__('The groupUser with id %s is invalid', $groupUser['GroupUser']['id']));
				}

				// Validate GroupUser Id.
				$dbGroupUser = $this->findById($groupUser['GroupUser']['id'], self::getFindFields('view', User::get('Role.name'))['fields']);
				if (empty($dbGroupUser)) {
					throw new Exception(__('The groupUser with id %s does not exist',
						$groupUser['GroupUser']['id']));
				}

				// Check that the GroupUser belongs to the given group.
				if ($dbGroupUser['GroupUser']['group_id'] != $groupId) {
					throw new Exception(__('The GroupUser provided doesn\'t belong to the edited group %s', $groupId));
				}

				// Everything ok, we process with saving the data.
				if ($deleteCase) {
					$changes['count']++;
					$changes['delete'][] = $dbGroupUser;
				} elseif ($updateCase) {
					$dbGroupUser['GroupUser']['is_admin'] = $groupUser['GroupUser']['is_admin'];
					$changes['count']++;
					$changes['update'][] = $dbGroupUser;
				}
			}
			elseif ($createCase) {
				$groupUser['GroupUser']['group_id'] = $groupId;
				$changes['count']++;
				$changes['create'][] = $groupUser;
			}
			else {
				throw new Exception('Unknown groupUser update operation');
			}
		}

		return $changes;
	}

/**
 * Create a group user and store the corresponding secrets.
 *
 * @param $groupUser
 *   the groupUser to create.
 * @param $secrets
 *   the strict list of secrets needed for the user and the group he will belong to.
 * @return array groupUser
 *   the groupUser created
 * @throws Exception
 * @throws ValidationException
 */
	public function createGroupUser($groupUser, $secrets) {
		// Validate GroupUser.
		$this->set($groupUser['GroupUser']);
		$validate = $this->validates();
		if (!$validate) {
			throw new ValidationException(
				__('Could not validate GroupUser'),
				$this->validationErrors
			);
		}

		// Get the list of resources he will need to access to.
		$neededResources = $this->Group->GroupResourcePermission->findUnauthorizedResourcesForUsers(
			$groupUser['GroupUser']['group_id'],
			$groupUser['GroupUser']['user_id']
		);
		$neededResources = isset($neededResources[$groupUser['GroupUser']['user_id']]) ? $neededResources[$groupUser['GroupUser']['user_id']] : [];
		$neededResourceIds = Hash::extract($neededResources, '{n}.Resource.id');

		if (count($neededResourceIds) != count($secrets)) {
			throw new Exception(
				__('Mismatch: %s secrets are expected for user %s',
					count($neededResourceIds),
					$groupUser['GroupUser']['user_id'])
			);
		}

		$Secret = Common::getModel('Secret');
		foreach($secrets as $secret) {
			// Make sure user_id matches.
			if ($secret['Secret']['user_id'] != $groupUser['GroupUser']['user_id']) {
				throw new Exception(__('The secret provided doesn\'t belong to the saved user'));
			}
			// Validate secret.
			$Secret->set($secret['Secret']);
			$validate = $Secret->validates();
			if (!$validate) {
				throw new ValidationException(
					__('Could not validate Secret'),
					$Secret->validationErrors
				);
			}

			// Make sure the secret resource_id is part of what we expect.
			$resourceIdIsNeeded = array_search($secret['Secret']['resource_id'], $neededResourceIds);
			if ($resourceIdIsNeeded === FALSE) {
				throw new Exception(__('The secret provided is not required'));
			}

			// Remove the resource_id from the needed resources.
			unset($neededResourceIds[$resourceIdIsNeeded]);

			// Save the secret.
			$Secret->create();
			$save = $Secret->save($secret['Secret']);
			if (!$save) {
				throw new Exception(__('The secret could not be saved'));
			}
		}

		// Save Group User.
		$this->create();
		$fields = $this->getFindFields('add', User::get('Role.name'));
		$savedGroupUser = $this->save($groupUser, [
			'fieldList' => $fields['fields'],
			'atomic' => false,
		]);
		if (!$savedGroupUser) {
			throw new Exception(__('Could not save model GroupUser'));
		}
		return $savedGroupUser;
	}

/**
 * Update a groupUser.
 *
 * Updates only the is_admin property for the time being.
 *
 * @param $groupUser
 * @return mixed
 * @throws Exception
 * @throws ValidationException
 */
	public function updateGroupUser($groupUser) {
		// Validate GroupUser.
		$this->set($groupUser['GroupUser']);
		$validate = $this->validates(['fieldList' => ['is_admin']]);
		if (!$validate) {
			throw new ValidationException(
				__('Could not validate GroupUser'),
				$this->validationErrors
			);
		}

		// Make sure that at least one admin is left.
		$removeAdmin = $groupUser['GroupUser']['is_admin'] == 0 ? true : false;
		if ($removeAdmin) {
			$countExistingAdmins = $this->countGroupAdmins($groupUser['GroupUser']['group_id']);
			if ($countExistingAdmins == 1) {
				throw new Exception(__('A group requires at least one manager'));
			}
		}

		// Update.
		$this->id = $groupUser['GroupUser']['id'];
		$update = $this->saveField('is_admin', $groupUser['GroupUser']['is_admin'], true);
		if (!$update) {
			throw new Exception(__('Could not update groupUser id %s', $groupUser['GroupUser']['id']));
		}

		return $this->findById($groupUser['GroupUser']['id'], self::getFindFields('view', User::get('Role.name'))['fields']);
	}

/**
 * Delete GroupUser and associated secrets that are not needed anymore.
 *
 * @param $groupUser
 * @return mixed
 * @throws Exception
 */
	public function deleteGroupUser($groupUser) {
		// Make sure that at least one admin is left.
		$removeAdmin = $groupUser['GroupUser']['is_admin'] == 1 ? true : false;
		$countExistingAdmins = $this->countGroupAdmins($groupUser['GroupUser']['group_id']);
		if ($countExistingAdmins == 1 && $removeAdmin == true) {
			throw new Exception(__('A group requires at least one manager'));
		}

		// Everything ok, we can delete.
		$del = $this->delete($groupUser['GroupUser']['id']);
		if (!$del) {
			throw new Exception(__('Could not delete groupUser id %s', $groupUser['GroupUser']['id']));
		}

		// Delete overhead useless secrets.
		$overheadResources = $this->Group->GroupResourcePermission->findUnauthorizedResourcesForUsers(
			$groupUser['GroupUser']['group_id'],
			$groupUser['GroupUser']['user_id']
		);
		if(isset($overheadResources[$groupUser['GroupUser']['user_id']]) && !empty($overheadResources[$groupUser['GroupUser']['user_id']])) {
			$Secret = Common::getModel('Secret');
			foreach($overheadResources[$groupUser['GroupUser']['user_id']] as $resource) {
				$del = $Secret->deleteAll([
					'user_id' => $groupUser['GroupUser']['user_id'],
					'resource_id' => $resource['Resource']['id'],
				]);
				if (!$del) {
					throw new Exception(__("Could not delete secrets for user %s", $groupUser['GroupUser']['user_id']));
				}
			}
		}

		return $groupUser;
	}
}
