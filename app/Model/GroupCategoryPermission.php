<?php
/**
 * GroupCategoryPermission Model
 *
 * @copyright		Copyright 2012, Passbolt.com
 * @license			http://www.passbolt.com/license
 * @package			app.Model.GroupCategoryPermission
 * @since			version 2.12.11
 */

class GroupCategoryPermission extends AppModel {
	
/**
 * Custom database table name, or null/false if no table association is desired.
 *
 * @var string
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html#usetable
 */
	public $useTable = "groups_categories_permissions";
	
/**
 * Model behaviors
 * @access public
 */
	public $actsAs = array('Containable');
 
/**
 * Details of belongs to relationships
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $belongsTo = array(
		'Group',
		'Category',
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
			case 'viewByCategory':
				$conditions = array(
					'conditions' => array(
						// not null permissions
						'GroupCategoryPermission.permission_id !=' => null,
						// permissions relative to the target category
						'GroupCategoryPermission.category_id' => $data['GroupCategoryPermission']['category_id'],
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
			case 'viewByCategory':
				$returnValue = array(
					'fields' => array('group_id', 'category_id', 'permission_id'),
					'contain' => array(
						'Permission' => array(
							'fields' => array('id', 'type'),
							'PermissionType' => array(
								'fields' => array('id', 'serial', 'name')
							),
							// Return the elements the permission has been defined for (group, category)
							'Group' => array(
								'fields' => array('id', 'name')
							),
							'Category' => array(
								'fields' => array('id', 'name', 'parent_id', 'category_type_id', 'lft', 'rght')
							)
						)
					)
				);
			break;
		}
		return $returnValue;
	}
}
