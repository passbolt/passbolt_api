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
App::uses('PermissionType', 'Model');

class PermissionableBehavior extends ModelBehavior {

/**
 * Details of before find method
 * @link http://api20.cakephp.org/class/model#method-ModelbeforeFind
 * 
 * The permissionnable before find method is used to augment the query to find reccords 
 * functions of the User and the User[AroModelName]Permission model
 */
	public function beforeFind(&$model, $queryData = array()) {
		if (User::get('Role.name') == Role::USER ||
			User::get('Role.name') == Role::GUEST) {
			
			$userPermissionModelName = 'User' . $model->alias . 'Permission'; // ex : UserCategoryPermission, UserResourcePermission
			$foreignModelPrimaryKey = Inflector::underscore($model->alias) . '_id'; // ex : category_id
			
			// Permissionable find request options
			$permOptions = array(
				'fields' => array(
					$userPermissionModelName . '.permission_id',
					$userPermissionModelName . '.permission_type'
				),
				'conditions' =>	 array(
					$userPermissionModelName . '.user_id' => User::get('id'),
					$userPermissionModelName . '.permission_type >' => PermissionType::DENY		
				),
				'contain' => array($userPermissionModelName)
			);

			// Bind the target model to the permissions models
			$model->bindModel(array('hasOne'=>array(
				// @todo automatically bind the [AroModelName][targetAcoModelName]Permission model to the model
				$userPermissionModelName => array(
					'foreignKey' => $foreignModelPrimaryKey
				),
				'GroupCategoryPermission' => array(
					'foreignKey' => $foreignModelPrimaryKey
				),
				'Permission'=> array(
					'foreignKey' => false,
					'conditions' => array('Permission.id' => $userPermissionModelName . '.permission_id '),
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
	public function afterFind(&$model, $results, $primary = false) {
		if (User::get('Role.name') == Role::USER ||
			User::get('Role.name') == Role::GUEST) {
			
			$model->unbindModel(array('hasOne'=>array('UserCategoryPermission', 'Permission', 'GroupCategoryPermission')), false);
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
	public function afterSave(&$model, $created) {
		if($created) {
			$Permission = Common::getModel('Permission');
			// make the creator administrator of the created instance
			if (User::get('Role.name') == Role::USER) {
				$data = array('Permission'=>array(
					'aco' => $model->alias,
					'aco_foreign_key' => $model->id,
					'aro' => 'User',
					'aro_foreign_key' => User::get('User.id'),
					'type' => PermissionType::ADMIN
				));
				$Permission->create();
				$Permission->set($data);
				if(!$Permission->validates()){
					$this->Message->error($Permission->validationErrors);
					return;
				}
				
				$permission = $Permission->save($data);				
			}	
		}
	}
	
/**
 * Check a user is authorized to access a reccord
 * @param uuid id Id of the record
 * @param integer permissionType The permission type
 * @return bool
 */
	public function isAuthorized(&$model, $id, $permissionType = PermissionType::READ) {
		$userPermissionModelName = 'User' . $model->alias . 'Permission';
		$UserPermission = Common::getModel($userPermissionModelName);
		$findOptions = array(
			'fields' => array('permission_id', 'permission_type'),
			'conditions' =>	 array(
				$userPermissionModelName . '.user_id' => User::get('id'),
				$userPermissionModelName . '.permission_type >=' => $permissionType		
			)
		);
		$result = $UserPermission->find('first', $findOptions);
		return !empty($result) ? true : false;
	}
}
