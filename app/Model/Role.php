<?php
/**
 * User Role Model
 *
 * Copyright 2012, Passbolt
 * Passbolt(tm), the simple password management solution 
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright		 Copyright 2012, Passbolt.com
 * @package			 app.Model.role
 * @since				 version 2.12.7
 * @license			 http://www.passbolt.com/license
 */
class Role extends AppModel {
	public $name = 'role';

	const GUEST = 'guest';
	const USER	= 'user';
	const ADMIN = 'admin';
	const ROOT	= 'root';
}
