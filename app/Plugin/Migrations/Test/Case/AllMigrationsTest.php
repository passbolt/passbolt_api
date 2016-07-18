<?php
/**
 * Copyright 2009 - 2014, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009 - 2014, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class AllMigrationsPluginTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('All Migrations Plugin Tests');

		$basePath = CakePlugin::path('Migrations') . DS . 'Test' . DS . 'Case' . DS;

		// Libs
		$suite->addTestFile($basePath . 'Lib' . DS . 'MigrationVersionTest.php');
		$suite->addTestFile($basePath . 'Lib' . DS . 'Model' . DS . 'CakeMigrationTest.php');
		$suite->addTestFile($basePath . 'Lib' . DS . 'Migration' . DS . 'PrecheckConditionTest.php');

		// Console
		$suite->addTestFile($basePath . 'Console' . DS . 'Command' . DS . 'MigrationShellTest.php');

		return $suite;
	}

}