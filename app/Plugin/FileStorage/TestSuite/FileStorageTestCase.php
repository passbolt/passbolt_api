<?php
App::uses('CakeTestCase', 'TestSuite');
App::uses('Folder', 'Utility');
/**
 * FileStorageTestCase
 *
 * @author Florian Krämer
 * @copyright 2012 Florian Krämer
 * @license MIT
 */
class FileStorageTestCase extends CakeTestCase {
/**
 * Setup test folders and files
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->testPath = TMP . 'file-storage-test' . DS;
		Configure::write('Media.basePath', $this->testPath);

		if (!is_dir($this->testPath)) {
			$Folder = new Folder($this->testPath, true);
		}

		Configure::write('Media.imageSizes', array(
			'Test' => array(
				't50' => array(
					'thumbnail' => array(
						'mode' => 'outbound',
						'width' => 50, 'height' => 50)),
				't150' => array(
					'thumbnail' => array(
						'mode' => 'outbound',
						'width' => 150, 'height' => 150)))));

		ClassRegistry::init('FileStorage.ImageStorage')->generateHashes();

		StorageManager::config('Local', array(
			'adapterOptions' => array($this->testPath, true),
			'adapterClass' => '\Gaufrette\Adapter\Local',
			'class' => '\Gaufrette\Filesystem'));
	}

/**
 * Cleanup test files
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		$Folder = new Folder(TMP . 'file-storage-test');
		//$Folder->delete();
	}

}