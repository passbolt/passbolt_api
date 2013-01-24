<?php
/**
 * GroupResourcePermission Model
 *
 * @copyright		Copyright 2012, Passbolt.com
 * @license			http://www.passbolt.com/license
 * @package			app.Model.GroupResourcePermission
 * @since			version 2.12.11
 */

class GroupResourcePermission extends AppModel {

	public $useTable = "groups_resources_permissions";
	
	public $belongsTo = array(
		'Group',
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
					'fields' => array('group_id', 'resource_id', 'permission_id'),
					'contain' => array(
						'Permission' => array(
							'fields' => array('id', 'type'),
							'PermissionType' => array(
								'fields' => array('serial', 'name')
							),
							// Return the elements the permission has been defined for (group, category)
							'Group' => array(
								'fields' => array('id', 'name')
							),
							'Resource' => array(
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
