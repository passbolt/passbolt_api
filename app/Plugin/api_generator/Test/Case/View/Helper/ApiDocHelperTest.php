<?php
/**
 * Api Doc Helper Test
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
 * @subpackage    api_generator.tests.helpers
 * @since         ApiGenerator 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('ApiDocHelper', 'ApiGenerator.View/Helper');
App::uses('HtmlHelper', 'View/Helper');

/**
* ApiDocHelper test case
*/
class ApiDocHelperTestCase extends CakeTestCase {
/**
 * setup
 *
 * @return void
 **/
	function setup() {
		parent::setup();
		$this->_pluginPath = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
		$controller = new Controller();
		$view = new View($controller);
		$view->set('basePath', $this->_pluginPath);
		$this->ApiDoc = new ApiDocHelper($view);

		Router::reload();
		Router::parse('/');
	}

/**
 * endTest
 *
 * @return void
 **/
	function tearDown() {
		parent::tearDown();
		unset($this->ApiDoc);
	}

/**
 * test inBasePath
 *
 * @return void
 **/
	function testInBasePath() {
		$this->assertFalse($this->ApiDoc->inBasePath('/some/random/path'));
		$this->assertTrue($this->ApiDoc->inBasePath(__FILE__));
	}
/**
 * test fileNameTrimming
 *
 * @return void
 **/
	function testTrimFileName() {
		$result = $this->ApiDoc->trimFileName($this->_pluginPath . '/Test/Case/View/Helper/ApiDocHelperTest.php');
		$this->assertEqual($result, 'Test/Case/View/Helper/ApiDocHelperTest.php');

		$result = $this->ApiDoc->trimFileName('/some/other/path/Test/Case/View/Helper/ApiDocHelperTest.php');
		$expected = 'Test/Case/View/Helper/ApiDocHelperTest.php';
		$this->assertEqual($result, $expected, 'Trim path with different bases is not working');
	}
/**
 * testFileLink
 *
 * Test file link / no link based on base path of file.
 *
 * @return void
 **/
	function testFileLink() {
		$result = $this->ApiDoc->fileLink('/foo/bar');
		$this->assertEqual($result, '/foo/bar');

		$testFile = $this->_pluginPath . '/views/helpers/api_doc.php';

		$result = $this->ApiDoc->fileLink($testFile);
		$expected = array(
			'a' => array('href' => '/api_generator/api_files/view_file/views/helpers/api_doc.php'),
			'views/helpers/api_doc.php',
			'/a'
		);
		$this->assertTags($result, $expected);

		$result = $this->ApiDoc->fileLink($testFile, array('controller' => 'foo', 'action' => 'bar'));
		$expected = array(
			'a' => array('href' => '/api_generator/foo/bar/views/helpers/api_doc.php'),
			'views/helpers/api_doc.php',
			'/a'
		);
		$this->assertTags($result, $expected);
	}
/**
 * test generation of package links.
 *
 * @return void
 **/
	function testPackageLink() {
		$result = $this->ApiDoc->packageLink('foo');
		$expected = array(
			'a' => array('href' => 'http://localhost/api_generator/api_packages/view/foo'),
			'foo',
			'/a'
		);
		$this->assertTags($result, $expected);

		$result = $this->ApiDoc->packageLink('some.package.deep');
		$expected = array(
			'a' => array('href' => 'http://localhost/api_generator/api_packages/view/some/package/deep'),
			'some.package.deep',
			'/a'
		);
		$this->assertTags($result, $expected);

		$result = $this->ApiDoc->packageLink('  some.package.deep');
		$expected = array(
			'a' => array('href' => 'http://localhost/api_generator/api_packages/view/some/package/deep'),
			'  some.package.deep',
			'/a'
		);
		$this->assertTags($result, $expected);
	}
/**
 * test that parsing method links works.
 *
 * @return void
 **/
	function testParsingMethodLinks() {
		$this->ApiDoc->setClassIndex(array('JsHelper', 'Model'));
		$text = 'This is some JsHelper::method() more text here.';
		$expected = '<p>This is some <a href="/api_generator/api_classes/view_class/js-helper#method-JsHelpermethod">JsHelper::method()</a> more text here.</p>';
		$result = $this->ApiDoc->parse($text);
		$this->assertEqual($result, $expected);
	}

/**
 * test access()
 *
 * @return void
 */
	function testAccess() {
		$doc = array(
			'access' => 'public',
			'isStatic' => true
		);
		$this->assertEquals('access public static', $this->ApiDoc->access($doc));
		
		$doc = array(
			'access' => 'public',
			'isStatic' => false
		);
		$this->assertEquals('access public', $this->ApiDoc->access($doc));
	}

}
