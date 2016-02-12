<?php
/**
 * Permission Type Model Test
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @package       app.Test.Case.Model.PermissionTypeTest
 * @since         version 2.14.6
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
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
		$this->assertEquals(true, PermissionType::DENY < PermissionType::READ, 'PermissionType::DENY should be inferior to PermissionType::READ');
		$this->assertEquals(true, PermissionType::READ < PermissionType::CREATE, 'PermissionType::READ should be inferior to PermissionType::CREATE');
		$this->assertEquals(true, PermissionType::CREATE < PermissionType::UPDATE, 'PermissionType::CREATE should be inferior to PermissionType::UPDATE');
		$this->assertEquals(true, PermissionType::UPDATE < PermissionType::OWNER, 'PermissionType::UPDATE should be inferior to PermissionType::OWNER');
	}
}