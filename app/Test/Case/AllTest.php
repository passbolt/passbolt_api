<?php
/**
 * All Passbolt Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Test.Case.AllTest
 * @since         version 2.12.7
 */
class AllTest extends CakeTestSuite {

	public static function suite() {
		$suite = new CakeTestSuite('All tests');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller' . DS . 'Component');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model' . DS . 'Behavior');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model' . DS . 'Utility');
		return $suite;
	}
}
