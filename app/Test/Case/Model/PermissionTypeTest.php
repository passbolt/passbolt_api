<?php
/**
 * Permission Type Model Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
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
		$this->assertEquals(true, PermissionType::READ < PermissionType::UPDATE, 'PermissionType::READ should be inferior to PermissionType::UPDATE');
		$this->assertEquals(true, PermissionType::UPDATE < PermissionType::OWNER, 'PermissionType::UPDATE should be inferior to PermissionType::OWNER');
	}

	public function testIsValidSerial() {
		$testcases = array(
			PermissionType::DENY => false,
			'' => false,
			'DENY' => false,
			'READ' => false,
			'UPDATE' => false,
			'OWNER' => false,
			PermissionType::READ => true,
			PermissionType::UPDATE => true,
			PermissionType::OWNER => true,
		);
		foreach ($testcases as $testcase => $result) {
			if ($result) {
				$msg = 'permission type ' . $testcase . ' should validate';
			} else {
				$msg = 'permission type ' . $testcase . ' should not validate';
			}
			$this->assertEquals($this->PermissionType->isValidSerial($testcase), $result, $msg);
		}
	}
}