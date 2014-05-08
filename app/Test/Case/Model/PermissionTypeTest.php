<?php
/**
 * Permission Type Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.PermissionTypeTest
 * @since         version 2.14.6
 * @license       http://www.passbolt.com/license
 */
App::uses('PermissionType', 'Model');

if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class PermissionTypeTest extends CakeTestCase {

	public $fixtures = array('app.permissionsType');

	public function setUp() {
		parent::setUp();

		$this->PermissionType = ClassRegistry::init('PermissionType');
	}

	public function testPermissionTypeConstants() {
		$this->assertEqual(true, PermissionType::DENY < PermissionType::READ, 'PermissionType::DENY should be inferior to PermissionType::READ');
		$this->assertEqual(true, PermissionType::READ < PermissionType::CREATE, 'PermissionType::READ should be inferior to PermissionType::CREATE');
		$this->assertEqual(true, PermissionType::CREATE < PermissionType::UPDATE, 'PermissionType::CREATE should be inferior to PermissionType::UPDATE');
		$this->assertEqual(true, PermissionType::UPDATE < PermissionType::ADMIN, 'PermissionType::UPDATE should be inferior to PermissionType::ADMIN');
	}
}