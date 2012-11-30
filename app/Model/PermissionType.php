<?php
/**
 * PermissionType Model
 *
 * @copyright		 Copyright 2012, Passbolt.com
 * @license			 http://www.passbolt.com/license
 * @package			 app.Model.PermissionDetail
 * @since				 version 2.12.9
 */

class PermissionType extends AppModel {

	public $useTable = "permissions_types";

		const DENY = '0';
		const READ	= '1';
		const CREATE	= '3';
		const UPDATE = '7';
		const ADMIN	= '15';
}
