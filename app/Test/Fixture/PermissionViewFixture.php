<?php
/**
 * PermissionViewFixture
 *
 */

require_once( APP . 'Config/Schema/permissions.php');

class PermissionViewFixture extends CakeTestFixture {

	public function create($db) {
		$permissionsSchema = new PermissionsSchema();
		$permissionsSchema->init();
		return true;
	}

}
