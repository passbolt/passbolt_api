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
App::uses('PermissionType', 'Model');
 
class PermissionsController extends AppController  {

	public function __construct($request = NULL, $response = NULL) {
		parent::__construct($request, $response);
		$this->PermissionType = ClassRegistry::init('PermissionType');
	}

/**
 * Add permission to a target instance of a given model
 * @param string acoModelName the model of the target aco model instance
 * @param uuid acoInstanceId the uuid of the target aco model instance
 * @return array
 */
	public function addAcoPermissions($acoModelName = '', $acoInstanceId = null) {
		// The target ACO Model to use
		$acoModel = null;
		// The target instance to add permissions on
		$acoInstance = null;
		// The target ARO Model name to use
		$aroModelName = null;
		// The target ARO Model to use
		$aroModel = null;
		// The target instance to add permissions on
		$aroInstance = null;
		// The given permission type
		$permissionType = isset($this->request->data['Permission']['type']) ? $this->request->data['Permission']['type'] : null; 

		// check if the target ACO model is permissionable
		if(!$this->Permission->isValidAco($acoModelName)) {
			$this->Message->error(__('The model (%s) is not permissionable', $acoModelName));
			return;
		}
		
		// no aco instance id given
		if(is_null($acoInstanceId)) {
			$this->Message->error(__('The ACO instance id parameter is missing'));
			return;
		}
		
		// the aco instance id is invalid
		if (!Common::isUuid($acoInstanceId)) {
			$this->Message->error(__('The id (%s) is invalid', $acoInstanceId));
			return;
		}
		
		// the aco instance instance does not exist
		if (!$this->Permission->isInstanceExisting($acoInstanceId, $acoModelName)) {
			$this->Message->error(__('The ACO instance (%s) for the model (%s) doesn\'t exist or the user is not allowed to access it', $acoInstanceId, $acoModelName));
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
		
		// no ARO instance id given
		if(is_null($aroInstanceId)) {
			$this->Message->error(__('The ARO id is missing'));
			return;
		}
		
		// the ARO instance id is invalid
		if (!Common::isUuid($aroInstanceId)) {
			$this->Message->error(__('The id (%s) is invalid', $id));
			return;
		}		
		// the ARO instance does not exist
		if (!$this->Permission->isInstanceExisting($aroInstanceId, $aroModelName)) {
			$this->Message->error(__('The ARO instance (%s) for the model (%s) doesn\'t exist or the user is not allowed to access it', $aroInstanceId, $aroModelName));
			return;
		}
		
		// Check the given permission
		if(is_null($permissionType)) {
			$this->Message->error(__('No permission type given'));
			return;
		}
		
		// Valid permission type
		if(!$this->PermissionType->isValidSerial($permissionType)) {
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
		
		$permission = $this->Permission->save($data);		
		$this->Message->success();
	}

/**
 * View applied permissions on an instance
 * @param string acoModelName the model of the target aco model instance
 * @param uuid acoInstanceId the uuid of the target aco model instance
 * @return array
 */
	public function viewAcoPermissions($acoModelName = '', $acoInstanceId = null) {
		$returnValue = array();
		// The target ACO Model to use
		$acoModel = null;
		// The target instance to add permissions on
		$acoInstance = null;

		// check if the target ACO model is permissionable
		if(!$this->Permission->isValidAco($acoModelName)) {
			$this->Message->error(__('The model (%s) is not permissionable', $acoModelName));
			return;
		}
				
		// no aco instance id given
		if(is_null($acoInstanceId)) {
			$this->Message->error(__('The ACO instance id parameter is missing'));
			return;
		}
		
		// the aco instance id is invalid
		if (!Common::isUuid($acoInstanceId)) {
			$this->Message->error(__('The id (%s) is invalid', $acoInstanceId));
			return;
		}
		
		// the aco instance instance does not exist
		if (!$this->Permission->isInstanceExisting($acoInstanceId, $acoModelName)) {
			$this->Message->error(__('The ACO instance (%s) for the model (%s) doesn\'t exist or the user is not allowed to access it', $acoInstanceId, $acoModelName));
			return;
		}
		
		// case used by find options functions
		// this view case has to be defined in model which represent the views
		$viewCase = 'viewBy' . $acoModelName;
		
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
