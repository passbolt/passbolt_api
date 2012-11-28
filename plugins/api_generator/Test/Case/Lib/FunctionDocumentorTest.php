<?php
App::import('Lib', 'ApiGenerator.FunctionDocumentor');
/**
 * my_random_test_function
 *
 * @param string $param this is a param
 * @param string $one this is one
 * @param string $two this is two
 * @return void
 */
function my_random_test_function($param, $one = 'foo', $two = 'param') {

}
/**
 * FunctionDocumentor Test Case
 *
 * @package default
 * @author Mark Story
 */
class FunctionDocumentorTestCase extends CakeTestCase {
/**
 * testInfo Getting
 *
 * @return void
 **/
	function testGetInfo() {
		$Docs = new FunctionDocumentor('my_random_test_function');
		$result = $Docs->getInfo();
		$expected = array(
			'name' => 'my_random_test_function',
			'declaredInFile' => __FILE__,
			'startLine' => 11,
			'endLine' => 13,
			'comment' => array(
				'description' => 'my_random_test_function',
				'tags' => array(
					'return' => 'void',
					'param' => array(
						'param' => array(
							'type' => 'string',
							'description' => 'this is a param',
						),
						'one' => array(
							'type' => 'string',
							'description' => 'this is one',
						),
						'two' => array(
							'type' => 'string',
							'description' => 'this is two',
						)
					)
				)
			),
			'internal' => false,
			'signature' => 'my_random_test_function( $param, $one = \'foo\', $two = \'param\' )',
		);
		$this->assertEqual($result, $expected);
		$this->assertEqual($Docs->info, $expected);
	}
/**
 * test getParameters
 *
 * @return void
 **/
	function testGetParameters() {
		$Docs = new FunctionDocumentor('my_random_test_function');
		$result = $Docs->getParams();
		$expected = array(
			'param' => array(
				'optional' => false,
				'default' => NULL,
				'position' => 0,
				'type' => 'string',
				'comment' => 'this is a param',
				'hasDefault' => false,
			),
			'one' => array(
				'optional' => true,
				'default' => 'foo',
				'position' => 1,
				'type' => 'string',
				'comment' => 'this is one',
				'hasDefault' => true,
			),
			'two' => array(
				'optional' => true,
				'default' => 'param',
				'position' => 2,
				'type' => 'string',
				'comment' => 'this is two',
				'hasDefault' => true,
			)
		);
		$this->assertEqual($result, $expected);
	}
}