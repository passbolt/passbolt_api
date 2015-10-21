<?php
/**
 * Copyright 2010 - 2013, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2013, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class AllImaginePluginTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('All Imagine Plugin Tests');

		$basePath = CakePlugin::path('Imagine') . DS . 'Test' . DS . 'Case' . DS;

		$suite->addTestFile($basePath . 'Controller' . DS . 'Component' . DS . 'ImagineComponentTest.php');

		$suite->addTestFile($basePath . 'Lib' . DS . 'ImagineUtilityTest.php');

		$suite->addTestFile($basePath . 'Model' . DS . 'Behavior' . DS . 'ImagineBehaviorTest.php');

		$suite->addTestFile($basePath . 'View' . DS . 'Helper' . DS . 'ImagineHelperTest.php');

		return $suite;
	}

}