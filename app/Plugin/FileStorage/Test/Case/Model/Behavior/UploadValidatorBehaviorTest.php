<?php
/**
 * Upload Validator Behavior Test
 *
 * @author Florian Krämer
 * @copyright 2012 Florian Krämer
 * @license MIT
 */
App::uses('Model', 'Model');
App::uses('UploadValidatorBehavior', 'FileStorage.Model\Behavior');

/**
 * TheVoid class
 *
 * @package       Cake.Test.Case.Model
 */
class VoidUploadModel extends CakeTestModel {

	/**
	 * name property
	 *
	 * @var string 'TheVoid'
	 */
	public $name = 'VoidUploadModel';

	/**
	 * useTable property
	 *
	 * @var bool false
	 */
	public $useTable = false;
}

/**
 * UploadValidatorBehaviorTest class
 *
 * @package       Cake.Test.Case.Model.Behavior
 */
class UploadValidatorBehaviorTest extends CakeTestCase {

/**
 * Holds the instance of the model
 *
 * @var mixed
 */
	public $Article = null;

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * startTest
 *
 * @return void
 */
	public function setUp() {
		$this->Model = new VoidUploadModel();
		$this->Model->Behaviors->load('FileStorage.UploadValidator', array(
			'localFile' => true));
		$this->FileUpload = $this->Model->Behaviors->UploadValidator;
		$this->testFilePath = CakePlugin::path('FileStorage') . 'Test' . DS . 'Fixture' . DS . 'File' . DS;
	}

/**
 * endTest
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Model);
		ClassRegistry::flush();
	}

/**
 * testValidateMime
 *
 * @return void
 */
	public function testValidateUploadExtension() {
		$this->Model->Behaviors->unload('FileStorage.UploadValidator');
		$this->Model->Behaviors->load('FileStorage.UploadValidator', array(
			'localFile' => true,
			'allowedExtensions' => array('png')));
		$this->Model->data[$this->Model->alias]['file']['name'] = $this->testFilePath . 'cake.icon.jpg';
		$this->assertFalse($this->Model->validateUploadExtension(array('png')));

		$this->Model->data[$this->Model->alias]['file']['name'] = $this->testFilePath . 'cake.icon.png';
		$this->assertTrue($this->Model->validateUploadExtension(array('png')));
	}

/**
 * testValidateMime
 *
 * @return void
 */
	public function testValidateMime() {
		$this->Model->data[$this->Model->alias]['file']['tmp_name'] = $this->testFilePath . 'cake.icon.png';
		$this->assertFalse($this->Model->validateAllowedMimeTypes(array('application/json')));

		$this->Model->data[$this->Model->alias]['file']['tmp_name'] = $this->testFilePath . 'cake.icon.png';
		$this->assertTrue($this->Model->validateAllowedMimeTypes(array('image/png')));
	}

/**
 * testBeforeValidate
 *
 * @return void
 */
	public function testBeforeValidate() {
		$post = array(
			$this->Model->alias => array(
				'file' => array(
					'name' => 'cake.power.gif',
					'type' => 'image/gif',
					'tmp_name' => $this->testFilePath . 'cake.icon.png',
					'error' => 0,
					'size' => 1212)));

		$post[$this->Model->alias]['file']['error'] = 1;
		$this->Model->data = $post;
		$this->assertFalse($this->FileUpload->beforeValidate($this->Model));
		$this->assertTrue(isset($this->Model->validationErrors['file']));
		unset($this->Model->validationErrors['file']);

		$post[$this->Model->alias]['file']['error'] = 2;
		$this->Model->data = $post;
		$this->assertFalse($this->FileUpload->beforeValidate($this->Model));
		$this->assertTrue(isset($this->Model->validationErrors['file']));
		unset($this->Model->validationErrors['file']);

		$post[$this->Model->alias]['file']['error'] = 3;
		$this->Model->data = $post;
		$this->assertFalse($this->FileUpload->beforeValidate($this->Model));
		$this->assertTrue(isset($this->Model->validationErrors['file']));
		unset($this->Model->validationErrors['file']);

		$this->Model->Behaviors->UploadValidator->settings['VoidUploadModel']['allowNoFileError'] = false;
		$post[$this->Model->alias]['file']['error'] = 4;
		$this->Model->data = $post;
		$this->assertFalse($this->FileUpload->beforeValidate($this->Model));
		$this->assertTrue(isset($this->Model->validationErrors['file']));
		unset($this->Model->validationErrors['file']);

		$post[$this->Model->alias]['file']['error'] = 6;
		$this->Model->data = $post;
		$this->assertFalse($this->FileUpload->beforeValidate($this->Model));
		$this->assertTrue(isset($this->Model->validationErrors['file']));
		unset($this->Model->validationErrors['file']);

		$post[$this->Model->alias]['file']['error'] = 7;
		$this->Model->data = $post;
		$this->assertFalse($this->FileUpload->beforeValidate($this->Model));
		$this->assertTrue(isset($this->Model->validationErrors['file']));
		unset($this->Model->validationErrors['file']);

		$post[$this->Model->alias]['file']['error'] = 8;
		$this->Model->data = $post;
		$this->assertFalse($this->FileUpload->beforeValidate($this->Model));
		$this->assertTrue(isset($this->Model->validationErrors['file']));
		unset($this->Model->validationErrors['file']);

		$post[$this->Model->alias]['file']['error'] = 8;
		$this->Model->data = $post;
		$this->assertFalse($this->FileUpload->beforeValidate($this->Model));
		$this->assertTrue(isset($this->Model->validationErrors['file']));
		unset($this->Model->validationErrors['file']);

		$post[$this->Model->alias]['file']['error'] = 42; // Unknow code
		$this->Model->data = $post;
		$this->assertFalse($this->FileUpload->beforeValidate($this->Model));
		$this->assertTrue(isset($this->Model->validationErrors['file']));
		unset($this->Model->validationErrors['file']);

		$post[$this->Model->alias]['file']['error'] = 0;
		$this->Model->data = $post;
		$this->assertTrue($this->FileUpload->beforeValidate($this->Model));

		$post[$this->Model->alias]['file']['error'] = null;
		$this->Model->data = $post;
		$this->assertTrue($this->FileUpload->beforeValidate($this->Model));

		// Test errors
		$this->Model->data = $post;
		$this->FileUpload->setup($this->Model, array('localFile' => false));
		$this->assertFalse($this->FileUpload->beforeValidate($this->Model));
		$this->assertTrue(isset($this->Model->validationErrors['file']));
		unset($this->Model->validationErrors['file']);

		$this->Model->data = $post;
		$this->FileUpload->setup($this->Model, array('localFile' => true, 'allowedMime' => array('jpg')));
		$this->assertFalse($this->FileUpload->beforeValidate($this->Model));
		$this->assertTrue(isset($this->Model->validationErrors['file']));
		unset($this->Model->validationErrors['file']);
	}

}
