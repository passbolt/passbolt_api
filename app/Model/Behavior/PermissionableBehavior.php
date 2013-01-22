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

/**
 * Details of before find method
 * @link http://api20.cakephp.org/class/model#method-ModelbeforeFind
 * 
 * The permissionnable before find method is used to augment the query to find reccords 
 * functions of the User and the User[AroModelName]Permission model
 */
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
				// @todo automatically bind the [AroModelName][targetAcoModelName]Permission model to the model
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
	
/**
 * Details of after find method
 * @link http://api20.cakephp.org/class/model#method-ModelafterFind
 */
	public function afterFind($Model, $results, $primary = false) {
		if (User::get('Role.name') == Role::USER ||
						User::get('Role.name') == Role::GUEST) {
			
			$Model->unbindModel(array('hasOne'=>array('UserCategoryPermission', 'Permission', 'GroupCategoryPermission')), false);
		}
		return $results;
	}

/**
 * Details of after save method
 * @link http://api20.cakephp.org/class/model#method-ModelafterSave
 * 
 * The permissionnable after save method is used to automatically give to the user
 * the ADMIN right to the reccords he has just inserted
 */
	public function afterSave($Model, $created) {
		// make the creator administrator of the created instance
		if (User::get('Role.name') == Role::USER) {
			$data = array('Permission'=>array(
				'aco' => $this->alias,
				'aco_foreign_key' => $this->id,
				'aro' => 'User',
				'aro_foreign_key' => User::get('User.id'),
				'type' => PermissionType::ADMIN
			));
			$this->Permission->create();
			$this->Permission->set($data);
			if(!$this->Permission->validates()){
				$this->Message->error($this->Permission->validationErrors);
				return;
			}
			
			$permission = $this->Permission->save($data);				
		}
	}
}
