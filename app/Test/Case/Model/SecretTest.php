<?php
/**
 * Secret Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.ResourceTest
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
App::uses('Secret', 'Model');

class SecretTest extends CakeTestCase {

	public $fixtures = array('app.secret', 'app.resource', 'app.user', 'app.role');

	public function setUp() {
		parent::setUp();
		$this->Secret = ClassRegistry::init('Secret');
		$this->Secret->Resource->Behaviors->disable('Permissionable');
	}

/**
 * Test User Validation
 * @return void
 */
	public function testUserIdValidation() {
		$testcases = array(
			'' => false, '?!#' => false, '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce' => true,
			'bbd56042-cccc-11e1-a0c5-080027796c4b' => false
		);
		foreach ($testcases as $testcase => $result) {
			$secret = array('Secret' => array('user_id' => $testcase));
			$this->Secret->set($secret);
			if ($result) {
				$msg = 'validation of the secret user_id with "' . $testcase . '" should validate';
			} else {
				$msg = 'validation of the secret user_id with "' . $testcase . '" should not validate';
			}
			$this->assertEqual($this->Secret->validates(array('fieldList' => array('user_id'))), $result, $msg);
		}
	}

/**
 * Test Resource Validation
 * @return void
 */
	public function testResourceIdValidation() {
		$testcases = array(
			'' => false, '?!#' => false, '50d77ffa-7278-41fc-a4bb-1b63d7a10fce' => true,
			'509bb871-3b14-4877-8a88-fb098cebc04b' => false
		);
		foreach ($testcases as $testcase => $result) {
			$secret = array('Secret' => array('resource_id' => $testcase));
			$this->Secret->set($secret);
			if($result) $msg = 'validation of the secret resource_id with ' . $testcase . ' should validate';
			else $msg = 'validation of the secret resource_id with ' . $testcase . ' should not validate';
			$this->assertEqual($this->Secret->validates(array('fieldList' => array('resource_id'))), $result, $msg);
		}
	}

/**
 * Test Data Validation
 * @return void
 */
	public function testDataValidation() {
		$testcases = array(
			'' => false,
			'!#*' => true,
			'blabla' => true
		);
		foreach ($testcases as $testcase => $result) {
			$secret = array('Secret' => array(
				'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
				'user_id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
				'data' => $testcase
			));
			$this->Secret->create();
			$this->Secret->set($secret);
			if($result) $msg = 'validation of the secret data with ' . $testcase . ' should validate';
			else $msg = 'validation of the secret data with ' . $testcase . ' should not validate';
			$validate = $this->Secret->validates(array('fieldList' => array('data')));
			$msg .= print_r($secret, true);
			$msg .= print_r($this->Secret->invalidFields(), true);
			$this->assertEqual($validate, $result, $msg);
		}
	}
}
