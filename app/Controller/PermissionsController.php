<?php
/**
 * Permissions controller
 * This file will define how permissions are managed
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Controller.PermissionsController
 * @since        version 2.12.7
 */

App::uses('Permissions', 'Model');

class PermissionsController extends AppController  {


/**
 * View applied permission on a resource
 * @param uuid resourceId the id of the resource
 * @return array
 */
	public function viewResourcePermissions($resourceId = null) {
		$returnValue = array();

		$Resource = ClassRegistry::init('Resource');
		
		// no resource id given
		if(is_null($resourceId)) {
			$this->Message->error(__('The resource id is missing'));
			return;
		}
		
		// the resource id is invalid
		if (!Common::isUuid($resourceId)) {
			$this->Message->error(__('The resource id invalid'));
			return;
		}
		
		// the resource does not exist
		$resource = $Resource->findById($resourceId);
		if (!$resource) {
			$this->Message->error(__('The resource doesn\'t exist'));
			return;
		}
		
		// Get user resource permissions
		$urpData = array(
			'UserResourcePermission' => array(
				'resource_id'	=> $resourceId
			)
		);
		$urpOptions = $Resource->UserResourcePermission->getFindOptions('viewByResource', User::get('Role.name'), $urpData);
		$urp = $Resource->UserResourcePermission->find('all', $urpOptions);
		
		// Get group resource permissions
		$grpData = array(
			'GroupResourcePermission' => array(
				'resource_id'	=> $resourceId
			)
		);
		$grpOptions = $Resource->GroupResourcePermission->getFindOptions('viewByResource', User::get('Role.name'), $grpData);
		$grp = $Resource->GroupResourcePermission->find('all', $grpOptions);
		$returnValue = array_merge($urp, $grp);
		
		$this->Message->success();
		$this->set('data', $returnValue);
	}
}

?>
