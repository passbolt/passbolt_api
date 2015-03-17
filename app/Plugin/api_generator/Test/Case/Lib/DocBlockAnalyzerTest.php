<?php
App::import('Lib', 'ApiGenerator.DocBlockAnalyzer');
App::import('Lib', 'ApiGenerator.ClassDocumentor');

/**
 * A class to run rules against
 *
 **/
class TestSubjectOne {
	public $noDoc = '';
/**
 * This one has a complete doc string
 *
 * @var string
 **/
	public $hasDoc;

	public function hasNoDocs() {

	}
/**
 * This func has docs
 *
 * @return void
 **/
	public function hasDocs() {

	}
/**
 * I haz docs, but no param tags
 *
 * @return void
 **/
	public function hasNoParams($one, $two, $three) {
		
	}
/**
 * This function has everything
 *
 * @param string $one Its the first param
 * @param string $two the second param
 * @param string $three the third param
 * @link http://mark-story.com
 * @return void
 **/
	public function bestFunction($one, $two, $three) {

	}
}

class DocBlockAnalyzerTestCase extends CakeTestCase {
/**
 * test construction and rule building.
 *
 * @return void
 **/
	function testConstruction() {
		$analyze = new DocBlockAnalyzer(array('MissingLink', 'IncompleteTags'));
		$rules = $analyze->getRules();
		$this->assertEqual(array_keys($rules), array('MissingLink', 'IncompleteTags'));
	}
/**
 * test that the source setting only allows Documentors.
 *
 * @expectedException RuntimeException
 * @return void
 **/
	function testSourceSetting() {
		$analyze = new DocBlockAnalyzer();
		$reflection = new ClassDocumentor('TestSubjectOne');
		$result = $analyze->setSource($reflection);
		$this->assertTrue($result);
		
		$fail = new StdClass();
		$result = $analyze->setSource($fail);
		$this->assertFalse($result);
	}
/**
 * test analyze method
 *
 * @return void
 **/
	function testAnalyze() {
		//test that rules get called properly
		$this->getMock('DocBlockRule', array(), array(), 'MockDocBlockRule');
		$analyze = new DocBlockAnalyzer(array('Mock'));
		$analyze->rules['Mock']->expects($this->exactly(7))->method('setSubject');
		$analyze->rules['Mock']->expects($this->exactly(7))->method('score');

		$reflection = new ClassDocumentor('TestSubjectOne');
		$result = $analyze->analyze($reflection);
		
		$analyze = new DocBlockAnalyzer();
		$reflection = new ClassDocumentor('TestSubjectOne');
		$result = $analyze->analyze($reflection);

		$this->assertEqual(count($result['properties']), 2);
		$this->assertEqual(count($result['methods']), 4);
		$this->assertTrue(isset($result['finalScore']));
	}
/**
 * test the link tag rule
 *
 * @return void
 **/
	function testLinkTagRule() {
		$analyze = new DocBlockAnalyzer();
		$reflection = new ClassDocumentor('TestSubjectOne');
		$result = $analyze->analyze($reflection);

		$links = Set::extract($result['properties'], '{n}.scores.{n}.rule');
		$this->assertEqual(count($links), count($result['properties']));
		
		$rules = Set::extract($result['methods'], '{n}.scores.{n}.rule');
		$this->assertNotEqual(count($rules[1]), count($rules[3]));
	}
/**
 * test the missing param tag rule
 *
 * @return void
 **/
	function testParamTagRule() {
		$analyze = new DocBlockAnalyzer();
		$reflection = new ClassDocumentor('TestSubjectOne');
		$result = $analyze->analyze($reflection);
		$result = $result['methods'][2];
		
		$this->assertEqual($result['scores'][1]['rule'], 'MissingParams');
		$this->assertEqual($result['scores'][1]['score'], 0);
	}
/**
 * test the empty doc block rule
 *
 * @return void
 **/
	function testEmptyDocblockRule() {
		$analyze = new DocBlockAnalyzer();
		$reflection = new ClassDocumentor('TestSubjectOne');
		$result = $analyze->analyze($reflection);
		$result = $result['methods'][0];

		$this->assertEqual($result['subject'], 'hasNoDocs');
		$this->assertEqual($result['scores'][1]['rule'], 'Empty');
		$this->assertEqual($result['scores'][1]['score'], '0');
	}
/**
 * test that the classInfo scoring works as intended.
 *
 * @return void
 **/
	function testClassInfoScoring() {
		$analyze = new DocBlockAnalyzer();
		$reflection = new ClassDocumentor('TestSubjectOne');
		$result = $analyze->analyze($reflection);
		
		$this->assertEqual($result['classInfo']['subject'], 'classInfo');
		$this->assertEqual($result['classInfo']['scores'][0]['rule'], 'MissingLink');
		$this->assertEqual($result['classInfo']['scores'][1]['rule'], 'IncompleteTags');
		$this->assertEqual($result['classInfo']['totalScore'], 0.5);
	}
/**
 * test method scoring results.
 *
 * @return void
 **/
	function testMethodScoring() {
		$analyze = new DocBlockAnalyzer();
		$reflection = new ClassDocumentor('TestSubjectOne');
		$result = $analyze->analyze($reflection);
		
		$this->assertEqual($result['methods'][0]['subject'], 'hasNoDocs');
		$this->assertEqual($result['methods'][0]['scores'][0]['rule'], 'MissingLink');
		$this->assertEqual($result['methods'][0]['scores'][1]['rule'], 'Empty');
		$this->assertEqual($result['methods'][0]['scores'][2]['rule'], 'IncompleteTags');
		$this->assertEqual($result['methods'][0]['totalScore'], 0.25);
	}
/**
 * test property scoring results.
 *
 * @return void
 **/
	function testPropertyScoring() {
		$analyze = new DocBlockAnalyzer();
		$reflection = new ClassDocumentor('TestSubjectOne');
		$result = $analyze->analyze($reflection);

		$this->assertEqual($result['properties'][1]['subject'], 'hasDoc');
		$this->assertEqual($result['properties'][1]['scores'][0]['rule'], 'MissingLink');
		$this->assertEqual($result['properties'][1]['totalScore'], 0.75);
	}
}
