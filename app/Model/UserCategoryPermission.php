<?php
/**
 * UserCategoryPermission Model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('User', 'Model');

class UserCategoryPermission extends AppModel {

/**
 * Custom database table name, or null/false if no table association is desired.
 *
 * @var string
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html#usetable
 */
	public $useTable = "users_categories_permissions";

/**
 * Model behaviors
 *
 * @access public
 */
	public $actsAs = ['Containable'];

/**
 * Details of belongs to relationships
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $belongsTo = [
		'User',
		'Category',
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
			case 'viewByCategory':
				$conditions = [
					'conditions' => [
						// not null permissions
						'UserCategoryPermission.permission_id !=' => null,
						// permissions relative to the target resource
						'UserCategoryPermission.category_id' => $data['UserCategoryPermission']['category_id'],
						// only permission which have been defined directly for users
						'Permission.aro' => 'User',
						'Permission.aro_foreign_key = UserCategoryPermission.user_id'
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
		$fields = ['fields' => []];
		switch ($case) {
			case 'viewByCategory':
				$fields = [
					'fields' => ['user_id', 'category_id', 'permission_id', 'permission_type'],
					'contain' => [
						'Permission' => [
							'fields' => ['id', 'type', 'aco', 'aco_foreign_key', 'aro', 'aro_foreign_key'],
							'PermissionType' => [
								'fields' => ['serial', 'name'],
							],
							// Return the elements the permission has been defined for (user, category)
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
							'Category' => [
								'fields' => ['id', 'name', 'parent_id', 'category_type_id', 'lft', 'rght'],
							]
						]
					]
				];
				break;
		}
		return $fields;
	}

}
