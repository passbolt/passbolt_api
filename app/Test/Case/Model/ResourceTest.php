<?php
/**
 * Resource Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.ResourceTest
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
App::uses('Resource', 'Model');

class ResourceTest extends CakeTestCase {

	public $fixtures = array('app.resource', 'app.user', 'app.role');

	public function setUp() {
		parent::setUp();
		$this->Resource = ClassRegistry::init('Resource');
	}

/**
 * Test Name Validation
 * @return void
 */
	public function testNameValidation() {
		$testcases = array(
			'' => false, '?!#' => true, 'test' => true,
			'test@test.com' => true, '1test' => true
		);
		foreach ($testcases as $testcase => $result) {
			$resource = array('Resource' => array('name' => $testcase));
			$this->Resource->set($resource);
			if ($result) {
				$msg = 'validation of the resource uri with "' . $testcase . '" should validate';
			} else {
				$msg = 'validation of the resource uri with "' . $testcase . '" should not validate';
			}
			$this->assertEqual($this->Resource->validates(array('fieldList' => array('name'))), $result, $msg);
		}
	}

/**
 * Test Username Validation
 * @return void
 */
	public function testUsernameValidation() {
		$testcases = array(
			'' => false, '?!#' => true, 'test' => true,
			'test@test.com' => true, '1test' => true
		);
		foreach ($testcases as $testcase => $result) {
			$resource = array('Resource' => array('username' => $testcase));
			$this->Resource->set($resource);
		if($result) $msg = 'validation of the resource username with ' . $testcase . ' should validate';
		else $msg = 'validation of the resource username with ' . $testcase . ' should not validate';
			$this->assertEqual($this->Resource->validates(array('fieldList' => array('username'))), $result, $msg);
		}
	}

/**
 * Test Username Validation
 * @return void
 */
	public function testUriValidation() {
		$testcases = array(
			'' => true, '?!#' => true, 'test' => true,
			'test@test.com' => true, 'http://www.passbolt.com' => true, '192.168.10.3' => true
		);
		foreach ($testcases as $testcase => $result) {
			$resource = array('Resource' => array('uri' => $testcase));
			$this->Resource->set($resource);
			if($result) $msg = 'validation of the resource uri with ' . $testcase . ' should validate';
			else $msg = 'validation of the resource uri with ' . $testcase . ' should not validate';
			$this->assertEqual($this->Resource->validates(array('fieldList' => array('uri'))), $result, $msg);
		}
	}

/**
 * Test expiry Date Validation
 * @return void
 */
	public function testExpiryDateValidation() {
		$testcases = array(
			'' => true, '14 Decembre 1980' => false, '27-12-2006' => false,
			'2006-14-12' => false, '2024-12-24' => true
		);
		foreach ($testcases as $testcase => $result) {
			$resource = array('Resource' => array('expiry_date' => $testcase));
			$this->Resource->set($resource);
			if($result) $msg = 'validation of the resource expiry date with ' . $testcase . ' should validate';
			else $msg = 'validation of the resource expiry date with ' . $testcase . ' should not validate';
			$this->assertEqual($this->Resource->validates(array('fieldList' => array('expiry_date'))), $result, $msg);
		}
	}

/**
 * Test Description Validation
 * @return void
 */
	public function testDescriptionValidation() {
		$testcases = array(
			'' => true, '?!#' => true, 'test' => true,
			'test@test.com' => true, 'http://www.passbolt.com' => true, '<strong>test</strong>' => false
		);
		foreach ($testcases as $testcase => $result) {
			$resource = array('Resource' => array('description' => $testcase));
			$this->Resource->set($resource);
			if($result) $msg = 'validation of the resource description with ' . $testcase . ' should validate';
			else $msg = 'validation of the resource description with ' . $testcase . ' should not validate';
			$this->assertEqual($this->Resource->validates(array('fieldList' => array('description'))), $result, $msg);
		}
	}
}
