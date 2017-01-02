<?php
App::uses('CakeEventListener', 'Event');
/**
 * Local Image Processor Event Listener for the CakePHP FileStorage plugin
 *
 * @deprecated Use ImageProcessingListener instead
 * @author Florian Krämer
 * @copy 2012 Florian Krämer
 * @license MIT
 */
class LocalImageProcessingListener extends Object implements CakeEventListener {

/**
 * Implemented Events
 *
 * @return array
 */
	public function implementedEvents() {
		return array(
			'ImageVersion.createVersion' => 'createVersions',
			'ImageVersion.removeVersion' => 'removeVersions',
			'ImageStorage.afterSave' => 'afterSave',
			'ImageStorage.afterDelete' => 'afterDelete',
			'FileStorage.ImageHelper.imagePath' => 'imagePath',
		);
	}

/**
 * Creates the different versions of images that are configured
 *
 * @param Model $Model
 * @param array $record
 * @param array $operations
 * @return void
 */
	protected function _createVersions($Model, $record, $operations) {
		$Storage = StorageManager::adapter($record['adapter']);
		$path = $this->_buildPath($record, true);
		$tmpFile = $this->_tmpFile($Storage, $path);

		foreach ($operations as $version => $imageOperations) {
			$hash = $Model->hashOperations($imageOperations);
			$string = $this->_buildPath($record, true, $hash);

			if ($Storage->has($string)) {
				continue;
			}

			try {
				$image = $Model->processImage($tmpFile, null, array('format' => $record['extension']), $imageOperations);
				$result = $Storage->write($string, $image->get($record['extension']), true);
			} catch (Exception $e) {
				$this->log($e->getMessage(), 'file_storage');
				unlink($tmpFile);
				throw $e;
			}
		}

		unlink($tmpFile);
	}

/**
 * Creates versions for a given image record
 *
 * @param CakeEvent $Event
 * @return void
 */
	public function createVersions($Event) {
		if ($this->_checkEvent($Event)) {
			$Model = $Event->subject();
			$Storage = $Event->data['storage'];
			$record = $Event->data['record'][$Model->alias];

			$this->_createVersions($Model, $record, $Event->data['operations']);

			$Event->stopPropagation();
		}
	}

/**
 * Removes versions for a given image record
 *
 * @param CakeEvent $Event
 * @return void
 */
	public function removeVersions($Event) {
		if ($this->_checkEvent($Event)) {
			$Model = $Event->subject();
			$Storage = $Event->data['storage'];
			$record = $Event->data['record'][$Model->alias];

			foreach ($Event->data['operations'] as $version => $operations) {
				$hash = $Model->hashOperations($operations);
				$string = $this->_buildPath($record, true, $hash);

				try {
					if ($Storage->has($string)) {
						$Storage->delete($string);
					}
				} catch (Exception $e) {
					$this->log($e->getMessage(), 'file_storage');
				}
			}

			$Event->stopPropagation();
		}
	}

/**
 * afterDelete
 *
 * @param CakeEvent $Event
 * @return void
 */
	public function afterDelete($Event) {
		if ($this->_checkEvent($Event)) {
			$Model = $Event->subject();
			$path = Configure::read('Media.basePath') . $Event->data['record'][$Model->alias]['path'];
			if (is_dir($path)) {
				$Folder = new Folder($path);
				return $Folder->delete();
			}
			return false;
		}
	}

/**
 * afterSave
 *
 * @param CakeEvent $Event
 * @return void
 */
	public function afterSave($Event) {
		if ($this->_checkEvent($Event)) {
			$Model = $Event->subject();
			$Storage = StorageManager::adapter($Model->data[$Model->alias]['adapter']);
			$record = $Model->data[$Model->alias];

			try {
				$id = $record[$Model->primaryKey];
				$filename = $Model->stripUuid($id);
				$file = $record['file'];
				$format = $record['extension'];
				$sizes = Configure::read('Media.imageSizes.' . $record['model']);
				$record['path'] = $Model->fsPath('images' . DS . $record['model'], $id);
				$result = $Storage->write($record['path'] . $filename . '.' . $record['extension'], file_get_contents($file['tmp_name']), true);

				$data = $Model->save(array($Model->alias => $record), array(
					'validate' => false,
					'callbacks' => false));

				$operations = Configure::read('Media.imageSizes.' . $record['model']);
				if (!empty($operations)) {
					$this->_createVersions($Model, $record, $operations);
				}

				$Model->data = $data;
			} catch (Exception $e) {
				$this->log($e->getMessage(), 'file_storage');
			}
		}
	}

/**
 * Generates the path the image depending on the storage adapter
 *
 * @param CakeEvent $Event
 * @return void
 */
	public function imagePath($Event) {
		if ($Event->data['image']['adapter'] == 'Local') {
			$Helper = $Event->subject();
			extract($Event->data);

			$path = $this->_buildPath($image, true, $hash);

			$Event->data['path'] = $path;
			$Event->stopPropagation();
		}
	}

/**
 * It is required to get the file first and write it to a tmp file
 *
 * The adapter might not be one that is using a local file system, so we first
 * get the file from the storage system, store it locally in a tmp file and
 * later load the new file that was generated based on the tmp file into the
 * storage adapter. This method here just generates the tmp file.
 *
 * @param $Storage
 * @param $path
 * @return bool|string
 */
	protected function _tmpFile($Storage, $path) {
		try {
			if (!is_dir(TMP . 'image-processing')) {
				mkdir(TMP . 'image-processing');
			}

			$tmpFile = TMP . 'image-processing' . DS . String::uuid();
			$imageData = $Storage->read($path);

			file_put_contents($tmpFile, $imageData);
			return $tmpFile;
		} catch (Exception $e) {
			throw $e;
		}
	}

/**
 * Builds a path to a file
 *
 * @param array $image
 * @param boolean $extension
 * @param string $hash
 */
	protected function _buildPath($image, $extension = true, $hash = null) {
		$path = $image['path'] . str_replace('-', '', $image['id']);
		if (!empty($hash)) {
			$path .= '.' . $hash;
		}
		if ($extension == true) {
			$path .= '.' . $image['extension'];
		}
		return '/' . $path;
	}

/**
 * Check if the event is of a type / model we want to process with this listener
 *
 * @param CakeEvent $Event
 * @return boolean
 */
	protected function _checkEvent($Event) {
		$Model = $Event->subject();
		return ($Model instanceOf ImageStorage && isset($Event->data['record'][$Model->alias]['adapter']) && $Event->data['record'][$Model->alias]['adapter'] == 'Local');
	}

}