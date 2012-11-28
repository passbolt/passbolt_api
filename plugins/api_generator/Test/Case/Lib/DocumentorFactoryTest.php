<?php

App::import('Lib', 'ApiGenerator.DocumentorFactory');

class DocumentorFactoryTestCase extends CakeTestCase {
/**
 * testGetReflector
 *
 * @access public
 * @return void
 */	
	function testGetReflector() {
		$result = DocumentorFactory::getReflector('function', 'substr');
		$this->assertTrue($result instanceof FunctionDocumentor);
		
		$result = DocumentorFactory::getReflector('class', 'DocumentorFactory');
		$this->assertTrue($result instanceof ClassDocumentor);
		
		$result = DocumentorFactory::getReflector('DocumentorFactory');
		$this->assertTrue($result instanceof ClassDocumentor);
	}
}
