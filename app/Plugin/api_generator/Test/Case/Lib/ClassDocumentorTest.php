<?php
/**
 * ClassDocumentor test case
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

App::import('Lib', 'ApiGenerator.ClassDocumentor');

/**
 * SimpleDocumentorSubjectClass
 *
 * A simple class to test ClassInfo introspection
 *
 * @package this is my package
 * @another-tag long value
 */
abstract class SimpleDocumentorSubjectClass extends StdClass implements Countable {
/**
 * This var is protected
 *
 * @var string
 **/
	protected $_protectedVar;
/**
 * This var is public
 *
 * @var string
 **/
	public $publicVar = 'value';
/**
 * This var is public static
 *
 * @var string
 **/
	public static $publicStatic;
/**
 * count
 * 
 * Implementation of Countable interface
 *
 * @access public
 * @return integer
 */
	public function count() { }
/**
 * something
 * 
 * does something
 *
 * @param string $arg1 First arg
 * @param string $arg2 Second arg
 * @access public
 * @return integer
 */
	protected function something($arg1, $arg2 = 'file') { }
/**
 * goGo
 * 
 * does lots of cool things
 * @param string $param a parameter
 * @return void
 **/
	public static function goGo($param) { }

/**
 * An abstract function
 *
 * @return void
 */
	abstract public function isAbstract();
}

/**
 * Test DocumentExtractor 
 * 
 * gives access to protected methods
 *
 * @package cake.api_documentor.tests
 */
class TestClassDocumentor extends ClassDocumentor {

}
/**
 * ClassDocumentor Test Case
 *
 * @package default
 * @author Mark Story
 **/
class ClassDocumentorTestCase extends CakeTestCase {
/**
 * test the ClassInfo introspection
 *
 * @return void
 **/
	function testGetClassInfo() {
		$Docs = new TestClassDocumentor('SimpleDocumentorSubjectClass');
		$result = $Docs->getClassInfo();
		$expected = array (
			'name' => 'SimpleDocumentorSubjectClass', 
			'fileName' => __FILE__,
			'classDescription' => 'abstract class SimpleDocumentorSubjectClass extends stdClass implements Countable ', 
			'comment' => array ( 
				'description' => "SimpleDocumentorSubjectClass\n\nA simple class to test ClassInfo introspection", 
				'tags' => array (
					'package' => 'this is my package', 
					'another-tag' => 'long value'
				), 
			),
			'parents' => array('stdClass'),
			'interfaces' => array('Countable')
		);
		$this->assertEqual($result, $expected);
		$this->assertEqual($Docs->classInfo, $expected);
	}
/**
 * Test getting properties and their info
 *
 * @return void
 **/
	function testGetProperties() {
		$Docs = new TestClassDocumentor('SimpleDocumentorSubjectClass');
		$result = $Docs->getProperties();
		$expected = array( 
			array(
				'name' => '_protectedVar',
				'declaredInClass' => 'SimpleDocumentorSubjectClass',
				'comment' => array(
					'description' => 'This var is protected', 
					'tags' => array(
						'var' => 'string'
					)
				), 
				'access' => 'protected'
			), 
			array(
				'name' => 'publicVar',
				'declaredInClass' => 'SimpleDocumentorSubjectClass',
				'comment' => array(
					'description' => 'This var is public', 
					'tags' => array(
						'var' => 'string'
					)
				),
				'access' => 'public'
			), 
			array(
				'name' => 'publicStatic',
				'declaredInClass' => 'SimpleDocumentorSubjectClass', 
				'comment' => array(
					'description' => 'This var is public static', 
					'tags' => array(
						'var' => 'string'
					)
				),
				'access' => 'public static'
			)
		);
		$this->assertEqual($result, $expected);
		$this->assertEqual($Docs->properties, $expected);
	}
/**
 * test getting of methods
 *
 * @return void
 **/
	function testGetMethods() {
		$Docs = new TestClassDocumentor('SimpleDocumentorSubjectClass');
		$result = $Docs->getMethods();
		$expected = array(
			array(
				'name' => 'count', 
				'comment' => array(
					'description' => "count\n\nImplementation of Countable interface",
					'tags' => array(
						'access' => 'public',
						'return' => 'integer'
					)
				),
				'startLine' => '58',
				'declaredInClass' => 'SimpleDocumentorSubjectClass',
				'declaredInFile' => __FILE__,
				'signature' => 'count(  )',
				'isStatic' => false,
				'isAbstract' => false,
				'args' => array( ), 
				'access' => 'public',
			), 
			array(
				'name' => 'something', 
				'comment' => array(
					'description' => "something\n\ndoes something", 
					'tags' => array(
						'access' => 'public', 
						'return' => 'integer'
					)
				),
				'startLine' => '69',
				'declaredInClass' => 'SimpleDocumentorSubjectClass',
				'declaredInFile' => __FILE__,
				'signature' => 'something( $arg1, $arg2 = \'file\' )',
				'isStatic' => false,
				'isAbstract' => false,
				'args' => array(
					'arg1' => array( 
						'optional' => false,
						'default' => NULL,
						'hasDefault' => false,
						'position' => 0,
						'type' => 'string',
						'comment' => 'First arg'
					), 
					'arg2' => array(
						'optional' => true,
						'default' => 'file',
						'hasDefault' => true,
						'position' => 1,
						'type' => 'string',
						'comment' => 'Second arg'
					)
				),
				'access' => 'protected',
			),
			array(
				'name' => 'goGo', 
				'comment' => array( 
					'description' => "goGo\n\ndoes lots of cool things", 
					'tags' => array(
						'return' => 'void'
					)
				),
				'startLine' => '77',
				'declaredInClass' => 'SimpleDocumentorSubjectClass',
				'declaredInFile' => __FILE__,
				'signature' => 'goGo( $param )',
				'isStatic' => true,
				'isAbstract' => false,
				'args' => array(
					'param' => array(
						'optional' => false,
						'default' => NULL,
						'hasDefault' => false,
						'position' => 0,
						'type' => 'string',
						'comment' => 'a parameter'
					)
				),
				'access' => 'public',
			),
			array(
				'name' => 'isAbstract', 
				'comment' => array( 
					'description' => "An abstract function", 
					'tags' => array(
						'return' => 'void'
					)
				),
				'startLine' => '84',
				'declaredInClass' => 'SimpleDocumentorSubjectClass',
				'declaredInFile' => __FILE__,
				'signature' => 'isAbstract(  )',
				'isStatic' => false,
				'isAbstract' => true,
				'args' => array(
				),
				'access' => 'public',
			)
		);
		$this->assertEquals($expected, $result);
		$this->assertEquals($expected, $Docs->methods);
	}
/**
 * test getAll()
 *
 * @return void
 **/
	function testGetAll() {
		$Docs = new TestClassDocumentor('SimpleDocumentorSubjectClass');
		$Docs->getAll();
		$this->assertFalse(empty($Docs->classInfo));
		$this->assertFalse(empty($Docs->properties));
		$this->assertFalse(empty($Docs->methods));
	}

	function testHasParentMethods() {
		$docs = new ClassDocumentor('SimpleDocumentorSubjectClass');
		$this->assertFalse($docs->hasParentMethods());
		
		$docs = new ClassDocumentor(get_class($this));
		$this->assertTrue($docs->hasParentMethods());
	}
	
	function testHasParentProperties() {
		$docs = new ClassDocumentor('SimpleDocumentorSubjectClass');
		$this->assertFalse($docs->hasParentProperties());
		
		$docs = new ClassDocumentor(get_class($this));
		$this->assertTrue($docs->hasParentProperties());
	}
}
