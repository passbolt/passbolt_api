<?php
/**
 * PermissionType Model
 *
 * @copyright		Copyright 2012, Passbolt.com
 * @license			http://www.passbolt.com/license
 * @package			app.Model.PermissionDetail
 * @since			version 2.12.11
 */

class PermissionType extends AppModel {

	public $useTable = "permissions_types";

	const DENY = '0';
	const READ	= '1';
	const CREATE = '3';
	const UPDATE = '7';
	const ADMIN	= '15';

/**
 * Check if the given type is a valid permission type
 * @param string type The type to check
 * @return boolean
 */
	public function isValidSerial($type) {
		switch($type) {
			case self::DENY:
			case self::READ:
			case self::CREATE:
			case self::UPDATE:
			case self::ADMIN:
				return true;
			break;
			default:
				return false;
			break;
		}
	}
}
