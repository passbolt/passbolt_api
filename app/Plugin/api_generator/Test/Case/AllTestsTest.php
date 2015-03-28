<?php
 
class AllTestsTest extends CakeTestSuite {
/**
 *
 * @return PHPUnit_Framework_TestSuite the instance of PHPUnit_Framework_TestSuite
 */
	public static function suite() {
		$path = dirname(__FILE__);
		echo $path;
		$suite = new CakeTestSuite('All ApiGenerator Tests.');
		$suite->addTestDirectoryRecursive($path);
		return $suite;
	}
}
