<?php
/**
 * UserResourcePermission Model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

class UserResourcePermission extends AppModel {

	public $useTable = "users_resources_permissions";

	public $belongsTo = array(
		'User',
		'Resource',
		'Permission'
	);

/**
 * Return the find conditions to be used for a given context.
 *
 * @param null|string $case The target case.
 * @param null|string $role The user role.
 * @param null|array $data (optional) Optional data to build the find conditions.
 * @return array
 */
	public static function getFindConditions($case = 'view', $role = Role::USER, $data = null) {
		$conditions = array();

		switch ($case) {
			case 'viewByResource':
				$conditions = array(
					'conditions' => array(
						// not null permissions
						'UserResourcePermission.permission_id !=' => null,
						// permissions relative to the target resource
						'UserResourcePermission.resource_id' => $data['UserResourcePermission']['resource_id'],
						// only permission which have been defined directly for users
						'Permission.aro' => 'User',
						'Permission.aro_foreign_key = UserResourcePermission.user_id'
					)
				);
			break;

			default:
				$conditions = array(
					'conditions' => array()
				);
		}

		return $conditions;
	}

/**
 * Return the list of field to fetch for given context
 * @param string $case context ex: login, activation
 * @return $condition array
 */
	public static function getFindFields($case = 'view', $role = Role::USER) {
		$returnValue = array('fields'=>array());
		switch($case){
			case 'viewByResource':
				$returnValue = array(
					'fields' => array('user_id', 'resource_id', 'permission_id', 'permission_type'),
					'contain' => array(
						'Permission' => array(
							'fields' => array('id', 'type', 'aco', 'aco_foreign_key', 'aro', 'aro_foreign_key'),
							'PermissionType' => array(
								'fields' => array('serial', 'name')
							),
							'User' => array(
								'fields' => array('id', 'username', 'role_id'),
								'Profile' => array(
									'fields' => array('id', 'first_name', 'last_name'),
									'Avatar' => array(
										'fields' => array(
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
										)
									),
								)
							),
							'Resource' => array(
								'fields' => array('id', 'name')
							),
							'Category' => array(
								'fields' => array('id', 'name')
							)
						)
					)
				);
			break;
		}
		return $returnValue;
	}

}
