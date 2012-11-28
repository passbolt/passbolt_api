<?php

App::import('Lib', 'ApiGenerator.DocblockTools');

class DocblockToolsTestCase extends CakeTestCase {

/**
 * test the correct parsing of comment blocks
 *
 * @return void
 **/
	function testCommentParsing() {
		$comment = <<<EOD
		/**
		 * This is the title
		 *
		 * This is my long description
		 *
		 * @param string \$foo Foo is an input
		 * @param int \$bar Bar is also an input
		 * @return string
		 */
EOD;
		$result = DocblockTools::parseDocBlock($comment);
		$expected = array(
			'description' => "This is the title\n\nThis is my long description",
			'tags' => array (
				'param' => array(
					'foo' => array(
						'type' => 'string',
						'description' => 'Foo is an input',
					),
					'bar' => array(
						'type' => 'int',
						'description' => 'Bar is also an input',
					)
				),
				'return' => 'string',
			),
		);
		$this->assertEqual($result, $expected);

		$comment = <<<EOD
		/**
		 * This is the title
		 *
		 * This is my long description
		 *
		 * @param string \$foo Foo is an input
		 * @param int \$bar Bar is also an input
		 * @param int \$baz Baz is also an input
		 * @return string
		 */
EOD;
		$result = DocblockTools::parseDocBlock($comment);
		$expected = array(
			'description' => "This is the title\n\nThis is my long description", 
			'tags' => array (
				'param' => array(
					'foo' => array(
						'type' => 'string',
						'description' => 'Foo is an input'
					),
					'bar' => array(
						'type' => 'int',
						'description' => 'Bar is also an input',
					),
					'baz' => array(
						'type' => 'int',
						'description' => 'Baz is also an input'
					),
				),
				'return' => 'string',
			),
		);
		$this->assertEqual($result, $expected);
		
		$comment = <<<EOD
		/**
		 * This is the title
		 *
		 * This is my long description
		 *
		 * @param string \$foo Foo is an input
		 * @param int \$bar Bar is also an input
		 * @param int \$baz Baz is also an input
		 * @return string This is a longer doc string for the
		 *   return string.
		 */
EOD;
		$result = DocblockTools::parseDocBlock($comment);
		$this->assertEqual($result['tags']['return'], 'string This is a longer doc string for the return string.', 'parsing spaces failed %s');
		
		$comment = <<<EOD
		/**
		 * This is the title
		 *
		 * This is my long description
		 *
		 * @return string This is a longer doc string for the
		 *	return string.
		 */
EOD;
		$result = DocblockTools::parseDocBlock($comment);
		$this->assertEqual($result['tags']['return'], 'string This is a longer doc string for the return string.', 'parsing single tab failed %s');
		
		
		$comment = <<<EOD
		/**
		 * This is the title
		 *
		 * This is my long description
		 *
		 * @return string This is a longer doc string
		 *	for the return string
		 *	more lines
		 *  more lines.
		 */
EOD;
		$result = DocblockTools::parseDocBlock($comment);
		$expected = 'string This is a longer doc string for the return string more lines more lines.';
		$this->assertEqual($result['tags']['return'], $expected, 'parsing n-line tags failed %s');
		
		
		$comment = <<<EOD
		/**
		 * This is the title
		 *
		 * This is my long description
		 *
		 * @param string \$foo Foo is an input
		 * @param int \$bar Bar is also an input
		 * @param int \$baz Baz is also an input
		 * @return string This is a longer doc string for the
		 * 		return string.
		 */
EOD;
		$result = DocblockTools::parseDocBlock($comment);
		$this->assertEqual($result['tags']['return'], 'string This is a longer doc string for the', 'multiple tabs should not be allowed %s');
		
		$comment = <<<EOD
		/**
		 * This is the title
		 *
		 * This is my long description
		 *
		 * @deprecated
		 */
EOD;
		$result = DocblockTools::parseDocBlock($comment);
		$this->assertTrue(isset($result['tags']['deprecated']), 'parsing deprecated tags failed %s');
	}
/**
 * test parsing singular tags. like deprecated
 *
 * @return void
 **/
	function testParsingDeprecatedTags() {
		$comment = <<<EOD
		/**
		 * This is the title
		 *
		 * This is my long description
		 *
		 * @deprecated
		 */
EOD;
		$result = DocblockTools::parseDocBlock($comment);
		$this->assertTrue(isset($result['tags']['deprecated']), 'parsing deprecated tags failed %s');
	}
/**
 * test that tag parsing is more forgiving of whitespace.
 *
 * @access public
 * @return void
 **/
	function testRelaxedTagParsing() {
		$comment = <<<EOD
		/**
		 * @param int \$normal normal is a good param
		 * @param				string		\$tabs			tabs is a good param
		 * @param        string         \$spaces    spaces is a good param
		 * @param  string    \$spacestwo  spacestwo is a good param
		 *   it also has a newline
		 */
EOD;
		$result = DocblockTools::parseDocBlock($comment);
		$expected = array(
			'normal' => array(
				'type' => 'int',
				'description' => 'normal is a good param'
			),
			'tabs' => array(
				'type' => 'string',
				'description' => 'tabs is a good param',
			),
			'spaces' => array(
				'type' => 'string',
				'description' => 'spaces is a good param'
			),
			'spacestwo' => array(
				'type' => 'string',
				'description' => 'spacestwo is a good param it also has a newline'
			),
		);
		$this->assertEqual(array_keys($result['tags']['param']), array_keys($expected), 'tag Keys do not match %s');
		$this->assertEqual($result['tags']['param']['normal'], $expected['normal']);
		$this->assertEqual($result['tags']['param']['tabs'], $expected['tabs']);
		$this->assertEqual($result['tags']['param']['spaces'], $expected['spaces']);
		$this->assertEqual($result['tags']['param']['spacestwo'], $expected['spacestwo']);
	}
/**
 * test function signature making
 *
 * @return void
 **/
	function testMakeFunctionSignature() {
		App::import('Lib', array('ApiGenerator.FunctionDocumentor', 'ApiGenerator.ClassDocumentor'));
		$Func = new FunctionDocumentor('count');
		$result = DocblockTools::makeFunctionSignature($Func);
		$expected = 'count( $var, $mode )';
		$this->assertEqual($result, $expected);
		
		$Class = new ClassDocumentor('ClassDocumentor');
		$function = $Class->getMethod('_parseComment');
		$result = DocblockTools::makeFunctionSignature($function);
		$expected = '_parseComment( $comments )';
		$this->assertEqual($result, $expected);
	}
}
