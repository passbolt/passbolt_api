<?php
class AllHtmlPurifierTest extends PHPUnit_Framework_TestSuite {

/**
 * Compile test suite with all tests
 *
 * @return CakeTestSuite The compiled test suite.
 */
	public static function suite() {
		$Suite = new CakeTestSuite('All Html Purifier Plugin Tests');
		$path = dirname(__FILE__);
		$Suite->addTestDirectory($path . DS . 'Model' . DS . 'Behavior');
		$Suite->addTestDirectory($path . DS . 'View' . DS . 'Helper');
		return $Suite;
	}

}
