<?php
App::uses('FileStorage', 'FileStorage.Model');
App::uses('S3StorageListener', 'FileStorage.Event');
App::uses('FileStorageTestCase', 'FileStorage.TestSuite');

class TestS3StorageListener extends S3StorageListener {

	public function buildPath(CakeEvent $CakeEvent) {
		return $this->_buildPath($CakeEvent);
	}

}

/**
 * LocalImageProcessingListener Test
 *
 * @author Florian Krämer
 * @copyright 2012 Florian Krämer
 * @license MIT
 */
class S3StorageListenerTest extends FileStorageTestCase {

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		$this->Model = new FileStorage();
		$this->Listener = $this->getMock('TestS3StorageListener', array('getAdapterconfig'), array(array()));
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

/**
 * testBuildPath
 *
 * @return void
 */
	public function testBuildPath() {
		$this->Model->data = array(
			'FileStorage' => array(
				'model' => 'Document',
				'adapter' => 'Test',
				'filename' => 'test.png',
				'extension' => 'png',
				'id' => '144c4170-6760-11e3-949a-0800200c9a66'
			)
		);

		$adapterConfig = array(
			'adapterClass' => '\Gaufrette\Adapter\AwsS3',
			'adapterOptions' => array(
				1 => 'my-cool-bucket'
			)
		);

		$this->Listener->expects($this->any())
			->method('getAdapterConfig')
			->with('Test')
			->will($this->returnValue($adapterConfig));

		$result = $this->Listener->buildPath(new CakeEvent(
			'FileStorage.afterSave',
			$this->Model
		));

		$expected = array(
			'filename' => '144c4170676011e3949a0800200c9a66.png',
			'path' => '/files/Document/16/59/52/144c4170676011e3949a0800200c9a66/',
			'combined' => '/files/Document/16/59/52/144c4170676011e3949a0800200c9a66/144c4170676011e3949a0800200c9a66.png',
			'url' => 'https://my-cool-bucket.s3.amazonaws.com/files/Document/16/59/52/144c4170676011e3949a0800200c9a66/144c4170676011e3949a0800200c9a66.png'
		);

		$this->assertEquals($result, $expected);

		$result = $this->Listener->buildPath(new CakeEvent(
			'FileStorage.afterSave',
			$this->Model
		));

		$expected = array(
			'filename' => '144c4170676011e3949a0800200c9a66.png',
			'path' => '/files/Document/16/59/52/144c4170676011e3949a0800200c9a66/',
			'combined' => '/files/Document/16/59/52/144c4170676011e3949a0800200c9a66/144c4170676011e3949a0800200c9a66.png',
			'url' => 'https://my-cool-bucket.s3.amazonaws.com/files/Document/16/59/52/144c4170676011e3949a0800200c9a66/144c4170676011e3949a0800200c9a66.png'
		);

		$this->assertEquals($result, $expected);
	}

}