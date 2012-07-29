<?php
//require_once(dirname(__FILE__).'/../../webroot/test.php');
class AllTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('All Passbolt test cases');

        $path = dirname(__FILE__);
        $suite->addTestFile($path . DS . 'Controller' . DS . 'CategoriesControllerTest.php');
        $suite->addTestFile($path . DS . 'Model' . DS . 'CategoryTest.php');
        return $suite;
    }
}
