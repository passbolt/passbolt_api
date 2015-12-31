<?php
/**
 * CakePHPStandardTest
 */
class CakePHPStandardTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		parent::setUp();
		if (empty($this->helper)) {
			$this->helper = new TestHelper();
		}
	}

/**
 * testFiles
 *
 * Run simple syntax checks, if the filename ends with pass.php - expect it to pass
 */
	public static function testProvider() {
		$tests = array();

		$standard = dirname(dirname(__FILE__));

		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(dirname(__FILE__) . '/files'));
		foreach ($iterator as $dir) {
			if ($dir->isDir()) {
				continue;
			}

			$file = $dir->getPathname();
			$expectPass = (substr($file, -8) === 'pass.php');
			$tests[] = array(
				$file,
				$standard,
				$expectPass
			);
		}
		return $tests;
	}

/**
 * _testFile
 *
 * @dataProvider testProvider
 *
 * @param string $file
 * @param string $standard
 * @param boolean $expectPass
 */
	public function testFile($file, $standard, $expectPass) {
		$outputStr = $this->helper->runPhpCs($file);
		if ($expectPass) {
			$this->assertNotRegExp(
				"/FOUND \d+ ERROR/",
				$outputStr,
				basename($file) . ' - expected to pass with no errors, some were reported. '
			);
		} else {
			$this->assertRegExp(
				"/FOUND \d+ ERROR/",
				$outputStr,
				basename($file) . ' - expected failures, none reported. '
			);
		}
	}

}
