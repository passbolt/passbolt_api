<?php
/**
 * GroupResourcePermission Model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

class GroupResourcePermission extends AppModel {

	public $useTable = "groups_resources_permissions";

	public $belongsTo = array(
		'Group',
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
						'GroupResourcePermission.permission_id !=' => null,
						// permissions relative to the target resource
						'GroupResourcePermission.resource_id' => $data['GroupResourcePermission']['resource_id'],
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
					'fields' => array('group_id', 'resource_id', 'permission_id', 'permission_type'),
					'contain' => array(
						'Permission' => array(
              'fields' => array('id', 'type', 'aco', 'aco_foreign_key', 'aro', 'aro_foreign_key'),
							'PermissionType' => array(
								'fields' => array('serial', 'name')
							),
							// Return the elements the permission has been defined for (group, category)
							'Group' => array(
								'fields' => array('id', 'name')
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
