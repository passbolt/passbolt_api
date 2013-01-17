<?php
/**
 * All Passbolt Behavior Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Test.Case.AllBehaviorsTest
 * @since         version 1.13.3
 */
class AllBehaviorsTest extends CakeTestSuite {

	public static function suite() {
		$suite = new CakeTestSuite('All behaviors tests');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model' . DS . 'Behavior');
		return $suite;
	}
}
