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
		return true;
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
		$acoKeyName = strtolower($acoModelName) . '_id';

		$model = Common::getModel("User{$acoModelName}Permission");
		$users = $model->find('all', array(
				'conditions' => array(
					$acoKeyName => $acoInstanceId,
					'permission_type <>' => null
				),
				'contain' => array(
					'User' => array(
						'fields' => array(
							'User.id'
						)
					)
				)
			));

		return $users;
	}
}