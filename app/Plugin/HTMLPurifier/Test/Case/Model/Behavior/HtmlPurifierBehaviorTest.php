<?php
App::uses('Model', 'Model');
App::uses('HtmlPurifierBehavior', 'HtmlPurifier.HtmlPurifier/Behavior');

class PurifierTestModel extends Model {

	public $useTable = false;

	public $actsAs = array(
		'HtmlPurifier.HtmlPurifier' => array(
			'purifierConfig' => 'default',
			'fields' => array(
				'markup'
			)
		)
	);

}

class HtmlPurifierBehaviorTest extends CakeTestCase {

/**
 * Purifier property
 *
 * @var object
 */
	public $Purifier = null;

/**
 * Fixture data, unclean HTML to test against
 *
 * @var string
 */
	public $uncleanHtml = '<p style="font-weight: bold;"><script>alert("alert!");</script><span style="text-decoration: line-through;" _mce_style="text-decoration: line-through;">shsfhshs</span></p><p><strong>sdhsdhds</strong></p><p><em>shsdh</em><span style="text-decoration: underline;" _mce_style="text-decoration: underline;">dsh</span></p><ul><li>sdgsgssgd</li><li>sdgdsg</li><li>sdgsdgsg</li><li>sdgdg<br></li></ul>';

/**
 * Expected HTML
 *
 * @var string
 */
	public $expectedHtml = '<p><span style="text-decoration:line-through;">shsfhshs</span></p><p><strong>sdhsdhds</strong></p><p><em>shsdh</em><span style="text-decoration:underline;">dsh</span></p><ul><li>sdgsgssgd</li><li>sdgdsg</li><li>sdgsdgsg</li><li>sdgdg</li></ul>';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Model = ClassRegistry::init('PurifierTestModel');

		Purifier::config('default', array(
			'HTML.AllowedElements' => 'a, em, blockquote, p, strong, pre, code, span,ul,ol,li,img',
			'HTML.AllowedAttributes' => 'a.href, a.title, img.src, img.alt, *.style',
			'CSS.AllowedProperties' => 'text-decoration',
			'HTML.TidyLevel' => 'heavy',
			'HTML.Doctype' => 'XHTML 1.0 Transitional'
		));
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Model);
	}

/**
 * testCleanFields
 *
 * @return void
 */
	public function testCleanFields() {
		$data = array(
			'PurifierTestModel' => array(
				'markup' => $this->uncleanHtml
			)
		);
		$result = $this->Model->cleanFields($data);
		$this->assertEquals($result['PurifierTestModel']['markup'], $this->expectedHtml);
	}

/**
 * testBeforeSave
 *
 * @return void
 */
	public function testBeforeSave() {
		$this->Model->set(array(
			'PurifierTestModel' => array(
				'markup' => $this->uncleanHtml
			)
		));
		$this->Model->save();
		$this->assertEquals($this->Model->data['PurifierTestModel']['markup'], $this->expectedHtml);
	}

/**
 * testCleanSomeTinyMceOutput
 *
 * @var array
 */
	public function testCleanSomeTinyMceOutput() {
		$html = $this->Model->purifyHtml($this->uncleanHtml, 'default');
		$this->assertEquals($html, $this->expectedHtml);
	}

}