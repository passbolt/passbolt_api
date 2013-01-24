<?php
/**
 * Permissions controller
 * This file will define how permissions are managed
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Controller.PermissionsController
 * @since        version 2.12.12
 */

App::uses('Permission', 'Model');
 
class PermissionsController extends AppController  {

/**
 * Add permission to a target instance of a given model
 * @param string acoModelName the model of the target aco model instance
 * @param uuid acoInstanceId the uuid of the target aco model instance
 * @return array
 */
	public function addAcoPermissions($acoModelName = '', $acoInstanceId = null) {
		// The target ARO Model name to use
		$aroModelName = null;
		// The given permission type
		$permissionType = isset($this->request->data['Permission']['type']) ? $this->request->data['Permission']['type'] : null;
		// The target aco instance
		$acoInstance = null;

		// check if the target ACO model is permissionable
		if(!$this->Permission->isValidAco($acoModelName)) {
			$this->Message->error(__('The model %s is not permissionable', $acoModelName));
			return;
		}
		
		// no aco instance id given
		if(is_null($acoInstanceId)) {
			$this->Message->error(__('The ACO instance id parameter is missing'));
			return;
		}
		
		// the aco instance id is invalid
		if (!Common::isUuid($acoInstanceId)) {
			$this->Message->error(__('The id %s is invalid', $acoInstanceId));
			return;
		}
		
		// the aco instance instance does not exist
		$this->loadModel($acoModelName);
		if (!$this->$acoModelName->isAuthorized($acoInstanceId, PermissionType::ADMIN)) {
			$this->Message->error(__('The user is not allowed to add a permission to the %s:%s', $acoInstanceId, $acoModelName));
			return;
		}
		
		// Treat the posted data
		// Get the target ARO model and instance id
		$dataAroModelNames = array_keys($this->request->data);
		foreach($dataAroModelNames as $dataAroModelName) {
			// if the current data key is an allowed ARO model
			if($this->Permission->isValidAro($dataAroModelName)) {
				$aroModelName = $dataAroModelName;
				if(isset($this->request->data[$aroModelName]['id'])) {
					$aroInstanceId = $this->request->data[$aroModelName]['id'];
				}
				break;
			}
		}
		
		// not allowed aro model
		if(is_null($aroModelName)) {
			$this->Message->error(__('The target ARO model is not allowed'));
			return;
		}
		
		// the ARO instance id is invalid
		if (!Common::isUuid($aroInstanceId)) {
			$this->Message->error(__('The id %s is invalid', $aroInstanceId));
			return;
		}		
		
		// the ARO instance does not exist
		$this->loadModel($aroModelName);
		if (!$this->$aroModelName->exists($aroInstanceId)) {
			$this->Message->error(__('The ARO instance %s for the model %s doesn\'t exist or the user is not allowed to access it', $aroInstanceId, $aroModelName));
			return;
		}
		
		// no permission type given
		if(is_null($permissionType)) {
			$this->Message->error(__('No permission type given'));
			return;
		}
		
		// the permission type is invalid
		if(!$this->Permission->PermissionType->isValidSerial($permissionType)) {
			$this->Message->error(__('The given permission type is not valid'));
			return;
		}
		
		// Check that the permission is not already existing
		if(!$this->Permission->isUniqueByFields($acoModelName, $acoInstanceId, $aroModelName, $aroInstanceId, $permissionType)) {
			$this->Message->error(__('The permission already exists'));
			return;
		}
		
		// add the new permission
		$data = array('Permission'=>array(
			'aco' => $acoModelName,
			'aco_foreign_key' => $acoInstanceId,
			'aro' => $aroModelName,
			'aro_foreign_key' => $aroInstanceId,
			'type' => $permissionType
		));
		$this->Permission->create();
		$this->Permission->set($data);
		if(!$this->Permission->validates()){
			$this->Message->error($this->Permission->validationErrors);
			return;
		}
		
		$this->Permission->save($data);
		
		// Get back the permission to return to the client
		$viewName = $aroModelName . $acoModelName . 'Permission'; // ex: UserCategoryPermission
		$viewCase = 'viewBy' . $acoModelName; // ex: viewByCategory
		$foreignKey = Inflector::underscore($acoModelName) . '_id'; // category_id
		$this->loadModel($viewName);
		$findData = array(
			$viewName => array( // UserCategoryPermission
				$foreignKey => $acoInstanceId // category_id = $acoInstanceId
			)
		);
		$findOptions = $this->$viewName->getFindOptions($viewCase, User::get('Role.name'), $findData);
		$this->set('data', $this->$viewName->find('first', $findOptions));
		$this->Message->success(__('The permission was sucessfully added'));
	}

/**
 * View applied permissions on an instance
 * @param string acoModelName the model of the target aco model instance
 * @param uuid acoInstanceId the uuid of the target aco model instance
 * @return array
 */
	public function viewAcoPermissions($acoModelName = '', $acoInstanceId = null) {
		$returnValue = array();
		// The target aco instance
		$acoInstance = null;

		// check if the target ACO model is permissionable
		if(!$this->Permission->isValidAco($acoModelName)) {
			$this->Message->error(__('The model %s is not permissionable', $acoModelName));
			return;
		}
				
		// no aco instance id given
		if(is_null($acoInstanceId)) {
			$this->Message->error(__('The ACO instance id parameter is missing'));
			return;
		}
		
		// the aco instance id is invalid
		if (!Common::isUuid($acoInstanceId)) {
			$this->Message->error(__('The id %s is invalid', $acoInstanceId));
			return;
		}
		
		// the aco instance does not exist
		$this->loadModel($acoModelName);
		// here the permissionable behavior will filter data to avoid user who are not allowed
		// not allowed => Permission.type < PermissionType::READ
		$acoInstance = $this->$acoModelName->findById($acoInstanceId);
		if (empty($acoInstance)) {
			$this->Message->error(__('The ACO instance %s for the model %s doesn\'t exist or the user is not allowed to access it', $acoInstanceId, $acoModelName));
			return;
		}
		
		// case used by find options functions
		// this view case has to be defined in model which represent the views
		$viewCase = 'viewBy' . $acoModelName;
		
		// @todo, automatic aro, based on optional parameters !??
		
		// get user's permissions for the target instance 
		// @todo We need a strong use case to check this part. Think about the case, direct user permission on the parent categories !!!  
		$viewName = 'User' . $acoModelName . 'Permission';
		$ModelView = ClassRegistry::init($viewName);
		$foreignKey = Inflector::underscore($acoModelName) . '_id';
		$upData = array(
			$viewName => array(
				$foreignKey => $acoInstanceId
			)
		);
		$upOptions = $ModelView->getFindOptions($viewCase, User::get('Role.name'), $upData);
		$ups = $ModelView->find('all', $upOptions);
		
		// get group's permissions for the target instance
		$viewName = 'Group' . $acoModelName . 'Permission';
		$ModelView = ClassRegistry::init($viewName);
		$foreignKey = strtolower($acoModelName) . '_id';
		$gpData = array(
			$viewName => array(
				$foreignKey => $acoInstanceId
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
