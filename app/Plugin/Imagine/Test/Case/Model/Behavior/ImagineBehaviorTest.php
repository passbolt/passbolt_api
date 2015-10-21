<?php
/**
 * Copyright 2011-2014, Florian Krämer
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * Copyright 2011-2014, Florian Krämer
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');
App::uses('Security', 'Utility');

class ImagineTestModel extends Model {
	public $name = 'ImagineTestModel';
	public $useTable = false;
}

class ImagineBehaviorTest extends CakeTestCase {

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
 * setUp
 *
 * @return void
 */
	public function setUp() {
		$this->Model = ClassRegistry::init('ImagineTestModel');
		$this->Model->Behaviors->load('Imagine.Imagine');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Model);
		ClassRegistry::flush();
	}

/**
 * testImagineObject
 *
 * @return void
 */
	public function testImagineObject() {
		$result = $this->Model->imagineObject();
		$this->assertTrue(is_a($result, 'Imagine\Gd\Imagine'));
	}

/**
 * testParamsAsFileString
 *
 * @return void
 */
	public function testOperationsToString() {
		$operations = array(
			'thumbnail' => array(
				'width' => 200,
				'height' => 150));
		$result = $this->Model->operationsToString($operations);
		$this->assertEquals($result, '.thumbnail+width-200+height-150');
	}

/**
 * getImageSize
 *
 * @return void
 */
	public function getImageSize() {
		$image = CakePlugin::path('Imagine') . 'Test' . DS . 'Fixture' . DS . 'cake.icon.png';
		$result = $this->Model->getImageSize($image);
		$this->assertEquals($result, array(20, 20));
	}

/**
 * testCropInvalidArgumentException
 *
 * @expectedException InvalidArgumentException
 * @return void
 */
	public function testCropInvalidArgumentException() {
		$image = CakePlugin::path('Imagine') . 'Test' . DS . 'Fixture' . DS . 'titus.jpg';
		$this->Model->processImage($image, TMP . 'crop.jpg', array(), array(
			'crop' => array()));
	}

/**
 * testCrop
 *
 * @return void
 */
	public function testCrop() {
		$image = CakePlugin::path('Imagine') . 'Test' . DS . 'Fixture' . DS . 'titus.jpg';
		$this->Model->processImage($image, TMP . 'crop.jpg', array(), array(
			'crop' => array(
				'height' => 300,
				'width' => 300)));
	}

/**
 * testThumbnail
 *
 * @return void
 */
	public function testThumbnail() {
		$image = CakePlugin::path('Imagine') . 'Test' . DS . 'Fixture' . DS . 'titus.jpg';
		$this->Model->processImage($image, TMP . 'thumbnail.jpg', array(), array(
			'thumbnail' => array(
				'mode' => 'outbound',
				'height' => 300,
				'width' => 300)));

		$result = $this->Model->getImageSize(TMP . 'thumbnail.jpg');
		$this->assertEquals($result,
			array(300, 300, 'x' => 300, 'y' => 300));

		$this->Model->processImage($image, TMP . 'thumbnail2.jpg', array(), array(
			'thumbnail' => array(
				'mode' => 'inset',
				'height' => 300,
				'width' => 300)));

		$result = $this->Model->getImageSize(TMP . 'thumbnail2.jpg');
		$this->assertEquals($result,
			array(226, 300, 'x' => 226, 'y' => 300));
	}

	public function testSquareCenterCrop() {
		$image = CakePlugin::path('Imagine') . 'Test' . DS . 'Fixture' . DS . 'titus.jpg';
		$this->Model->processImage($image, TMP . 'testSquareCenterCrop.jpg', array(), array(
			'squareCenterCrop' => array(
				'size' => 255)));
	}

/**
 * testgetImageSize
 *
 * @return void
 */
	public function testgetImageSize() {
		$image = CakePlugin::path('Imagine') . 'Test' . DS . 'Fixture' . DS . 'titus.jpg';
		$result = $this->Model->getImageSize($image);
		$this->assertEquals($result,
			array(500, 664, 'x' => 500, 'y' => 664));
	}


/**
 * testWidenAndHeighten
 *
 * @return void
 */
	public function testWidenAndHeighten() {
		$image = CakePlugin::path('Imagine') . 'Test' . DS . 'Fixture' . DS . 'titus.jpg';

		$result = $this->Model->getImageSize($image);
		$this->assertEquals($result,
			array(500, 664, 'x' => 500, 'y' => 664));

		// Width
		$this->Model->processImage($image, TMP . 'thumbnail2.jpg', array(), array(
			'widen' => array(
				'size' => 200)));

		$result = $this->Model->getImageSize(TMP . 'thumbnail2.jpg');
		$this->assertEquals($result,
			array(200, 266, 'x' => 200, 'y' => 266));

		// Height
		$this->Model->processImage($image, TMP . 'thumbnail3.jpg', array(), array(
			'heighten' => array(
				'size' => 200)));

		$result = $this->Model->getImageSize(TMP . 'thumbnail3.jpg');
		$this->assertEquals($result,
			array(151, 200, 'x' => 151, 'y' => 200));

	}

/**
 * testScale
 *
 * @return void
 */
	public function testScale() {
		$image = CakePlugin::path('Imagine') . 'Test' . DS . 'Fixture' . DS . 'titus.jpg';

		// Scale
		$this->Model->processImage($image, TMP . 'thumbnail4.jpg', array(), array(
			'scale' => array(
				'factor' => 2)));

		$result = $this->Model->getImageSize(TMP . 'thumbnail4.jpg');
		$this->assertEquals($result,
			array(1000, 1328, 'x' => 1000, 'y' => 1328));

		// Scale2
		$this->Model->processImage($image, TMP . 'thumbnail5.jpg', array(), array(
			'scale' => array(
				'factor' => 1.25)));

		$result = $this->Model->getImageSize(TMP . 'thumbnail5.jpg');
		$this->assertEquals($result,
			array(625, 830, 'x' => 625, 'y' => 830));
	}

}