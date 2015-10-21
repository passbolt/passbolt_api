<?php
App::uses('StorageManager', 'FileStorage.Lib');
/**
 * StorageManagerTest
 *
 * @author Florian Krämer
 * @copyright 2012 Florian Krämer
 * @license MIT
 */
class StorageManagerTest extends CakeTestCase {
/**
 * testAdapter
 *
 * @todo more tests
 * @return void
 */
	public function testAdapter() {
		$result = StorageManager::adapter('Local');
		$this->assertEqual(get_class($result), 'Gaufrette\Filesystem');

		$result = StorageManager::activeAdapter();
		$this->assertEqual($result, 'Local');

		$result = StorageManager::activeAdapter('invalid-adapter');
		$this->assertFalse($result);

		$result = StorageManager::config();
	}
}