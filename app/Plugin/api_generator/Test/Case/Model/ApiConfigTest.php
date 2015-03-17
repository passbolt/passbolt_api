<?php
/**
 * ApiConfig test case
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
App::import('Model', 'ApiGenerator.ApiConfig');
/**
 * ApiConfigTestCase
 *
 *
 * @package api_generator.tests
 **/
/* SAMPLE
 	[paths]
	/home/cake/plugins/api_generator = true
	/home/cake/plugins/api_generator_extensions = true

	[exclude]
	properties = private
	methods = private
	classes = MyClass, MyOtherClass

	[dependencies]
	MyClass = MyBaseClass, MyInterface
	MyOtherClass = MyClass

	[mappings]
	MyClass = My_Funky_File.php
	MyOtherClass = My_Other_File.php
*/

class ApiConfigTestCase extends CakeTestCase {
/**
 * setup
 *
 * @return void
 **/
	function setUp() {
		parent::setUp();
		$this->ApiConfig = ClassRegistry::init('ApiGenerator.ApiConfig');
		$this->ApiConfig->path = TMP . 'api_config.ini';
	}
/**
 * teardown
 *
 * @return void
 **/
	function tearDown() {
		parent::tearDown();
		$Cleanup = new File($this->ApiConfig->path);
		$Cleanup->delete();
		unset($this->ApiConfig);
	}

/**
 * test that makeAbsolute adds on the CAKE_CORE_INCLUDE_PATH
 *
 * @return void
 */
	function testMakeAbosolute() {
		$path = 'View/Helper/HtmlHelper.php';
		$result = $this->ApiConfig->makeAbsolute($path);
		$this->assertEquals(CAKE . $path, $result);
	}

/**
 * test the saving of an ini file
 *
 * @return void
 */
	function testSave() {
		$data = array(
			'[paths]',
			'/home/cake/plugins/api_generator = true',
			'[exclude]',
			'properties = private',
			'method = private',
			'[dependencies]',
			'MyClass = MyBaseClass, MyInterface',
			'[mappings]',
			'MyClass = My_Funky_File.php',
		);
		$this->assertTrue($this->ApiConfig->save($data));
		$this->assertTrue($this->ApiConfig->save(TMP . 'api_config.ini', $data));

		$data = "[paths]\n\n/home/cake/plugins/api_generator = true\n\n";
		$this->assertTrue($this->ApiConfig->save($data));
		$this->assertTrue($this->ApiConfig->save(TMP . 'api_config.ini', $data));

		$data = array(
			'paths' => array(
				'/home/cake/plugins/api_generator' => true
			),
			'exclude' => array(
				'properties' => 'private',
				'methods' => 'private'
			),
			'file' => array(
				'extensions' => 'php, ctp',
				'regex' => '[a-z]'
			),
		);
		$this->assertTrue($this->ApiConfig->save($data));
		$this->assertTrue($this->ApiConfig->save(TMP . 'api_config.ini', $data));
	}

	function testRead() {
		$result = $this->ApiConfig->read();
		$this->assertEqual($result, array());
		
		$data = "[paths]\n\n/home/cake/plugins/api_generator = true\n\n";
		$this->assertTrue($this->ApiConfig->save($data));

		$result = $this->ApiConfig->read();
		$this->assertEqual($result, array(
			'paths' => array(
				'/home/cake/plugins/api_generator' => 'true'
			)
		));

		$result = $this->ApiConfig->read($data);
		$this->assertEqual($result, array(
			'paths' => array(
				'/home/cake/plugins/api_generator' => 'true'
			)
		));

		$data = array(
			'[paths]',
			'/home/cake/plugins/api_generator = true',
		);

		$result = $this->ApiConfig->read();
		$this->assertEqual($result, array(
			'paths' => array(
				'/home/cake/plugins/api_generator' => 'true'
			)
		));


		$data = "[paths]\n\n/Home/Cake/Plugins/Api_generator = true\n\n";
		$result = $this->ApiConfig->read($data);
		$this->assertEqual($result, array(
			'paths' => array(
				'/Home/Cake/Plugins/Api_generator' => 'true'
			)
		));
	}

	function testToString() {
		$data = array(
			'paths' => array(
				'/home/cake/plugins/api_generator' => true
			),
			'exclude' => array(
				'properties' => 'private',
				'methods' => 'private'
			),
			'file' => array(
				'extensions' => 'php, ctp',
				'regex' => '[a-z]'
			),
		);
		$result = $this->ApiConfig->toString($data);
		$expected = "[paths]\n/home/cake/plugins/api_generator = true\n";
		$expected .= "[exclude]\nproperties = private\nmethods = private\n";
		$expected .= "[file]\nextensions = php, ctp\nregex = [a-z]";
		$this->assertEqual($result, $expected);
	}

	function testSaveAndRead() {
		$data = array(
			'paths' => array(
				'/home/cake/plugins/api_generator' => 'true'
			),
			'exclude' => array(
				'properties' => 'private',
				'methods' => 'private'
			),
			'file' => array(
				'extensions' => 'php, ctp',
				'regex' => '[a-z]'
			),
		);
		$this->assertTrue($this->ApiConfig->save($data));
		$result = $this->ApiConfig->read();
		$this->assertEqual($result, $data);
	}
}
