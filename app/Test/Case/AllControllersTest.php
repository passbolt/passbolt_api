<?php
/**
 * All Passbolt Controllers Tests
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.AllControllerTest
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
class AllControllersTest extends CakeTestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('All controllers tests');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller');
		return $suite;
	}
}
