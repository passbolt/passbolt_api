<?php
/**
 * PermissionDetail Model
 *
 * @copyright		 Copyright 2012, Passbolt.com
 * @license			 http://www.passbolt.com/license
 * @package			 app.Model.PermissionDetail
 * @since				 version 2.12.9
 */
 // TODO : should become PermissionType
class PermissionDetail extends AppModel {
		const DENY = '1';
		const READONLY	= '2';
		const CREATE	= '4';
		const MODIFY = '8';
		const ADMIN	= '16';
}
