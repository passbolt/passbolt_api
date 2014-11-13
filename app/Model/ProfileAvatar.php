<?php
App::uses('ImageStorage', 'FileStorage.Model');

class ProfileAvatar extends ImageStorage {

/**
 * Details of use table
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html
 */
	public $useTable = 'file_storage';

/**
 * Model behaviors
 *
 * @link http://api20.cakephp.org/class/model#
 */
	public $actsAs = array(
		'FileStorage.UploadValidator' => array(
			'allowedExtensions' => array(
				'jpg',
				'png',
			),
		),
	);

/**
 * beforeSave callback
 *
 * @param array $options
 * @return boolean true on success
 */
	public function beforeSave($options = array()) {
		if (!parent::beforeSave($options)) {
			return false;
		}

		// delete the previous avatar
		$this->deleteAll(array(
			'foreign_key' => $this->data['Avatar']['foreign_key']
		));

		return true;
	}

/**
 * Serializes and then hashes an array of operations that are applied to an image
 *
 * @param array $operations
 * @return array
 */
	public function hashOperations($operations) {
		$versions = Configure::read('Media.imageSizes.ProfileAvatar');
		$versionName = array_search($operations, $versions);
		if ($versionName) {
			return $versionName;
		}
		return 'VERSION_NOT_FOUND';
	}

/**
 * Upload a file
 *
 * @param $foreignId
 * @param $data
 * @return mixed
 */
	public function upload($foreignId, $data) {
		// Check if an avatar has already been uploaded.
		$options['conditions']['Avatar.foreign_key'] = $foreignId;
		$avatar = $this->find('first', $options);

		// If an avatar exists, delete it and its versions.
		if (!empty($avatar)) {
			// Delete the versions of the file.
			$this->id = $avatar['Avatar']['id'];
			$operations = Configure::read('Media.imageSizes.ProfileAvatar');
			$Event = new CakeEvent('ImageVersion.removeVersion', $this, array(
				'record' => $avatar,
				'storage' => StorageManager::adapter('Local'),
				'operations' => $operations));
			CakeEventManager::instance()->dispatch($Event);

			// Delete the file.
			$imagePath = Configure::read('ImageStorage.basePath') . DS . $avatar['Avatar']['path'] . DS . $this->stripUuid($avatar['Avatar']['id']) . '.' . $avatar['Avatar']['extension'];

			$imagePath = $avatar['Avatar']['path'] . DS . $this->stripUuid($avatar['Avatar']['id']) . '.' . $avatar['Avatar']['extension'];
			StorageManager::adapter($avatar['Avatar']['adapter'])->delete($imagePath);

			// Delete the db resource.
			$this->delete($avatar['Avatar']['id']);
		}

		// Save the given avatar.
		$data[$this->alias]['adapter'] = 'Local';
		$data[$this->alias]['model'] = 'ProfileAvatar';
		$data[$this->alias]['extension'] = $this->fileExtension($data['Avatar']['file']['tmp_name']);
		$data[$this->alias]['foreign_key'] = $foreignId;
		$this->create();
		return $this->save($data);
	}

}
