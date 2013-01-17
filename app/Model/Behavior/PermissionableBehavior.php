<?php
/**
 * Permission Behavior
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Test.Case.Model.Behavior.PermissionBehavior
 * @since        version 2.12.12
 */

App::uses('Permission', 'Model');

class PermissionableBehavior extends ModelBehavior {

	public function beforeFind($Model, $queryData = array()) {
		if (User::get('Role.name') == Role::USER ||
						User::get('Role.name') == Role::GUEST) {
			
			// Permissionable find request options
			$permOptions = array(
				'fields' => array('UserCategoryPermission.permission_id', 'UserCategoryPermission.permission_type'),
				'conditions' =>	 array(
					'UserCategoryPermission.user_id' => User::get('id'),
					'UserCategoryPermission.permission_type >' => PermissionType::DENY		
				),
				'contain' => array('UserCategoryPermission')
			);

			// Bind the target model to the permissions models
			$Model->bindModel(array('hasOne'=>array(
				'UserCategoryPermission' => array(
					'foreignKey' => 'category_id'
				),
				'GroupCategoryPermission' => array(
					'foreignKey' => 'resource_id'
				),
				'Permission'=> array(
					'foreignKey' => false,
					'conditions' => array(' Permission.id = UserCategoryPermission.permission_id '),
					'type' => 'LEFT'
				))), false
			);

			// Add the field / conditions / contains to the query
			if (!empty($queryData['fields'])) {
				if(!is_array($queryData['fields'])) $queryData['fields'] = array($queryData['fields']);
				$queryData['fields'] = array_merge($queryData['fields'], $permOptions['fields']);
			}

			if(empty($queryData['conditions'])) $queryData['conditions'] = array();
			if(!is_array($queryData['conditions'])) $queryData['conditions'] = array($queryData['conditions']);
			$queryData['conditions'] = array_merge($queryData['conditions'], $permOptions['conditions']);

			if(empty($queryData['contain'])) $queryData['contain'] = array();
			if(!is_array($queryData['contain'])) $queryData['contain'] = array($queryData['contain']);
			$queryData['contain'] = array_merge($queryData['contain'], $permOptions['contain']);
		}
		
		return $queryData;
	}
	
	public function afterFind($Model, $results, $primary = false) {
		if (User::get('Role.name') == Role::USER ||
						User::get('Role.name') == Role::GUEST) {
			
			$Model->unbindModel(array('hasOne'=>array('UserCategoryPermission', 'Permission', 'GroupCategoryPermission')), false);
		}
		return $results;
	}
	
}