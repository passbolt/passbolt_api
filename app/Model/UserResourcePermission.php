<?php
/**
 * UserResourcePermission Model
 *
 * @copyright		Copyright 2012, Passbolt.com
 * @license			http://www.passbolt.com/license
 * @package			app.Model.UserResourcePermission
 * @since			version 2.12.11
 */

class UserResourcePermission extends AppModel {

	public $useTable = "users_resources_permissions";
	
	public $belongsTo = array(
		'User',
		'Resource',
		'Permission'
	);

	/**
 * Return the conditions to be used for a given context
 *
 * @param $context string{guest or id}
 * @param $data used in find conditions (such as User.id)
 * @return $condition array
 * @access public
 */
	public static function getFindConditions($case = 'view', $role = Role::USER, &$data = null) {
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
		switch($case){
			case 'viewByResource':
				$fields = array(
					'fields' => array(
						'UserResourcePermission.user_id', 'UserResourcePermission.resource_id', 'UserResourcePermission.permission_id',
						'User.id', 'User.username', 'User.role_id',
						'Permission.id', 'Permission.type', 'Permission.aco', 'Permission.aco_foreign_key', 'Permission.aro', 'Permission.aro_foreign_key'
					),
					'contain' => array(
						'User',
						'Permission'
					)
				);
			break;
		}
		return $fields;
	}

}
