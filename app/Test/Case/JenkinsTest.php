<?php
class JenkinsTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('Jenkins test the phpunit ');

        $path = dirname(__FILE__) . DS;
        $suite->addTestFile($path . 'FakeTestCase.php');
        return $suite;
    }
}