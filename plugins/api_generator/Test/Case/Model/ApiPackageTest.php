<?php
/**
 * ApiPackage test case
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
App::import('Model', 'ApiGenerator.ApiPackage');
App::import('Lib', 'ApiGenerator.ClassDocumentor');

/**
 * ApiPackageSampleClass doc block
 *
 * @package default.another.level
 */
class ApiPackageSampleClass {

}

/**
 * ApiPackage Sample Class
 *
 * @package default
 * @subpackage another.level.two
 **/
class ApiPackageSampleClassTwo {

}

/**
 * ApiPackageTestCase
 *
 * @package api_generator.tests
 **/
class ApiPackageTestCase extends CakeTestCase {
/**
 * fixtures
 *
 * @var array
 **/
	var $fixtures = array('plugin.api_generator.api_class', 'plugin.api_generator.api_package');
/**
 * startTest
 *
 * @return void
 **/
	function setup() {
		parent::setup();
		$this->_path = APP . 'plugins' . DS . 'api_generator';
		$this->_testAppPath = dirname(dirname(dirname(__FILE__))) . DS . 'test_app' . DS;

		Configure::write('ApiGenerator.filePath', $this->_path);
		$this->ApiPackage = ClassRegistry::init('ApiPackage');
	}
/**
 * endTest
 *
 * @return void
 **/
	function tearDown() {
		parent::tearDown();
		unset($this->ApiPackage);
	}
/**
 * test getting the package index tree
 *
 * @return void
 **/
	function testPackageIndex() {
		$result = $this->ApiPackage->getPackageIndex();

		$this->assertFalse(isset($result[0]['ApiClass']), 'ApiClass has snuck in, big queries are happening %s');
		$this->assertTrue(isset($result[0]['children']), 'No children, might not be a tree %s');
	}
/**
 * test parsing of @package / @subpackage strings in doc block arrays.
 *
 * @return void
 **/
	function testPackageStringParse() {
		try {
			$docBlock = array();
			$result = $this->ApiPackage->parsePackage($docBlock);
			$this->fail('No exception thrown');
		} catch(InvalidArgumentException $e) {
			$this->assertTrue(true, 'Exception thrown');
		}

		$docBlock = array(
			'tags' => array(
				'package' => 'cake',
				'subpackage' => 'model.behavior'
			)
		);
		$result = $this->ApiPackage->parsePackage($docBlock);
		$expected = array('cake', 'model', 'behavior');
		$this->assertEqual($result, $expected);

		$docBlock = array(
			'tags' => array(
				'package' => 'cake',
				'subpackage' => 'cake.model.behavior'
			)
		);
		$result = $this->ApiPackage->parsePackage($docBlock);
		$expected = array('cake', 'model', 'behavior');
		$this->assertEqual($result, $expected, 'Duplicates not removed %s');

		$docBlock = array(
			'tags' => array(
				'package' => '			cake	',
				'subpackage' => '     cake.model.behavior'
			)
		);
		$result = $this->ApiPackage->parsePackage($docBlock);
		$expected = array('cake', 'model', 'behavior');
		$this->assertEqual($result, $expected, 'Duplicates not removed %s');
	}
/**
 * test updating the package tree and ensure that duplicate named packages do not get inserted.
 *
 * @return void
 **/
	function testUpdatePackageTree() {
		$packages = array('cake', 'model', 'datasource', 'dbo');
		$result = (bool) $this->ApiPackage->updatePackageTree($packages);
		$this->assertTrue($result);

		$result = $this->ApiPackage->findAllByParentId(4);
		$this->assertEqual(count($result), 2);

		$packages = array('cake', 'model', 'datasource', 'dbo');
		$result = (bool) $this->ApiPackage->updatePackageTree($packages);
		$this->assertTrue($result);

		$result = $this->ApiPackage->findAllBySlug('model');
		$this->assertEqual(count($result), 1, 'Too many model slugs');
	}
/**
 * test that findEndPackageId finds the end package and returns its Id
 *
 * @return void
 **/
	function testFindEndPackageId() {
		$packages = array('cake', 'model');
		$result = $this->ApiPackage->findEndPackageId($packages);
		$this->assertEqual($result, 4);
	}

}
