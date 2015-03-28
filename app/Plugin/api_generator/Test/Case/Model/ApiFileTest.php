<?php
/**
 * ApiFile test case
 *
 * PHP 5.2+
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2008-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2008-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org
 * @package       api_generator
 * @subpackage    api_generator.tests.models
 * @since         ApiGenerator 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
App::import('Model', 'ApiGenerator.ApiFile');

/**
 * ApiFileTestCase
 *
 * @package api_generator.tests
 **/
class ApiFileTestCase extends CakeTestCase {
/**
 * startTest
 *
 * @return void
 **/
	function setUp() {
		parent::setUp();
		$this->_path = dirname(dirname(dirname(dirname(__FILE__))));
		Configure::write('ApiGenerator.filePath', $this->_path);
		$this->ApiFile = new ApiFile();
		$this->_testAppPath = dirname(dirname(dirname(__FILE__))) . DS . 'test_app' . DS;

		$this->ApiFile->excludeDirectories = array();
		$this->ApiFile->excludeFiles = array();
		
	}
/**
 * endTest
 *
 * @return void
 **/
	function tearDown() {
		parent::tearDown();
		unset($this->ApiFile);
	}
/**
 * test extractor loading
 *
 * @return void
 **/
	function testLoadExtractor() {
		$this->ApiFile->loadExtractor('class', 'ApiFile');
		$result = $this->ApiFile->getExtractor();
		$this->assertTrue($result instanceof ReflectionClass);
		$this->assertEqual($result->name, 'ApiFile');
	}
/**
 * testReading files from a folder
 *  - test ignored files
 *  - test ignored folders
 *  - test extensions
 *  - test reading evil paths
 *
 * @return void
 **/
	function testRead() {
		$result = $this->ApiFile->read($this->_path  . DS . 'Model');
		$this->assertTrue(empty($result[0]));
		$expected = array('ApiConfig.php', 'ApiClass.php', 'ApiFile.php', 'ApiPackage.php', 'ApiGeneratorAppModel.php');
		sort($result[1]);
		sort($expected);
		$this->assertEquals($expected, $result[1]);

		$this->ApiFile->allowedExtensions = array('php', 'ctp');
		$result = $this->ApiFile->read($this->_path  . DS . 'Model');
		$this->assertTrue(empty($result[0]));
		$expected = array('ApiConfig.php', 'ApiClass.php', 'ApiFile.php', 'ApiPackage.php', 'ApiGeneratorAppModel.php');
		sort($result[1]);
		sort($expected);
		$this->assertEqual($result[1], $expected);

		$this->ApiFile->excludeFiles[] = 'ApiClass.php';
		$result = $this->ApiFile->read($this->_path . DS . 'Model');
		$expected = array('ApiConfig.php', 'ApiFile.php', 'ApiGeneratorAppModel.php', 'ApiPackage.php');
		$this->assertEqual($result[1], $expected);

		$this->ApiFile->excludeDirectories = array('models', 'controllers');
		$result = $this->ApiFile->read($this->_path);
		$this->assertFalse(in_array('controllers', $result[0]));
		$this->assertFalse(in_array('models', $result[0]));

		$this->ApiFile->allowedExtensions = array('css');
		$this->ApiFile->excludeDirectories = array('Model');
		$result = $this->ApiFile->read($this->_path);
		$this->assertTrue(empty($result[1]), 'file with not allowed extension found. %s');
		$this->assertFalse(in_array('models', $result[0]), 'file in ignored folder found %s');

		$this->ApiFile->allowedExtensions = array('php');
		$this->ApiFile->excludeDirectories = array();
		$result = $this->ApiFile->read($this->_path . '../../../../../../');
		$this->assertEqual($result, array(array(), array()), 'Evil file path read.');
	}
/**
 * test file list generation
 *  - test folder exclusion
 *  - test extension permission
 *  - test file regex
 *
 * @return void
 **/
	function testGetFileList() {
		$this->ApiFile->excludeDirectories = array('Config', 'webroot');
		$result = $this->ApiFile->fileList(APP);
		$core = APP . 'Config' . DS . 'core.php';
		$index = WWW_ROOT . 'index.php';
		$this->assertFalse(in_array($core, $result));
		$this->assertFalse(in_array($index, $result));

		$this->ApiFile->excludeDirectories = array();
		$this->ApiFile->allowedExtensions = array('css');
		$result = $this->ApiFile->fileList(APP);
		$core = APP . 'Config' . DS . 'core.php';
		$this->assertFalse(in_array($core, $result));
		$this->assertFalse(in_array($index, $result));

		$this->ApiFile->excludeDirectories = array();
		$this->ApiFile->allowedExtensions = array('css');
		$result = $this->ApiFile->fileList(APP);
		$core = APP . 'Config' . DS . 'core.php';
		$this->assertFalse(in_array($core, $result));
		$this->assertFalse(in_array($index, $result));

		$this->ApiFile->excludeDirectories = array();
		$this->ApiFile->allowedExtensions = array('php');
		$this->ApiFile->excludeFiles = array('index.php');
		$this->ApiFile->fileRegExp = '[a-z_0-9]+';
		$result = $this->ApiFile->fileList(APP);
		$core = APP . 'Config' . DS . 'core.php';
		$index = APP . 'index.php';
		$this->assertTrue(in_array($core, $result));
		$this->assertFalse(in_array($index, $result));
	}
/**
 * test Processed docs retrieval
 *
 * @return void
 **/
	function testGetDocs() {
		$result = $this->ApiFile->getDocs();
		$this->assertTrue(empty($result));

		$this->ApiFile->loadExtractor('class', 'ApiFile');
		$result = $this->ApiFile->getDocs();
		$this->assertTrue($result instanceof ClassDocumentor);
		$this->assertTrue(isset($result->classInfo));
	}
/**
 * test loadFile() on a file that has already been included once
 *
 * @return void
 **/
	function testLoadFileOnAlreadyIncludedFile() {
		$this->ApiFile->allowedExtensions = array('test', 'php');

		$result = $this->ApiFile->loadFile(__FILE__);
		$this->assertTrue(isset($result['class']));
		$this->assertTrue(isset($result['function']));
		$this->assertTrue($result['class'][__CLASS__] instanceof ClassDocumentor);

		$result = $this->ApiFile->loadFile($this->_path . DS . 'Model' . DS . 'ApiClass.php');
		$this->assertTrue(isset($result['class']));
		$this->assertTrue(isset($result['function']));
		$this->assertTrue($result['class']['ApiClass'] instanceof ClassDocumentor);
	}
/**
 * test loading an evil path that leads to a secured location
 *
 * @return void
 **/
	function testLoadingEvilPath() {
		$result = $this->ApiFile->loadFile($this->_path . '../../../Config/database.php');
		$this->assertEqual($result, array('class' => array(), 'function' => array()));
	}
/**
 * test loadFile() with a dependancy map.
 *
 * @return void
 **/
	function testLoadFileWithDependancyMap() {
		$this->assertNoErrors();
		$this->ApiFile->classMap['MappedRandomFile'] = $this->_testAppPath . 'mapped_file.php';
		$this->ApiFile->classMap['SillyTestInterface'] = $this->_testAppPath . 'silly_interface_file.php';
		$this->ApiFile->loadFile($this->_testAppPath . 'test_file.php');
	}
/**
 * test loading a file whose classes have no parent classes
 *
 * @return void
 **/
	function testLoadingFileWithNoParentClass() {
		$result = $this->ApiFile->loadFile($this->_testAppPath . 'no_parents.php');
		$this->assertEqual(count($result['class']), 2);
	}
/**
 * test loading files that contain both parent and child classes.
 *
 * @return void
 **/
	function testLoadingFileParentClassInSameFile() {
		$result = $this->ApiFile->loadFile($this->_testAppPath . 'inline_parents.php');
		$this->assertEqual(count($result['class']), 4);
	}
/**
 * test loading files that have sloppy code conventions
 *
 * @return void
 **/
	function testLoadingSloppyFiles() {
		$result = $this->ApiFile->loadFile($this->_testAppPath . 'sloppy_code.php');
		$this->assertEqual(count($result['class']), 5);
	}
/**
 * Test that ApiFile Throws down when needed.
 *
 * @return void
 **/
	function testExceptionThrowing() {
		$this->ApiFile->classMap = array();
		try {
			$this->ApiFile->loadFile($this->_testAppPath . 'throw_down.php');
			$this->assertFalse(true, 'No exception was thrown, when loading a garbage file');
		} catch (MissingClassException $e) {
			$this->assertTrue(true);
		}
	}
/**
 * Test Loading files, that have method config() and having global config() from core included
 *
 * @return void
 **/
	function testLoadingFileWithAmbiguousFunction() {
		$cacheFile = CAKE . 'Cache' . DS . 'Cache.php';
		$this->assertTrue(function_exists('config'));
		$results = $this->ApiFile->loadFile($cacheFile);
		$this->assertEqual($results['function'], array());
	}
/**
 * test isExcluded
 *
 * @return void
 */
	function testIsAllowed() {
		$file = '/something/test/file.php';

		$this->ApiFile->excludeDirectories = array('test');
		$this->assertFalse($this->ApiFile->isAllowed($file));

		$this->ApiFile->excludeDirectories = array();
		$this->ApiFile->excludeFiles = array('file.php');
		$this->assertFalse($this->ApiFile->isAllowed($file));

		$this->ApiFile->excludeDirectories = array();
		$this->ApiFile->excludeFiles = array();
		$this->ApiFile->allowedExtensions = array('banana');
		$this->assertFalse($this->ApiFile->isAllowed($file));
	}
/**
 * test that _initConfig doesn't need ', ' between items.
 *
 * @return void
 */
	function testInitConfigNotNeedingSpaces() {
		$config = ClassRegistry::init('ApiGenerator.ApiConfig');
		$config->data = array(
			'exclude' => array(
				'files' => 'one,two, three',
				'methods' => 'one,two, three',
				'directories' => 'one, two,three',
				'properties' => 'one, two,three'
			)
		);
		ClassRegistry::addObject('ApiConfig', $config);
		$file = new ApiFile();
		$expected = array('one', 'two', 'three');

		$this->assertEqual($file->excludeFiles, $expected);
		$this->assertEqual($file->excludeMethods, $expected);
		$this->assertEqual($file->excludeProperties, $expected);
		$this->assertEqual($file->excludeDirectories, $expected);
	}
}
