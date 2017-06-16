<?php
/**
 * UserResourcePermission Model
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class UserResourcePermission extends AppModel {

/**
 * Custom database table name, or null/false if no table association is desired.
 *
 * @var string
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html#usetable
 */
	public $useTable = "users_resources_permissions";

/**
 * Model behaviors
 *
 * @access public
 */
	public $actsAs = ['Containable'];

/**
 * Details of belongs to relationships
 *
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $belongsTo = [
		'User',
		'Resource',
		'Permission'
	];

/**
 * Return the find conditions to be used for a given context.
 *
 * @param null|string $case The target case.
 * @param null|string $role The user role.
 * @param null|array $data (optional) Optional data to build the find conditions.
 * @return array
 */
	public static function getFindConditions($case = 'view', $role = Role::USER, &$data = null) {
		$conditions = [];

		switch ($case) {
			case 'findByUserAndResource':
				$conditions = [
					'conditions' => [
						'UserResourcePermission.resource_id' => $data['UserResourcePermission']['resource_id'],
						'UserResourcePermission.user_id' => $data['UserResourcePermission']['user_id']
					]
				];
				break;
			case 'viewByResource':
				$conditions = [
					'conditions' => [
						// not null permissions
						'UserResourcePermission.permission_id !=' => null,
						// permissions relative to the target resource
						'UserResourcePermission.resource_id' => $data['UserResourcePermission']['resource_id'],
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
		$fields = ['fields' => []];
		switch ($case) {
			case 'findByUserAndResource':
				$fields = [
					'fields' => [
						'UserResourcePermission.user_id',
						'UserResourcePermission.resource_id',
						'UserResourcePermission.permission_id',
						'UserResourcePermission.permission_type'
					],
					'contain' => [
						'Permission' => [
							'fields' => [
								'Permission.id',
								'Permission.type',
								'Permission.aco',
								'Permission.aco_foreign_key',
								'Permission.aro',
								'Permission.aro_foreign_key'
							],
							'PermissionType' => [
								'fields' => [
									'PermissionType.serial',
									'PermissionType.name'
								]
							],
						]
					]
				];
				break;
			case 'viewByResource':
				$fields = [
					'fields' => ['user_id', 'resource_id', 'permission_id', 'permission_type'],
					'contain' => [
						'Permission' => [
							'fields' => [
								'id',
								'type',
								'aco',
								'aco_foreign_key',
								'aro',
								'aro_foreign_key'
							],
							'PermissionType' => [
								'fields' => [
									'serial',
									'name'
								]
							],
							'User' => [
								'fields' => [
									'id',
									'username',
									'role_id'
								],
								'Profile' => [
									'fields' => [
										'id',
										'first_name',
										'last_name'
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
									],
								]
							],
							'Resource' => [
								'fields' => [
									'id',
									'name'
								]
							],
						]
					]
				];
				break;
		}
		return $fields;
	}

/**
 * Get the list of resources ids that are accessible for a given permission by a user.
 *
 * @param string $userId The user identifier
 * @param string $permissionType The permission identifier, by default 1 (READ)
 * @return array Array of resources ids that are accessible for a given permission by the user
 *
 * @throws InvalidArgumentException if the argument userId is not a valid uuid
 * @throws InvalidArgumentException if the argument $permissionType is not a valid permission type
 */
	public function findAuthorizedResourcesIds($userId, $permissionType = PermissionType::READ) {
		if (!Common::isUuid($userId)) {
			throw new InvalidArgumentException('$userId is not a valid uuid');
		}
		if (!PermissionType::isValidSerial($permissionType)) {
			throw new InvalidArgumentException('$permissionType is not a valid permission type');
		}

		// Find the resources the user is authorized to access with the permission type given in argument.
		$result = $this->find('all', [
			'conditions' => [
				'user_id' => $userId,
				'permission_type >=' => $permissionType
			]
		]);

		return Hash::extract($result, '{n}.UserResourcePermission.resource_id');
	}

/**
 * Find the resources whose sole owner is the given user.
 *
 * @param uuid $userId id of the user.
 * @return array Array of resources that are solely owned by the user.
 *
 * @throws InvalidArgumentException if the argument userId is not a valid uuid
 */
	public function findSoleOwnerSharedResourcesIds($userId) {
		if (!Common::isUuid($userId)) {
			throw new InvalidArgumentException('$userId is not a valid uuid');
		}

		// Find the resources ids the user is authorized to administrate.
		$authorizedResourcesIds = $this->findAuthorizedResourcesIds($userId, PermissionType::OWNER);

		// Filter to keep only the resources shared with others
		// - Get all the user_resource_permission rows that :
		// - * Includes the resources the user is owner ($authorizedResourcesIds).
		// - * And have resources shared with others users than the user.
		$result = $this->find('all', [
			'fields' => [
				'UserResourcePermission.resource_id',
				'COUNT(UserResourcePermission.user_id) as count_rows',
			],
			'conditions' => [
				'resource_id' => $authorizedResourcesIds,
			],
			'group' => 'UserResourcePermission.resource_id HAVING count_rows > 1',
		]);
		$sharedResourcesIds = Hash::extract($result, '{n}.UserResourcePermission.resource_id');

		// Filter to keep only the resources the user is the sole owner.
		// - Get all the user_resource_permission rows that :
		// - * Includes the resources the user is owner, and shared with others ($sharedResourcesIds).
		// - * And have resources owned only by the user.
		$result = $this->find('all', [
			'fields' => [
				'UserResourcePermission.resource_id',
				'COUNT(UserResourcePermission.user_id) as count_rows',
			],
			'conditions' => [
				'resource_id' => $sharedResourcesIds,
				'permission_type' => PermissionType::OWNER,
			],
			'group' => 'UserResourcePermission.resource_id HAVING count_rows = 1',
		]);
		$soleOwnerResourcesIds = Hash::extract($result, '{n}.UserResourcePermission.resource_id');

		return $soleOwnerResourcesIds;
	}
}
