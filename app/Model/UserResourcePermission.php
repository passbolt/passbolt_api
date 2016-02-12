<?php
/**
 * UserResourcePermission Model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
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
	public static function getFindConditions($case = 'view', $role = Role::USER, $data = null) {
		$conditions = [];

		switch ($case) {
			case 'viewByResource':
				$conditions = [
					'conditions' => [
						// not null permissions
						'UserResourcePermission.permission_id !=' => null,
						// permissions relative to the target resource
						'UserResourcePermission.resource_id' => $data['UserResourcePermission']['resource_id'],
						// only permission which have been defined directly for users
						'Permission.aro' => 'User',
						'Permission.aro_foreign_key = UserResourcePermission.user_id'
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
		$returnValue = ['fields' => []];
		switch ($case) {
			case 'viewByResource':
				$returnValue = [
					'fields' => ['user_id', 'resource_id', 'permission_id', 'permission_type'],
					'contain' => [
						'Permission' => [
							'fields' => ['id', 'type', 'aco', 'aco_foreign_key', 'aro', 'aro_foreign_key'],
							'PermissionType' => [
								'fields' => ['serial', 'name']
							],
							'User' => [
								'fields' => ['id', 'username', 'role_id'],
								'Profile' => [
									'fields' => ['id', 'first_name', 'last_name'],
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
								'fields' => ['id', 'name']
							],
							'Category' => [
								'fields' => ['id', 'name']
							]
						]
					]
				];
				break;
		}
		return $returnValue;
	}
}
