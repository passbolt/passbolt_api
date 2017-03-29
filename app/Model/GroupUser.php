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
 * Update several groupUsers on the same time for a given group id.
 *
 * @param $groupId
 * @param array $groupUsers
 *  list of groupUsers to update.
 *  The ones to delete must have a 'delete' entry as part of the object.
 *  The ones to update must have an id provided
 *  The ones to create shouldn't have any id provided
 *
 * @return array
 *   alterations: number of alterations performed
 *   created: list of groupUsers created
 *   updated: list of groupUsers updated
 *   deletes: list of groupUsers deleted
 *
 * @throws Exception
 * @throws ValidationException
 */
	public function bulkUpdate($groupId, $groupUsers) {
		$changes = [
			'alterations' => 0,
			'created' => [],
			'updated' => [],
			'deleted' => [],
		];

		foreach($groupUsers as $groupUser) {
			$deleteCase = isset($groupUser['GroupUser']['id']) && isset($groupUser['GroupUser']['delete']);
			$updateCase = isset($groupUser['GroupUser']['id']) && isset($groupUser['GroupUser']['is_admin']);
			$createCase = !isset($groupUser['GroupUser']['id']) && isset($groupUser['GroupUser']['user_id']);

			if ($deleteCase || $updateCase) {
				// Validate GroupUser id is a uuid.
				if (!Common::isUuid($groupUser['GroupUser']['id'])) {
					throw new Exception(__('The groupUser with id %s is invalid', $groupUser['GroupUser']['id']));
				}

				// Validate Permission Id.
				$exist = $this->exists($groupUser['GroupUser']['id']);
				if (!$exist) {
					throw new Exception(__('The groupUser with id %s does not exist',
						$groupUser['GroupUser']['id']));
				}

				// Check that the GroupUser belongs to the given group.
				$groupUserBelongsToGroup = $this->find('first', [
					'conditions' => [
						'GroupUser.id' => $groupUser['GroupUser']['id'],
						'GroupUser.group_id' => $groupId
					]
				]);
				if (empty($groupUserBelongsToGroup)) {
					throw new Exception(__('The GroupUser provided doesn\'t belong to the edited group %s', $groupUser['GroupUser']['id']));
				}

				// Everything ok, we process with saving the data.
				if ($deleteCase) {
					$del = $this->delete($groupUser['GroupUser']['id']);
					if (!$del) {
						throw new Exception(__('Could not delete groupUser id %s', $groupUser['GroupUser']['id']));
					}
					$changes['alterations'] ++;
					$changes['deleted'][] = $groupUser;
				} elseif ($updateCase) {
					// Update.
					$this->id = $groupUser['GroupUser']['id'];
					$update = $this->saveField('is_admin', $groupUser['GroupUser']['is_admin'], true);
					if (!$update) {
						throw new Exception(__('Could not update groupUser id %s', $groupUser['GroupUser']['id']));
					}
					$changes['alterations'] ++;
					$changes['updated'][] = $groupUser;
				}
			}
			elseif ($createCase) {
				// Force group id.
				$groupUser['GroupUser']['group_id'] = $groupId;

				// Validate GroupUser.
				$this->set($groupUser['GroupUser']);
				$validate = $this->validates();
				if (!$validate) {
					throw new ValidationException(
						__('Could not validate GroupUser'),
						$this->validationErrors
					);
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
				$changes['alterations'] ++;
				$changes['created'][] = $savedGroupUser;
			}
		}

		return $changes;
	}
}
