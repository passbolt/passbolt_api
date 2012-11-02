<?php
/**
 * CategoryResource Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.CategoryResourceTest
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
App::uses('CategoryResource', 'Model');
App::uses('User', 'Model');

class CategoryResourceTest extends CakeTestCase {

	public $fixtures = array('app.category', 'app.resource', 'app.user', 'app.role');

	public function setUp() {
		parent::setUp();
		$this->CategoryResource = ClassRegistry::init('CategoryResource');
	}

/**
 * Test CategoryId Validation
 * @return void
 */
	public function testCategoryIdValidation() {
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => false,
			'4ff6111b-efb8-4a26-aab4-2184cbdd56c' => false,
			'4ff6111c-8534-4d17-869c-2184cbdd56cb' => true
		);

		foreach ($testcases as $testcase => $result) {
			$cr = array('CategoryResource' => array('category_id' => $testcase));
			$this->CategoryResource->set($cr);
			if ($result) {
				$msg = 'validation of the category_resource "category id" with "' . $testcase . '" should validate';
			} else {
				$msg = 'validation of the category_resource "category id" with "' . $testcase . '" should not validate';
			}
			$validation = $this->CategoryResource->validates(array('fieldList' => array('category_id')));
			if (!$validation) {
				$msg .= print_r($this->CategoryResource->invalidFields(), true);
			}
			$this->assertEqual($validation, $result, "$msg");
		}
	}

/**
 * Test ResourceId Validation
 * @return void
 */
	public function testResourceIdValidation() {
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => false,
			'4ff6111b-efb8-4a26-aab4-2184cbdd56c' => false,
			'4ff6111b-efb8-4a26-aab4-2184cbdd56cb' => true
		);
		foreach ($testcases as $testcase => $result) {
			$cr = array('CategoryResource' => array('resource_id' => $testcase));
			$this->CategoryResource->set($cr);
			if ($result) {
				$msg = 'validation of the category_resource "resource id" with "' . $testcase . '" should validate';
			} else {
				$msg = 'validation of the category_resource "resource id" with "' . $testcase . '" should not validate';
			}
			$validation = $this->CategoryResource->validates(array('fieldList' => array('resource_id')));
			if (!$validation) {
				$msg .= print_r($this->CategoryResource->invalidFields(), true);
			}
			$this->assertEqual($validation, $result, $msg);
		}
	}

}
