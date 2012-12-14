<?php
/**
 * Group Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.GroupTest
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
App::uses('Group', 'Model');

class GroupTest extends CakeTestCase {

	public $fixtures = array('app.group', 'app.user', 'app.role');

	public function setUp() {
		parent::setUp();
		$this->Group = ClassRegistry::init('Group');
	}

/**
 * Test Name Validation
 * @return void
 */
	public function testNameValidation() {
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => true,
			'test@test.com' => false,
			'1test' => true
		);

		foreach ($testcases as $testcase => $result) {
			$group = array('Group' => array('name' => $testcase));
			$this->Group->set($group);
			if ($result) {
				$msg = 'validation of the group name with "' . $testcase . '" should validate';
			} else {
				$msg = 'validation of the group name with "' . $testcase . '" should not validate';
			}
			$this->assertEqual($this->Group->validates(array('fieldList' => array('name'))), $result, $msg);
		}
	}
}

