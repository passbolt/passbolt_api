<?php
/**
 * All Passbolt Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.AllModelTest
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
class AllModelsTest extends CakeTestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('All models tests');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model');
		return $suite;
	}
}
