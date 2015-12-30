<?php
/**
 * PermissionHelper Component
 * This class offers tools for controllers who needs to access permissions.
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Controller.PermissionHelperComponent
 * @since        version 2.12.7
 */

class PermissionHelperComponent extends Component {

	/**
	 * Initialize
	 */
	public function initialize(Controller $controller, $settings = array()) {
		$this->Controller = $controller;
		$this->Permission = Common::getModel('Permission');
		parent::initialize($controller);
	}

	/**
	 * Get list of ACO permissions.
	 *
	 * @param string $acoModelName
	 * @param null   $acoInstanceId
	 *
	 * @return array
	 */
	public function findAcoPermissions($acoModelName = '', $acoInstanceId = null) {

		// check if the target ACO model is permissionable
		if (!$this->Permission->isValidAco($acoModelName)) {
			throw new Exception(__('The model %s is not permissionable', $acoModelName));
		}

		// no aco instance id given
		if (is_null($acoInstanceId)) {
			throw new Exception(__('The %s id is missing', $acoModelName));
		}

		// the aco instance id is invalid
		if (!Common::isUuid($acoInstanceId)) {
			throw new Exception(__('The %s id is invalid', $acoModelName));
		}

		// the aco instance does not exist
		$this->$acoModelName = Common::getModel($acoModelName);
		// here the permissionable behavior will filter data to avoid user who are not allowed
		// not allowed => Permission.type < PermissionType::READ
		$acoInstance = $this->$acoModelName->findById($acoInstanceId);
		if (empty($acoInstance)) {
			// If the user is not allowed to access an instance, this instance is simply hidden to him
			throw new Exception(__('The %s does not exist', $acoModelName));
		}

		// case used by find options functions
		// this view case has to be defined in model which represent the views
		$viewCase = 'viewBy' . $acoModelName;

		// @todo, automatic aro, based on optional parameters !??

		// get user's permissions for the target instance
		// @todo We need a strong use case to check this part. Think about the case, direct user permission on the parent categories !!!
		$viewName = 'User' . $acoModelName . 'Permission';
		$ModelView = Common::getModel($viewName);
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
		$ModelView = Common::getModel($viewName);
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

		return $returnValue;
	}

	/**
	 * Get a list of users who can access to the given ACO, with their corresponding permissions.
	 *
	 * @param $acoModelName
	 * @param $acoInstanceId
	 *
	 * @return array
	 */
	public function findAcoUsers($acoModelName, $acoInstanceId) {
		$AcoModel = Common::getModel($acoModelName);
		return $AcoModel->getAuthorizedUsers($acoInstanceId);
	}
}