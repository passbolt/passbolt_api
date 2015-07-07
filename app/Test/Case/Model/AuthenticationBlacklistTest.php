<?php
/**
 * AuthenticationBlacklist Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.AuthenticationBlacklist
 * @since         version 2.13.03
 * @license       http://www.passbolt.com/license
 */
App::uses('AuthenticationBlacklist', 'Model');

class AuthenticationBlacklistTest extends CakeTestCase {

	public $fixtures = array('app.authenticationBlacklist');

	public function setUp() {
		parent::setUp();
		$this->AuthenticationBlacklist = ClassRegistry::init('AuthenticationBlacklist');
	}

/**
 * Test Ip Validation
 * @return void
 */
	public function testIpValidation() {
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => false,
			'192.168.10.2e' => false,
			'192.168.10.24' => true,
			'192.168.*.*' => true,
			'192.168.10.10/24' => true,
			'192.168.10.10-192.168.10.100' => true,
		);
		foreach ($testcases as $testcase => $result) {
			$ab = array('AuthenticationBlacklist' => array('ip' => $testcase));
			$this->AuthenticationBlacklist->set($ab);
			if($result) $msg = 'validation of the ip with ' . $testcase . ' should validate';
			else $msg = 'validation of the ip with ' . $testcase . ' should not validate';
			$this->assertEquals($this->AuthenticationBlacklist->validates(array('fieldList' => array('ip'))), $result, $msg);
		}
	}
}