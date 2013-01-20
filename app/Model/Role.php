<?php
/**
 * User Role Model
 *
 * Copyright 2012, Passbolt
 * Passbolt(tm), the simple password management solution 
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright		Copyright 2012, Passbolt.com
 * @package			app.Model.role
 * @since			version 2.12.7
 * @license			http://www.passbolt.com/license
 */
class Role extends AppModel {
	
	const GUEST = 'guest';
	const USER	= 'user';
	const ADMIN = 'admin';
	const ROOT	= 'root';
	
	public static function getFindFields($case = '', $role = Role::USER) {
		$returnValue = array();
		switch ($case) {
			case 'view':
				$returnValue = array(
					'fields' => array(
						'Role.id', 'Role.name'
					)
				);
				break;
			default:
				$returnValue = array(
					'fields' => array()
				);
				break;
		}
		return $returnValue;
	}
}
