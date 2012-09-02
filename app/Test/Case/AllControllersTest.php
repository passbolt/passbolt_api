<?php
/**
 * All Passbolt Controllers Tests
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Test.Case.AllControllerTest
 * @since         version 2.12.7
 */
class AllControllersTest extends CakeTestSuite {

	public static function suite() {
		$suite = new CakeTestSuite('All controllers tests');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller');
		return $suite;
	}
}
