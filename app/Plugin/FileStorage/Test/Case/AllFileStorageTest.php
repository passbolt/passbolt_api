<?php
/**
 * FileStorage Plugin - All plugin tests
 */
class AllFileStorageTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite method, defines tests for this suite.
 *
 * @return void
 */
	public static function suite() {
		$Suite = new CakeTestSuite('All FileStorage tests');

		$path = dirname(__FILE__);

		$Suite->addTestDirectory($path . DS . 'Lib');
		$Suite->addTestDirectory($path . DS . 'Lib' . DS . 'Utility');
		$Suite->addTestDirectory($path . DS . 'Event');
		$Suite->addTestDirectory($path . DS . 'View' . DS . 'Helper');
		$Suite->addTestDirectory($path . DS . 'Model');
		$Suite->addTestDirectory($path . DS . 'Model' . DS . 'Behavior');

		return $Suite;
	}

}