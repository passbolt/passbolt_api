<?php
App::uses('ImageStorage', 'FileStorage.Model');
App::uses('ImageProcessingListener', 'FileStorage.Event');
App::uses('FileStorageTestCase', 'FileStorage.TestSuite');

class TestImageProcessingListener extends ImageProcessingListener {

	public function buildPath($image, $extension = true, $hash = null) {
		return $this->_buildPath($image, $extension, $hash);
	}

}

/**
 * LocalImageProcessingListener Test
 *
 * @author Florian Krämer
 * @copyright 2012 Florian Krämer
 * @license MIT
 */
class ImageProcessingListenerTest extends FileStorageTestCase {

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Model = new ImageStorage();
		$this->Listener = new ImageProcessingListener();
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Listener, $this->Model);
		ClassRegistry::flush();
	}

	public function testBuildPath() {
		$this->Listener = new TestImageProcessingListener(array(
			'preserveFilename' => true,
		));

		$result = $this->Listener->buildPath(array(
			'filename' => 'foobar.jpg',
			'path' => '/xx/xx/xx/uuid/',
			'extension' => 'jpg'
		));
		$this->assertEqual($result, '/xx/xx/xx/uuid/foobar.jpg');

		$result = $this->Listener->buildPath(array(
			'filename' => 'foobar.jpg',
			'path' => '/xx/xx/xx/uuid/',
			'extension' => 'jpg'
		), true, '5gh2hf');
		$this->assertEqual($result, '/xx/xx/xx/uuid/foobar.5gh2hf.jpg');
	}

}