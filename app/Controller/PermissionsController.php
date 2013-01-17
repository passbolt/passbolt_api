<?php
/**
 * Permissions controller
 * This file will define how permissions are managed
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Controller.PermissionsController
 * @since        version 2.12.11
 */

App::uses('Permission', 'Model');
 
class PermissionsController extends AppController  {

/**
 * Add permission on an instance
 * @param string model the model of the instance
 * @param uuid id the id of the target instance
 * @return array
 */
	public function addAcoPermissions($model = '', $id = null) {
		// camelize the model parameter
		$model = Inflector::camelize($model);
		// The model to use relative to the given model string
		$Model = null;
		// The instance relative to the given id string
		$instance = null;

		// check if the target model is permissionable
		if(!in_array($model, Permission::getAllowedAcos())) {
			$this->Message->error(__('The model is not permissionable'));
			return;
		}
		// load the ACO relative model
		$Model = ClassRegistry::init($model);
		
		// no instance id given
		if(is_null($id)) {
			$this->Message->error(__('The id is missing'));
			return;
		}
		
		// the id is invalid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The id is invalid'));
			return;
		}
		
		// the instance does not exist
		$instance = $Model->findById($id);
		if (!$instance) {
			$this->Message->error(__('The instance doesn\'t exist'));
			return;
		}
		
		
		
		$this->Message->success();
	}

/**
 * View applied permissions on an instance
 * @param string model the model of the instance
 * @param uuid id the id of the target instance
 * @return array
 */
	public function viewAcoPermissions($model = null, $id = null) {
		$returnValue = array();
		// camelize the model parameter
		$model = Inflector::camelize($model);
		// The model to use relative to the given model string
		$Model = null;
		// The instance relative to the given id string
		$instance = null;

		// check if the target model is permissionable
		if(!in_array($model, Permission::getAllowedAcos())) {
			$this->Message->error(__('The model is not permissionable'));
			return;
		}
		
		$Model = ClassRegistry::init($model);
		
		// no instance id given
		if(is_null($id)) {
			$this->Message->error(__('The id is missing'));
			return;
		}
		
		// the id is invalid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The id is invalid'));
			return;
		}
		
		// the instance does not exist
		$instance = $Model->findById($id);
		if (!$instance) {
			$this->Message->error(__('The instance doesn\'t exist'));
			return;
		}
		
		// case used by find options function
		$viewCase = 'viewBy' . $model;
		
		// get user's permissions for the target instance
		$viewName = 'User' . $model . 'Permission';
		$ModelView = ClassRegistry::init($viewName);
		$foreignKey = strtolower($model) . '_id';
		$upData = array(
			$viewName => array(
				$foreignKey => $id
			)
		);
		$upOptions = $ModelView->getFindOptions($viewCase, User::get('Role.name'), $upData);
		$ups = $ModelView->find('all', $upOptions);
		
		// get group's permissions for the target instance
		$viewName = 'Group' . $model . 'Permission';
		$ModelView = ClassRegistry::init($viewName);
		$foreignKey = strtolower($model) . '_id';
		$gpData = array(
			$viewName => array(
				$foreignKey => $id
			)
		);
		$gpOptions = $ModelView->getFindOptions($viewCase, User::get('Role.name'), $gpData);
		$gps = $ModelView->find('all', $gpOptions);
		
		// merge user's and group's permissions
		$returnValue = array_merge($ups, $gps);
		
		$this->Message->success();
		$this->set('data', $returnValue);
	}
}

?>
