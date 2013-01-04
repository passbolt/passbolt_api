<?php
/**
 * Permission Behavior
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Test.Case.Model.Behavior.PermissionBehavior
 * @since        version 2.12.7
 */

App::uses('Permission', 'Model');

class PermissionableBehavior extends ModelBehavior {

	public function beforeFind($Model, $queryData = array()) {
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
		return $queryData;
	}
	
	public function afterFind($Model, $results, $primary = false) {
		$Model->unbindModel(array('hasOne'=>array('UserCategoryPermission', 'Permission', 'GroupCategoryPermission')), false);
		return $results;
	}
	
}