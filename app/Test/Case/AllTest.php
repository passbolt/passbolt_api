<?php
/**
 * All Passbolt Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.AllTest
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
class AllTest extends CakeTestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('All tests');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model');
		return $suite;
	}
}